<?php

namespace App\Console\Commands;

use App\Models\Cast_Schedule;
use App\Models\Clone_Cast_Shedule_Log;
use App\Models\M_Cast;
use App\Models\X459x_Cast_Shedule;
use Illuminate\Console\Command;

class CreateCastShedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-cast-shedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'キャストのスケジュールを自動作成';

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
            $cloneData = Clone_Cast_Shedule_Log::findOrFail(1);
            $time = time();
            $targetDate = $cloneData->date;
            $weekAry = [0 =>'Sun',1=> 'Mon',2=> 'Tue',3=> 'Wed',4=> 'Thu',5=> 'Fri',6=> 'Sat'];
            $castData = M_Cast::fetchAutoData();
            $parameter = [];
            foreach($castData as $data) {
                $castAutoWeekAry = explode(',',$data->auto_week);
                if(!in_array($weekAry[date('w',strtotime($targetDate))],$castAutoWeekAry)) {
                    continue;
                }
                $isCastSheduleData = Cast_Schedule::fetchFilteringFirstData(['date' => $targetDate,'cast_id' => $data->id]);
                if($isCastSheduleData) {
                    continue;
                }
                $parameter[] = [
                    'created_at' => $time,
                    'cast_id' => $data->id,
                    'date' => date('Y-m-d',strtotime($targetDate)),
                    'is_work' => 1,
                    'start_time' => $data->auto_start_time,
                    'end_time' => $data->auto_end_time,
                    'comment' => $data->auto_rest_comment,
                ];
                $oldParameter[] = [
                    'scdid' => date('Y-m-d',strtotime($targetDate)).'_'.$data->id,
                    'scd1' => $data->id,
                    'scd2' => date('Y-m-d',strtotime($targetDate)),
                    'scd3' => 'work',
                    'scd4' => $data->auto_start_time,
                    'scd5' => $data->auto_end_time,
                    'scd6' => $data->auto_rest_comment,
                    'scd30' => $time,
                ];
            }
            if($parameter) {
                Cast_Schedule::insert($parameter);
            }
            if($oldParameter) {
                X459x_Cast_Shedule::insert($oldParameter);
            }
            $cloneData->fill(['date' => date('Y-m-d',strtotime($targetDate . '+1 day'))])->save();
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
        }
    }
}
