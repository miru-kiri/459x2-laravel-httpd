<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Admin extends Model
{
    
    use HasFactory;
    // protected $table = 'x459x_admin.admin';
    protected $table;
    protected $primaryKey = 'admid';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = env('DB_PREFIX_X459X') . 'admin' . '.admin';
    }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['admid'];

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
            ->where('admid',$parameter["id"])
            ->update([
                "adm1" => $parameter['name'],
                "adm2" => $parameter['login_id'],
                "adm3" => $parameter['password'],
                "adm4" => $parameter['role'],
                "adm5" => $parameter['adm5'],
                "adm6" => $parameter['mail'],
                "adm47" => $parameter['adm47'],
                "adm49" => time()
                // "adm50" => time()
            ]);
        } else {
            return $query
                ->insert([
                    "adm1" => $parameter['name'],
                    "adm2" => $parameter['login_id'],
                    "adm3" => $parameter['password'],
                    "adm4" => $parameter['role'],
                    "adm5" => $parameter['adm5'],
                    "adm6" => $parameter['mail'],
                    "adm47" => $parameter['adm47'],
                    "adm48" => 'none',
                    "adm49" => time(),
                    "adm50" => time()
                ]);
        }
    }
}
