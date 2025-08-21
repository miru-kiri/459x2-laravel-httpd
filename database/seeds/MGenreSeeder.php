<?php

namespace Database\Seeders;

use App\Models\M_Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MGenreSeeder extends Seeder
{
    protected $defaultColumns = [
        'genreid',
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
        'id' => 'genreid',
        'created_at'  => 'fd15',
        'updated_at'  => 'fd14',
        'deleted_at'  => 'fd13',
        'name' => 'fd2',
        'old_name' => 'fd1',
        'group_id' => 'fd3',
        'content' => 'fd4',
        'sort' => 'fd6',
        'is_public'  => 'fd12',
    ];
    protected $genreGroups = [
        'cate_adult' => 1,
        'cate_nightclub' => 2,
        'cate_banquet' => 3,
        'cate_shopinfo' => 4,
        'cate_office' => 5, 
        'cate_office_estate' => 6,
        'cate_service_beauty' => 7,
        'cate_service_health' => 8,
        'cate_office_welfare' => 9,
        'cate_office_mediate' => 10,
        'cate_office_advertising' => 11,
        'cate_service_etc' => 12
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
                ->table('genre')
                ->where('fd13','none')
                // ->select($this->defaultColumns)
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
                    $data->$oldKey = isset($this->genreGroups[$data->$oldKey]) ? $this->genreGroups[$data->$oldKey] : 0;
                }
                $param[$newKey] = $data->$oldKey;
            }
            $parameter[] = $param;
        }
        $parameter = collect($parameter);
        // 1000件ずつデータを入れる
        foreach ($parameter->chunk(1000) as $chunkParams) {
            M_Genre::insert($chunkParams->toArray());
        }
    }
}
