<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class D_Cast_Blog_Image extends Model
{
    use HasFactory;
    protected $table = 'd_cast_blog_image';

    public $timestamps = false;
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "article_id",
        "image_url",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return object
     */
    public function scopeFetchFilterIdData(object $query,$id):object
    {
        return $query
            ->whereNull("deleted_at")
            ->when(!empty($id), function($query) use($id){
                if (is_array($id)) {
                    return $query->whereIn("article_id", $id);
                }
                return $query->where("article_id", $id);
            })
            ->select($this->defaultFetchColumns)
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return
     */
    public function scopeFetchFilterIdFirstData(object $query,int $id)
    {
        try {
            return $query
            ->whereNull("deleted_at")
            ->where("article_id", $id)
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
