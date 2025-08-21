<?php

namespace Database\Seeders;

use App\Models\D_Cast_Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DCastBlogSeeder extends Seeder
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
                    ->table('articles')
                    ->whereNull('deleted_at')
                    ->get()
                    ->toArray();

            $formatData = [];
            foreach($datas as $index => $data) {
                $formatData[$index]['id'] = $data->id;
                $formatData[$index]['old_id'] = $data->old_id;
                $formatData[$index]['cast_id'] = $data->cast_id;
                $formatData[$index]['title'] = $data->title;
                $formatData[$index]['content'] = $data->content;
                $formatData[$index]['created_at'] = $data->created_at;
                $formatData[$index]['updated_at'] = $data->updated_at;
                $formatData[$index]['deleted_by'] = $data->deleted_by;
                $formatData[$index]['published_at'] = $data->published_at;
                $formatData[$index]['type'] = $data->type;
                $formatData[$index]['is_draft'] = $data->is_draft;
            }
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $parameter = collect($formatData);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                D_Cast_Blog::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
