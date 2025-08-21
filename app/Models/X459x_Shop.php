<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Shop extends Model
{
    use HasFactory;
    // protected $table = 'x459x_corp.shop';
    protected $table;
    protected $primaryKey = 'shopid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'corp' . '.shop';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['shopid'];
    /**
     * データ更新
     *
     * @param object $query
     * @param array $parameter
     * @return bool
     */
    public function scopeUpsertData(object $query,array $parameter)
    {
        if(isset($parameter['id'])) {
            return $query
                ->where('shopid',$parameter['id'])
                ->update([
                    "fd1" => $parameter['corporate_id'],
                    "fd2" => $parameter['name'],
                    "fd3" => $parameter['style'],
                    "fd4" => $parameter['genre'],
                    "fd5" => $parameter['responsible_name'],
                    "fd6" => $parameter['postal_code'],
                    "fd7" => $parameter['address1'],
                    "fd8" => $parameter['address2'],
                    "fd9" => $parameter['address3'],
                    "fd10" => $parameter['tel'],
                    "fd11" => $parameter['fax'],
                    "fd12" => $parameter['is_notification'] == 1 ? 'ok' : 'none',
                    "fd13" => $parameter['remarks'],
                    "fd14" => $parameter['sort'],
                    "fd15" => $parameter['applying'],
                    "fd16" => $parameter['short_name'],
                    "fd17" => $parameter['is_cosmo'] == 1 ? 'cosmogroup' :  'general',
                    "fd18" => $parameter['opening_text'],
                    "fd19" => $parameter['holiday_text'],
                    "fd20" => $parameter['mail'],
                    // "fd21" => $parameter[''], //求人番号
                    // "fd22" => $parameter[''],//求人メール
                    "fd23" => $parameter['short_kana'], 
                    "fd77" => $parameter['is_public'] == 1 ? 'start' : 'stop',
                    "fd78" => 'none',
                    "fd79" => time()
                    // "fd80" => time()
                ]);
        } else {
            return $query
                ->insert([
                    "fd1" => $parameter['corporate_id'],
                    "fd2" => $parameter['name'],
                    "fd3" => $parameter['style'],
                    "fd4" => $parameter['genre'],
                    "fd5" => $parameter['responsible_name'],
                    "fd6" => $parameter['postal_code'],
                    "fd7" => $parameter['address1'],
                    "fd8" => $parameter['address2'],
                    "fd9" => $parameter['address3'],
                    "fd10" => $parameter['tel'],
                    "fd11" => $parameter['fax'],
                    "fd12" => $parameter['is_notification'] == 1 ? 'ok' : 'none',
                    "fd13" => $parameter['remarks'],
                    "fd14" => $parameter['sort'],
                    "fd15" => $parameter['applying'],
                    "fd16" => $parameter['short_name'],
                    "fd17" => $parameter['is_cosmo'] == 1 ? 'cosmogroup' :  'general',
                    "fd18" => $parameter['opening_text'],
                    "fd19" => $parameter['holiday_text'],
                    "fd20" => $parameter['mail'],
                    // "fd21" => $parameter[''], //求人番号
                    // "fd22" => $parameter[''],//求人メール
                    "fd23" => $parameter['short_kana'], 
                    "fd77" => $parameter['is_public'] == 1 ? 'start' : 'stop',
                    "fd78" => 'none',
                    "fd79" => time(),
                    "fd80" => time()
                ]);
        }
    }
}
