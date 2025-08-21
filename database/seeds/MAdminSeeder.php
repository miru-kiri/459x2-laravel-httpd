<?php

namespace Database\Seeders;

use App\Models\M_Admin;
use App\Models\Site_Admin;
use App\Models\Site_Area;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MAdminSeeder extends Seeder
{
    protected $defaultColumns = [
        'admid',
        'adm1',
        'adm2',
        'adm3',
        'adm4',
        'adm5',
        'adm6',
        'adm47',
        'adm48',
        'adm49',
        'adm50',
    ];
    protected $newColumns = [
        'id' => 'admid',
        'created_at' => 'adm50',
        'updated_at' => 'adm49',
        'deleted_at' => 'adm48',
        'name' => 'adm1',
        'login_id' => 'adm2',
        'password' => 'adm3',
        'role' => 'adm4',
        'mail'  => 'adm6',
        'is_public'  => 'adm47',
    ];
    protected $rowColumns = [
        'admin' => 1,
        '459x_admin' => 1,
        'area_user' => 2,
        'site_user' => 3,
        'st50m_admin' => 4,
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
            // $connectionName = 'x459x_admin';
            $connectionName = env('DB_PREFIX_X459X') . 'admin';
            list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
            // 動的な接続を使用してクエリを実行
            $connection = DB::connection($connectionName);
            $datas = $connection
                    ->table('admin')
                    ->where('adm48','none')
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
                    if($newKey == 'role') {
                        $data->$oldKey = isset($this->rowColumns[$data->$oldKey]) ? $this->rowColumns[$data->$oldKey] : 0;
                    }
                    $param[$newKey] = $data->$oldKey;
                }
                $siteParameter[$data->admid] = explode(',',$data->adm5);
                $parameter[] = $param;
            }
            $parameter = collect($parameter);
            // 1000件ずつデータを入れる
            foreach ($parameter->chunk(1000) as $chunkParams) {
                M_Admin::insert($chunkParams->toArray());
            }
            $parameter = [];
            foreach($siteParameter as $adminId => $data) {
                foreach($data as $index => $siteId) {
                    if($siteId) {
                        $parameter[] = [
                            'created_at' => time(),
                            'updated_at' => time(),
                            'admin_id'   => $adminId,
                            'site_id'   => $siteId,
                        ];
                    }
                }
            }
            if($parameter) {
                Site_Admin::insert($parameter);
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
