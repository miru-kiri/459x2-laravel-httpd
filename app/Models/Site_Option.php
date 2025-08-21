<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site_Option extends Model
{
    use HasFactory;
    protected $table = 'site_option';

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
        'id',
        'site_id',
        'name',
        'fee',
        'sort_no',
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return object
     */
    public function scopeFetchFilterSiteData(object $query,int $site_id)
    {
        return $query
        ->whereNull("deleted_at")
        ->where("site_id",$site_id)
        ->select($this->defaultFetchColumns)
        ->orderby('sort_no')
        ->get();
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringFirstSortNoData(object $query,array $filter)
    {
        return $query
            ->where('deleted_at',0)
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                return $query->where("site_id", $filter["site_id"]);
            })
            ->min('sort_no');
    }
}
