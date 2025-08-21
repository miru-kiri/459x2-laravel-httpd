<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site_Image extends Model
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
    protected $table = 'site_image';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "site_id",
        "category_id",
        "image",
        "url",
        "sort_no",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @return void
     */
    public function scopeFetchSiteCategoryData(object $query,$siteId,$categoryId)
    {
        return $query
        ->where([
            'deleted_at' => 0,
            'site_id' => $siteId,
            'category_id' => $categoryId,
        ])
        ->select($this->defaultFetchColumns)
		->orderby('sort_no','ASC')
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @return void
     */
    public function scopeFilterSiteMaxSortNo(object $query,$siteId,$categoryId)
    {
        return $query
        ->where([
            'deleted_at' => 0,
            'site_id' => $siteId,
            'category_id' => $categoryId,
        ])
        ->max('sort_no');
    }
}
