<?php

namespace Database\Seeders;

use App\Models\D_Reserve;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;

class DReserveSeeder extends Seeder
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
                    ->table('book_casts')
                    // ->whereNull('deleted_at')
                    ->get()
                    ->toArray();

            $formatData = [];
            foreach($datas as $index => $data) {
                $formatData[$index]['id'] = $data->id;
                $formatData[$index]['user_id'] = $data->user_id;
                $formatData[$index]['cast_id'] = $data->cast_id;
                $formatData[$index]['status'] = $data->status;
                $formatData[$index]['type'] = $data->type;
                $formatData[$index]['type_reserve'] = $data->type_reserve;
                $formatData[$index]['indicate_fee1'] = $data->indicate_fee1;
                $formatData[$index]['indicate_fee1_flg'] = $data->indicate_fee1_flg;
                $formatData[$index]['indicate_fee2'] = $data->indicate_fee1_flg;
                $formatData[$index]['indicate_fee2_flg'] = $data->indicate_fee1_flg;
                $formatData[$index]['extension_time'] = $data->extension_time;
                $formatData[$index]['extension_money'] = $data->extension_money;
                $formatData[$index]['discount'] = $data->discount;
                $formatData[$index]['site_id_reserve'] = $data->site_id_reserve;
                $formatData[$index]['amount'] = $data->amount;
                $formatData[$index]['start_time'] = $data->start_time;
                $formatData[$index]['end_time'] = $data->end_time;
                $formatData[$index]['memo'] = $data->memo;
                $formatData[$index]['address'] = $data->address;
                $formatData[$index]['course_money'] = $data->course_money;
                $formatData[$index]['course_time'] = $data->course_time;
                $formatData[$index]['transaction_fee'] = $data->transaction_fee;
                $formatData[$index]['course_name'] = $data->course_name;
                $formatData[$index]['created_at'] = $data->created_at;
                $formatData[$index]['updated_at'] = $data->updated_at;
            }
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = collect($formatData);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                D_Reserve::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
