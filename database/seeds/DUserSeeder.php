<?php

namespace Database\Seeders;

use App\Models\D_User;
use App\Models\M_Point_User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DUserSeeder extends Seeder
{
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
            $connectionName = env('DB_OLD_API_DATABASE');
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_API_HOST'),env('DB_OLD_API_USERNAME'),env('DB_OLD_API_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('users')
                    // ->whereNull('deleted_at')
                    ->get()
                    ->toArray();

            $formatData = [];
            $formatPointData = [];
            foreach($datas as $index => $data) {
                $formatData[$index]['id'] = $data->id;
                $formatData[$index]['name'] = $data->name;
                $formatData[$index]['name_furigana'] = $data->name_furigana;
                $formatData[$index]['nickname'] = $data->nickname;
                $formatData[$index]['name_show'] = $data->name_show;
                $formatData[$index]['email'] = $data->email;
                $formatData[$index]['phone'] = $data->phone;
                $formatData[$index]['site_id'] = $data->site_id;
                $formatData[$index]['rank'] = $data->rank;
                $formatData[$index]['block'] = $data->block;
                $formatData[$index]['memo'] = $data->memo;
                $formatData[$index]['address'] = $data->address;
                $formatData[$index]['birth_day'] = $data->birth_day;
                $formatData[$index]['avatar'] = $data->avatar;
                $formatData[$index]['created_at'] = $data->created_at;
                $formatData[$index]['updated_at'] = $data->updated_at;
                $formatData[$index]['password'] = $data->password;
                $formatData[$index]['deleted_at'] = $data->deleted_at;
                $formatData[$index]['otp_code'] = $data->otp_code;
                $formatData[$index]['expired_otp_code'] = $data->expired_otp_code;
                $formatData[$index]['phone_otp'] = $data->phone_otp;
                $formatData[$index]['last_login'] = $data->last_login;
                $birthDayAry = [];
                if($data->birth_day) {
                    $birthDayAry = explode('-',$data->birth_day);
                }
                $formatPointData[] = [
                    'created_at' => time(),
                    'user_id' => $data->id,
                    'site_id' => $data->site_id,
                    'card_no' => $data->id, // 7桁0埋め
                    'name' => $data->name_show,
                    'year' => $birthDayAry[0] ,
                    'month' => $birthDayAry[1],
                    'day' => $birthDayAry[2],
                    'sex' => 1,
                    'tel' => $data->phone,
                ];
            }
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = collect($formatData);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                D_User::insert($chunkParams->toArray());
            }
            foreach($formatPointData as $pd) {
                M_Point_User::insert($pd);
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
