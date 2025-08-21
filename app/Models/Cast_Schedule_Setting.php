<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast_Schedule_Setting extends Model
{
    use HasFactory;
    protected $table = 'cast_schedule_setting';

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
        "cast_schedule_setting.id",
        "date_time",
        "date",
        "time",
        "cast_id",
        "cast_schedule_setting.status",
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
        ->where("deleted_at",0)
        ->when(!empty($filter["cast_id"]), function($query) use($filter){
            if (is_array($filter["cast_id"])) {
                return $query->whereIn("cast_id", $filter["cast_id"]);
            }
            return $query->where("cast_id", $filter["cast_id"]);
        })
        ->when(!empty($filter["date"]), function($query) use($filter){
            return $query->where("date", $filter["date"]);
        })
        ->select($this->defaultFetchColumns)
        ->get();
    }

    /**
    * スタッフ全件取得
    *
    * @param object $query
    * @return object
    */
    public function scopeFetchFilteringBetweenData(object $query,$filter):object
    {
        return $query
        ->when(!empty($filter["cast_id"]), function($query) use($filter){
            if (is_array($filter["cast_id"])) {
                return $query->whereIn("cast_id", $filter["cast_id"]);
            }
            return $query->where('cast_id',$filter['cast_id']);
        })
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where('site_id',$filter['site_id']);
        })
        ->when(!empty($filter["date"]), function($query) use($filter){
            if (is_array($filter["date"])) {
                return $query->whereBetween('date',$filter['date']);
            }
            return $query->where('date',$filter['date']);
        })
        ->leftjoin('m_cast','m_cast.id','cast_id')
        ->select($this->defaultFetchColumns)
        ->get();
    }
}
