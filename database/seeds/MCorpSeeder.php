<?php

namespace Database\Seeders;

use App\Models\M_Corp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MCorpSeeder extends Seeder
{
    protected $defaultColumns = [
        'corpid',
        'fd1',
        'fd2',
        'fd3',
        'fd4',
        'fd5',
        'fd6',
        'fd7',
        'fd8',
        'fd9',
        'fd10',
        'fd11',
        'fd12',
        'fd13',
        'fd57',
        'fd58',
        'fd59',
        'fd60'
    ];
    protected $newColumns = [
        'id' => 'corpid',
        'created_at' => 'fd60',
        'updated_at' => 'fd59',
        'deleted_at' => 'fd58',
        'name' => 'fd1',
        'short_name' => 'fd13',
        'type' => 'fd2',
        'responsible_name' => 'fd3',
        'postal_code' => 'fd4',
        'address1' => 'fd5',
        'address2' => 'fd6',
        'address3' => 'fd7',
        'tel' => 'fd8',
        'fax' => 'fd9',
        'remakers' => 'fd10',
        'is_cosmo' => 'fd11',
        'sort' => 'fd12',
        'is_public' => 'fd57',    
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $connectionName = 'x459x_corp';
        $connectionName = env('DB_PREFIX_X459X') . 'corp';
        list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
        // 動的な接続を使用してクエリを実行
        $connection = DB::connection($connectionName);
        $datas = $connection
                ->table('corp')
                ->where('fd58','none')
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
                if($newKey == 'is_cosmo') {
                    $data->$oldKey = $data->$oldKey == 'cosmogroup' ? 1 : 0;
                }
                if($newKey == 'type') {
                    $data->$oldKey = $data->$oldKey == 'corp' ? 1 : 0;
                }
                $param[$newKey] = $data->$oldKey;
            }
            $parameter[] = $param;
        }
        $parameter = collect($parameter);
        // 1000件ずつデータを入れる
        foreach ($parameter->chunk(1000) as $chunkParams) {
            M_Corp::insert($chunkParams->toArray());
        }
    }
}
