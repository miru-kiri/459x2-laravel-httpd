<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User_Like_Cast extends Model
{
    use HasFactory;
    protected $table = 'user_like_cast';

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
        "user_like_cast.id",
        "cast_id",
        "site_id",
        "m_cast.shop_id",
        "m_cast.source_name",
        "m_cast.age",
        "m_cast.height",
        "m_cast.bust",
        "m_cast.cup",
        "m_cast.waist",
        "m_cast.hip",
        "m_site.name as site_name",
        "template",
        "approval_status"
    ];
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
        ->whereNull("user_like_cast.deleted_at")
        ->where([
            "m_cast.deleted_at" => 0,
            "m_cast.is_public" => 1,
            "m_site.deleted_at" => 0,
        ])
        ->when(!empty($filter["user_id"]), function($query) use($filter){
            if (is_array($filter["user_id"])) {
                return $query->whereIn("user_id", $filter["user_id"]);
            }
            return $query->where("user_id", $filter["user_id"]);
        })
        ->leftjoin("m_cast","m_cast.id","user_like_cast.cast_id")
        ->leftjoin("m_site","m_site.id","m_cast.site_id")
        ->select($this->defaultJoinFetchColumns)
        ->orderby('user_like_cast.updated_at','DESC')
        ->when(!empty($filter["limit"]), function($query) use($filter){
            return $query->limit($filter["limit"]);
        })
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
            ->whereNull("user_like_cast.deleted_at")
            ->when(!empty($filter['user_id']), function($query) use($filter){
                return $query->where("user_id", $filter['user_id']);
            })
            ->when(!empty($filter['cast_id']), function($query) use($filter){
                return $query->where("cast_id", $filter['cast_id']);
            })
            ->leftjoin("m_cast","m_cast.id","user_like_cast.cast_id")
            ->leftjoin("m_site","m_site.id","m_cast.site_id")
            ->select($this->defaultJoinFetchColumns)
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
