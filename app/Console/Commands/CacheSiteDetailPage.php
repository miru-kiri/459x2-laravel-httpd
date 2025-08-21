<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SiteDetailPageController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\M_Genre;
use App\Models\M_Area;
use App\Models\M_Site;
use App\Models\Site_Area;
use App\Models\D_Cast_Blog;
use App\Jobs\CacheSiteDetailPageJob;

class CacheSiteDetailPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:site-detail-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache site detail page';

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
            $genreIds = M_Genre::where('is_public', 1)->where('id', '<=', 7)->pluck('id');
            foreach ($genreIds as $genreId) {
                $siteIdAry = M_Site::fetchFilterIdAry(['template' => $genreId]);
                if ($siteIdAry) {
                    $siteAreas = Site_Area::fetchFilterData(['site_id' => $siteIdAry]);
                }
                $areaIds = $siteAreas->pluck('area_id')->unique()->values()->toArray();
                foreach ($areaIds as $key => $areaId) {
                    $siteIds = Site_Area::FetchFilterSiteIdAry(['site_id' => 0, 'area_id' => $areaId, 'template' => $genreId]);
                    foreach ($siteIds as $siteId) {
                        CacheSiteDetailPageJob::dispatch($genreId, $areaId, $siteId);
                    }
                }
            }

        } catch (\Exception $e) {
            // Log::channel('cache_log')->error("Cache site detail page command failed: " . $e->getMessage());
        }

        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        // Log::channel('cache_log')->info("Cache site detail page command run");
        $this->info("Cache site detail page successfully. Start time: {$startTimeFormatted}. Execution time: {$executionTime} seconds.");
    }
}
