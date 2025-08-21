<?php

namespace Database\Seeders;

use App\Models\D_Shop_Manager_Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DShopManagerBlogSeeder extends Seeder
{
    protected $newColumns = [
        'old_id' => 'sibid',
        'site_id' => 'fd1',
        'old_kiji_id' => 'fd2',
        'mail' => 'fd3',
        'published_at' => 'fd4',
        'title' => 'fd5',
        'content' => 'fd6',
        'content2' => 'fd7',
        'content3' => 'fd8',
        'mail2' => 'fd9',
        'image' => 'fd10',
        'image_direction' => 'fd11',
        'site_name' => 'fd12',
        'image_directory' => 'fd13',
        'old_category' => 'fd14',
        'category_name' => 'fd15',
        'delete_flg' => 'fd31',
        'updated_at' => 'fd32',
        'created_at' => 'fd33',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set("memory_limit", "-1");
        try {
            DB::beginTransaction();
            // $connectionName = 'x459x_blog';
            $connectionName = env('DB_PREFIX_X459X') . 'blog';
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('site')
                    ->where('fd31','none')
                    ->get();
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = [];
            foreach($datas as $data) {
                $param = [];
                foreach($this->newColumns as $newKey => $oldKey) {
                    if($newKey == 'deleted_at') {
                        $data->$oldKey = $data->$oldKey == 'none' ? 0 : 1;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                $parameter[] = $param;
            }
            $parameter = collect($parameter);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                D_Shop_Manager_Blog::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
