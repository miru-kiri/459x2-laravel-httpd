<?php

namespace Database\Seeders;

use App\Models\D_Move;
use App\Models\D_Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DMoveSeeder extends Seeder
{
    protected $newColumns = [
        'id' => 'id',
        'created_at' => 'fld30',
        'shop_id' => 'fld1',
        'site_id' => 'fld2',
        'cast_id' => 'fld4',
        'file_path' => 'fld9',
        'file_name' => 'fld6',
        'cast_name' => 'fld11',
        'title' => 'fld12',
        'content' => 'fld13',
        'time' => 'fld14',
        'tag_name' => 'fld21',
        'is_cm_display'  => 'fld28',
        'is_display'  => 'fld29',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            // DB::beginTransaction();
            // $connectionName = 'x459x_movie';
            $connectionName = env('DB_PREFIX_X459X') . 'movie';
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            // dd($connection);
            $datas = $connection
                    ->table('tbl_upfile')
                    // ->where('fld29','none')
                    ->get();
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = [];
            foreach($datas as $data) {
                $param = [];
                foreach($this->newColumns as $newKey => $oldKey) {
                    if($newKey == 'shop_id') {
                        if(!$data->$oldKey){
                            $data->$oldKey = 0;
                        }
                    }
                    if($newKey == 'cast_id') {
                        $data->$oldKey = $data->$oldKey == 'free' ? 0 : $data->$oldKey;
                    }
                    if($newKey == 'is_cm_display') {
                        $data->$oldKey = $data->$oldKey == 'none' ? 0 : 1;
                    }
                    if($newKey == 'is_display') {
                        $data->$oldKey = $data->$oldKey == 'none' ? 0 : 1;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                $parameter[] = $param;
            }
            $parameter = collect($parameter);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                D_Movie::insert($chunkParams->toArray());
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}
