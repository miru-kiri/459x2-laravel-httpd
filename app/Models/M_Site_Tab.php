<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class M_Site_Tab extends Model
{
    use HasFactory;

    protected $table = 'm_site_tab';

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
        "template",
        "name",
        "url",
        "is_display"
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringData(object $query,array $filter):object
    {
        return $query
        ->where("deleted_at",0)
        ->when(!empty($filter["template"]), function($query) use($filter){
            return $query->where("template", $filter["template"]);
        })
        ->select($this->defaultFetchColumns)
        ->orderby('id')
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFirstTemplateData(object $query,array $filter)
    {
        try {
            return $query
                ->where("deleted_at",0)
                ->when(!empty($filter["template"]), function($query) use($filter){
                    return $query->where("template", $filter["template"]);
                })
                ->when(!empty($filter["name"]), function($query) use($filter){
                    return $query->where("name", $filter["name"]);
                })
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
