<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Cast_Image extends Model
{
    use HasFactory;

    protected $table = 'cast_image';

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
        "created_at",
        "site_id",
        "cast_id",
        "directory",
        "path",
        "is_direction",
        "comment",
        "sort_no",
    ];
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringData(object $query,array $filter):object
    {
        return $query
            ->where('deleted_at',0)
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                return $query->where("cast_id", $filter["cast_id"]);
            })
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->select($this->defaultFetchColumns)
            ->orderby('sort_no')
            ->get();
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringSiteData(object $query,array $filter):object
    {
        return $query
            ->where('deleted_at',0)
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->select($this->defaultFetchColumns)
            ->selectRaw('MAX(sort_no) as max_sort_no')
            ->groupby('site_id')
            ->orderBy('max_sort_no') 
            ->get();
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringFirstData(object $query,array $filter)
    {
        try {
            return $query
                ->where('deleted_at',0)
                ->when(!empty($filter["cast_id"]), function($query) use($filter){
                    return $query->where("cast_id", $filter["cast_id"]);
                })
                ->select($this->defaultFetchColumns)
                ->orderby('sort_no')
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringLastSortNoData(object $query,array $filter)
    {
        return $query
            ->where('deleted_at',0)
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                return $query->where("cast_id", $filter["cast_id"]);
            })
            ->max('sort_no') ?? 0;
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringFirstSortNoData(object $query,array $filter)
    {
        return $query
            ->where('deleted_at',0)
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                return $query->where("cast_id", $filter["cast_id"]);
            })
            ->min('sort_no');
    }
}
