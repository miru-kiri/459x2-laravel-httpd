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

class UpdateCastSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $castId;
    protected $status;
    protected $dateTime;
    protected $date;
    protected $time;

    public function __construct($castId, $status, $dateTime, $date, $time)
    {
        $this->castId = $castId;
        $this->status = $status;
        $this->dateTime = $dateTime;
        $this->date = $date;
        $this->time = $time;
    }

    public function handle()
    {
        DB::beginTransaction();

        try {
            if ($this->status == -1) {
                Cast_Schedule_Setting::where('cast_id', $this->castId)
                    ->where('date', date('Y-m-d', strtotime($this->date)))
                    ->where('time', $this->time)
                    ->delete();
            } else {
                $updateData = [
                    'cast_id' => $this->castId,
                    'status' => $this->status,
                    'date_time' => $this->dateTime,
                    'date' => date('Y-m-d', strtotime($this->date)),
                    'time' => $this->time,
                ];

                Cast_Schedule_Setting::updateOrCreate(
                    ['cast_id' => $this->castId, 'date_time' => $this->dateTime],
                    $updateData
                );
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('UpdateCastSchedule Error', [
                'castId' => $this->castId,
                'status' => $this->status,
                'dateTime' => $this->dateTime,
                'date' => $this->date,
                'time' => $this->time,
                'exception' => $e,
            ]);
            throw $e; // để hệ thống queue tự retry nếu cần
        }
    }
}
