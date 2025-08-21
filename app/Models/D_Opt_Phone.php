<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class D_Opt_Phone extends Model
{
    use HasFactory;

    protected $table = 'd_opt_phone';

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
        "user_id",
        "category_id",
        "expiration_time",
        "phone",
        "code",
        "is_confirm",
        "token",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilterCodeData(object $query,array $filter)
    {
        try {
            return $query
                ->where("deleted_at", 0)
                ->when(!empty($filter["user_id"]), function($query) use($filter){
                    return $query->where("user_id", $filter["user_id"]);
                })
                ->when(!empty($filter["code"]), function($query) use($filter){
                    return $query->where("code", $filter["code"]);
                })
                ->when(!empty($filter["category_id"]), function($query) use($filter){
                    return $query->where("category_id", $filter["category_id"]);
                })
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilterCodeIsConfirmData(object $query,array $filter)
    {
        try {
            return $query
                ->where("deleted_at", 0)
                ->when(!empty($filter["user_id"]), function($query) use($filter){
                    return $query->where("user_id", $filter["user_id"]);
                })
                ->when(!empty($filter["code"]), function($query) use($filter){
                    return $query->where("code", $filter["code"]);
                })
                ->when(!empty($filter["category_id"]), function($query) use($filter){
                    return $query->where("category_id", $filter["category_id"]);
                })
                ->where("is_confirm", 0)
                ->orderby('id','DESC')
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
}
