<?php

namespace Database\Seeders;

use App\Models\Site_Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SiteImageListSeeder extends Seeder
{
    protected $newColumns = [
        // 'id' => 'admid',
        'created_at' => 'sim30',
        'site_id' => 'sim1',
        'category_id' => '',
        'image' => '',
        'url' => '',
        'sort_no' => 'sim3',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            // $connectionName = 'x459x_image';
            $connectionName = env('DB_PREFIX_X459X') . 'image';
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('site_img')
                    // ->where('sim','none')
                    ->get();
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = [];
            foreach($datas as $data) {
                $param = [];
                foreach($this->newColumns as $newKey => $oldKey) {
                    if($newKey == 'image') {
                        $data->$oldKey = $data->sim8.'img_'.$data->sim5;
                    }
                    if($newKey == 'url') {
                        $data->$oldKey = env('APP_URL').'/storage'.$data->sim8.'img_'.$data->sim5;
                    }
                    if($newKey == 'category_id') {
                        $data->$oldKey = 2;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                $parameter[] = $param;
            }
            $parameter = collect($parameter);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                Site_Image::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
