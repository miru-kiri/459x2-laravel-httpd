<?php

namespace Database\Seeders;

use App\Models\Cast_Answer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CastAnswerSeeder extends Seeder
{
    protected $defaultColumns = [
        'prfid',
        'prf1',
        'prf2',
        'prf3',
        'prf4',
        'prf18',
        'prf19',
        'prf20',
    ];
    protected $newColumns = [
        'id' => 'prfid',
        'cast_id' => 'prf1',
        'question_id' => 'prf2',
        'answer' => 'prf3',
        'is_public'  => 'prf4',
        'deleted_at'  => 'prf18',
        'updated_stamp'  => 'prf19',
        'created_stamp'  => 'prf20',
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
                ->table('profile')
                ->where('prf18','none')
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
            Cast_Answer::insert($chunkParams->toArray());
        }
    }
}
