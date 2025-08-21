<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_Corp extends Model
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
    protected $table = 'm_corp';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "name",
        "short_name",
        "type",
        "responsible_name",
        "postal_code",
        "address1",
        "address2",
        "address3",
        "tel",
        "fax",
        "remarks",
        "is_cosmo",
        "sort",
        "is_public"
    ];
    /**
     * スタッフ全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchAll(object $query):object
    {
        return $query
        ->where("deleted_at", 0)
        ->select($this->defaultFetchColumns)
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
                "name" => $parameter['name'],
                "short_name" => $parameter['short_name'],
                "type" => $parameter['type'],
                "responsible_name" => $parameter['responsible_name'],
                "postal_code" => $parameter['postal_code'],
                "address1" => $parameter['address1'],
                "address2" => $parameter['address2'],
                "address3" => $parameter['address3'],
                "tel" => $parameter['tel'],
                "fax" => $parameter['fax'],
                "is_cosmo" => $parameter['is_cosmo'],
                "sort" => $parameter['sort'],
                "remarks" => $parameter['remarks'],
                "is_public" => $parameter['is_public'],
            ])->save();
    }
}
