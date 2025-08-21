<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member_Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'member_message';

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
        "cast_id",
        "content",
        "member_message.content",
        "admin_check_status",
        "cast_last_visited",
        "user_last_visited",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinFetchColumns = [
        "member_message.id",
        "member_message.created_at",
        "user_id",
        "d_user.name as user_name",
        "rank",
        "d_user.avatar as user_avatar",
        "m_cast.avatar as cast_avatar",
        "cast_id",
        "source_name",
        "member_message.content",
        "admin_check_status",
        "cast_last_visited",
        "user_last_visited",
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
    public function scopeFetchJoinUserData(object $query,int $id):object
    {
        return $query
        ->whereNull("member_message.deleted_at")
        ->where("member_message.id",$id)
        ->leftjoin("m_cast","m_cast.id","member_message.cast_id")
        ->leftjoin("d_user","d_user.id","member_message.user_id")
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
            ->where("m_cast.deleted_at",0)
            ->whereNull("d_user.deleted_at")
            ->whereNull("member_message.deleted_at")
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("m_cast.site_id", $filter["site_id"]);
                }
                return $query->where("m_cast.site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                // if (is_array($filter["site_id"])) {
                //     return $query->whereIn("m_cast.site_id", $filter["site_id"]);
                // }
                return $query->where("m_cast.id", $filter["cast_id"]);
            })
            ->leftjoin("m_cast","m_cast.id","member_message.cast_id")
            ->leftjoin("d_user","d_user.id","member_message.user_id")
            ->select($this->defaultJoinFetchColumns)
            ->orderby('member_message.created_at','DESC')
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopefilteringUserFirstData(object $query,array $filter)
    {
        try {
            return $query
                ->where("m_cast.deleted_at",0)
                ->whereNull("d_user.deleted_at")
                ->whereNull("member_message.deleted_at")
                ->when(!empty($filter["cast_id"]), function($query) use($filter){
                    return $query->where("m_cast.id", $filter["cast_id"]);
                })
                ->when(!empty($filter["user_id"]), function($query) use($filter){
                    return $query->where("d_user.id", $filter["user_id"]);
                })
                ->leftjoin("m_cast","m_cast.id","member_message.cast_id")
                ->leftjoin("d_user","d_user.id","member_message.user_id")
                ->select($this->defaultJoinFetchColumns)
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
    public function scopeFilteringUserData(object $query,array $filter)
    {
        return $query
            ->where("m_cast.deleted_at",0)
            ->whereNull("d_user.deleted_at")
            ->whereNull("member_message.deleted_at")
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                return $query->where("m_cast.id", $filter["cast_id"]);
            })
            ->when(!empty($filter["user_id"]), function($query) use($filter){
                return $query->where("d_user.id", $filter["user_id"]);
            })
            ->leftjoin("m_cast","m_cast.id","member_message.cast_id")
            ->leftjoin("d_user","d_user.id","member_message.user_id")
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
    public function scopeFetchFilterData(object $query,array $filter):object
    {
        return $query
        ->whereNull("deleted_at")
        ->when(!empty($filter["cast_id"]), function($query) use($filter){
            if (is_array($filter["cast_id"])) {
                return $query->whereIn("cast_id", $filter["cast_id"]);
            }
            return $query->where("cast_id", $filter["cast_id"]);
        })
        ->when(!empty($filter["user_id"]), function($query) use($filter){
            return $query->where("user_id", $filter["user_id"]);
        })
        ->select($this->defaultFetchColumns)
        ->get();
    }
}
