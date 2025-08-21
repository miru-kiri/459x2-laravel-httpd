<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point_User extends Model
{
    use HasFactory;

    protected $table = 'point_user';

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
    protected $defaultFetchJoinColumns = [
        "point_user.id",
        "point_user.user_id",
        "point_user.site_id",
        "point_user.date",
        "point_user.time",
        "point_user.category_id",
        "point_user.branch_id",
        "point_user.expiration_date",
        "point_user.point",
        "m_site.name as site_name",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopefetchFilteringData(object $query,array $filter):object
    {
        return $query
        ->where("point_user.deleted_at",0)
        ->when(!empty($filter["user_id"]), function($query) use($filter){
            return $query->where("user_id", $filter["user_id"]);
        })
        ->when(!empty($filter["card_no"]), function($query) use($filter){
            return $query->where("card_no", $filter["card_no"]);
        })
        ->leftjoin("m_site","m_site.id","point_user.site_id")
        ->select($this->defaultFetchJoinColumns)
        ->orderby('id','DESC')
        // ->orderby('date','DESC')
        // ->orderby('time','DESC')
        ->get();
    }
    public function scopefetchValidPoint(object $query,$card_no)
    {
        return $query
            ->where([
                "deleted_at" => 0,
                "card_no" => $card_no,
            ])
            ->where(function($query) {
                $query->where('expiration_date', 0)
                    ->orWhere('expiration_date', '>=', date('Y-m-d'));
            })
            ->sum('point');
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopefetchFilterSumPoint(object $query,array $filter):object
    {
        return $query
        ->where("deleted_at",0)
        ->when(!empty($filter["card_no"]), function($query) use($filter){
            if (is_array($filter["card_no"])) {
                return $query->whereIn("card_no", $filter["card_no"]);
            }
            return $query->where("card_no", $filter["card_no"]);
        })
        ->selectRaw("sum(point) as total_point,card_no")
        ->groupby("card_no")
        ->get();
    }
}
