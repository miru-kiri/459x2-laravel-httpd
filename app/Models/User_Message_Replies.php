<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Message_Replies extends Model
{
    use HasFactory;
    protected $table = 'user_message_replies';

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
        "user_message_replies.id",
        "user_message_replies.created_at",
        "user_message_id",
        "user_id",
        "d_user.name as user_name",
        "rank",
        "d_user.avatar as user_avatar",
        // "m_cast.avatar cast_avatar",
        "user_message_replies.content",
        "user_message_replies.author_flag",
        "user_message_replies.is_read",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinMessageFetchColumns = [
        "user_message_id",
        "user_message_replies.id",
        "user_message_replies.content", 
        "user_message_replies.author_flag",
        "user_message_replies.is_read",
        "site_id",
        "user_id",
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
        ->whereNull("user_message.deleted_at")
        ->whereNull("user_message_replies.deleted_at")
        ->where("user_message_id",$id)
        ->leftjoin("user_message","user_message.id","user_message_id")
        ->leftjoin("d_user","d_user.id","user_message.user_id")
        ->leftjoin("m_site","m_site.id","user_message.site_id")
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
    public function scopeFetchFilterIsNotReadData(object $query,array $filter):object
    {
        return $query
        ->whereNull("user_message_replies.deleted_at")
        ->where("user_message_replies.is_read", 0)
        ->when(!empty($filter['user_message_id']), function($query) use($filter){
            if (is_array($filter["user_message_id"])) {
                return $query->whereIn("user_message_id", $filter["user_message_id"]);
            }
            return $query->where("user_message_id", $filter['user_message_id']);
        })
        ->when(isset($filter['author_flag']), function($query) use($filter){
            return $query->where("user_message_replies.author_flag", $filter['author_flag']);
        })
        ->leftjoin("user_message","user_message_replies.user_message_id","user_message.id")
        ->select($this->defaultJoinMessageFetchColumns)
        ->get();
    }
}
