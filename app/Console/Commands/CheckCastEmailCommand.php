<?php

namespace App\Console\Commands;

use App\Models\D_Cast_Blog;
use App\Models\D_Cast_Blog_Image;
use App\Models\M_Cast;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpMimeMailParser\Parser;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CheckCastEmailCommand extends Command
{
    protected $signature = 'cast:check-incoming-mail';
    protected $description = 'Check cast maildir for new emails and process them';

    public function handle()
    {
        $basePath = '/var/qmail/mailnames/459x.com';
        $casts = M_Cast::whereNotNull('post_email')->get();

        foreach ($casts as $cast) {
            $email = $cast->post_email;
            $local = explode('@', $email)[0];
            $maildir = "{$basePath}/{$local}/Maildir/new";

            if (!is_dir($maildir)) {
                $this->warn("Maildir not found for {$email}");
                continue;
            }

            $files = glob("$maildir/*");
            if (empty($files)) continue;

            foreach ($files as $filePath) {
                try {
                    DB::beginTransaction();

                    $parser = new Parser();
                    $parser->setPath($filePath);

                    $to = $this->getMailAddress($parser->getHeader('to'));
                    $subject = $parser->getHeader('subject');
                    $body = $parser->getMessageBody('htmlEmbedded')
                        ?: $parser->getMessageBody('html')
                        ?: $parser->getMessageBody('text');

                    $blogId = D_Cast_Blog::insertGetId([
                        'cast_id' => $cast->id,
                        'title' => $subject,
                        'content' => $body,
                        'created_at' => now(),
                        'published_at' => now(),
                        'type' => 1,
                    ]);

                    foreach ($parser->getAttachments() as $attachment) {
                        $filename = $attachment->getFilename();
                        $contentType = $attachment->getContentType();

                        if (strpos($contentType, 'image/') === 0) {
                            $fileContents = $attachment->getContent();
                            $savePath = "/articles/{$blogId}/images/{$filename}";
                            $dirPath = "public/articles/{$blogId}/images";

                            if (!Storage::exists($dirPath)) {
                                Storage::makeDirectory($dirPath, 0775, true);
                            }

                            if ($contentType === 'image/gif') {
                                Storage::put("public{$savePath}", $fileContents);
                            } else {
                                $image = Image::make($fileContents);

                                if ($contentType === 'image/jpeg') {
                                    $stream = $attachment->getStream();
                                    $exif = @exif_read_data($stream);
                                    if (!empty($exif['Orientation'])) {
                                        match ($exif['Orientation']) {
                                            3 => $image->rotate(180),
                                            6 => $image->rotate(-90),
                                            8 => $image->rotate(90),
                                            default => null
                                        };
                                    }
                                }

                                $image->resize(null, 800, fn($c) => $c->aspectRatio());
                                Storage::put("public{$savePath}", (string)$image->encode());
                            }

                            D_Cast_Blog_Image::insert([
                                'created_at' => now(),
                                'article_id' => $blogId,
                                'image_url' => $savePath,
                            ]);
                        }
                    }

                    DB::commit();
                    unlink($filePath); // Đã xử lý xong thì xóa
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::error("Error parsing mail {$filePath}: " . $e->getMessage());
                }
            }
        }

        $this->info('✔ Email check completed.');
        return 0;
    }

    private function getMailAddress($raw)
    {
        if (preg_match('/[\w\.-]+@[\w\.-]+/', $raw, $matches)) {
            return $matches[0];
        }
        return false;
    }
}
