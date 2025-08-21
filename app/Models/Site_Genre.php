<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site_Genre extends Model
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
    protected $table = 'site_genre';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "site_id",
        "genre_id",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "site_genre.id",
        "site_id",
        "genre_id",
        "name",
        "group_id",
        "content",
        "remarks",
        "sort",
        "is_public",
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
    public function scopeFetchFilteringData(object $query,array $filter):object
    {
        return $query
        ->where("site_genre.deleted_at", 0)
        ->when(!empty($filter['site_id']), function($query) use($filter){
            if (is_array($filter['site_id'])) {
                return $query->whereIn("site_id", $filter['site_id']);
            }
            return $query->where("site_id", $filter['site_id']);
        })
        ->leftjoin('m_genre','m_genre.id','genre_id')
        ->select($this->defaultFetchJoinColumns)
        ->get();
    }
}
