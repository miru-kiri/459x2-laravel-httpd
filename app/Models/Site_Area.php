<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Site_Area extends Model
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
    protected $table = 'site_area';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "site_id",
        "area_id",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "site_area.id",
        "site_id",
        "area_id",
        "name",
        "group_id",
        "content",
        "remarks",
        "sort",
        "color",
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
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilterData(object $query,array $filter):object
    {
        return $query
        ->where("m_area.deleted_at", 0)
        ->where("site_area.deleted_at", 0)
        ->where("is_public", 1)
        ->when(!empty($filter['site_id']), function($query) use($filter){
            if (is_array($filter['site_id'])) {
                return $query->whereIn("site_id", $filter['site_id']);
            }
            return $query->where("site_id", $filter['site_id']);
        })
        ->leftjoin("m_area","m_area.id","site_area.area_id")
        ->select($this->defaultFetchJoinColumns)
        ->orderby('sort')
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilterFirstData(object $query,array $filter)
    {
        try {
            return $query
            ->where("m_area.deleted_at", 0)
            ->where("site_area.deleted_at", 0)
            ->where("is_public", 1)
            ->when(!empty($filter['site_id']), function($query) use($filter){
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("site_id", $filter['site_id']);
                }
                return $query->where("site_id", $filter['site_id']);
            })
            ->leftjoin("m_area","m_area.id","site_area.area_id")
            ->select($this->defaultFetchJoinColumns)
            ->orderby('sort')
            ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return false;
        }
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return array
     */
    public function scopeFetchFilterSiteIdAry(object $query,array $filter):array
    {
        return $query
        ->where("m_area.deleted_at", 0)
        ->where("site_area.deleted_at", 0)
        ->where("m_site.is_public", 1)
        ->where("m_area.is_public", 1)
        ->when(!empty($filter['site_id']), function($query) use($filter){
            if (is_array($filter['site_id'])) {
                return $query->whereIn("site_id", $filter['site_id']);
            }
            return $query->where("site_id", $filter['site_id']);
        })
        ->when(!empty($filter['area_id']), function($query) use($filter){
            if (is_array($filter['area_id'])) {
                return $query->whereIn("area_id", $filter['area_id']);
            }
            return $query->where("area_id", $filter['area_id']);
        })
        ->when(!empty($filter['template']), function($query) use($filter){
            if (is_array($filter['template'])) {
                return $query->whereIn("template", $filter['template']);
            }
            return $query->where("template", $filter['template']);
        })
        ->leftjoin("m_site","m_site.id","site_area.site_id")
        ->leftjoin("m_area","m_area.id","site_area.area_id")
        ->pluck('site_id')
        ->toArray();
    }
}
