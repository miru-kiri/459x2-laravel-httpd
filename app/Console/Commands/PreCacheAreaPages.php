<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\SiteDetailPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\M_Genre;
use App\Models\M_Area;
use Illuminate\Support\Facades\Log;
use App\Helpers\FakeRequestHelper;

class PreCacheAreaPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:preload-area-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preload cache for area pages every 5 minutes';

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
        $startTime = microtime(true);
        $startTimeFormatted = now()->format('Y-m-d H:i:s');
        
        try {
            $genreIds = M_Genre::where('is_public', 1)->pluck('id');
            $areaIds = M_Area::where('is_public', 1)->pluck('id');

            foreach ($genreIds as $genreId) {
                foreach ($areaIds as $areaId) {
                    $cacheKey = "sitePages.detail.area.{$genreId}.{$areaId}";

                    Cache::forget($cacheKey);

                    $_GET['genre_id'] = $genreId;
                    $_GET['area_id'] = $areaId;

                    // $request = new Request(['genre_id' => $genreId, 'area_id' => $areaId]);
                    $request = FakeRequestHelper::fake('/detail/area', ['genre_id' => $genreId, 'area_id' => $areaId]);

                    $controller = app(SiteDetailPageController::class);

                    try {
                        $response = $controller->area($request);

                        if ($response->getStatusCode() === 200) {
                            $viewContent = $response->getContent();
                            Cache::put($cacheKey, $viewContent, now()->addMinutes(8));
                            // Log::channel('cache_log')->info("Successfully cached genre_id={$genreId}, area_id={$areaId}");
                        } else {
                            $status = $response->getStatusCode();
                            $body = strip_tags($response->getContent()); // loại bỏ HTML nếu là view
                            $excerpt = mb_strimwidth($body, 0, 500, '...');
                            // Log::channel('cache_log')->warning("Failed to cache genre_id={$genreId}, area_id={$areaId}. Status: {$status}. Content: {$excerpt}");
                        }
                    } catch (\Exception $e) {
                        // Log::channel('cache_log')->error("Error caching genre_id={$genreId}, area_id={$areaId}: " . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            // Log::channel('cache_log')->error("PreCacheAreaPages command failed: " . $e->getMessage());
        }

        $endTime = microtime(true);  // Record the end time
        $executionTime = round($endTime - $startTime, 2);
        // $this->info("Cache Site Area Page successfully. Start time: {$startTimeFormatted}. Execution time: {$executionTime} seconds.");
    }
}
