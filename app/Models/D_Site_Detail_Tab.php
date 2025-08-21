<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class D_Site_Detail_Tab extends Model
{
    use HasFactory;

    protected $table = 'd_site_detail_tab';
    const DEVICE_PC = 0;
    const DEVICE_SMART_PHONE = 1;
    const DEVICE_MOBILE = 2;

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
        "site_id",
        "title",
        "sub_title",
        "content",
        "sort_no",
        "is_display",
        "color",
        "background_color",
        "event"
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringData(object $query, array $filter): object
    {
        return $query
            ->where("deleted_at", 0)
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                return $query->where("site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["master_id"]), function ($query) use ($filter) {
                return $query->where("master_id", $filter["master_id"]);
            })
            ->when(!empty($filter["is_display"]), function ($query) use ($filter) {
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
    public function scopeFetchFilteringDataPluckId(object $query, array $filter)
    {
        return $query
            ->where("deleted_at", 0)
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                return $query->where("site_id", $filter["site_id"]);
            })
            ->pluck('master_detail_id')
            ->toArray();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringSiteData(object $query, array $filter)
    {
        try {
            return $query
                // ->where("deleted_at", 0)
                ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                    return $query->where("site_id", $filter["site_id"]);
                })
                ->when(!empty($filter["master_id"]), function ($query) use ($filter) {
                    return $query->where("master_id", $filter["master_id"]);
                })
                ->when(!empty($filter["is_display"]), function ($query) use ($filter) {
                    return $query->where("is_display", $filter["is_display"]);
                })
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringSiteEventData(object $query, array $filter)
    {
        try {
            return $query
                ->where("deleted_at", 0)
                ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                    return $query->where("site_id", $filter["site_id"]);
                })
                ->when(!empty($filter["event"]), function ($query) use ($filter) {
                    return $query->where("event", $filter["event"]);
                })
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
