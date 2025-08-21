<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class M_Shop extends Model
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
    protected $table = 'm_shop';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "id",
        "corporate_id",
        "name",
        "kana",
        "short_name",
        "short_kana",
        "style",
        "genre_id",
        "responsible_name",
        "postal_code",
        "address1",
        "address2",
        "address3",
        "tel",
        "fax",
        "is_notification",
        "remarks",
        "sort",
        "applying",
        "is_cosmo",
        "workday_start_time",
        "workday_end_time",
        "friday_start_time",
        "friday_end_time",
        "saturday_start_time",
        "saturday_end_time",
        "sunday_start_time",
        "sunday_end_time",
        "holiday_start_time",
        "holiday_end_time",
        "mail",
        "recruit_tel",
        "recruit_mail",
        "is_public",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchSiteColumns = [
        "m_shop.id",
        "corporate_id",
        "m_shop.name",
        "url",
        "m_site.id as site_id",
        "m_site.name as site_name",
        "content",
        "kana",
        "short_name",
        "short_kana",
        "m_shop.style",
        "genre_id",
        "responsible_name",
        "postal_code",
        "address1",
        "address2",
        "address3",
        
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "m_shop.id",
        "corporate_id",
        "m_shop.name",
        "m_corp.name as corp_name",
        "m_genre.name as genre_name",
        "kana",
        "m_shop.short_name",
        "m_corp.short_name as corp_short_name",
        "short_kana",
        "m_shop.style",
        "genre_id",
        "m_shop.responsible_name",
        "m_shop.postal_code",
        "m_shop.address1",
        "m_shop.address2",
        "m_shop.address3",
        "m_shop.tel",
        "m_shop.fax",
        "is_notification",
        "m_shop.remarks",
        "m_shop.sort",
        "applying",
        "m_shop.is_cosmo",
        "workday_start_time",
        "workday_end_time",
        "friday_start_time",
        "friday_end_time",
        "saturday_start_time",
        "saturday_end_time",
        "sunday_start_time",
        "sunday_end_time",
        "holiday_start_time",
        "holiday_end_time",
        "m_shop.mail",
        "recruit_tel",
        "recruit_mail",
        "m_shop.is_public",
        "opening_text",
        "holiday_text",
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
        ->where("deleted_at", 0)
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterAryId(object $query,$id):object
    {
        return $query
        ->where("deleted_at", 0)
        ->when(!empty($id), function($query) use($id){
            if (is_array($id)) {
                return $query->whereIn("id", $id);
            }
            return $query->where("id", $id);
        })
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterData(object $query,array $filter):object
    {
        return $query
        ->where("m_shop.deleted_at", 0)
        ->where("m_site.deleted_at", 0)
        ->where("m_shop.is_public", 1)
        ->where("m_site.is_public", 1)
        ->when(!empty($filter['shop_id']), function($query) use($filter){
            if (is_array($filter['shop_id'])) {
                return $query->whereIn("m_shop.id", $filter['shop_id']);
            }
            return $query->where("m_shop.id", $filter['shop_id']);
        })
        ->when(!empty($filter['genre_id']), function($query) use($filter){
            if (is_array($filter['genre_id'])) {
                return $query->whereIn("genre_id", $filter['genre_id']);
            }
            return $query->where("genre_id", $filter['genre_id']);
        })
        ->leftjoin("m_site","m_site.shop_id","m_shop.id")
        ->select($this->defaultFetchSiteColumns)
        ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchJoinData(object $query):object
    {
        return $query
        ->where("m_shop.deleted_at", 0)
        ->leftjoin("m_corp","m_corp.id","m_shop.corporate_id")
        ->leftjoin("m_genre","m_genre.id","m_shop.genre_id")
        ->select($this->defaultFetchJoinColumns)
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
                'corporate_id' => $parameter['corporate_id'],
                'name' => $parameter['name'],
                'kana' => $parameter['kana'],
                'short_name' => $parameter['short_name'],
                'short_kana' => $parameter['short_kana'],
                'style' => $parameter['style'],
                'genre_id' => $parameter['genre_id'],
                'responsible_name' => $parameter['responsible_name'],
                'postal_code' => $parameter['postal_code'],
                'address1' => $parameter['address1'],
                'address2' => $parameter['address2'],
                'address3' => $parameter['address3'],
                'mail' => $parameter['mail'],
                'tel' => $parameter['tel'],
                'fax' => $parameter['fax'],
                'is_cosmo' => $parameter['is_cosmo'],
                'applying' => $parameter['applying'],
                // 'recruit_tel' => $parameter['recruit_tel'],
                // 'recruit_mail' => $parameter['recruit_mail'],
                'opening_text' => $parameter['opening_text'],
                'holiday_text' => $parameter['holiday_text'],
                'sort' => $parameter['sort'],
                'remarks' => $parameter['remarks'],
                'is_notification' => $parameter['is_notification'],
                'is_public' => $parameter['is_public'],
            ])->save();
    }
    public function sites()
    {
       return $this->hasMany(M_Site::class, 'id');
    }
}
