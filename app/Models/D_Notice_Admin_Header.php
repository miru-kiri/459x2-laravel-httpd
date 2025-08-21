<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class D_Notice_Admin_Header extends Model
{
    use HasFactory;
    protected $table = 'd_notice_admin_header';

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
        "title",
        "content",
        "is_draft",
        "display_date",
        "created_user",
        "updated_user"
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "d_notice_admin_header.id",
        "title",
        "d_notice_admin_header.content",
        "d_notice_admin_header.type",
        "is_draft",
        "display_date",
        "d_notice_admin_header.created_user",
        "d_notice_admin_header.updated_user",
        "header_id",
        "site_id",
        "m_site.name as site_name",
        "cast_id",
        "is_read"
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
    public function scopeFetchJoinAll(object $query, array $filter):object
    {
        return $query
        ->where("d_notice_admin_header.deleted_at", 0)
        ->where("d_notice_admin_detail.deleted_at", 0)
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            if (is_array($filter["site_id"])) {
                return $query->whereIn("site_id", $filter["site_id"]);
            }
            return $query->where('site_id',$filter['site_id']);
        })
        ->select($this->defaultFetchJoinColumns)
        ->leftjoin('d_notice_admin_detail','d_notice_admin_detail.header_id','d_notice_admin_header.id')
        ->leftjoin('m_site','m_site.id','d_notice_admin_detail.site_id')
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchJoinFilterSiteData(object $query, array $filter):object
    {
        return $query
        ->where("d_notice_admin_header.deleted_at", 0)
        ->where("d_notice_admin_detail.deleted_at", 0)
        ->where("site_id",'<>',0)
        ->where("display_date",'<',$filter['display_date'])
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            if (is_array($filter["site_id"])) {
                return $query->whereIn("site_id", $filter["site_id"]);
            }
            return $query->where('site_id',$filter['site_id']);
        })
        ->select($this->defaultFetchJoinColumns)
        ->leftjoin('d_notice_admin_detail','d_notice_admin_detail.header_id','d_notice_admin_header.id')
        ->leftjoin('m_site','m_site.id','d_notice_admin_detail.site_id')
        ->orderby('display_date','DESC')
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchJoinFilterCastData(object $query, array $filter):object
    {
        return $query
        ->where("d_notice_admin_header.deleted_at", 0)
        ->where("d_notice_admin_detail.deleted_at", 0)
        ->where("cast_id",'<>',0)
        ->where("display_date",'<',$filter['display_date'])
        ->when(!empty($filter["cast_id"]), function($query) use($filter){
            if (is_array($filter["cast_id"])) {
                return $query->whereIn("cast_id", $filter["cast_id"]);
            }
            return $query->where('cast_id',$filter['cast_id']);
        })
        ->select($this->defaultFetchJoinColumns)
        ->leftjoin('d_notice_admin_detail','d_notice_admin_detail.header_id','d_notice_admin_header.id')
        ->leftjoin('m_site','m_site.id','d_notice_admin_detail.site_id')
        ->orderby('display_date','DESC')
        ->get();
    }
    /**
     * データ更新
     *
     * @param object $query
     * @param array $parameter
     * @return 
     */
    public function scopeSaveData(object $query,array $parameter)
    {
        return $query
        ->findOrFail($parameter["id"])
        ->fill([
            'updated_at' => $parameter['updated_at'],
            'title' => $parameter['title'],
            'content' => $parameter['content'],
            'type' => $parameter['type'],
            'display_date' => $parameter['display_date'],
            'updated_user' => $parameter['updated_user']
        ])->save();
    }
}
