<?php

namespace Database\Seeders;

use App\Models\Cast_Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MCastImageSeeder extends Seeder
{
    protected $defaultColumns = [
        // '',
        'stim1',
        'stim2',
        'stim3',
        'stim4',
        'stim5',
        'stim6',
        'stim7',
        'stim8',
        'stim9',
        'stim10',
        // 'stim29',
        'stim30',
    ];
    protected $newColumns = [
        'created_at'  => 'stim30',
        'updated_at'  => 'stim30',
        'site_id' => 'stim1',
        'cast_id' => 'stim2',
        'directory' => 'stim8',
        'path' => 'stim5',
        'is_direction' => 'stim6',
        'comment' => 'stim10',
        'sort_no' => 'stim9',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $connectionName = 'x459x_image';
        $connectionName = env('DB_PREFIX_X459X') . 'image';
        list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
        // 動的な接続を使用してクエリを実行
        $connection = DB::connection($connectionName);
        //delete_flgがない？
        $datas = $connection
                ->table('staf_img')
                // ->where('prf18','none')
                ->select($this->defaultColumns)
                ->get();
            
        // デフォルトの接続情報を復元
        Config::set("database.connections.$defaultConnection", $defaultConfig);
        $parameter = [];
        foreach($datas as $data) {
            $param = [];
            foreach($this->newColumns as $newKey => $oldKey) {
                if($newKey == 'is_direction') {
                    if($data->$oldKey == 'tate') {
                        $data->$oldKey = 0;
                    }
                    if($data->$oldKey == 'yoko') {
                        $data->$oldKey = 1;
                    }
                }
                $param[$newKey] = $data->$oldKey;
            }
            $parameter[] = $param;
        }
        $parameter = collect($parameter);
        // 1000件ずつデータを入れる
        foreach ($parameter->chunk(1000) as $chunkParams) {
            Cast_Image::insert($chunkParams->toArray());
        }
    }
}
