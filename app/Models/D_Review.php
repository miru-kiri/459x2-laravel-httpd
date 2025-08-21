<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class D_Review extends Model
{
    // use SoftDeletes;
    use HasFactory;

    protected $table = 'd_review';

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
    protected $defaultFetchJoinColumns = [
        "d_review.id",
        "d_review.created_at",
        "d_review.user_id",
        "d_user.name as user_name",
        "d_review.cast_id",
        "m_site.name as site_name",
        "source_name",
        "d_review.title",
        "d_review.content",
        "time_play",
        "display",
        "age",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
        "m_cast.deleted_at as cast_delete",
        "m_site.deleted_at as site_delete",
        "admin_feedback",
        "admin_feedback_time"
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchAll(object $query):object
    {
        return $query
        ->whereNull("d_review.deleted_at")
        ->leftjoin("d_user","d_user.id","d_review.user_id")
        ->leftjoin("m_cast","m_cast.id","d_review.cast_id")
        ->leftjoin("m_site","m_site.id","d_review.site_id")
        ->select($this->defaultFetchJoinColumns)
        ->orderby('time_play','DESC')
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
        ->whereNull("d_review.deleted_at")
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            if (is_array($filter["site_id"])) {
                return $query->whereIn("d_review.site_id", $filter["site_id"]);
            }
            return $query->where("d_review.site_id", $filter["site_id"]);
        })
        ->leftjoin("d_user","d_user.id","d_review.user_id")
        ->leftjoin("m_cast","m_cast.id","d_review.cast_id")
        ->leftjoin("m_site","m_site.id","d_review.site_id")
        ->select($this->defaultFetchJoinColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilterPublicCastData(object $query,array $filter):object
    {
        return $query
        ->whereNull("d_review.deleted_at")
        ->where("d_review.display",1)
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            if (is_array($filter["site_id"])) {
                return $query->whereIn("d_review.site_id", $filter["site_id"]);
            }
            return $query->where("d_review.site_id", $filter["site_id"]);
        })
        ->when(!empty($filter["cast_id"]), function($query) use($filter){
            if (is_array($filter["cast_id"])) {
                return $query->whereIn("d_review.cast_id", $filter["cast_id"]);
            }
            return $query->where("d_review.cast_id", $filter["cast_id"]);
        })
        ->leftjoin("d_user","d_user.id","d_review.user_id")
        ->leftjoin("m_cast","m_cast.id","d_review.cast_id")
        ->leftjoin("m_site","m_site.id","d_review.site_id")
        ->when(!empty($filter["limit"]), function($query) use($filter){
            return $query->limit($filter['limit']);
        })
        ->orderBy('d_review.id', 'desc')
        ->select($this->defaultFetchJoinColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchBookCastIdAry(object $query,array $filter):array
    {
        return $query
        ->whereNull("deleted_at")
        ->when(!empty($filter["user_id"]), function($query) use($filter){
            // if (is_array($filter["user_id"])) {
            //     return $query->whereIn("d_review.user_id", $filter["user_id"]);
            // }
            return $query->where("user_id", $filter["user_id"]);
        })
        ->pluck('bookcast_id')
        ->toArray();
    }
}
