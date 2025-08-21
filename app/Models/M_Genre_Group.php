<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Genre_Group extends Model
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
    protected $table = 'm_genre_group';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "name",
        "category_id",        
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
                CASE category_id
                    WHEN 1 THEN '高収入'
                    WHEN 2 THEN '乾杯系'
                    WHEN 3 THEN '一般系'
                    ELSE '未登録'
                END AS category_name
                ")
        ->get();
    }
}
