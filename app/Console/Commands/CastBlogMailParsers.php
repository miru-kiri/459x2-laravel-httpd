<?php

namespace App\Console\Commands;

use App\Models\D_Cast_Blog;
use App\Models\D_Cast_Blog_Image;
use App\Models\M_Cast;
use Exception;
use Illuminate\Console\Command;
//use eXorus\PhpMimeMailParser\Parser;
//use eXorus\PhpMimeMailParser\Attachment;
use PhpMimeMailParser\Parser;
use PhpMimeMailParser\Attachment;


class CastBlogMailParsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cast-blog-mail-parser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            \DB::beginTransaction();
            $parser = new Parser();
            $parser->setStream(fopen('php://stdin', 'r'));
            // to取得
            $rawHeaderto = $parser->getHeader('to');
            // from取得
            //$rawHeaderFrom = $parser->getHeader('from'); 変換エラーでたらから一旦
            // メールアドレス部分のみ取得
            $formatHeaderTo = $this->getMailAddress($rawHeaderto);
            if($formatHeaderTo) {
                //　対象キャストの取得
                $castData = M_Cast::checkPostMail($formatHeaderTo);
                // 件名
                $subject = $parser->getHeader('subject');
                // メール本文取得
                $text = $parser->getMessageBody('text');
                $html = $parser->getMessageBody('html');
                $htmlEmbedded = $parser->getMessageBody('htmlEmbedded');   
                $content = "";
                $imagePrameter = [];
                $attachments = [];
                if($castData) {
                    if(!empty($text) || !empty($html)) {
                        if(!empty($text)) {
                            $content = $text;
                        }
                        if(!empty($html)) {
                            $content = $html;
                        }
                        if(!empty($htmlEmbedded)) {
                            $content = $htmlEmbedded;
                        }
                        $parameter = [
                            'cast_id' => $castData->id,
                            'title' => $subject,
                            'content' => $content,
                            'created_at' => now(),
                            'published_at' => now(),
                            'type' => 1,
                        ];
                        $blogId = D_Cast_Blog::insertGetId($parameter);
                        //添付画像
                        $attachments = $parser->getAttachments();
                        if($attachments) {
                            foreach ($attachments as $attachment) {
                                    $filename = $attachment->getFilename();
                                    $fileContents = $attachment->getContent();
	                                // MIMEタイプを取得
    	                            $contentType = $attachment->getContentType();
                                    // 画像かどうかの判断
                                    $isImage = strpos($attachment->getContentType(), 'image/') === 0;

                                    /*if ($isImage) {
                                        // ファイルの保存先パス
                                        $savePath = "/articles/$blogId/images/$filename";
                                        $directoryPath = "public/articles/{$blogId}/images";
                                        if (!\Storage::exists($directoryPath)) {
                                            \Storage::makeDirectory($directoryPath, 0775, true, true);
                                        }
                                        // \File::put("storage/$savePath",$fileContents);
                                        \Storage::put("public/$savePath", $fileContents);
                                        $imagePrameter = [
                                            'created_at' => now(),
                                            'article_id' => $blogId,
                                            'image_url' => $savePath,
                                        ];
                                    }*/
								if ($isImage) {
                                    // ファイルの保存先パス
                                    $savePath = "/articles/$blogId/images/$filename";
                                    $directoryPath = "public/articles/{$blogId}/images";
                                    if (!\Storage::exists($directoryPath)) {
                                        \Storage::makeDirectory($directoryPath, 0775, true, true);
                                    }
                                    if($contentType === 'image/gif') {
                                        \Storage::put("public/$savePath", $fileContents);
                                    } else {
                                        $image = \Image::make($fileContents);
										 // Exifデータを読み取り画像の向きを修正
										if($contentType === 'image/jpeg') {
											$getStream = $attachment->getStream();
											$exifData = exif_read_data($getStream);
											if (!empty($exifData['Orientation'])) {
												switch ($exifData['Orientation']) {
													case 3:
														$image->rotate(180); // 180度回転
														break;
													case 6:
														$image->rotate(-90); // 90度時計回りに回転
														break;
													case 8:
														$image->rotate(90); // 90度反時計回りに回転
														break;
												}
											}
										}
										// Intervention Imageでメモリ上でリサイズを行う
                                        $image->resize(null, 800, function ($constraint) {
                                            $constraint->aspectRatio();
                                        });
                                        $encodeImage = $image->encode();
                                        // リサイズ後の画像を保存
                                        // \Storage::put("public/$savePath", $fileContents);
                                        \Storage::put("public/$savePath", (string)$encodeImage);
                                    }
                                    $imagePrameter = [
                                        'created_at' => now(),
                                        'article_id' => $blogId,
                                        'image_url' => $savePath,
                                    ];
                                }
                            }
                            if($imagePrameter) {
                                D_Cast_Blog_Image::insert($imagePrameter);
                            }
                        }
                    }
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
        }
    }
    /**
     * メールアドレス部分だけ取得
     *
     * @param [type] $mail
     * @return void
     */
    public function getMailAddress($mail)
    {
        $emailAddress = false;
        if (preg_match('/[\w\.-]+@[\w\.-]+/', $mail, $matches)) {
            $emailAddress = $matches[0];
        }
        return $emailAddress;
    }
}