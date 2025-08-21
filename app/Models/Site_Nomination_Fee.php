<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Site_Nomination_Fee extends Model
{
    use HasFactory;
    protected $table = 'site_nomination_fee';

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
        'site_id',
        'nomination_fee',
        'extension_fee',
        'extension_time_unit',
        'fee',
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $id
     * @return object
     */
    public function scopeFetchFilterSiteData(object $query,int $site_id)
    {
        try {
            return $query
            ->whereNull("deleted_at")
            ->where("site_id",$site_id)
            ->select($this->defaultFetchColumns)
            ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return false;
        }
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
                "site_id" => $parameter['site_id'],
                "nomination_fee" => $parameter['nomination_fee'],
                "extension_fee" => $parameter['extension_fee'],
                "extension_time_unit" => $parameter['extension_time_unit'],
                "fee" => $parameter['fee'],
                "updated_at" => now()
            ])->save();
    }
}
