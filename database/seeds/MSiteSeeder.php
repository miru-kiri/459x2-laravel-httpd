<?php

namespace Database\Seeders;

use App\Models\M_Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MSiteSeeder extends Seeder
{
    protected $newColumns = [
        'id' => 'siteid',
        'created_at'  => 'fd80',
        'updated_at'  => 'fd79',
        'deleted_at'  => 'fd78',
        'shop_id'  => 'fd1',
        'url'  => 'fd2',
        'name'  => 'fd3',
        'style'  => 'fd4',
        'top_url'  => 'fd5',
        'recruit_key'  => 'fd7',
        // 'template'  => 'fd',
        'is_cosmo'  => 'fd11',
        'sort'  => 'fd12',
        'remarks'  => 'fd13',
        'content'  => 'fd14',
        'switching_time'  => 'fd15',
        'blog_owner_host'  => 'fd16',
        'blog_owner_user'  => 'fd17',
        'blog_owner_pass'  => 'fd18',
        'blog_staff_host'  => 'fd19',
        'blog_staff_user'  => 'fd20',
        'blog_staff_pass'  => 'fd21',
        'mail_magazine_url'  => 'fd22',
        'mail_magazine_key'  => 'fd23',
        'mail_magazine_create_mail'  => 'fd24',
        'mail_magazine_delete_mail'  => 'fd25',
        'recruit_line_url'  => 'fd26',
        'recruit_line_id'  => 'fd27',
        'analytics_code'  => 'fd28',
        'analytics_api'  => 'fd29',
        'is_https'  => 'fd30',
        'portal_template_url'  => 'fd31',
        'portal_tab'  => 'fd32',
        'staff_sort'  => 'fd33',
        'open_time'  => 'fd34',
        'close_time'  => 'fd35',
        'is_externally_server'  => 'fd74',
        'is_public'  => 'fd77',
    ];
    protected $styleColumns = [
        'official01_shop' => 1,
        'official02_shop' => 2,
        'recruit' => 3,
        'portal' => 4,
    ];
    protected $staffSortColumns = [
        'web' => 1,
        'scd' => 2,
        'name' => 3,
        'shopno' => 4,
    ];

    protected $fuzokuAry = [
        'soapland',
        'fashion_health',
        'delivery_health',
        'exciting_pub',
        'psalon',
    ];
    protected $estheAry = [
        'aroma_esthe'
    ];
    protected $caba = [
        'caba'
    ];
    protected $sexCaba = [
        'sexy_caba'
    ];
    protected $eat = [
        'eat'
    ];
    protected $companion = [
        'enkai_companion',
        'deri_companion'
    ];
    // protected $style = config('site.style');
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $connectionName = 'x459x_corp';
        $connectionName = env('DB_PREFIX_X459X') . 'corp';
        list($defaultConnection,$defaultConfig) = getDynamicConnection($connectionName,env('DB_OLD_HOST'),env('DB_OLD_USERNAME'),env('DB_OLD_PASSWORD'));
        // 動的な接続を使用してクエリを実行
        $connection = DB::connection($connectionName);
        $datas = $connection
                ->table('site')
                ->where('fd78','none')
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
                if($newKey == 'style') {
                    $data->$oldKey = isset($this->styleColumns[$data->$oldKey]) ? $this->styleColumns[$data->$oldKey] : 0;
                }
                if($newKey == 'staff_sort') {
                    $data->$oldKey = isset($this->staffSortColumns[$data->$oldKey]) ? $this->staffSortColumns[$data->$oldKey] : 0;
                }
                if($newKey == 'is_cosmo') {
                    $data->$oldKey = $data->$oldKey == 'cosmogroup' ? 1 : 0;
                }
                if($newKey == 'is_https') {
                    $data->$oldKey = $data->$oldKey == 'NO' ? 0 : 1;
                }
                if($newKey == 'is_externally_server') {
                    $data->$oldKey = $data->$oldKey == 'NO' ? 0 : 1;
                }
                if($newKey == 'sort') {
                    $data->$oldKey = $data->$oldKey > 0 ? $data->$oldKey :  0;
                }
                if($newKey == 'close_time') {
                    if($data->$oldKey == '00:00') {
                        $data->$oldKey = '24:00';
                    }
                    if($data->$oldKey == '25:00') {
                        $data->$oldKey = '01:00';
                    }
                    if($data->$oldKey == '26:00') {
                        $data->$oldKey = '02:00';
                    }
                    if($data->$oldKey == '27:00') {
                        $data->$oldKey = '02:00';
                    }
                    if($data->$oldKey == '28:00') {
                        $data->$oldKey = '03:00';
                    }
                    $data->$oldKey = $data->$oldKey > 0 ? $data->$oldKey :  0;
                }
                $param[$newKey] = $data->$oldKey;
            }
            $param['template'] = 0;
            // カンマが見つかった場合は、カンマ以降の文字を削除
            $formatGenre = $data->fd75;
            $isComma = strpos($formatGenre, ',');
            if ($isComma !== false) {
                $formatGenre = substr($formatGenre, 0, $isComma);
            }
            if(in_array($formatGenre,$this->fuzokuAry)) {
                $param['template'] = 1;
            }
            if(in_array($formatGenre,$this->estheAry)) {
                $param['template'] = 2;
            }
            if(in_array($formatGenre,$this->caba)) {
                $param['template'] = 3;
            }
            if(in_array($formatGenre,$this->sexCaba)) {
                $param['template'] = 4;
            }
            if(in_array($formatGenre,$this->eat)) {
                $param['template'] = 5;
            }
            if(in_array($formatGenre,$this->companion)) {
                $param['template'] = 6;
            }
            $parameter[] = $param;
        }
        $parameter = collect($parameter);
        // 1000件ずつデータを入れる
        foreach ($parameter->chunk(1000) as $chunkParams) {
            M_Site::insert($chunkParams->toArray());
        }
    }
}
