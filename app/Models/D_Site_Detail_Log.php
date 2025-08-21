<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D_Site_Detail_Log extends Model
{
    use HasFactory;
    protected $table = 'd_site_detail_log';

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
        "date",
        "time",
        "device",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringDateData(object $query,array $filter):object
    {
        return $query
        ->where("deleted_at",0)
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where("site_id", $filter["site_id"]);
        })
        ->when(!empty($filter["date"]), function($query) use($filter){
            return $query->where("date", "LIKE",$filter['date']."%");
        })
        ->when(!empty($filter["device"]), function($query) use($filter){
            return $query->where("device", $filter["device"]);
        })
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return
     */
    public function scopeFetchFilteringDateCount(object $query,array $filter)
    {
        return $query
        ->where("deleted_at",0)
        ->when(!empty($filter["start_date"]), function($query) use($filter){
            return $query->where('date','>=',$filter['start_date']);
        })
        ->when(!empty($filter["end_date"]), function($query) use($filter){
            return $query->where('date','<=',$filter['end_date']);
        })
        // ->where("date", "LIKE",$filter['date']."%")
        ->where("device", $filter['device'])
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where("site_id", $filter["site_id"]);
        })
        ->count();
    }
}
