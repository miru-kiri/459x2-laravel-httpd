<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UpdateCastEmails extends Command
{
    protected $signature = 'cast:secure-email
        {--id-start=1 : Start cast ID}
        {--id-end=999999 : End cast ID}';

    protected $description = 'Update cast email by replacing old one with a new secure one';

    public function handle()
    {
        $start = (int) $this->option('id-start');
        $end = (int) $this->option('id-end');

        $casts = DB::table('m_cast')
            ->whereBetween('id', [$start, $end])
            ->whereNotNull('post_email')
            ->orderBy('id')
            ->get();

        if ($casts->isEmpty()) {
            $this->info("No casts found in range $start to $end.");
            return 0;
        }

        $this->info("Updating {$casts->count()} casts from ID $start to $end...");

        $updatedEmails = [];
        $mailPassword = env('MAIL_PASSWORD');

        foreach ($casts as $cast) {
            $oldEmail = strtolower($cast->post_email);
            $username = strtolower($cast->username);
            $randomPart = strtolower(Str::random(20));
            $newEmail = "{$username}_{$randomPart}@459x.com";

            // Step 1: Remove old mailbox
            $removeCmd = "sudo /usr/sbin/plesk bin mail --remove " . escapeshellarg($oldEmail);
            exec($removeCmd, $removeOutput, $removeReturn);

            if ($removeReturn !== 0) {
                $this->warn("⚠️ Could not remove mailbox: $oldEmail (may not exist)");
                Log::warning("Could not remove mailbox: {$oldEmail}\nOutput: " . implode("\n", $removeOutput));
            }

            // Step 2: Create new mailbox
            $createCmd = sprintf(
                "sudo /usr/sbin/plesk bin mail --create %s -passwd %s -mailbox true",
                escapeshellarg($newEmail),
                escapeshellarg($mailPassword)
            );
            exec($createCmd, $createOutput, $createReturn);

            if ($createReturn !== 0) {
                $this->error("❌ Failed to create mailbox: $newEmail");
                Log::error("Failed to create mailbox: $newEmail\nOutput: " . implode("\n", $createOutput));
                continue;
            }

            // Step 3: Update DB
            DB::table('m_cast')
                ->where('id', $cast->id)
                ->update(['post_email' => $newEmail]);

            $updatedEmails[] = [
                'cast_id'   => $cast->id,
                'old_email' => $oldEmail,
                'new_email' => $newEmail,
            ];

            $this->line("✔ {$username}: {$oldEmail} → {$newEmail}");
            usleep(300000); // nghỉ 0.3 giây để tránh quá tải
        }

        exec("sudo /usr/sbin/postmap /var/spool/postfix/plesk/virtual");
        exec("sudo /bin/systemctl reload postfix");

        // Log ra CSV nếu có bản ghi thành công
        if (count($updatedEmails) > 0) {
            $timestamp = now()->format('Ymd_His');
            $directory = storage_path('app/email_updates');
            $filename = "{$directory}/updated_emails_{$timestamp}.csv";

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = fopen($filename, 'w');
            fputcsv($file, ['cast_id', 'old_email', 'new_email']);

            foreach ($updatedEmails as $record) {
                fputcsv($file, $record);
            }

            fclose($file);
            $this->info("📋 Updated emails saved to: $filename");
        } else {
            $this->info("No emails were updated.");
        }

        $this->info("🎉 Email update complete.");
        return 0;
    }
}
