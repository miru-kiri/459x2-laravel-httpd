<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D_Site_Blog_Log extends Model
{
    use HasFactory;
    protected $table = 'd_site_blog_log';

    public $timestamps = false;
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['id'];
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
        ->where("d_site_blog_log.deleted_at",0)
        ->where("m_cast.deleted_at",0)
        // ->where("date", "LIKE",$filter['date']."%")
        ->when(!empty($filter["start_date"]), function($query) use($filter){
            return $query->where("date",">=",$filter['start_date']);
        })
        ->when(!empty($filter["end_date"]), function($query) use($filter){
            return $query->where("date","<=",$filter['end_date']);
        })
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where("d_site_blog_log.site_id", $filter["site_id"]);
        })
        ->leftjoin("m_cast","m_cast.id","d_site_blog_log.cast_id")
        ->leftjoin("m_site","m_site.id","m_cast.site_id")
        ->groupBy('cast_id','source_name','age','name')
        ->select('cast_id','source_name','age','name', \DB::raw('COUNT(*) as total'))
        ->orderby('total','DESC')
        ->get();
    }
}
