<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class D_Notice extends Model
{
    use HasFactory;
    protected $table = 'd_notice';

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
        'title',
        'content',
        // 'is_display',
        'display_date',
        'created_user',
        'updated_user'
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchAll(object $query):object
    {
        return $query
        ->where("deleted_at",0)
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchPublicAll(object $query):object
    {
        return $query
        ->where("deleted_at",0)
        ->where("display_date", '<=',date('Y-m-d H:i:s'))
        ->select($this->defaultFetchColumns)
        ->orderby('display_date','DESC')
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return 
     */
    public function scopeSaveData(object $query,array $parameter)
    {
        return $query
            ->findOrFail($parameter["id"])
            ->fill([
                'title' => $parameter['title'],
                'content' => $parameter['content'],
                'display_date' => $parameter['display_date']
            ])->save();
    }
}
