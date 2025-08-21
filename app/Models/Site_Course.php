<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Site_Course extends Model
{
    use HasFactory;
    protected $table = 'site_course';

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
        'time',
        'fee',
        'type',
        'sort_no'
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return object
     */
    public function scopeFetchFilterSiteData(object $query,int $site_id, int $type_id)
    {
        return $query
        ->whereNull("deleted_at")
        ->where("site_id",$site_id)
        ->where("type",$type_id)
        ->select($this->defaultFetchColumns)
        ->orderby('sort_no')
        ->orderby('time')
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return object
     */
    public function scopeFetchFilterSiteFirstData(object $query,int $site_id, int $type_id)
    {
        try {
            return $query
            ->whereNull("deleted_at")
            ->where("site_id",$site_id)
            ->where("type",$type_id)
            ->select($this->defaultFetchColumns)
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
