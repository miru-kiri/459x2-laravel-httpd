<?php

namespace Database\Seeders;

use App\Models\D_Shop_Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DShopBlogSeeder extends Seeder
{
    protected $newColumns = [
        'id' => 'kjid',
        'site_id' => 'fd1',
        'conts' => 'fd2',
        'published_at' => 'fd3',
        'title' => 'fd4',
        'content' => 'fd5',
        'content2' => 'fd6',
        'delete_flg' => 'fd28',
        'updated_at'  => 'fd29',
        'created_at'  => 'fd30',
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
            // $connectionName = 'x459x_kiji';
            $connectionName = env('DB_PREFIX_X459X') . 'kiji';
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('kiji')
                    ->where('fd28','none')
                    ->where('fd3','>=',date(date('Y-m-d H:i:s',strtotime("-1 year"))))
                    ->orderby('fd3')
                    ->get();
                    // ->limit(100)
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = [];
            foreach($datas as $data) {
                $param = [];
                foreach($this->newColumns as $newKey => $oldKey) {
                    if($newKey == 'delete_flg') {
                        $data->$oldKey = 0;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                $parameter[] = $param;
            }
            // dd($parameter[0]);
            $parameter = collect($parameter);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                D_Shop_Blog::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            // echo $e->getMessage();
        }
    }
}
