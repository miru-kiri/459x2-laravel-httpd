<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;

class M_Cast extends Model
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
    protected $table = 'm_cast';
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchColumns = [
        "m_cast.id",
        "site_id",
        "source_name",
        "catch_copy",
        "age",
        "blood_type",
        "constellation",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
        "figure",
        "figure_comment",
        "self_pr",
        "shop_manager_pr",
        "m_cast.shop_id",
        "stay_status",
        "exclusive_status",
        "m_cast.sort",
        "m_cast.is_public",
        "avatar",
        "is_auto",
        "auto_start_time",
        "auto_end_time",
        "auto_rest_comment",
        "auto_week",
        "sokuhime_date",
        "sokuhime_status",
        "is_sokuhime",
        "username",
        "password",
        "post_email",
        "register_at",
        "m_cast.last_updated_status_at"
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "m_cast.id",
        "site_id",
        "m_site.name as site_name",
        "source_name",
        "catch_copy",
        "age",
        "blood_type",
        "constellation",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
        "figure",
        "figure_comment",
        "self_pr",
        "shop_manager_pr",
        "avatar",
        "is_auto",
        "auto_start_time",
        "auto_end_time",
        "auto_rest_comment",
        "auto_week",
        "username",
        "token_register",
        "is_recommend",
        "m_cast.shop_id",
        "m_shop.name as shop_name",
        "stay_status",
        "exclusive_status",
        "m_cast.sort",
        "m_cast.sokuhime_status",
        "m_cast.last_updated_status_at",
        "m_cast.is_public",
        "m_site.open_time",
        "m_site.close_time",
        "transfer_mail",
        "password_reset_token",
        "avatar",
        "post_email",
        "approval_status",
        "m_cast.created_at",
        "m_cast.updated_at",
        "option",
        "cast_cd"
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchImageJoinColumns = [
        "m_cast.id",
        "m_cast.site_id",
        "source_name",
        "catch_copy",
        "age",
        "blood_type",
        "constellation",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
        "figure",
        "figure_comment",
        "self_pr",
        "shop_manager_pr",
        "m_cast.shop_id",
        "stay_status",
        "exclusive_status",
        "m_cast.sort",
        "m_cast.is_public",
        "avatar",
        "directory",
        "path"
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    private $reserveCountCastColums =
    "m_cast.id,
        m_cast.site_id,
        source_name,
        age,
        bust,
        cup,
        waist,
        hip,
        shop_manager_pr,
        count(d_reserve.id) as reserve_count";
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    private $userLikeCountCastColums =
    "m_cast.id,
        m_cast.site_id,
        source_name,
        age,
        bust,
        cup,
        waist,
        hip,
        count(user_like_cast.id) as like_count";
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    private $userAccessCountCastColums =
    "m_cast.id,
        m_cast.site_id,
        source_name,
        age,
        bust,
        cup,
        waist,
        hip,
        count(d_site_cast_log.id) as access_count";
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    private $userBlogCountCastColums =
    "m_cast.id,
        m_cast.site_id,
        source_name,
        age,
        bust,
        cup,
        waist,
        hip,
        count(d_site_blog_log.id) as blog_count";
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    private $reserveCastColums = [
        "m_cast.id",
        "m_cast.site_id",
        "source_name",
        "catch_copy",
        "age",
        "blood_type",
        "constellation",
        "height",
        "bust",
        "cup",
        "waist",
        "hip",
        "figure",
        "figure_comment",
        "self_pr",
        "shop_manager_pr",
        "m_cast.shop_id",
        "stay_status",
        "exclusive_status",
        "m_cast.sort",
        "m_cast.is_public",
        "avatar",
        "directory",
        "path"
    ];
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchAll(object $query): object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->select($this->defaultFetchColumns)
            ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterAll(object $query, array $filter): object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->when(!empty($filter['site_id']), function ($query) use ($filter) {
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("site_id", $filter['site_id']);
                }
                return $query->where("site_id", $filter['site_id']);
            })
            ->when(!empty($filter['cast_id']), function ($query) use ($filter) {
                return $query->where("m_cast.id", $filter['cast_id']);
            })
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->select($this->defaultFetchColumns)
            ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterAllSortNo(object $query, array $filter): object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->when(!empty($filter['site_id']), function ($query) use ($filter) {
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("site_id", $filter['site_id']);
                }
                return $query->where("site_id", $filter['site_id']);
            })
            ->when(!empty($filter['cast_id']), function ($query) use ($filter) {
                return $query->where("m_cast.id", $filter['cast_id']);
            })
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->leftjoin("m_shop", "m_shop.id", "m_site.shop_id")
            ->orderby('sort')
            ->select($this->defaultFetchJoinColumns)
            ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilteringData(object $query, array $filter): object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->when($filter['is_auto'], function ($query) use ($filter) {
                return $query->where("is_auto", $filter['is_auto']);
            })
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->select($this->defaultFetchColumns)
            ->get();
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @param int $userId
     * @return object
     */
    public function scopeFetchFilterId(object $query, int $castId)
    {
        try {
            return $query
                ->where([
                    "m_site.deleted_at" => 0,
                    "m_cast.id" => $castId,
                ])
                ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
                ->leftjoin("m_shop", "m_shop.id", "m_cast.shop_id")
                ->select($this->defaultFetchJoinColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * 全件取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterAryId(object $query, $id): object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    return $query->whereIn("site_id", $id);
                }
                return $query->where("site_id", $id);
            })
            ->where('m_cast.is_public', 1)
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->orderby('m_cast.sort')
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
    public function scopeFilteringMultiSiteData(object $query, array $filter): object
    {
        return $query
            ->where([
                "m_cast.deleted_at" => 0,
                "m_site.deleted_at" => 0,
                "m_shop.deleted_at" => 0,
            ])
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            // ->when(!empty($filter["shop_id"]), function($query) use($filter){
            //     return $query->where("m_cast.shop_id", $filter["shop_id"]);
            // })
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->leftjoin("m_shop", "m_shop.id", "m_site.shop_id")
            ->select($this->defaultFetchJoinColumns)
            ->orderby('m_cast.sort')
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchSiteIdLimitData(object $query, array $siteIdAry, int $limit): object
    {
        return $query
            // ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->whereIn("site_id", $siteIdAry)
            // ->leftjoin("m_site","m_site.id","m_cast.site_id")
            ->select($this->defaultFetchColumns)
            ->orderby('id')
            ->limit($limit)
            ->get();
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchFilteringFirstData(object $query, array $filter)
    {
        try {
            return $query
                ->where('deleted_at', 0)
                ->where('is_public', 1)
                ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                    return $query->where("site_id", $filter["site_id"]);
                })
                ->select($this->defaultFetchColumns)
                ->orderby('sort')
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
    public function scopeFetchReserveCountCast(object $query, array $filter): object
    {
        return $query
            ->where("deleted_at", 0)
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                return $query->where("site_id", $filter["site_id"]);
            })
            ->where('m_cast.is_public', 1)
            ->leftjoin("d_reserve", "d_reserve.cast_id", "m_cast.id")
            ->selectRaw($this->reserveCountCastColums)
            ->groupby('m_cast.id', 'm_cast.site_id', 'source_name', 'age', 'bust', 'cup', 'waist', 'hip', 'shop_manager_pr')
            ->orderby('reserve_count', 'DESC')
            ->when(!empty($filter["limit"]), function ($query) use ($filter) {
                return $query->limit($filter['limit']);
            })
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchAccessCountCast(object $query, array $filter): object
    {
        return $query
            ->where("m_cast.deleted_at", 0)
            ->where("d_site_cast_log.deleted_at", 0)
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["date"]), function ($query) use ($filter) {
                return $query->whereBetween("date", $filter["date"]);
            })
            ->leftjoin("d_site_cast_log", "d_site_cast_log.cast_id", "m_cast.id")
            ->selectRaw($this->userAccessCountCastColums)
            ->groupby('m_cast.id', 'm_cast.site_id', 'source_name', 'age', 'bust', 'cup', 'waist', 'hip')
            ->orderby('access_count', 'DESC')
            ->when(!empty($filter["limit"]), function ($query) use ($filter) {
                return $query->limit($filter['limit']);
            })
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchBlogCountCast(object $query, array $filter): object
    {
        return $query
            ->where("m_cast.deleted_at", 0)
            ->where("d_site_blog_log.deleted_at", 0)
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("m_cast.site_id", $filter["site_id"]);
                }
                return $query->where("m_cast.site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["date"]), function ($query) use ($filter) {
                return $query->whereBetween("date", $filter["date"]);
            })
            ->when(!empty($filter["category_id"]), function ($query) use ($filter) {
                return $query->where("category_id", $filter["category_id"]);
            })
            ->leftjoin("d_site_blog_log", "d_site_blog_log.cast_id", "m_cast.id")
            ->selectRaw($this->userBlogCountCastColums)
            ->groupby('m_cast.id', 'm_cast.site_id', 'source_name', 'age', 'bust', 'cup', 'waist', 'hip')
            ->orderby('blog_count', 'DESC')
            ->when(!empty($filter["limit"]), function ($query) use ($filter) {
                return $query->limit($filter['limit']);
            })
            ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchLikeCountCast(object $query, array $filter): object
    {
        return $query
            ->where("m_cast.deleted_at", 0)
            ->whereNull("user_like_cast.deleted_at")
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->when(!empty($filter["date"]), function ($query) use ($filter) {
                return $query->whereBetween("date", $filter["date"]);
            })
            ->leftjoin("user_like_cast", "user_like_cast.cast_id", "m_cast.id")
            ->selectRaw($this->userLikeCountCastColums)
            ->groupby('m_cast.id', 'm_cast.site_id', 'source_name', 'age', 'bust', 'cup', 'waist', 'hip')
            ->orderby('like_count', 'DESC')
            ->when(!empty($filter["limit"]), function ($query) use ($filter) {
                return $query->limit($filter['limit']);
            })
            ->get();
    }
    /**
     * ログイン
     *
     * @param object $query
     * @return object
     */
    public function scopeCheckLogin(object $query, $username)
    {
        try {
            return $query
                ->where("m_site.deleted_at", 0)
                ->where("m_cast.deleted_at", 0)
                ->where("m_cast.username", $username)
                ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
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
    public function scopeFetchAutoData(object $query): object
    {
        return $query
            ->where([
                "deleted_at" => 0,
                "is_auto" => 1,
            ])
            ->orderby('id')
            ->get();
    }
    /**
     * ログイン
     *
     * @param object $query
     * @param string $token
     * @return object
     */
    public function scopeCheckToken(object $query, $token)
    {
        try {
            return $query
                ->where("m_site.deleted_at", 0)
                ->where("m_cast.deleted_at", 0)
                ->where("m_cast.token_register", $token)
                ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * ログイン
     *
     * @param object $query
     * @param string $token
     * @return object
     */
    public function scopeCheckPasswordToken(object $query, $token)
    {
        try {
            return $query
                ->where("m_site.deleted_at", 0)
                ->where("m_cast.deleted_at", 0)
                ->where("m_cast.password_reset_token", $token)
                ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * ログイン
     *
     * @param object $query
     * @param string $token
     * @return object
     */
    public function scopeCheckPostMail(object $query, $mail)
    {
        try {
            return $query
                ->where("m_site.deleted_at", 0)
                ->where("m_cast.deleted_at", 0)
                ->where("m_cast.post_email", $mail)
                ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }
    public function scopeFetchFilterRecommendCast(object $query, $filter)
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->where("m_cast.deleted_at", 0)
            ->where("m_cast.is_public", 1)
            ->where("m_cast.is_recommend", 1)
            ->when(!empty($filter["site_id"]), function ($query) use ($filter) {
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->leftjoin("m_site", "m_site.id", "m_cast.site_id")
            ->select($this->defaultFetchColumns)
			->orderby('sort')
            ->get();
    }
    /**
     * 条件からIDを配列で取得
     *
     * @param object $query
     * @return object
     */
    public function scopeFetchFilterIdAry(object $query, array $filter): array
    {
        return $query
            ->where("m_cast.deleted_at", 0)
            ->when(!empty($filter['site_id']), function ($query) use ($filter) {
                if (is_array($filter['site_id'])) {
                    return $query->whereIn("site_id", $filter['site_id']);
                }
                return $query->where("site_id", $filter['site_id']);
            })
            ->pluck('id')
            ->toArray();
    }
}
