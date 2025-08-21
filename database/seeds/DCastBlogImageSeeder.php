<?php

namespace Database\Seeders;

use App\Models\D_Cast_Blog_Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DCastBlogImageSeeder extends Seeder
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
                    ->table('article_images')
                    ->whereNull('deleted_at')
                    ->get()
                    ->toArray();
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = collect($datas);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                D_Cast_Blog_Image::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
