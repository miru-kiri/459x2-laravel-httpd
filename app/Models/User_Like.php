<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User_Like extends Model
{
    use HasFactory;
    protected $table = 'user_like';

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
        "user_like.id",
        "site_id", 
        "m_site.name as site_name", 
        "url", 
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchJoinUserData(object $query,int $userId):object
    {
        return $query
        ->whereNull("user_like.deleted_at")
        ->where("user_id",$userId)
        ->leftjoin("m_site","m_site.id","site_id")
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
    public function scopeFetchJoinUserDataIsPublic(object $query,int $userId):object
    {
        return $query
        ->whereNull("user_like.deleted_at")
        ->where([
            "user_id" => $userId,
            "is_public" => 1,
        ])
        ->leftjoin("m_site","m_site.id","site_id")
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
    public function scopeFilterFirstData(object $query,array $filter)
    {
        try {
            return $query
            ->whereNull("user_like.deleted_at")
            ->when(!empty($filter['user_id']), function($query) use($filter){
                return $query->where("user_id", $filter['user_id']);
            })
            ->when(!empty($filter['site_id']), function($query) use($filter){
                return $query->where("site_id", $filter['site_id']);
            })
            ->leftjoin("m_site","m_site.id","site_id")
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
    public function scopeFetchJoinUserLimitData(object $query,int $userId,int $limit):object
    {
        return $query
        ->whereNull("user_like.deleted_at")
        ->where([
            "user_id" => $userId,
            "is_public" => 1,
        ])
        ->leftjoin("m_site","m_site.id","site_id")
        ->select($this->defaultJoinFetchColumns)
        ->limit($limit)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return 
     */
    public function scopeDeleteLike(object $query,int $id)
    {
        return $query
                ->findOrFail($id)
                ->fill([
                    'deleted_at' => now()
                ])
                ->save();
    }
}
