<?php

namespace Database\Seeders;

use App\Models\Cast_Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CastScheduleSeeder extends Seeder
{
    protected $defaultColumns = [
        // 'prfid',
        'scd1',
        'scd2',
        'scd3',
        'scd4',
        'scd5',
        'scd6',
    ];
    protected $newColumns = [
        // 'id' => 'prfid',
        'cast_id' => 'scd1',
        'date' => 'scd2',
        'is_work' => 'scd3',
        'start_time'  => 'scd4',
        'end_time'  => 'scd5',
        'comment'  => 'scd6',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('max_execution_time', 0);
        try {
            DB::beginTransaction();
            // $connectionName = 'x459x_staff';
            $connectionName = env('DB_PREFIX_X459X') . 'staff';
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('scd')
                    // ->where('scd2',"LIKE",date('Ym')."%")
                    ->where('scd2',">=",date('Y-m-d')) //処理が重すぎてタイムアウトする。。。
                    ->select($this->defaultColumns)
                    ->get(); 
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = [];
            foreach($datas as $data) {
                $param = [];
                foreach($this->newColumns as $newKey => $oldKey) {   
                    if($newKey == 'is_work') {
                        $data->$oldKey = $data->$oldKey == 'work' ? 1 : 0;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                $param['created_at'] = time();
                $parameter[] = $param;
            }
            $parameter = collect($parameter);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                Cast_Schedule::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
