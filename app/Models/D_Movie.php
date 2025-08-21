<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D_Movie extends Model
{
    use HasFactory;
    protected $table = 'd_movie';

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
        "d_movie.id",
        "d_movie.site_id",
        "m_site.name as site_name",
        "cast_id",
        "source_name",
        "file_path",
        "file_name",
        "title",
        "d_movie.content",
        "time",
        "tag_name",
        "is_cm_display",
        "is_display",
        "m_cast.deleted_at"
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilterSiteIdData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            // ->where("m_cast.deleted_at", 0)
            ->where("d_movie.deleted_at",0)
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                // if (is_array($filter["site_id"])) {
                //     return $query->whereIn("site_id", $filter["site_id"]);
                // }
                return $query->where("d_movie.site_id", $filter["site_id"]);
            })
            ->leftjoin("m_site","m_site.id","d_movie.site_id")
            ->leftjoin("m_cast","m_cast.id","d_movie.cast_id")
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
    public function scopeFetchFilterData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            // ->where("m_cast.deleted_at", 0)
            ->where("d_movie.deleted_at",0)
            ->where("d_movie.is_display",1)
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("d_movie.site_id", $filter["site_id"]);
                }
                return $query->where("d_movie.site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                if (is_array($filter["cast_id"])) {
                    return $query->whereIn("cast_id", $filter["cast_id"]);
                }
                return $query->where("cast_id", $filter["cast_id"]);
            })
            ->leftjoin("m_site","m_site.id","d_movie.site_id")
            ->leftjoin("m_cast","m_cast.id","d_movie.cast_id")
			// d_movie.cast_idが0以外の場合のみ、m_castの条件を追加
            ->when($query->getModel()->cast_id !== 0, function ($query) {
                return $query->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where("m_cast.deleted_at", 0)
                            ->orWhereNull("m_cast.deleted_at");
                    })
                        ->where(function ($query) {
                            $query->where("m_cast.is_public", 1)
                                ->orWhereNull("m_cast.is_public");
                        });
                });
            })
            ->when(!empty($filter["limit"]), function($query) use($filter){
                return $query->limit($filter["limit"]);
            })
            ->orderby('d_movie.id','DESC')
            ->select($this->defaultFetchJoinColumns)
            ->get();
    }
}
