<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class M_Point_Event extends Model
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
    protected $table = 'm_point_event';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultJoinFetchColumns = [
        "m_point_event.id",
        "site_id",
        "m_site.name as site_name",
        "title",
        "m_point_event.content",
        "start_date",
        "end_date",
        "persent",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchSiteData(object $query,array $filter) 
    {
        return $query
        ->where("m_point_event.deleted_at",0)
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where("site_id", $filter["site_id"]);
        })
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
    public function scopesiteCheckDate(object $query,$id,$siteId,$startDate,$endDate) 
    {
        try {
            return $query
            ->where([
                "m_point_event.deleted_at" => 0,
                "site_id" => $siteId,
            ])
            ->where('m_point_event.id','<>' ,$id)
            ->where('start_date','<' ,$endDate)
            ->where('end_date','>' ,$startDate)
            ->leftjoin('m_site','m_site.id','site_id')
            ->select($this->defaultJoinFetchColumns)
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
