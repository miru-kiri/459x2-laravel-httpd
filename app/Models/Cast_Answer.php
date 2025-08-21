<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Cast_Answer extends Model
{
    use HasFactory;

    protected $table = 'cast_answer';

    public $timestamps = false;
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * デフォルトカラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        'id',
        'cast_id',
        'question_id',
        'answer',
        'is_public',
    ];
    /**
     * 該当するキャストの回答内容を取得
     *
     * @param object $query
     * @param integer $castId
     * @return void
     */
    public function scopeFetchFilteringCastId(object $query,int $castId)
    {
        return $query->where([
                        'deleted_at' => 0,
                        'cast_id' => $castId,
                    ])
                    ->select($this->defaultFetchColumns)
                    ->get();
    }
    /**
     * 該当するキャストの回答内容を取得
     *
     * @param object $query
     * @param integer $castId
     * @return void
     */
    public function scopeFetchFilteringData(object $query,array $filter)
    {
        try {
            return $query->where([
                'deleted_at' => 0,
            ])
            ->when(!empty($filter['cast_id']), function($query) use($filter){
                return $query->where("cast_id", $filter['cast_id']);
            })
            ->when(!empty($filter['question_id']), function($query) use($filter){
                return $query->where("question_id", $filter['question_id']);
            })
            ->select($this->defaultFetchColumns)
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
