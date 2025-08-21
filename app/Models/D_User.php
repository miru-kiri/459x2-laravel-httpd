<?php

namespace App\Models;

use Dotenv\Util\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class D_User extends Model
{
    // use SoftDeletes;
    use HasFactory;

    protected $table = 'd_user';

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
        "name",
        "created_at",
        "deleted_at",
        "name",
        "name_furigana",
        "nickname",
        "name_show",
        "email",
        "phone",
        "site_id",
        "rank",
        "block",
        "memo",
        "address",
        "birth_day",
        "avatar",
        "password",
        "otp_code",
        "expired_otp_code",
        "phone_otp",
        "last_login",
        "sso_token",
        "sso_token_expiration"
    ];
    /**
     * デフォルト取得カラム
     *
     * @var array
     */
    protected $defaultFetchJoinColumns = [
        "d_user.id",
        "d_user.name",
        "d_user.created_at",
        "m_site.name as site_name",
        "name_furigana",
        "nickname",
        "name_show",
        "email",
        "phone",
        "site_id",
        "rank",
        "block",
        "memo",
        "address",
        "birth_day",
        "avatar",
        "password",
        "otp_code",
        "expired_otp_code",
        "phone_otp",
        "last_login",
    ];
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchAllData(object $query):object
    {
        $mainQuery = $query
            ->where([
                "m_site.deleted_at" => 0,
            ])
            ->whereNull("d_user.deleted_at")
            ->leftJoin("m_site", "m_site.id", "d_user.site_id")
            ->select($this->defaultFetchJoinColumns);

        $guestUserQuery = D_User::query()
            ->where('d_user.email', 'guest@example.com')
            ->leftJoin("m_site", "m_site.id", "d_user.site_id")
            ->select($this->defaultFetchJoinColumns);

        return $mainQuery->union($guestUserQuery)->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopeFetchUserData(object $query):object
    {
        return $query
        ->whereNull("d_user.deleted_at")
        ->leftjoin("m_site","m_site.id","d_user.site_id")
        ->select($this->defaultFetchJoinColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param int $userId
     * @return object
     */
    public function scopeFetchFilterUserIdData(object $query,int $userId):object
    {
        return $query
        ->where("d_user.id",$userId)
        ->leftjoin("m_site","m_site.id","site_id")
        ->select($this->defaultFetchJoinColumns)
        ->get();
    }
    /**
     * 条件取得
     *
     * @param object $query
     * @param array $filter
     * @return object
     */
    public function scopefilteringMultiSiteData(object $query,array $filter):object
    {
        return $query
            ->where("m_site.deleted_at", 0)
            ->whereNull("d_user.deleted_at")
            ->whereNull("d_user.expired_otp_code")
            ->when(!empty($filter["site_id"]), function($query) use($filter){
                if (is_array($filter["site_id"])) {
                    return $query->whereIn("site_id", $filter["site_id"]);
                }
                return $query->where("site_id", $filter["site_id"]);
            })
            ->leftjoin("m_site","m_site.id","d_user.site_id")
            ->select($this->defaultFetchJoinColumns)
            ->orderby("d_user.id","DESC")
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
                'name' => $parameter['name'],
                // 'name_furigana' => $parameter['name_furigana'],
                // 'name_show' => $parameter['name_show'] ?? "",
                // 'nickname' => $parameter['nickname'],
                // 'phone' => $parameter['phone'],
                'email' => $parameter['email'],
                'address' => $parameter['address'],
                'birth_day' => $parameter['birth_day'],
                'rank' => $parameter['rank'],
                'block' => $parameter['block'],
                'memo' => $parameter['memo'],
            ])->save();
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param string $loginId
     * @return object
     */
    public function scopeLoginCheck(object $query,string $loginId)
    {
        try {
            return $query
                ->whereNull('deleted_at')
                ->where('phone',$loginId)
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param string $loginId
     * @return object
     */
    public function scopeFilterCodeData(object $query,string $code)
    {
        try {
            return $query
                ->whereNull('deleted_at')
                ->where('code',$code)
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return false;
        }
    }
    /**
     * 条件によってデータ取得
     *
     * @param object $query
     * @param string $loginId
     * @return object
     */
    public function scopeFilterTokenData(object $query,array $filter)
    {
        try {
            return $query
                ->whereNull('deleted_at')
                ->where('sso_token',$filter['sso_token'])
                ->when(!empty($filter["sso_token_expiration"]), function($query) use($filter){
                    return $query->where('sso_token_expiration','>',$filter['sso_token_expiration']);
                })
                ->select($this->defaultFetchColumns)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return false;
        }
    }
}
