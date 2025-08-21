<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Area extends Model
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
    protected $table = 'm_area';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "m_area.id",
        "m_area.name",
        "m_area_group.name as group_name",
        "category_id",
        "group_id",
        "content",
        "remarks",
        "sort",
        "is_public",
        "color"
    ];
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchAll(object $query):object
    {
        return $query
        ->where("m_area.deleted_at", 0)
        ->select($this->defaultFetchColumns)
        ->leftjoin("m_area_group","m_area_group.id","m_area.group_id")
        ->get();
    }
    /**
     * データ更新
     *
     * @param object $query
     * @param array $parameter
     * @return bool
     */
    public function scopeSaveData(object $query,array $parameter):bool
    {
        return $query
            ->findOrFail($parameter["id"])
            ->fill([
                'group_id' => $parameter['group_id'],
                'name' => $parameter['name'],
                'content' => $parameter['content'],
                'sort' => $parameter['sort'],
                'remarks' => $parameter['remarks'],
                'color' => $parameter['color'],
                'is_public' => $parameter['is_public']
            ])->save();
    }
}
