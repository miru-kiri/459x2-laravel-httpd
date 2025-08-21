<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Member_Message_Replies extends Model
{
    use HasFactory;
    protected $table = 'member_message_replies';

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
        "member_message_replies.id",
        "member_message_replies.created_at",
        "message_id",
        "user_id",
        "d_user.name as user_name",
        "rank",
        "d_user.avatar as user_avatar",
        "m_cast.avatar as cast_avatar",
        "cast_id",
        "source_name",
        // "m_cast.avatar cast_avatar",
        "member_message_replies.content",
        "admin_check_status",
        "author_flag",
        "is_read",
        "status",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinMessageFetchColumns = [
        "message_id",
        "member_message_replies.id",
        "member_message_replies.content", 
        "author_flag",
        "is_read",
        "status",
        "user_id",
        "cast_id",
    ];
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
        ->whereNull("member_message_replies.deleted_at")
        ->where("message_id",$id)
        ->leftjoin("member_message","member_message.id","message_id")
        ->leftjoin("m_cast","m_cast.id","member_message.cast_id")
        ->leftjoin("d_user","d_user.id","member_message.user_id")
        ->select($this->defaultJoinFetchColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return
     */
    public function scopeFetchFirstData(object $query,array $filter)
    {
        try {
            return $query
            ->whereNull("deleted_at")
            ->when(!empty($filter['message_id']), function($query) use($filter){
                return $query->where("message_id", $filter['message_id']);
            })
            ->when(!empty($filter['status']), function($query) use($filter){
                return $query->where("status", $filter['status']);
            })
            ->orderby('id','DESC')
            ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return false;
        }
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return object
     */
    public function scopeFetchFilterIsNotReadData(object $query,array $filter):object
    {
        return $query
        ->whereNull("member_message_replies.deleted_at")
        ->where("is_read", 0)
        ->when(!empty($filter['message_id']), function($query) use($filter){
            if (is_array($filter["message_id"])) {
                return $query->whereIn("message_id", $filter["message_id"]);
            }
            return $query->where("message_id", $filter['message_id']);
        })
        ->when(!empty($filter['status']), function($query) use($filter){
            if (is_array($filter["status"])) {
                return $query->whereIn("status", $filter["status"]);
            }
            return $query->where("status", $filter['status']);
        })
        ->when(isset($filter['author_flag']), function($query) use($filter){
            return $query->where("author_flag", $filter['author_flag']);
        })
        ->leftjoin("member_message","member_message_replies.message_id","member_message.id")
        ->select($this->defaultJoinMessageFetchColumns)
        ->get();
    }
}
