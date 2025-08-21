<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Cast_Schedule extends Model
{
    use HasFactory;
    protected $table = 'cast_schedule';

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
        "cast_id",
        "date",
        "is_work",
        "start_time",
        "end_time",
        "comment",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "cast_schedule.id as cast_schedule_id",
        "site_id",
        "cast_id",
        "date",
        "m_cast.sort as cast_sort",
        "m_cast.is_public",
        "is_work",
        "start_time",
        "end_time",
        "cast_schedule.comment as rest_comment",
        "source_name",
        "age",
        "bust",
        "cup",
        "waist",
        "hip",
        "sokuhime_date",
        "sokuhime_status",
        "is_sokuhime",
        "sokuhime_comment",
        "stay_status",
        "exclusive_status"
    ];
    /**
     * 条件に一致するデータを取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilteringData(object $query, array $filter): object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->where("cast_schedule.deleted_at", 0)
            ->when(!empty($filter['date']), function ($query) use ($filter) {
                if (is_array($filter['date'])) {
                    return $query->whereIn("date", $filter['date']);
                }
                return $query->where("date", $filter['date']);
            })
            ->when(!empty($filter['cast_id']), function ($query) use ($filter) {
                return $query->where("cast_id", $filter['cast_id']);
            })
            ->when(!empty($filter['site_id']), function ($query) use ($filter) {
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("site_id", $filter['site_id']);
                }
                return $query->where("site_id", $filter['site_id']);
            })
            ->when(isset($filter['is_work']), function ($query) use ($filter) {
                return $query->where("is_work", $filter['is_work']);
            })
            ->when(isset($filter['is_public']), function ($query) use ($filter) {
                return $query->where("m_cast.is_public", $filter['is_public']);
            })
            ->leftjoin("m_cast", "m_cast.id", "cast_schedule.cast_id")
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->select(array_merge($this->defaultFetchJoinColumns, ["m_cast.last_updated_status_at"]))
            // ->orderby('is_sokuhime','DESC')
            ->when(isset($filter['sort']), function ($query) use ($filter) {
                return $query->orderby("m_cast.sort", $filter['sort']);
            })
            ->when(isset($filter['sokuhime_sort']), function ($query) use ($filter) {
                return $query->orderby("m_cast.sokuhime_sort", $filter['sokuhime_sort']);
            })
            ->get();
    }
    /**
     * 条件データの最初のデーターを取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilteringFirstData(object $query, array $filter)
    {
        try {
            return $query
                ->where("m_site.deleted_at", 0)
                ->where("m_cast.deleted_at", 0)
                ->where("cast_schedule.deleted_at", 0)
                ->when(!empty($filter['date']), function ($query) use ($filter) {
                    return $query->where("date", $filter['date']);
                })
                ->when(!empty($filter['cast_id']), function ($query) use ($filter) {
                    return $query->where("cast_id", $filter['cast_id']);
                })
                ->when(isset($filter['is_work']), function ($query) use ($filter) {
                    return $query->where("is_work", $filter['is_work']);
                })
                ->leftjoin("m_cast", "m_cast.id", "cast_schedule.cast_id")
                ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
                ->select($this->defaultFetchJoinColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * 日付を配列で取得
     *
     * @param object $query
     * @param int $castId
     * @return array
     */
    public function scopeFetchFilteringAryData(object $query, int $castId, $date)
    {
        return $query
            ->where([
                "deleted_at" => 0,
                "cast_id" => $castId,
                ["date", ">=", $date]
            ])
            ->pluck('date')
            ->toArray();
    }
    /**
     * 日付間のデータを取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilteringBetWeenData(object $query, array $filter): object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->where("cast_schedule.deleted_at", 0)
            ->when(!empty($filter['date']), function ($query) use ($filter) {
                // if (is_array($filter['date'])) {
                //     return $query->whereIn("date", $filter['date']);
                // }
                return $query->where("date", $filter['date']);
            })
            ->when(!empty($filter['first_date']), function ($query) use ($filter) {
                // if (is_array($filter['date'])) {
                //     return $query->whereIn("date", $filter['date']);
                // }
                return $query->where("date", '>=', $filter['first_date']);
            })
            ->when(!empty($filter['end_date']), function ($query) use ($filter) {
                // if (is_array($filter['date'])) {
                //     return $query->whereIn("date", $filter['date']);
                // }
                return $query->where("date", '<=', $filter['end_date']);
            })
            ->when(!empty($filter['cast_id']), function ($query) use ($filter) {
                return $query->where("cast_id", $filter['cast_id']);
            })
            ->when(!empty($filter['site_id']), function ($query) use ($filter) {
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("site_id", $filter['site_id']);
                }
                return $query->where("site_id", $filter['site_id']);
            })
            ->when(isset($filter['is_work']), function ($query) use ($filter) {
                return $query->where("is_work", $filter['is_work']);
            })
            ->leftjoin("m_cast", "m_cast.id", "cast_schedule.cast_id")
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->select($this->defaultFetchJoinColumns)
            ->when(isset($filter['sort']), function ($query) use ($filter) {
                return $query->orderby("m_cast.sort", $filter['sort']);
            })
            ->get();
    }
    /**
     * 対象のユーザー及び対象の日付のデータを取得
     *
     * @param object $query
     * @param array $filter
     * @return array
     */
    public function scopeFetchFilteringCastData(object $query,array $filter)
    {
        return $query
            ->where("deleted_at", 0)
            ->when(!empty($filter['cast_id']), function ($query) use ($filter) {
                if (is_array($filter['cast_id'])) {
                    return $query->whereIn("cast_id", $filter['cast_id']);
                }
                return $query->where("cast_id", $filter['cast_id']);
            })
            ->when(!empty($filter['date']), function ($query) use ($filter) {
                return $query->where("date", $filter['date']);
            })
            ->when(isset($filter['is_work']), function ($query) use ($filter) {
                return $query->where("is_work", $filter['is_work']);
            })
            ->select($this->defaultFetchColumns)
            ->get();
    }
}
