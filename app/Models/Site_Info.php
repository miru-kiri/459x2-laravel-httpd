<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Site_Info extends Model
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
    protected $table = 'site_info';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "site_id",
        "title",
        "color",
        "image",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param integer $site
     * @return void
     */
    public function scopeFetchSiteData(object $query,int $siteId)
    {
        try {
            return $query
            ->where([
                'deleted_at' => 0,
                'site_id' => $siteId,
            ])
            ->select($this->defaultFetchColumns)
            ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            return false;
        }
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @return void
     */
    public function scopeFetchAll(object $query)
    {
        return $query
        ->where([
            'deleted_at' => 0,
        ])
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @return void
     */
    public function scopeFetchFilterSiteAll(object $query,array $filter)
    {
        return $query
        ->where('deleted_at',0)
        ->when(!empty($filter), function($query) use($filter){
            if (is_array($filter['site_id'])) {
                return $query->whereIn("site_id", $filter['site_id']);
            }
            return $query->where("site_id", $filter['site_id']);
        })
        ->select($this->defaultFetchColumns)
        ->get();
    }
}
