<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class D_Notice_Admin_Detail extends Model
{
    use HasFactory;
    protected $table = 'd_notice_admin_detail';

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
        "header_id",
        "site_id",
        "cast_id",
        "is_read",
    ];
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterSiteIdAry(object $query,array $filter)
    {
        return $query
        ->where("deleted_at", 0)
        ->where($filter['colums'],'<>',0)
        ->when(!empty($filter["header_id"]), function($query) use($filter){
            return $query->where("header_id", $filter["header_id"]);
        })
        ->pluck($filter['colums'])
        ->toArray();
    }
}
