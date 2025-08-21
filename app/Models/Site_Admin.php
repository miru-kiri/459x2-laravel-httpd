<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site_Admin extends Model
{
    use HasFactory;
    /**
     * 自動タイムスタンプを無効
     *
     * @var boolean
     */
    public $timestamps = false;
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'site_admin';
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "site_id",
        "admin_id",
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
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterAdminId(object $query,int $adminId):object
    {
        return $query
        ->where([
            "deleted_at" => 0,
            "admin_id" => $adminId
        ])
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @param int $adminId
     * @return object
     */
    public function scopeSoftDelete(object $query,int $adminId):bool
    {
        return $query
        ->where([
            'deleted_at' => 0,
            'admin_id' => $adminId
        ])
        ->update(['updated_at' => time(),'deleted_at' => time()]);
    }
}
