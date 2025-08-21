<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class X459x_Site extends Model
{
    use HasFactory;
    protected $table = 'x459x_corp.site';
    // protected $table;
    protected $primaryKey = 'siteid';
    public $timestamps = false;
    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);
    //     $this->table = env('DB_PREFIX_X459X') . 'corp' . '.site';
    // }
    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = ['siteid'];
    /**
     * データ取得
     *
     * @param object $query
     * @return void
     */
    public function scopeFetchAll(object $query)
    {
        return $query
        ->where("fd78",'none')
        ->get();
    }
    /**
     * データ更新
     *
     * @param object $query
     * @param array $parameter
     * @return bool
     */
    public function scopeUpsertData(object $query,array $parameter)
    {
        if(isset($parameter['id'])) {
            return $query
                ->where('siteid',$parameter['id'])
                ->update([
                    "fd1" => $parameter['shop_id'],
                    "fd2" => $parameter['url'],
                    "fd3" => $parameter['name'],
                    "fd4" => $parameter['style'],
                    "fd5" => $parameter['pc_top_url'],
                    "fd6" => $parameter['sp_top_url'],
                    "fd7" => $parameter['p_top_url'],
                    "fd8" => $parameter['recruit_key'],
                    "fd9" => $parameter['old_template'],
                    "fd10" => $parameter['system_text'], //システム(=,;の配列型式)
                    "fd11" => $parameter['is_cosmo'] == 1 ? 'cosmogroup' :  'general',
                    "fd12" => $parameter['sort'],
                    "fd13" => $parameter['remarks'],
                    "fd14" => $parameter['content'],
                    "fd15" => $parameter['switching_time'],
                    "fd16" => $parameter['blog_owner_host'],
                    "fd17" => $parameter['blog_owner_user'],
                    "fd18" => $parameter['blog_owner_pass'],
                    "fd19" => $parameter['blog_staff_host'],
                    "fd20" => $parameter['blog_staff_user'],
                    "fd21" => $parameter['blog_staff_pass'],
                    "fd22" => $parameter['mail_magazine_url'],
                    "fd23" => $parameter['mail_magazine_key'], 
                    "fd24" => $parameter['mail_magazine_create_mail'], 
                    "fd25" => $parameter['mail_magazine_delete_mail'], 
                    "fd26" => $parameter['recruit_line_url'], 
                    "fd27" => $parameter['recruit_line_id'], 
                    "fd28" => $parameter['analytics_code'], 
                    "fd29" => $parameter['analytics_api'], 
                    "fd30" => $parameter['is_https'], 
                    "fd31" => $parameter['portal_template_url'], 
                    "fd32" => $parameter['portal_tab'], 
                    // "fd33" => $parameter['staff_sort'], 
                    "fd34" => $parameter['open_time'], 
                    "fd35" => $parameter['close_time'], 
                    "fd73" => $parameter['site_hidden'],
                    "fd74" => $parameter['is_externally_server'],
                    // "fd75" => $parameter['site_genre'],
                    // "fd76" => $parameter['site_area'],
                    "fd77" => $parameter['is_public'] == 1 ? 'start' : 'stop',
                    "fd78" => 'none',
                    "fd79" => time()
                    // "fd80" => time()
                ]);
        } else {
            return $query
                ->insert([
                    "fd1" => $parameter['shop_id'],
                    "fd2" => $parameter['url'],
                    "fd3" => $parameter['name'],
                    "fd4" => $parameter['style'],
                    "fd5" => $parameter['pc_top_url'],
                    "fd6" => $parameter['sp_top_url'],
                    "fd7" => $parameter['p_top_url'],
                    "fd8" => $parameter['recruit_key'],
                    "fd9" => $parameter['old_template'],
                    "fd10" => $parameter['system_text'], //システム(=,;の配列型式)
                    "fd11" => $parameter['is_cosmo'] == 1 ? 'cosmogroup' :  'general',
                    "fd12" => $parameter['sort'],
                    "fd13" => $parameter['remarks'],
                    "fd14" => $parameter['content'],
                    "fd15" => $parameter['switching_time'],
                    "fd16" => $parameter['blog_owner_host'],
                    "fd17" => $parameter['blog_owner_user'],
                    "fd18" => $parameter['blog_owner_pass'],
                    "fd19" => $parameter['blog_staff_host'],
                    "fd20" => $parameter['blog_staff_user'],
                    "fd21" => $parameter['blog_staff_pass'],
                    "fd22" => $parameter['mail_magazine_url'],
                    "fd23" => $parameter['mail_magazine_key'], 
                    "fd24" => $parameter['mail_magazine_create_mail'], 
                    "fd25" => $parameter['mail_magazine_delete_mail'], 
                    "fd26" => $parameter['recruit_line_url'], 
                    "fd27" => $parameter['recruit_line_id'], 
                    "fd28" => $parameter['analytics_code'], 
                    "fd29" => $parameter['analytics_api'], 
                    "fd30" => $parameter['is_https'], 
                    "fd31" => $parameter['portal_template_url'], 
                    "fd32" => $parameter['portal_tab'], 
                    // "fd33" => $parameter['staff_sort'], 
                    "fd34" => $parameter['open_time'], 
                    "fd35" => $parameter['close_time'], 
                    "fd73" => $parameter['site_hidden'],
                    "fd74" => $parameter['is_externally_server'],
                    // "fd75" => $parameter['site_genre'],//ワンチャンインサートいらん
                    // "fd76" => $parameter['site_area'],//ワンチャンインサートいらん
                    "fd77" => $parameter['is_public'] == 1 ? 'start' : 'stop',
                    "fd78" => 'none',
                    "fd79" => time(),
                    "fd80" => time()
                ]);
        }
    }
}
