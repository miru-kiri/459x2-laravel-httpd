<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Cast_Option extends Model
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
    protected $table = 'm_cast_option';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        'id',
        'site_id',
        'name',
    ];
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchAll(object $query): object
    {
        return $query
            ->where("deleted_at", 0)
            ->select($this->defaultFetchColumns)
            ->get();
    }
    /**
     * サイトに紐づくデータを取得
     *
     * @param object $query
     * @param array $filter
     * @return void
     */
    public function scopeFetchFilterData(object $query, array $filter)
    {
        return $query
            ->where('deleted_at', 0)
            ->when(!empty($filter['site_id']), function ($query) use ($filter) {
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("site_id", $filter['site_id']);
                }
                return $query->where("site_id", $filter['site_id']);
            })
            ->select($this->defaultFetchColumns)
            ->get();
    }
}
