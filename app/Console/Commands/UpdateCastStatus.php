<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\M_Site;
use App\Models\M_Cast;
use Illuminate\Support\Facades\Log;

class UpdateCastStatus extends Command
{
    protected $signature = 'cast:update-status';
    protected $description = 'Update cast status to "defo" after site closing time unless updated after closing';

    public function handle()
    {
        $startTime = microtime(true);
        $startTimeFormatted = now()->format('Y-m-d H:i:s');

        $now = now();

        // Get all public sites
        $sites = M_Site::all();

        foreach ($sites as $siteDetail) {
            $siteId = $siteDetail->id;
            $close_time = $siteDetail->close_time;

            // Convert close_time to Carbon object
            $close_hour = (int)substr($close_time, 0, 2);
            $close_minute = (int)substr($close_time, 2, 2);
            $closingDate = $now->copy();

            // If shop closes after midnight (e.g., 2730 means 03:30 next day)
            if ($close_hour >= 24) {
                $close_hour -= 24;
            } else {
                $closingDate->subDay();
            }

            // Ensure close hour and close minute are always two digits
            $close_hour = str_pad($close_hour, 2, '0', STR_PAD_LEFT);
            $close_minute = str_pad($close_minute, 2, '0', STR_PAD_LEFT);

            // Construct the closing time as "Y-m-d H:i" format
            try {
                $closingTime = Carbon::createFromFormat('Y-m-d H:i', $closingDate->format('Y-m-d') . " {$close_hour}:{$close_minute}");
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
                // $this->error('Error parsing closing time: ' . $e->getMessage());
                // $this->error('Closing date: ' . $closingDate->format('Y-m-d'));
                // $this->error('Close hour: ' . $close_hour);
                // $this->error('Close minute: ' . $close_minute);
                continue; // Skip this site if there's an error
            }

            $closingTimePlus10Minutes = $closingTime->copy()->addMinutes(12);

            // // If more than 10 minutes have passed since closing time, skip the cast processing
            if ($now->greaterThan($closingTimePlus10Minutes)) {
                // $this->info("More than 10 minutes passed since the closing time for site {$siteId}, skipping cast update.");
                // $this->info('ClosingTime (Local Time) ' . $closingTime);
                continue; // Skip this site if the time window is over
            }

            // Get all casts for the current site with filtering by site_id
            $castDatas = M_Cast::filteringMultiSiteData(['site_id' => $siteId]);

            foreach ($castDatas as $cast) {
                $lastUpdatedStatusAt = $cast->last_updated_status_at
                    ? Carbon::parse($cast->last_updated_status_at)
                    : null;

                // Debugging: Print the values to check if the comparison is working as expected
                // if ($cast->id == 6913) {
                //     $this->info('Cast id ' . $cast->id);
                //     $this->info('last_updated_status_at ' . $cast->last_updated_status_at);
                //     $this->info('Last_updated_status_at (Local Time) ' . $lastUpdatedStatusAt);
                //     $this->info('ClosingTime  ' . $close_time);
                //     $this->info('ClosingTime (Local Time) ' . $closingTime);
                // }

                // Only update if last_updated_status_at is before closing time
                if (!$lastUpdatedStatusAt || $lastUpdatedStatusAt->lessThan($closingTime)) {
                    $cast->sokuhime_status = "defo";
                    $cast->last_updated_status_at = $now;
                    $cast->save();
                }
            }
        }

        $endTime = microtime(true);  // Record the end time
        $executionTime = round($endTime - $startTime, 2);
        // $this->info("Cast statuses updated successfully.Start time: {$startTimeFormatted}. Execution time: {$executionTime} seconds.");
        // Log::channel('cache_log')->info("Successfully update cast status");
    }
}
