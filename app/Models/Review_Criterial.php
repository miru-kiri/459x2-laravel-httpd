<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review_Criterial extends Model
{
    use HasFactory;
    protected $table = 'review_criterial';

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
    protected $defaultFetchJoinColumns = [
        "id",
        "review_id",
        "criterial_id",
        "evaluate",
    ];
    /**
     * 平均取得
     *
     * @param object $query
     * @param int $reviewId
     * @return
     */
    public function scopeFetchAverage(object $query,int $reviewId)
    {
        return $query
        ->where("review_id",$reviewId)
        ->avg('evaluate');
    }
    /**
     * 平均取得
     *
     * @param object $query
     * @param int $reviewId
     * @return
     */
    public function scopeFetchData(object $query,int $reviewId)
    {
        return $query
        ->where("review_id",$reviewId)
        ->get();
    }
    /**
     * 平均取得
     *
     * @param object $query
     * @param array $filter
     * @return
     */
    public function scopeFetchFilterIdAryData(object $query,array $filter)
    {
        return $query
        ->when(!empty($filter["review_id"]), function($query) use($filter){
            if (is_array($filter["review_id"])) {
                return $query->whereIn("review_id", $filter["review_id"]);
            }
            return $query->where("review_id", $filter["review_id"]);
        })
        ->orderby('review_id')
        ->orderby('criterial_id')
        ->get();
    }
}
