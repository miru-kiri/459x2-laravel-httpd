<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SiteDetailPageController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\M_Genre;
use App\Helpers\FakeRequestHelper;

class CacheDetailGenre extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:detail-genre-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache detail genre page';

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

            foreach ($genreIds as $genreId) {
                $cacheKey = "cache.detail.genre.{$genreId}";

                Cache::forget($cacheKey);

                $_GET['genre_id'] = $genreId;

                // $request = new Request(['genre_id' => $genreId]);
                $request = FakeRequestHelper::fake('/detail', ['genre_id' => $genreId]);

                $controller = app(SiteDetailPageController::class);

                try {
                    $response = $controller->index($request);

                    if ($response->getStatusCode() === 200) {
                        $viewContent = $response->getContent();
                        Cache::put($cacheKey, $viewContent, now()->addMinutes(12));
                        // Log::channel('cache_log')->info("Successfully cached genre_id={$genreId}");
                    } else {
                        $status = $response->getStatusCode();
                        $body = strip_tags($response->getContent());
                        $excerpt = mb_strimwidth($body, 0, 500, '...');
                        // Log::channel('cache_log')->warning("Failed to cache genre_id={$genreId} . Status: {$status}. Content: {$excerpt}");
                    }
                } catch (\Exception $e) {
                    // Log::channel('cache_log')->error("Error caching genre_id={$genreId}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            // Log::channel('cache_log')->error("CacheDetailGenre command failed: " . $e->getMessage());
        }

        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        // $this->info("Cache Detail Genre Page successfully. Start time: {$startTimeFormatted}. Execution time: {$executionTime} seconds.");
    }
}
