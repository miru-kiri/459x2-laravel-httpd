<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Corp extends Model
{
    use HasFactory;
    // protected $table = 'x459x_corp.corp';
    protected $table;
    protected $primaryKey = 'corpid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'corp' . '.corp';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['corpid'];
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
                ->where('corpid',$parameter['id'])
                ->update([
                    "fd1" => $parameter['name'],
                    "fd2" => $parameter['type'] == 1 ? 'corp' : 'person',
                    "fd3" => $parameter['responsible_name'],
                    "fd4" => $parameter['postal_code'],
                    "fd5" => $parameter['address1'],
                    "fd6" => $parameter['address2'],
                    "fd7" => $parameter['address3'],
                    "fd8" => $parameter['tel'],
                    "fd9" => $parameter['fax'],
                    "fd10" => $parameter['remarks'],
                    "fd11" => $parameter['is_cosmo'] == 1 ? 'cosmogroup' :  'general',
                    "fd12" => $parameter['sort'],
                    "fd13" => $parameter['short_name'],
                    "fd57" => $parameter['is_public'] == 1 ? 'start' : 'stop',
                    "fd58" => 'none',
                    "fd59" => time(),
                    // "fd60" => time()
                ]);
        } else {
            return $query
                ->insert([
                    "fd1" => $parameter['name'],
                    "fd2" => $parameter['type'] == 1 ? 'corp' : 'person',
                    "fd3" => $parameter['responsible_name'],
                    "fd4" => $parameter['postal_code'],
                    "fd5" => $parameter['address1'],
                    "fd6" => $parameter['address2'],
                    "fd7" => $parameter['address3'],
                    "fd8" => $parameter['tel'],
                    "fd9" => $parameter['fax'],
                    "fd10" => $parameter['remarks'],
                    "fd11" => $parameter['is_cosmo'] == 1 ? 'cosmogroup' :  'general',
                    "fd12" => $parameter['sort'],
                    "fd13" => $parameter['short_name'],
                    "fd57" => $parameter['is_public'] == 1 ? 'start' : 'stop',
                    "fd58" => 'none',
                    "fd59" => time(),
                    "fd60" => time()
                ]);
        }
    }
}
