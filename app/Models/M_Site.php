<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class M_Site extends Model
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
    protected $table = 'm_site';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */

    protected $defaultFetchColumns = [
            'id',
            'shop_id',
            'url',
            'name',
            'style',
            'top_url',
            'recruit_key',
            'template',
            'is_cosmo',
            'sort',
            'remarks',
            'content',
            'switching_time',
            'blog_owner_host',
            'blog_owner_user',
            'blog_owner_pass',
            'blog_staff_host',
            'blog_staff_user',
            'blog_staff_pass',
            'mail_magazine_url',
            'mail_magazine_key',
            'mail_magazine_create_mail',
            'mail_magazine_delete_mail',
            'recruit_line_url',
            'recruit_line_id',
            'analytics_code',
            'analytics_api',
            'is_https',
            'portal_template_url',
            'portal_tab',
            'staff_sort',
            'open_time',
            'close_time',
            'is_externally_server',
            'is_public',
            'reserve_approval_date',
            'reserve_buffer_time',
            'reserve_close_branch',
            'reserve_close'
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
            'm_site.id',
            'shop_id',
            'url',
            'm_site.name',
            'm_shop.name as shop_name',
            'm_site.style',
            'top_url',
            'recruit_key',
            'template',
            'm_site.is_cosmo',
            'm_site.sort',
            'm_site.remarks',
            'm_site.content',
            'switching_time',
            'blog_owner_host',
            'blog_owner_user',
            'blog_owner_pass',
            'blog_staff_host',
            'blog_staff_user',
            'blog_staff_pass',
            'mail_magazine_url',
            'mail_magazine_key',
            'mail_magazine_create_mail',
            'mail_magazine_delete_mail',
            'recruit_line_url',
            'recruit_line_id',
            'analytics_code',
            'analytics_api',
            'is_https',
            'portal_template_url',
            'portal_tab',
            'staff_sort',
            'open_time',
            'close_time',
            'is_externally_server',
            'm_site.is_public',
            'opening_text',
            'holiday_text',
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinImageColumns = [
            'm_site.id as site_id',
            'm_site.shop_id',
            'url',
            'm_site.name',
            'm_site.style',
            'top_url',
            // 'recruit_key',
            'template',
            // 'm_site.is_cosmo',
            'm_site.sort',
            'm_site.remarks',
            'm_site.content', 
            'staff_sort',
            'open_time',
            'close_time',
            'm_site.is_public',
            'm_cast.id as cast_id',
            'source_name',
            'cast_image.id as cast_image_id',
            'directory',
            'path',
            'sort_no',
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
    public function scopeFetchPublicData(object $query,array $filter):object
    {
        return $query
        ->where("deleted_at", 0)
        ->when(!empty($filter['template']), function($query) use($filter){
            if (is_array($filter['template'])) {
                return $query->whereIn("template", $filter['template']);
            }
            return $query->where("template", $filter['template']);
        })
        ->when(!empty($filter['site_id']), function($query) use($filter){
            if (is_array($filter['site_id'])) {
                return $query->whereIn("id", $filter['site_id']);
            }
            return $query->where("id", $filter['site_id']);
        })
        ->where('is_public',1)
        ->select($this->defaultFetchColumns)
        ->get();
    }
    /**
     * 条件に合ったIDを配列で取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterIdAry(object $query,array $filter):array
    {
        return $query
        ->where("deleted_at", 0)
        ->when(!empty($filter['template']), function($query) use($filter){
            return $query->where("template", $filter['template']);
        })
        ->where('is_public',1)
        ->pluck('id')
        ->toArray();
    }
    /**
     * 条件に合ったIDを配列で取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterShopIdAry(object $query,array $filter):array
    {
        return $query
        ->where("deleted_at", 0)
        ->when(!empty($filter['template']), function($query) use($filter){
            if (is_array($filter['template'])) {
                return $query->whereIn("template", $filter['template']);
            }
            return $query->where("template", $filter['template']);
        })
        ->where('is_public',1)
        ->pluck('shop_id')
        ->toArray();
    }
    /**
     * 条件に合ったIDを配列で取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterAreaNameIdAry(object $query,array $filter):array
    {
        return $query
        ->where("m_site.deleted_at", 0)
        ->where("m_shop.deleted_at", 0)
        ->leftjoin("m_shop","m_shop.id","m_site.shop_id")
        ->when(!empty($filter['template']), function($query) use($filter){
            $query->where('template',$filter['template']);
        })
        ->when(!empty($filter['area_name']), function($query) use($filter){
            $query->where(DB::raw("CONCAT(address1,address2,address3)"), "LIKE", "%" . $filter["area_name"] . "%");
        })
        ->where('m_site.is_public',1)
        // ->where('m_shop.is_public',1)
        ->pluck('m_site.id')
        ->toArray();
    }
    /**
     * 全件取得店舗絞り込み
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchJoinAll(object $query):object
    {
        return $query
        ->where("m_site.deleted_at", 0)
        ->leftjoin("m_shop","m_shop.id","m_site.shop_id")
        ->select($this->defaultFetchJoinColumns)
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
    public function scopeFetchShopFilterAryId(object $query,$id):object
    {
        return $query
        ->where("m_site.deleted_at", 0)
        ->when(!empty($id), function($query) use($id){
            if (is_array($id)) {
                return $query->whereIn("m_site.id", $id);
            }
            return $query->where("m_site.id", $id);
        })
        ->orderByRaw('m_site.sort IS NULL, m_site.sort ASC')
        ->leftjoin("m_shop","m_shop.id","m_site.shop_id")
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
                'shop_id' => $parameter['shop_id'],
                'url' => $parameter['url'],
                'name' => $parameter['name'],
                'style' => $parameter['style'],
                // 'top_url' => $parameter['top_url'],
                // 'recruit_key',
                'template' => $parameter['template'],
                'is_cosmo' => $parameter['is_cosmo'],
                'sort' => $parameter['sort'],
                'remarks' => $parameter['remarks'],
                'content' => $parameter['content'],
                // 'switching_time',
                // 'blog_owner_host',
                // 'blog_owner_user',
                // 'blog_owner_pass',
                // 'blog_staff_host',
                // 'blog_staff_user',
                // 'blog_staff_pass',
                // 'mail_magazine_url',
                // 'mail_magazine_key',
                // 'mail_magazine_create_mail',
                // 'mail_magazine_delete_mail',
                // 'recruit_line_url',
                // 'recruit_line_id',
                // 'analytics_code',
                // 'analytics_api',
                // 'is_https',
                // 'portal_template_url',
                // 'portal_tab',
                // 'staff_sort' => $parameter['staff_sort'],
                // 'open_time' => $parameter['open_time'],
                // 'close_time' => $parameter['close_time'],
                // 'is_externally_server',
                'is_public' => $parameter['is_public'],
            ])->save();
    }
    /**
     * サイト取得
     *
     * @param object $query
     * @param int $siteId
     * @return object
     */
    public function scopeFetchTargetDataForSiteId(object $query, $siteId)
    {
        try {
            return $query
                ->where([
                    "m_site.deleted_at" => 0,
                    "m_site.id" => $siteId,
                ])
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
