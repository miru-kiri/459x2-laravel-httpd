<?php

namespace Database\Seeders;

use App\Models\M_Cast;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MCastSeeder extends Seeder
{
    protected $defaultNewColumns = [
        'id',
        'username',
        'password',
        'deleted_at',
        'generate_link_register_at',
        'token_register',
        'register_at',
        'block',
        'post_email',
        'avatar',
    ];
    protected $defaultOldColumns = [
        'staffid',
        'st1',
        'st2',
        'st3',
        'st4',
        'st5',
        'st6',
        'st7',
        'st8',
        'st9',
        'st10',
        'st11',
        'st12',
        'st13',
        'st14',
        'st15',
        'st16',
        'st17',
        'st17',
        'st18',
        'st19',
        'st20',
        'st21',
        'st22',
        'st23',
        'st24', //キャストオプション
        'st25',
        'st26',
        'st27',
        'st61',
        'st62',
        'st63',
        'st64',
        'st65',
        'st76',
        'st77',
        'st78',
        'st79',
        'st80',
    ];
    protected $newColumns = [
        'id' => 'staffid',
        'created_at' => 'st80',
        'updated_at' => 'st79',
        'deleted_at' => 'st78',
        'site_id' => 'st1',
        'source_name' => 'st2',
        'catch_copy' => 'st3',
        'shop_id' => 'st4',
        'stay_status' => 'st5',
        'exclusive_status' => 'st6',
        'age' => 'st7',
        'blood_type' => 'st8',
        'constellation' => 'st9',
        'height' => 'st10',
        'bust' => 'st11',
        'cup' => 'st12',
        'waist' => 'st13',
        'hip' => 'st14',
        'figure' => 'st15',
        'figure_comment' => 'st16',
        'self_pr' => 'st17',
        'shop_manager_pr' => 'st18',
        'post_mail' => 'st19',
        'huzoku_dx_id' => 'st20',
        'sokuhime_date' => 'st21',
        'sokuhime_status' => 'st22',
        'is_sokuhime' => 'st23',
        'transfer_mail' => 'st25',
        'recruit_status' => 'st26',
        'serch_word' => 'st27',
        'is_auto' => 'st61',
        'auto_start_time' => 'st62',
        'auto_end_time' => 'st63',
        'auto_rest_comment' => 'st64',
        'auto_week' => 'st65',
        'sort' => 'st76',
        'is_public' => 'st77',
        'username' => 'username',
        'password' => 'password',
        'generate_link_register_at' => 'generate_link_register_at',
        'token_register' => 'token_register',
        'register_at' => 'register_at',
        'block' => 'block',
        'post_email' => 'post_email',
        'avatar' => 'avatar',
    ];
    protected $stayStatusColumns = [
        '00_member' =>  1,
        '01_support' =>  2, 
        '99_dammy' =>  -1,
    ];
    protected $exclusiveStatusColumns = [
        '50_none' =>  1,
        '10_new' =>  2,
        '05_experi' => 3 ,
        '01_limit' =>  4,
        '60_recommend' =>  5,
        '71_rank' =>  6,
        '72_rank' => 7,
        '73_rank' =>  8,
        '99_vacation' =>  9,
    ];
    protected $figureColumns = [
        't_s_bs' =>  1,
        't_s_bm' =>  2,
        't_s_bb' =>  3,
        't_m_bs' =>  4,
        't_m_bm' =>  5,
        't_m_bb' =>  6,
        't_b_bs' =>  7,
        't_b_bm' =>  8,
        't_b_bb' =>  9,
    ];
    // 'defo' => "予約受付中", 'none' => "待ち時間無し", 'end' => "受付終了", 'mini' => "残り枠少ない"
    // 'all_end' => "本日完売", 'cancel_end' => "キャンセル待ち", 'mini_one' => "あと一枠"
    // protected $sokuhimeColumns = [
    //     'defo' => 1,
    //     'none' => 2,
    //     'end' => 3,
    //     'mini' => 4,
    //     'all_end' => 5,
    //     'cancel_end' => 6,
    //     'mini_one' => 7,
    // ];
    // protected $recruitColumns = [
    //     '11_normal' => 1,
    //     '22_scout' => 2,
    //     '33_dekasegi' => 3
    // ];
    /**
     * 新しい方のデータから古い方の詳細データを取得する
     *
     * @return void
     */
    public function run()
    {
        $connectionName = env('DB_OLD_API_DATABASE');
        list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_API_HOST'),env('DB_OLD_API_USERNAME'),env('DB_OLD_API_PASSWORD'));
        // 動的な接続を使用してクエリを実行
        $connection = DB::connection($connectionName);
        $newCastDatas = $connection
                ->table('casts')
                ->whereNull('deleted_at')
                ->select($this->defaultNewColumns)
                ->get();
        $formatNewCastDatas = [];
        foreach($newCastDatas as $data) {
            $formatNewCastDatas[$data->id] = $data;
        }
        //castIdだけ取得
        $newCastArrayId = $newCastDatas->pluck('id')->toArray();
        // デフォルトの接続情報を復元
        Config::set("database.connections.$defaultConnection", $defaultConfig);

        // $connectionName = 'x459x_staff';
        $connectionName = env('DB_PREFIX_X459X') . 'staff';
        list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
        // 動的な接続を使用してクエリを実行
        $connection = DB::connection($connectionName);
        $oldCastDatas = $connection
                ->table('staff')
                ->whereIn('staffid',$newCastArrayId)
                ->where('st78','none')
                ->select($this->defaultOldColumns)
                ->get();
        // デフォルトの接続情報を復元
        Config::set("database.connections.$defaultConnection", $defaultConfig);
        $formatData = [];
        foreach($oldCastDatas as $index => $data) {
            $formatData[$index] = $data;
            if(isset($formatNewCastDatas[$data->staffid])) {
                $formatData[$index]->username = $formatNewCastDatas[$data->staffid]->username;
                $formatData[$index]->password = $formatNewCastDatas[$data->staffid]->password;
                $formatData[$index]->generate_link_register_at = $formatNewCastDatas[$data->staffid]->generate_link_register_at;
                $formatData[$index]->token_register = $formatNewCastDatas[$data->staffid]->token_register;
                // $formatData[$index]->register_at = $formatNewCastDatas[$data->staffid]->register_at;
                $formatData[$index]->register_at = null; //メールアドレスを新しく発行する必要あることからnullへ
                $formatData[$index]->block = $formatNewCastDatas[$data->staffid]->block;
                $formatData[$index]->post_email = $formatNewCastDatas[$data->staffid]->post_email;
                $formatData[$index]->avatar = $formatNewCastDatas[$data->staffid]->avatar;
            }
        }
        $parameter = [];
        foreach($formatData as $data) {
            $param = [];
            foreach($this->newColumns as $newKey => $oldKey) {
                if($newKey == 'is_public') {
                    $data->$oldKey = $data->$oldKey == 'start' ? 1 : 0;
                }
                if($newKey == 'deleted_at') {
                    $data->$oldKey = $data->$oldKey == 'none' ? 0 : 1;
                }
                if($newKey == 'is_auto') {
                    $data->$oldKey = $data->$oldKey == 'auto' ? 1 : 0;
                }
                if($newKey == 'stay_status') {
                    $data->$oldKey = isset($this->stayStatusColumns[$data->$oldKey]) ? $this->stayStatusColumns[$data->$oldKey] : 0;
                }
                if($newKey == 'exclusive_status') {
                    $data->$oldKey = isset($this->exclusiveStatusColumns[$data->$oldKey]) ? $this->exclusiveStatusColumns[$data->$oldKey] : 0;
                }
                if($newKey == 'figure') {
                    $data->$oldKey = isset($this->figureColumns[$data->$oldKey]) ? $this->figureColumns[$data->$oldKey] : 0;
                }
                // if($newKey == 'sokuhime_status') {
                //     $data->$oldKey = isset($this->sokuhimeColumns[$data->$oldKey]) ? $this->sokuhimeColumns[$data->$oldKey] : $data->$oldKey;
                // }
                if($newKey == 'is_sokuhime') {
                    $data->$oldKey = $data->$oldKey == 'YES' ? 1 : 0;
                }
                if($newKey == 'recruit_status') {
                    $data->$oldKey = isset($this->recruitColumns[$data->$oldKey]) ? $this->recruitColumns[$data->$oldKey] : 0;
                }
                //none削除
                if($newKey == 'age' ||$newKey == 'blood_type' ||$newKey == 'constellation' ||$newKey == 'height' ||$newKey == 'bust' || $newKey == 'cup'|| $newKey == 'waist'|| $newKey == 'hip') {
                    if($data->$oldKey == 'none') {
                        $data->$oldKey = NULL;
                    }
                }
                $param[$newKey] = $data->$oldKey;
            }
            $optionParameter[$data->staffid] = explode(',',$data->st24);
            $parameter[] = $param;
        }
        $parameter = collect($parameter);
        // 1000件ずつデータを入れる
        foreach ($parameter->chunk(1000) as $chunkParams) {
            M_Cast::insert($chunkParams->toArray());
        }

        // $parameter = [];
        // foreach($optionParameter as $staffId => $data) {
        //     foreach($data as $index => $optionId) {
        //         if($optionId) {
        //             $parameter[] = [
        //                 'created_at' => time(),
        //                 'updated_at' => time(),
        //                 'admin_id'   => $staffId,
        //                 'option_id'   => $optionId,
        //             ];
        //         }
        //     }
        // }
        // if($parameter) {
        //     ::insert($parameter);
        // }
    }
}
