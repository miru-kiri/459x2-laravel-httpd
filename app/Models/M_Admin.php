<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class M_Admin extends Model
{
    use HasFactory;
    /**
     * 自動タイムスタンプを無効
     *
     * @var boolean
     */
    public $timestamps = false;
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'm_admin';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "name",
        "login_id",
        "password",
        "role",
        "mail",
        "is_public"
    ];
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchAll(object $query):object
    {
        return $query
        ->where("deleted_at", 0)
        ->select($this->defaultFetchColumns)
        ->selectRaw("
                CASE role
                    WHEN 1 THEN '管理者'
                    WHEN 2 THEN 'エリア管理者'
                    WHEN 3 THEN '店舗管理者'
                    WHEN 4 THEN '機密管理者'
                    ELSE '未登録'
                END AS role_name
                ")
        ->get();
    }
    /**
     * 条件に合うデータ取得
     *
     * @param object $query
     * @param string $loginId
     * @param string $password
     * @return 
     */
    public function scopeAdminLogin(object $query, $loginId, $password)
    {
        try {
            return $query
            ->where([
                'deleted_at' => 0,
                'password'   => $password,
                'login_id'   => $loginId
            ])
            ->select($this->defaultFetchColumns)
            ->selectRaw("
                    CASE role
                        WHEN 1 THEN '管理者'
                        WHEN 2 THEN 'エリア管理者'
                        WHEN 3 THEN '店舗管理者'
                        WHEN 4 THEN '機密管理者'
                        ELSE '未登録'
                    END AS role_name
            ")
            ->firstOrFail();
        } catch(ModelNotFoundException $ex) {
            return false;
        }
    }
    /**
     * データ更新
     *
     * @param object $query
     * @param array $parameter
     * @return bool
     */
    public function scopeSaveData(object $query,array $parameter):bool
    {
        return $query
            ->findOrFail($parameter["id"])
            ->fill([
                "name" => $parameter['name'],
                "login_id" => $parameter['login_id'],
                "password" => $parameter['password'],
                "mail" => $parameter['mail'],
                "role" => $parameter['role'],
                "is_public" => $parameter['is_public']
            ])->save();
    }
}
