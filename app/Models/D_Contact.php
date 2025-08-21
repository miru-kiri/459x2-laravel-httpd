<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class D_Contact extends Model
{
    use HasFactory;

    protected $table = 'd_contact';

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
        'd_contact.id',
        'user_id',
        'd_contact.site_id',
        'cast_id',
        'date',
        'time',
        'd_contact.name',
        'site_name',
        'cast_name',
        'd_contact.phone',
        'd_contact.email',
        'title',
        'd_contact.content',
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchAll(object $query):object
    {
        return $query
        ->where("d_contact.deleted_at",0)
        // ->where("d_user.deleted_at",0)
        ->where("m_site.deleted_at",0)
        // ->where("m_cast.deleted_at",0)
        ->select($this->defaultFetchJoinColumns)
        ->leftjoin("d_user","d_user.id","d_contact.user_id")
        ->leftjoin("m_site","m_site.id","d_contact.site_id")
        ->leftjoin("m_cast","m_cast.id","d_contact.cast_id")
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilterData(object $query,$filter):object
    {
        return $query
        ->where("d_contact.deleted_at",0)
        // ->where("d_user.deleted_at",0)
        ->where("m_site.deleted_at",0)
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            if (is_array($filter["site_id"])) {
                return $query->whereIn("d_contact.site_id", $filter["site_id"]);
            }
            return $query->where("d_contact.site_id", $filter["site_id"]);
        })
        // ->where("m_cast.deleted_at",0)
        ->select($this->defaultFetchJoinColumns)
        ->leftjoin("d_user","d_user.id","d_contact.user_id")
        ->leftjoin("m_site","m_site.id","d_contact.site_id")
        ->leftjoin("m_cast","m_cast.id","d_contact.cast_id")
        ->get();
    }
}
