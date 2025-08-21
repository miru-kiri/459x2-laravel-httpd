<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Site_Detail_Tab extends Model
{
    use HasFactory;

    protected $table = 'm_site_detail_tab';

    public $timestamps = false;
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
        "master_id",
        "title",
        "sub_title",
        "content",
        "sort_no",
        "is_display",
        "event"
        // "color",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopefetchFilteringData(object $query,array $filter):object
    {
        return $query
        ->where("deleted_at",0)
        ->when(!empty($filter["master_id"]), function($query) use($filter){
            return $query->where("master_id", $filter["master_id"]);
        })
        ->when(!empty($filter["is_display"]), function($query) use($filter){
            return $query->where("is_display", $filter["is_display"]);
        })
        ->select($this->defaultFetchColumns)
        ->orderby('sort_no')
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchMasterIdAryData(object $query,array $masterId):object
    {
        return $query
        ->where("deleted_at",0)
        ->whereIn("master_id", $masterId)
        ->select($this->defaultFetchColumns)
        ->orderby('sort_no')
        ->get();
    }
}
