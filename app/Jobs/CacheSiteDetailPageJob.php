<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\SiteDetailPageController;
use App\Helpers\FakeRequestHelper;

class CacheSiteDetailPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $genreId, $areaId, $siteId;
    public $timeout = 30;

    public function __construct($genreId, $areaId, $siteId)
    {
        $this->genreId = $genreId;
        $this->areaId = $areaId;
        $this->siteId = $siteId;
    }

    public function handle()
    {
        $cacheKey = "site.detail.{$this->genreId}.{$this->areaId}.{$this->siteId}";
        // Log::channel('cache_log')->info("Step 1 - Start job for key: {$cacheKey}");

        try {
            // Log::channel('cache_log')->info("Step 2 - About to forget cache");

            Cache::forget($cacheKey);

            // Log::channel('cache_log')->info("Step 3 - Building Request");

            // $request = new Request([
            //     'genre_id' => $this->genreId,
            //     'area_id' => $this->areaId,
            //     'site_id' => $this->siteId
            // ]);

            $request = FakeRequestHelper::fake('/detail/top', ['genre_id' => $this->genreId, 'area_id' => $this->areaId, 'site_id' => $this->siteId]);

            // Log::channel('cache_log')->info("Step 4 - Instantiating controller");
            $controller = app(SiteDetailPageController::class);
            // Log::channel('cache_log')->info("Step 5 - Calling top()");
            $response = $controller->top($request);
            // Log::channel('cache_log')->info("Step 6 - Got response, checking status");

            if ($response->getStatusCode() === 200) {
                $viewContent = $response->getContent();
                Cache::put($cacheKey, $viewContent, now()->addMinutes(12));
                // Log::channel('cache_log')->info("Step 7 - ✅ Cache success: " . $cacheKey);
            } else {
                $status = $response->getStatusCode();
                $excerpt = mb_strimwidth(strip_tags($response->getContent()), 0, 300, '...');
                // Log::channel('cache_log')->warning("Step 8 - ⚠️ Non-200 response: [{$status}] {$cacheKey} — {$excerpt}");
                throw new \Exception("Response {$status} while caching {$cacheKey}");
            }
        } catch (\Exception $e) {
            // Log::channel('cache_log')->error("Step 9 - ❌ Failed to cache: {$cacheKey}. Error: " . $e->getMessage());
            throw $e;
        }
    }
}
