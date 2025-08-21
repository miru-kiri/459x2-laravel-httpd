<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D_Shop_Manager_Blog extends Model
{
    use HasFactory;

    protected $table = 'd_shop_manager_blog';

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
    protected $defaultJoinFetchColumns = [
        "d_shop_manager_blog.id",
        "site_id",
        "m_site.name as site_name",
        "published_at",
        "title",
        "d_shop_manager_blog.content",
        "d_shop_manager_blog.category_name",
        "d_shop_manager_blog.created_at",
    ];
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
            ->where("d_shop_manager_blog.delete_flg",0)
			->where("m_site.is_public", 1)
            ->where("published_at", '<=',date('Y-m-d H:i:s'))
            ->whereIn("site_id", $siteIdAry)
            ->leftjoin("m_site","m_site.id","d_shop_manager_blog.site_id")
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
    public function scopeFetchFilterSiteIdData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("d_shop_manager_blog.delete_flg",0)
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                // if (is_array($filter["site_id"])) {
                //     return $query->whereIn("site_id", $filter["site_id"]);
                // }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->leftjoin("m_site","m_site.id","d_shop_manager_blog.site_id")
            ->orderby('published_at','DESC')
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
    public function scopeFetchFilterSiteIdPublicData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
			->where("m_site.is_public", 1)
            ->where("d_shop_manager_blog.delete_flg",0)
            ->where("published_at", '<=',date('Y-m-d H:i:s'))
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                // if (is_array($filter["site_id"])) {
                //     return $query->whereIn("site_id", $filter["site_id"]);
                // }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->leftjoin("m_site","m_site.id","d_shop_manager_blog.site_id")
            ->orderby('published_at','DESC')
            ->select($this->defaultJoinFetchColumns)
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
                "site_id" => $parameter['site_id'],
                "title" => $parameter['title'],
                "content" => $parameter['content'],
                "published_at" => $parameter['published_at'],
                "updated_at" => time(),
            ])->save();
    }
}
