<?php

namespace Database\Seeders;

use App\Models\Cast_Schedule_Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CastScheduleSetting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            $connectionName = env('DB_OLD_API_DATABASE');
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_API_HOST'),env('DB_OLD_API_USERNAME'),env('DB_OLD_API_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('cast_schedule_settings')
                    // ->whereNull('deleted_at')
                    ->get()
                    ->toArray();

            $formatData = [];
            foreach($datas as $index => $data) {
                $formatData[$index]['id'] = $data->id;
                $formatData[$index]['date_time'] = $data->date_time;
                $formatData[$index]['date'] = $data->date;
                $formatData[$index]['time'] = $data->time;
                $formatData[$index]['cast_id'] = $data->cast_id;
                $formatData[$index]['status'] = $data->status;
                $formatData[$index]['created_at'] = $data->created_at;
                $formatData[$index]['updated_at'] = $data->updated_at;
            }
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = collect($formatData);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                Cast_Schedule_Setting::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
