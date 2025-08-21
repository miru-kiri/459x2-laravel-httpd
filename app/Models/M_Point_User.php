<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class M_Point_User extends Model
{
    use HasFactory;
    /**
     * 自動タイムスタンプを無効
     *
     * @var boolean
     */
    public $timestamps = false;
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'm_point_user';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "user_id",
        "site_id",
        "card_no",
        "name",
        "year",
        "month",
        "day",
        "sex",
        "tel",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinFetchColumns = [
        "m_point_user.id",
        "user_id",
        "site_id",
        "m_site.name as site_name",
        "card_no",
        "m_point_user.name",
        "year",
        "month",
        "day",
        "sex",
        "tel",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchAll(object $query):object
    {
        return $query
        ->where("m_point_user.deleted_at",0)
        ->leftjoin('m_site','m_site.id','site_id')
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
    public function scopeFetchFilterData(object $query, array $filter):object
    {
        return $query
        ->where("m_point_user.deleted_at",0)
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where("site_id", $filter["site_id"]);
        })
        ->when(!empty($filter["is_web"]), function($query) use($filter){
            if($filter["is_web"] == 1) {
                return $query->whereNotNull("user_id");
            } else {
                return $query->whereNull("user_id");
            }
        })
        ->leftjoin('m_site','m_site.id','site_id')
        ->orderby('card_no','DESC')
        ->select($this->defaultJoinFetchColumns)
        ->get();
    }

    public function scopeFetchFilteringFirstData(object $query,array $filter){
        try {
            return $query
                ->where([
                    'deleted_at' => 0,
                ])
                ->when(!empty($filter["card_no"]), function($query) use($filter){
                    return $query->where("card_no", $filter["card_no"]);
                })
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return false;
        }
    }
}
