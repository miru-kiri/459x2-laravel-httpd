<?php

namespace App\Jobs;

use App\Models\Cast_Schedule_Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCastScheduleBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch;
    protected $siteId;
    protected $date;

    public function __construct(array $batch, $siteId, $date)
    {
        $this->batch = $batch;
        $this->siteId = $siteId;
        $this->date = $date;
    }

    public function handle()
    {
        $jobId = uniqid("job_");
        Log::info("[$jobId] START: site_id={$this->siteId}, date={$this->date}, time=" . now());

        try {
            if (empty($this->batch)) {
                Log::info("[$jobId] BATCH EMPTY, SKIP.");
                return;
            }
            // Lấy danh sách cast_id duy nhất từ batch
            $castId = $this->batch[0]['castId']; // vì tất cả phần tử cùng castId
            $date = date('Y-m-d', strtotime($this->date));

            Cast_Schedule_Setting::where('cast_id', $castId)
                ->where('date', $date)
                ->delete();

            // Tạo mới nếu status != -1
            foreach ($this->batch as $item) {
                if ($item['status'] == -1) {
                    continue;
                }

                Cast_Schedule_Setting::create([
                    'cast_id'   => $item['castId'],
                    'status'    => $item['status'],
                    'date_time' => $item['dateTime'],
                    'date'      => date('Y-m-d', strtotime($item['date'])),
                    'time'      => $item['time'],
                ]);
            }

            Log::info("[$jobId] END: site_id={$this->siteId}, date={$this->date}, time=" . now());
        } catch (QueryException $e) {
            Log::error('UpdateCastScheduleBatch Error', [
                'site_id' => $this->siteId,
                'date'    => $this->date,
                'batch'   => $this->batch,
                'exception' => $e,
            ]);
            throw $e;
        }
    }
}
