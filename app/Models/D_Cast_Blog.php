<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class D_Cast_Blog extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'd_cast_blog';

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
        "old_id",
        "cast_id",
        "title",
        "content",
        "updated_by",
        "deleted_by",
        "published_at",
        "type",
        "is_draft",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinFetchColumns = [
        "d_cast_blog.id",
        "old_id",
        "cast_id",
        "m_site.id as site_id",
        "m_site.name as site_name",
        "source_name",
        "title",
		//"d_cast_blog.content",
        "updated_by",
        "deleted_by",
        "published_at",
        "type",
        "is_draft",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinCastFetchColumns = [
        "d_cast_blog.id",
        "old_id",
        "cast_id",
        "site_id",
        "source_name",
        "title",
        "d_cast_blog.content",
        // "updated_by",
        // "deleted_by",
        "published_at",
        // "type",
        // "is_draft",
        // "image_url",
        "age",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchAllData(object $query):object
    {
        return $query
        ->where([
            "d_cast_blog.deleted_at" => 0,
            "m_site.deleted_at" => 0,
        ])
        ->leftjoin("m_site","m_site.id","d_user.site_id")
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
    public function scopefilteringMultiSiteData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
			->where("m_cast.deleted_at",0)
            ->whereNull("d_cast_blog.deleted_at")
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                // if (is_array($filter["site_id"])) {
                //     return $query->whereIn("site_id", $filter["site_id"]);
                // }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
				if (is_array($filter["cast_id"])) {
                    return $query->whereIn("cast_id", $filter["cast_id"]);
                }
                return $query->where("cast_id", $filter["cast_id"]);
            })
            ->leftjoin("m_cast","m_cast.id","d_cast_blog.cast_id")
            ->leftjoin("m_site","m_site.id","m_cast.site_id")
            ->orderby("published_at",'DESC')
            ->select($this->defaultJoinFetchColumns)
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchSiteIdLimitData(object $query,array $siteIdAry,int $limit):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
			->where("m_cast.is_public", 1)
            ->whereNull("d_cast_blog.deleted_at")
            ->where("published_at", '<=',date('Y-m-d H:i:s'))
            ->whereIn("site_id", $siteIdAry)
            ->leftjoin("m_cast","m_cast.id","d_cast_blog.cast_id")
            ->leftjoin("m_site","m_site.id","m_cast.site_id")
            ->select($this->defaultJoinFetchColumns)
            ->orderby('published_at','DESC')
            ->limit($limit)
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
			->where("m_cast.is_public", 1)
            ->whereNull("d_cast_blog.deleted_at")
            ->where("published_at", '<=',date('Y-m-d H:i:s'))
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                if (is_array($filter["cast_id"])) {
                    return $query->whereIn("cast_id", $filter["cast_id"]);
                }
                return $query->where("cast_id", $filter["cast_id"]);
            })
            ->leftjoin("m_cast","m_cast.id","d_cast_blog.cast_id")
            ->leftjoin("m_site","m_site.id","m_cast.site_id")
            ->select($this->defaultJoinFetchColumns)
            ->orderby('published_at','DESC')
            ->when(!empty($filter["limit"]), function($query) use($filter){
                return $query->limit($filter['limit']);
            })
            ->get();
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
                "cast_id" => $parameter['cast_id'],
                "title" => $parameter['title'],
                "content" => $parameter['content'],
                "published_at" => $parameter['published_at'],
                "created_at" => now(),
                "updated_at" => now(),
            ])->save();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchSiteDataJoinImagePaginated(object $query,array $filter):object
    {
        return $query
            ->where("m_cast.deleted_at", 0)
			->where("m_cast.is_public", 1)
            ->whereNull("d_cast_blog.deleted_at")
            //画像をjoinしたら死ぬほど重なるから一旦
            // ->whereNull("d_cast_blog_image.deleted_at")
            ->where("published_at", '<=',date('Y-m-d H:i:s'))
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                // if (is_array($filter["cast_id"])) {
                //     return $query->whereIn("cast_id", $filter["cast_id"]);
                // }
                return $query->where("cast_id", $filter["cast_id"]);
            })
            ->leftjoin("m_cast","m_cast.id","d_cast_blog.cast_id")
            //画像をjoinしたら死ぬほど重なるから一旦
            // ->leftjoin("d_cast_blog_image","d_cast_blog_image.article_id","d_cast_blog.id")
            ->orderby('published_at','DESC')
            ->select($this->defaultJoinCastFetchColumns)
            ->get();
            // ->paginate($perPage); // ページネーションを追加
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param  $castId
     * @return object
     */
    public function scopefilteringCastIdAryId(object $query,$castId)
    {
        return $query
            // ->where("m_site.deleted_at", 0)
            ->whereNull("deleted_at")
            ->when(!empty($castId), function($query) use($castId){
                return $query->where("cast_id", $castId);
            })
            ->pluck('id')
            ->toArray();
    }
}
