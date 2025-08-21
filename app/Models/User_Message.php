<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'user_message';

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
        "user_id",
        "site_id",
        "title",
        "content",
        "is_read",
        "author_flg",
        "user_last_visited",
        "created_at",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinFetchColumns = [
        "user_message.id",
        "user_id",
        "d_user.name as user_name",
        "user_message.site_id",
        "m_site.name as site_name",
        "title",
        "user_message.content",
        "is_read",
        "author_flag",
        "user_last_visited",
        "user_message.created_at",
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
        ->whereNull("deleted_at")
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return object
     */
    public function scopeFetchFilterIdJoinData(object $query,int $id):object
    {
        return $query
        ->whereNull("user_message.deleted_at")
        ->where('user_message.id',$id)
        ->leftjoin("m_site","m_site.id","user_message.site_id")
        ->leftjoin("d_user","d_user.id","user_message.user_id")
        ->select($this->defaultJoinFetchColumns)
        ->first();
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
            ->where("m_site.deleted_at",0)
            ->whereNull("d_user.deleted_at")
            ->whereNull("user_message.deleted_at")
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("user_message.site_id", $filter["site_id"]);
                }
                return $query->where("user_message.site_id", $filter["site_id"]);
            })
            ->leftjoin("m_site","m_site.id","user_message.site_id")
            ->leftjoin("d_user","d_user.id","user_message.user_id")
            ->select($this->defaultJoinFetchColumns)
            ->orderby('user_message.created_at','DESC')
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFilteringUserData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at",0)
            ->whereNull("d_user.deleted_at")
            ->whereNull("user_message.deleted_at")
            ->when(!empty($filter["user_id"]), function($query) use($filter){
                return $query->where("user_message.user_id", $filter["user_id"]);
            })
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("user_message.site_id", $filter["site_id"]);
                }
                return $query->where("user_message.site_id", $filter["site_id"]);
            })
            ->leftjoin("m_site","m_site.id","user_message.site_id")
            ->leftjoin("d_user","d_user.id","user_message.user_id")
            ->select($this->defaultJoinFetchColumns)
            ->get();
    }
}
