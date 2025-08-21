<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class D_Reserve extends Model
{
    use HasFactory;

    protected $table = 'd_reserve';

    // public $timestamps = false;
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
        "user_id",
        "cast_id",
        "status",
        "type",
        "type_reserve",
        "indicate_fee1",
        "indicate_fee_1_flg",
        "indicate_fee_2",
        "indicate_fee2_flg",
        "extension_time",
        "extension_money",
        "discount",
        "site_id_reserve",
        "amount",
        "start_time",
        "end_time",
        "memo",
        "address",
        "course_money",
        "course_time",
        "transaction_fee",
        "course_name",
        "is_guest",
        "guest_name",
        "guest_phone",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "d_reserve.id",
        "user_id",
        "cast_id",
        "source_name",
        "age",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
        "status",
        "type",
        "type_reserve",
        "indicate_fee1",
        "indicate_fee1_flg",
        "indicate_fee2",
        "indicate_fee2_flg",
        "extension_time",
        "extension_money",
        "discount",
        "site_id_reserve",
        "m_site.name as site_name",
        "amount",
        "start_time",
        "end_time",
        "memo",
        "address",
        "course_money",
        "course_time",
        "transaction_fee",
        "course_name",
        "is_guest",
        "guest_name",
        "guest_phone",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinUserColumns = [
        "d_reserve.id",
        "user_id",
        "d_user.name as user_name",
        "d_user.name_show",
        "cast_id",
        "status",
        "type",
        "type_reserve",
        "indicate_fee1",
        "indicate_fee1_flg",
        "indicate_fee2",
        "indicate_fee2_flg",
        "extension_time",
        "extension_money",
        "discount",
        "site_id_reserve",
        "m_site.name as site_name",
        "amount",
        "start_time",
        "end_time",
        "d_reserve.memo",
        "d_reserve.address",
        "course_money",
        "course_time",
        "transaction_fee",
        "course_name",
        "is_guest",
        "guest_name",
        "guest_phone",
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinUserCastColumns = [
        "d_reserve.id",
        "user_id",
        "cast_id",
        "source_name",
        "age",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
        "status",
        "type",
        "type_reserve",
        "indicate_fee1",
        "indicate_fee1_flg",
        "indicate_fee2",
        "indicate_fee2_flg",
        "extension_time",
        "extension_money",
        "discount",
        "site_id_reserve",
        "m_site.name as site_name",
        "amount",
        "start_time",
        "end_time",
        "d_reserve.memo",
        "d_reserve.address",
        "course_money",
        "course_time",
        "transaction_fee",
        "course_name",
        "d_user.name as user_name",
        "name_furigana",
        "name_show",
        "phone",
        "is_guest",
        "guest_name",
        "guest_phone",
    ];
    /**
     * スタッフ全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterUserData(object $query,$userId):object
    {
        return $query
        ->where('user_id',$userId)
        ->leftjoin('m_cast','m_cast.id','cast_id')
        ->leftjoin('m_site','m_site.id','site_id_reserve')
        ->select($this->defaultFetchJoinColumns)
        ->get();
    }
    /**
     * スタッフ全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterCastData(object $query,$filter):object
    {
        return $query
        ->when(!empty($filter["cast_id"]), function($query) use($filter){
            if (is_array($filter["cast_id"])) {
                return $query->whereIn("cast_id", $filter["cast_id"]);
            }
            return $query->where('cast_id',$filter['cast_id']);
        })
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where('site_id_reserve',$filter['site_id']);
        })
        ->when(!empty($filter["start_time"]), function($query) use($filter){
            return $query->where('start_time','>=',$filter['start_time']);
        })
        ->when(!empty($filter["end_time"]), function($query) use($filter){
            return $query->where('end_time','<=',$filter['end_time']);
        })
        ->when(!empty($filter["status"]), function($query) use($filter){
            if (is_array($filter["status"])) {
                return $query->whereIn("status", $filter["status"]);
            }
            return $query->where('status',$filter['status']);
        })
        ->leftjoin('d_user','d_user.id','user_id')
        ->leftjoin('m_cast','m_cast.id','cast_id')
        ->leftjoin('m_site','m_site.id','site_id_reserve')
        ->select($this->defaultFetchJoinUserCastColumns)
        ->get();
    }
    /**
     * スタッフ全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilteringData(object $query,array $filter):object
    {
        return $query
        // ->whereNull("d_reserve.deleted_at")
        ->when(!empty($filter["user_id"]), function($query) use($filter){
            return $query->where('user_id',$filter['user_id']);
        })
        ->when(!empty($filter["status"]), function($query) use($filter){
            if (is_array($filter["status"])) {
                return $query->whereIn("status", $filter["status"]);
            }
            return $query->where('status',$filter['status']);
        })
        ->when(!empty($filter["start_time"]), function($query) use($filter){
            return $query->where('start_time','>=',$filter['start_time']);
        })
        ->leftjoin('m_cast','m_cast.id','cast_id')
        ->leftjoin('m_site','m_site.id','site_id_reserve')
        ->select($this->defaultFetchJoinColumns)
        ->when(!empty($filter["limit"]), function($query) use($filter){
            return $query->limit($filter['limit']);
        })
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
            ->fill($parameter)->save();
    }
    /**
     * スタッフ全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterStatusCastData(object $query,$filter):object
    {
        return $query
        ->when(!empty($filter["cast_id"]), function($query) use($filter){
            if (is_array($filter["cast_id"])) {
                return $query->whereIn("cast_id", $filter["cast_id"]);
            }
            return $query->where('cast_id',$filter['cast_id']);
        })
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            return $query->where('site_id_reserve',$filter['site_id']);
        })
        ->when(!empty($filter["status"]), function($query) use($filter){
            if (is_array($filter["status"])) {
                return $query->whereIn("status", $filter["status"]);
            }
            return $query->where('status',$filter['status']);
        })
        ->when(!empty($filter["start_time"]), function($query) use($filter){
            return $query->where('start_time','>=',$filter['start_time']);
        })
        ->when(!empty($filter["end_time"]), function($query) use($filter){
            return $query->where('end_time','<=',$filter['end_time']);
        })
        // ->leftjoin('d_user','d_user.id','user_id')
        ->leftjoin('m_cast','m_cast.id','cast_id')
        ->leftjoin('m_site','m_site.id','site_id_reserve')
        ->select($this->defaultFetchJoinColumns)
        ->get();
    }
    /**
     * スタッフ全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterStatusUserData(object $query,$filter):object
    {
        return $query
        ->whereNull('cast_id')
        ->where('type',1)
        ->when(!empty($filter["site_id"]), function($query) use($filter){
            if (is_array($filter["site_id"])) {
                return $query->whereIn("site_id_reserve", $filter["site_id"]);
            }
            return $query->where('site_id_reserve',$filter['site_id']);
        })
        ->when(!empty($filter["status"]), function($query) use($filter){
            if (is_array($filter["status"])) {
                return $query->whereIn("status", $filter["status"]);
            }
            return $query->where('status',$filter['status']);
        })
        ->when(!empty($filter["start_time"]), function($query) use($filter){
            return $query->where('start_time','>=',$filter['start_time']);
        })
        ->when(!empty($filter["end_time"]), function($query) use($filter){
            return $query->where('end_time','<=',$filter['end_time']);
        })
        ->leftjoin('d_user','d_user.id','user_id')        
        ->leftjoin('m_site','m_site.id','site_id_reserve')
        ->select($this->defaultFetchJoinUserColumns)
        ->get();
    }
    /**
     * スタッフ全件取得
     *
     * @param object $query
     * @return
     */
    public function scopeIsReserveData(object $query,$filter)
    {
        try {
            return $query
            ->when(!empty($filter["cast_id"]), function($query) use($filter){
                if (is_array($filter["cast_id"])) {
                    return $query->whereIn("cast_id", $filter["cast_id"]);
                }
                return $query->where('cast_id',$filter['cast_id']);
            })
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id_reserve", $filter["site_id"]);
                }
                return $query->where('site_id_reserve',$filter['site_id']);
            })
            ->when(!empty($filter["status"]), function($query) use($filter){
                if (is_array($filter["status"])) {
                    return $query->whereIn("status", $filter["status"]);
                }
                return $query->where('status',$filter['status']);
            })
            ->where(function ($query) use ($filter) {
                // $query->whereBetween('start_time', [$filter['start_time'], $filter['end_time']])
                //         ->orWhereBetween('end_time', [$filter['start_time'], $filter['end_time']])
                //         ->orWhere(function ($query) use ($filter) {
                //             $query->where('start_time', '<', $filter['start_time'])
                //                 ->where('end_time', '>', $filter['end_time']);
                //         });
                // 完全に重なる部分を除外する条件
                $query->where('start_time', '<', $filter['end_time'])
                ->where('end_time', '>', $filter['start_time']);
                })->where(function($query) use ($filter) {
                // 開始時刻が指定された範囲内にかかるものは除外
                $query->where('end_time', '>', $filter['start_time'])
                        ->where('start_time', '<', $filter['end_time']);
            })
            ->leftjoin('d_user','d_user.id','user_id')        
            ->leftjoin('m_site','m_site.id','site_id_reserve')
            ->select($this->defaultFetchJoinUserColumns)
            ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        } 
    }
}
