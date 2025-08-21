<?php

namespace Database\Seeders;

use App\Models\M_Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MAreaSeeder extends Seeder
{
    protected $defaultColumns = [
        'areaid',
        // 'fd1',
        'fd2',
        'fd3',
        'fd4',
        'fd6',
        'fd12',
        'fd13',
        'fd14',
        'fd15',
    ];
    protected $newColumns = [
        'id' => 'areaid',
        'created_stamp'  => 'fd15',
        'updated_stamp'  => 'fd14',
        'deleted_at'  => 'fd13',
        'name' => 'fd2',
        'group_id' => 'fd3',
        'content' => 'fd4',
        // 'remarks' => 'fd4',
        'sort' => 'fd6',
        'is_public'  => 'fd12',
    ];
    protected $areaGroups = [
        'kagawa_tosan' => 1, 
        'kagawa_seisan' => 2,
        'ehime_chuyo' => 3, 
        'ehime_toyo' => 4,
        'ehime_nanyo' => 5
    ]; 
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $connectionName = 'x459x_conf';
        $connectionName = env('DB_PREFIX_X459X') . 'conf';
        list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
        // 動的な接続を使用してクエリを実行
        $connection = DB::connection($connectionName);
        $datas = $connection
                ->table('area')
                ->where('fd13','none')
                ->select($this->defaultColumns)
                ->get();
            
        // デフォルトの接続情報を復元
        Config::set("database.connections.$defaultConnection", $defaultConfig);
        $parameter = [];
        foreach($datas as $data) {
            $param = [];
            foreach($this->newColumns as $newKey => $oldKey) {
                if($newKey == 'is_public') {
                    $data->$oldKey = $data->$oldKey == 'start' ? 1 : 0;
                }
                if($newKey == 'deleted_at') {
                    $data->$oldKey = $data->$oldKey == 'none' ? 0 : 1;
                }
                if($newKey == 'group_id') {
                    $data->$oldKey = isset($this->areaGroups[$data->$oldKey]) ? $this->areaGroups[$data->$oldKey] : 0;
                }
                $param[$newKey] = $data->$oldKey;
            }
            $parameter[] = $param;
        }
        $parameter = collect($parameter);
        // 1000件ずつデータを入れる
        foreach ($parameter->chunk(1000) as $chunkParams) {
            M_Area::insert($chunkParams->toArray());
        }
    }
}
