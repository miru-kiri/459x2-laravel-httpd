<?php

namespace Database\Seeders;

use App\Models\M_Cast_Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MCastQuestionSeeder extends Seeder
{
    protected $defaultColumns = [
        'profid',
        'pr1',
        'pr2',
        'pr3',
        'pr4',
        'pr12',
        'pr13',
        'pr14',
        'pr15',
    ];
    protected $newColumns = [
        'id' => 'profid',
        'site_id' => 'pr1',
        'question' => 'pr2',
        'default_answer' => 'pr3',
        'sort_no' => 'pr4',
        'is_public'  => 'pr12',
        'deleted_at'  => 'pr13',
        'updated_stamp'  => 'pr14',
        'created_stamp'  => 'pr15',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $connectionName = 'x459x_staff';
        $connectionName = env('DB_PREFIX_X459X') . 'staff';
        list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
        // 動的な接続を使用してクエリを実行
        $connection = DB::connection($connectionName);
        $datas = $connection
                ->table('profile_m')
                ->where('pr13','none')
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
                $param[$newKey] = $data->$oldKey;
            }
            $parameter[] = $param;
        }
        $parameter = collect($parameter);
        // 1000件ずつデータを入れる
        foreach ($parameter->chunk(1000) as $chunkParams) {
            M_Cast_Question::insert($chunkParams->toArray());
        }
    }
}
