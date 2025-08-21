<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SitePageController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CacheHomePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:home-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache home page';

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

        $request = Request::create('/', 'GET');

        try {
            $cacheKey = "cache.home.page";

            Cache::forget($cacheKey);
            $controller = app(SitePageController::class);
            try {
                $response = $controller->index($request);

                if ($response->getStatusCode() === 200) {
                    $viewContent = $response->getContent();
                    Cache::put($cacheKey, $viewContent, now()->addMinutes(12));
                    // Log::channel('cache_log')->info("Successfully cached Home page");
                } else {
                    $status = $response->getStatusCode();
                    $body = strip_tags($response->getContent()); // loại bỏ HTML nếu là view
                    $excerpt = mb_strimwidth($body, 0, 500, '...');
                    // Log::channel('cache_log')->warning("Failed to cache Home page. Status: {$status}. Content: {$excerpt}");
                }
            } catch (\Exception $e) {
                // Log::channel('cache_log')->error("Error caching Home page: " . $e->getMessage());
            }
        } catch (\Exception $e) {
            // Log::channel('cache_log')->error("CacheHomePage command failed: " . $e->getMessage());
        }

        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        // $this->info("Cache Home Page successfully. Start time: {$startTimeFormatted}. Execution time: {$executionTime} seconds.");
    }
}
