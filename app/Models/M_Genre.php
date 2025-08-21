<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class M_Genre extends Model
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
    protected $table = 'm_genre';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "m_genre.id",
        "m_genre.name",
        "m_genre.old_name",
        "m_genre_group.name as group_name",
        "category_id",
        "group_id",
        "content",
        "remarks",
        "sort",
        "is_public",
        "search_category"
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
        ->where("m_genre.deleted_at", 0)
        ->select($this->defaultFetchColumns)
        ->leftjoin("m_genre_group","m_genre_group.id","m_genre.group_id")
        ->selectRaw("
                CASE category_id
                    WHEN 1 THEN '高収入系'
                    WHEN 2 THEN '乾杯系'
                    WHEN 3 THEN '一般系'
                    ELSE '未登録'
                END AS category_name
                ")
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchIsSerch(object $query):object
    {
        return $query
        ->where([
            "m_genre.deleted_at" => 0,
        ])
        ->where("m_genre.search_category",">",0)
        ->leftjoin("m_genre_group","m_genre_group.id","m_genre.group_id")
        ->select($this->defaultFetchColumns)        
        ->orderby('sort')
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchIsSerchPluckAry(object $query): array
    {
        return $query
        ->where([
            "m_genre.deleted_at" => 0,
        ])
        ->where("m_genre.search_category",">",0)
        ->pluck('id')
        ->toArray();
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
                'group_id' => $parameter['group_id'],
                'name' => $parameter['name'],
                'content' => $parameter['content'],
                'sort' => $parameter['sort'],
                'remarks' => $parameter['remarks'],
                'is_public' => $parameter['is_public']
            ])->save();
    }
}
