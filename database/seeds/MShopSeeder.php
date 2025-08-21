<?php

namespace Database\Seeders;

use App\Models\M_Genre;
use App\Models\M_Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MShopSeeder extends Seeder
{
    protected $newColumns = [
        'id' => 'shopid',
        'created_at' => 'fd80',
        'updated_at' => 'fd79',
        'deleted_at' => 'fd78',
        'corporate_id' => 'fd1',
        'name' => 'fd2',
        'kana' => 'fd23',
        'short_name' => 'fd16',
        // 'short_kana' => '',
        'style' => 'fd3',
        'genre_id' => 'fd4',
        'responsible_name' => 'fd5',
        'postal_code' => 'fd6',
        'address1' => 'fd7',
        'address2' => 'fd8',
        'address3' => 'fd9',
        'tel' => 'fd10',
        'fax' => 'fd11',
        'is_notification' => 'fd12',
        // 'remarks' => 'fd',
        'sort' => 'fd14',
        'applying' => 'fd15',
        'is_cosmo' => 'fd17',
        'mail' => 'fd20',
        'recruit_tel' => 'fd21',
        'recruit_mail' => 'fd22',
        'is_public' => 'fd77',
        'opening_text' => 'fd18',
        'holiday_text' => 'fd19'
    ];
    protected $styleColumns = [
        'shop' => 1,
        'unshop' => 2,
        'office' => 3,
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
            // $connectionName = 'x459x_corp';
            $connectionName = env('DB_PREFIX_X459X') . 'corp';
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('shop')
                    ->where('fd78','none')
                    ->get();
            // デフォルトの接続情報を復元
            Config::set("database.connections.$defaultConnection", $defaultConfig);
            $formatGenre = [];
            $genre = M_Genre::FetchAll();
            foreach($genre as $g) {
                $formatGenre[$g->old_name] = $g->id;
            }
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
                    if($newKey == 'is_notification') {
                        $data->$oldKey = $data->$oldKey == 'ok' ? 1 : 0;
                        // $data->$oldKey = isset($this->rowColumns[$data->$oldKey]) ? $this->rowColumns[$data->$oldKey] : 0;
                    }
                    if($newKey == 'style') {
                        $data->$oldKey = isset($this->styleColumns[$data->$oldKey]) ? $this->styleColumns[$data->$oldKey] : 0;
                    }
                    if($newKey == 'is_cosmo') {
                        $data->$oldKey = $data->$oldKey == 'cosmogroup' ? 1 : 0;
                        // $data->$oldKey = isset($this->rowColumns[$data->$oldKey]) ? $this->rowColumns[$data->$oldKey] : 0;
                    }
                    if($newKey == 'genre_id') {
                        $data->$oldKey = isset($formatGenre[$data->$oldKey]) ? $formatGenre[$data->$oldKey] : 0;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                //システム上判断できないから一旦固定で入れる。
                // $param = [
                //     'workday_start_time' => '0900',
                //     'workday_end_time' => '2400',
                //     'friday_start_time' => '0900',
                //     'friday_end_time' => '2400',
                //     'saturday_start_time' => '0900',
                //     'saturday_end_time' => '2400',
                //     'sunday_start_time' => '0900',
                //     'sunday_end_time' => '2400',
                //     'holiday_start_time' => '0900',
                //     'holiday_end_time' => '2400',
                // ];
                $parameter[] = $param;
            }
            $parameter = collect($parameter);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                M_Shop::insert($chunkParams->toArray());
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
