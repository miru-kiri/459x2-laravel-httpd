<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast_Message_Ngword extends Model
{
    use HasFactory;
    protected $table = 'cast_message_ngword';

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
        "created_at",
        "site_id",
        "content",
    ];

    /**
     * 全件取得
     *
     * @param object $query
     * @param  $siteId
     * @return object
     */
    public function scopeFetchFilterSiteId(object $query, $siteId):object
    {
        return $query
            ->where('deleted_at',0)
            ->when(!empty($siteId), function($query) use($siteId){
                if (is_array($siteId)) {
                    return $query->whereIn('site_id',$siteId);
                }
                return $query->where('site_id',$siteId);
            })
            ->select($this->defaultFetchColumns)
            ->orderby('created_at','DESC')
            ->get();
    }
}
