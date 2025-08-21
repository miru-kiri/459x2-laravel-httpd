<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Cast_Question extends Model
{
    use HasFactory;

    protected $table = 'm_cast_question';

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
        'site_id',
        'question',
        'default_answer',
        'sort_no',
        'is_public',
    ];
    /**
     * 該当するサイトの質問内応を取得
     *
     * @param object $query
     * @param integer $siteId
     * @return void
     */
    public function scopeFetchFilteringSiteId(object $query,int $siteId)
    {
        return $query->where([
                        'deleted_at' => 0,   
                        'site_id' => $siteId,
                    ])
                    ->select($this->defaultFetchColumns)
                    ->orderby('sort_no')
                    ->get();
    }
}
