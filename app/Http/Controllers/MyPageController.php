<?php

namespace App\Http\Controllers;

use App\Mail\MemberRegistration;
use App\Mail\OptMail;
use App\Mail\ReserveAdminMail;
use App\Mail\ReserveMail;
use App\Mail\ReviewAdminMail;
use App\Mail\UrlMail;
use App\Models\Cast_Image;
use App\Models\Cast_Message_Ngword;
use App\Models\Cast_Schedule;
use App\Models\Cast_Schedule_Setting;
use App\Models\D_Contact;
use App\Models\D_Opt_Email;
use App\Models\D_Opt_Phone;
use App\Models\D_Reserve;
use App\Models\D_Review;
use App\Models\D_User;
use App\Models\M_Cast;
use App\Models\M_Point_User;
use App\Models\M_Shop;
use App\Models\M_Site;
use App\Models\Member_Message;
use App\Models\Member_Message_Replies;
use App\Models\Point_User;
use App\Models\Review_Criterial;
use App\Models\Site_Course;
use App\Models\Site_Info;
use App\Models\Site_Nomination_Fee;
use App\Models\User_Like;
use App\Models\User_Like_Cast;
use App\Models\User_Message;
use App\Models\User_Message_Replies;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\M_SiteBreak;

class MyPageController extends Controller
{
    /**
     * デフォルトメッセージ
     *
     * @var array
     */
    protected $resArray = [
        'result' => 0,
        'message' => '処理に成功しました',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginAuth(Request $request)
    {
        $resArray = $this->resArray;
        $codeArea = $request->code_area;
        $loginId = $request->login_id;
        $password = $request->password;
        $formatPhone = substr($loginId, 0, 1) == '0' ? $codeArea . substr($loginId, 1) : $codeArea . $loginId;
        $isPortal = $request->is_portal ?? 0;

        $data = D_User::loginCheck($formatPhone);
        if(!$data) {
            $resArray = [
                'result' => 1,
                'message' => '入力された電話番号は登録されていません。',
            ];
            return response()->json($resArray);
        }
        // if($data->deleted_at) {
        //     $resArray = [
        //         'result' => 1,
        //         'message' => '退会済みユーザーです。',
        //     ];
        //     return response()->json($resArray);
        // }
        if(is_null($password)) {
            $resArray = [
                'result' => 1,
                'message' => 'パスワードを入力してください',
            ];
            return response()->json($resArray);
        }
        if (!Hash::check($password, $data->password)) {
            $resArray = [
                'result' => 1,
                'message' => 'パスワードが違います。',
            ];
            return response()->json($resArray);
        }
        if ($data->expired_otp_code) {
            $result = sendCode($formatPhone);
            $data->update([
                'otp_code' => $result['code'],
                'expired_otp_code' => Carbon::now()->addSeconds(180),
                'phone_otp' => $formatPhone,
            ]);
            if(!$result['result']) {
                $previousUrl = app('url')->previous();
                session()->flash('status', '処理に失敗しました。');
                session()->flash('error', true);
                return redirect()->to($previousUrl);
            }
            session()->flash('status', '認証コードを入力してください。');
            session()->flash('error', true);
            session([
                "opt_user_id"  => $data->id,
            ]);
            $resArray = [
                'result' => 2,
                'message' => '認証コードを入力してください。',
            ];
            return response()->json($resArray);
        }
        $userId = $data->id;
        $userToken = Hash::make($data->id);
        if($isPortal == 1) {
            $ssoTokenExpiration = Carbon::now()->addSeconds(180); 
            D_User::findOrFail($userId)->fill(['sso_token' => $userToken,'sso_token_expiration' => $ssoTokenExpiration])->save();
        } else {
            D_User::findOrFail($userId)->fill(['last_login' => now()])->save();
        }
        session([
            "user_id"  => $userId,
            "user_token" => $userToken
        ]);
        $resArray['user_id'] = $userId;
        $resArray['user_token'] = $userToken;
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $href = $request->ref ?? "";
        return view('mypages.login',compact('href'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserToken(Request $request)
    {
        $resArray = [
            'result' => 1,
        ];
        $userId = $request->user_id;
        if(empty($userId)) {
            return response()->json($resArray);
        }
        $userToken = $request->user_token;
        if(empty($userToken)) {
            return response()->json($resArray);
        }
        $userData = D_User::findOrFail($userId);
        if(Hash::check($userData->id,$userToken)) {
            session([
                "user_id"  => $userData->id,
                "user_token" => $userToken
            ]);
            $resArray = [
                'result' => 0,                    
            ];
        }
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $href = $request->ref ?? "";
        if($href) {
            return redirect()->away($href);
        } else {
            $request->session()->forget('user_id');
            return redirect()->route('site');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request)
    {
        $siteId = (filter_var($request->site_id, FILTER_VALIDATE_INT) !== false && (int)$request->site_id > 0) ? (int)$request->site_id : 1;
        $siteData = M_Site::fetchPublicData(['template' => [1,2,3,4,5,6,7],'site_id' => $siteId]);
        return view('mypages.signup',compact('siteId','siteData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signupPortal(Request $request)
    {
        $siteId = (filter_var($request->site_id, FILTER_VALIDATE_INT) !== false && (int)$request->site_id > 0) ? (int)$request->site_id : 1;
        $siteData = M_Site::fetchPublicData(['template' => [1,2,3,4,5,6,7],'site_id' => $siteId]);
        return view('mypages.signup',compact('siteId','siteData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signupSmsPage(Request $request)
    {
        // $userId = session('user_id');
        $userId = session('opt_user_id');
        return view('mypages.signupSms',compact('userId'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordForgetPage(Request $request)
    {
        return view('mypages.passwordForGet');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordForgetCodePage(Request $request)
    {
        return view('mypages.passwordForGetCode');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordForgetConfirmPage(Request $request)
    {
        return view('mypages.passwordForGetConfirm');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordForget(Request $request)
    {
        $codeArea = $request->code_area;
        $formatPhone = substr($request->phone, 0, 1) == '0' ? $codeArea . substr($request->phone, 1) : $codeArea . $request->phone;
        $user = D_User::LoginCheck($formatPhone);
        if(!$user) {
            $resArray = [
                'result' => 1,
                'message' => '登録されていない電話番号です。',
            ];
            return response()->json($resArray);
        }
        // if($user->deleted_at) {
        //     $resArray = [
        //         'result' => 1,
        //         'message' => '退会済みユーザーです。',
        //     ];
        //     return response()->json($resArray);
        // }
        $result = sendCode($formatPhone);
        if(!$result['result']) {
            $resArray = [
                'result' => 1,
                'message' => 'SMS送信に失敗しました。',
            ];
            return response()->json($resArray);
        }
        $currentTime = time();
        $parameter = [
            'created_at' => $currentTime,
            'user_id' => $user->id,
            'category_id' => 2,
            'expiration_time' => $currentTime + 180,
            'phone' => $formatPhone,
            'code' => $result['code']
        ];
        D_Opt_Phone::insert($parameter);
        
        // $user->update([
        //     'otp_code' => $result['code'],
        //     'expired_otp_code' => Carbon::now()->addSeconds(180),
        //     'phone_otp' => $formatPhone
        // ]);
        session([
            'forget_user_id' => $user->id
        ]);
        $resArray = [
            'result' => 0,
            'message' => '送信しました。'
        ];
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordForgetCode(Request $request)
    {
        $resArray = [
            'result' => 0,
            'message' => '送信しました。'
        ];
        // $user = D_User::findOrFail(session('forget_user_id'));
        $optData = D_Opt_Phone::fetchFilterCodeIsConfirmData(['user_id' => session('forget_user_id'),'code' => 0,'category_id' => 2]);
        if(!$optData) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '既に変更認証済みの番号です。');
            return redirect()->to($previousUrl);
        }
        if($optData->code != $request->code) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '認証コードが違います。');
            return redirect()->to($previousUrl);
        }
        if($optData->expiration_time <= time()) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '有効期限切れです。再度変更フォームから登録してください。');
            return redirect()->to($previousUrl);
        }
        $optData->update([
            'updated_at' => time(),
            'is_confirm' => 1,
        ]);
        // $nowDate = date('Y-m-d H:i:s');
        // $expireDate = $user->expired_otp_code;
        // if ($nowDate > $expireDate) {
        //     $resArray = [
        //         'result' => 1,
        //         'message' => '有効期限切れです。',
        //     ];
        //     return response()->json($resArray);
        // }
        // if($user->otp_code != $request->code) {
        //     $resArray = [
        //         'result' => 1,
        //         'message' => '認証コードが違います。',
        //     ];
        //     return response()->json($resArray);
        // }
        // $user->update([
        //     'otp_code' => null,
        //     'expired_otp_code' => null,
        //     'phone_otp' => null
        // ]);
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordForgetConfirm(Request $request)
    {
        $userId = session('forget_user_id');
        $password = $request->password;
        $resArray = [
            'result' => 0,
            'message' => '送信しました。'
        ];
        $user = D_User::findOrFail($userId);
        $user->update([
            'password' => Hash::make($password),
        ]);
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signupSms(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'name_furigana' => 'required',
            // 'name_show' => 'required',
            // 'email' => 'required|email',
            // 'birth_day' => 'required|date',
            'phone' => 'required',
            // 'address' => 'required',
            // 'nickname' => 'required',
            'password' => 'required',
        ]);
        $phoneCorrect = substr($request->phone, 0, 1) == '0' ? "+81" . substr($request->phone, 1) : "+81" . $request->phone;
        $defaultUser = D_User::LoginCheck($phoneCorrect);
        if($defaultUser) {
            if ($defaultUser->expired_otp_code) {
                $result = sendCode($phoneCorrect);
                $defaultUser->update([
                    'otp_code' => $result['code'],
                    'expired_otp_code' => Carbon::now()->addSeconds(180),
                    'phone_otp' => $phoneCorrect,
                ]);
                if(!$result['result']) {
                    $previousUrl = app('url')->previous();
                    session()->flash('status', '処理に失敗しました。');
                    session()->flash('error', true);
                    return redirect()->to($previousUrl);
                }
                session()->flash('status', '認証コードを入力してください。');
                session()->flash('error', true);
                session([
                    "opt_user_id"  => $defaultUser->id,
                ]);
                return redirect()->route('mypage.signup.sms.page');
            }
            // if($detaluUser->deleted_at) {
                // 退会者は登録できるように。
                // $previousUrl = app('url')->previous();
                // session()->flash('status', '既に退会されたユーザーです。');
                // session()->flash('error', true);
                // return redirect()->to($previousUrl);   
            // }
            $previousUrl = app('url')->previous();
            session()->flash('status', '入力された電話番号は既に登録されています。ログインしてください。');
            session()->flash('error', true);
            return redirect()->route('mypage.login'); 
        }
        $siteId = $request->site_id;
        if($siteId == 0) {
            $siteId = 1;
        }
        // 該当サイトが存在するかチェック
        $isSite = M_Site::fetchTargetDataForSiteId($siteId);
        if(!$isSite) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '該当のサイトは存在しません。');
            session()->flash('error', true);
            return redirect()->route('mypage.login'); 
        }
        $result = sendCode($phoneCorrect);
        if(!$result['result']) {
            $previousUrl = app('url')->previous();
            session()->flash('status', 'SMS送信に失敗しました。');
            session()->flash('error', true);
            return redirect()->to($previousUrl);   
        }
        $parameter = [
            'name' => $request->name,
            // 'name_furigana' => $request->name_furigana,
            // 'name_show' => $request->name_show,
            // 'email' => $request->email,
            // 'birth_day' => date('Y-m-d',strtotime($request->birth_day)),
            'phone' => $phoneCorrect,
            'site_id' => $siteId,
            // 'address' => $request->address,
            // 'nickname' => $request->nickname, //一旦不使用
            'password' => Hash::make($request->password),
            'otp_code' => $result['code'],
            'expired_otp_code' => Carbon::now()->addSeconds(180),
            'phone_otp' => $phoneCorrect,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $userId = D_User::insertGetId($parameter);
        session([
            "opt_user_id"  => $userId,
        ]);
        return redirect()->route('mypage.signup.sms.page');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signupSmsAgain(Request $request)
    {
        $user = D_User::findOrFail($request->user_id);
        if(!$user) {
            $resArray = [
                'result' => 1,
                'message' => 'ユーザーが存在しません。',
            ];
            return response()->json($resArray);
        }
        $result = sendCode($user->phone);
        if(!$result['result']) {
            $resArray = [
                'result' => 1,
                'message' => 'SMS送信に失敗しました。',
            ];
            return response()->json($resArray);
        }
        $user->update([
            'otp_code' => $result['code'],
            'expired_otp_code' => Carbon::now()->addSeconds(180),
            'phone_otp' => $user->phone
        ]);
        $resArray = [
            'result' => 0,
            'message' => '再送信しました。'
        ];
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signupConfirm(Request $request)
    {
        $userId = $request->user_id;

        $request->validate([
            'code' => 'required',
        ]);
        $user = D_User::findOrFail($userId);
        $nowDate = date('Y-m-d H:i:s');
        $expireDate = $user->expired_otp_code;
        $previousUrl = app('url')->previous();
        if ($nowDate > $expireDate) {
            session()->flash('status', '有効期限が切れています。');
            session()->flash('error', true);
            return redirect()->to($previousUrl);   
        }
        if($user->otp_code != $request->code) {
            session()->flash('status', '認証コードが違います。');
            session()->flash('error', true);
            return redirect()->to($previousUrl);   
        }
        try {
            DB::beginTransaction();
            // $password = $user->password;
            $user->update([
                // 'password' => Hash::make($request->password),
                'otp_code' => null,
                'expired_otp_code' => null,
                'phone_otp' => null,
                "last_login" => now()
            ]);
            $userBirthDay = [];
            if($user->birth_day) {
                $userBirthDay = explode('-',$user->birth_day);
            }
            $parameter = [
                "created_at" => time(),
                "user_id" => $user->id,
                "site_id" => $user->site_id,
                "card_no" => $user->id,
                "name" => $user->name,
                "year" => isset($userBirthDay[0]) ? $userBirthDay[0] : null,
                "month" => isset($userBirthDay[1]) ? $userBirthDay[1] : null,
                "day" => isset($userBirthDay[2]) ? $userBirthDay[2] : null,
                "sex" => 1,
                "tel" => $user->phone,
            ];
            $isPointUserData = M_Point_User::fetchFilteringFirstData(['card_no' => $user->id]);
            if(!$isPointUserData) {
                M_Point_User::insert($parameter);
            } else {
                unset($parameter['created_at']);
                $parameter['updated_at'] = time();
                $isPointUserData->fill($parameter)->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::debug($e->getMessage());
            session()->flash('status', '登録に失敗しました。');
            session()->flash('error', true);
            return redirect()->to($previousUrl);
        }
        //メール送れない時登録できないから分ける
        try {
            Mail::to($user->email)->send(new MemberRegistration($user));
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
        }
        session()->forget('opt_user_id');
        session([
            "user_id"  => $user->id,
            "user_token" => Hash::make($user->id)
        ]);
        return redirect()->route('mypage.top');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(Request $request)
    {
        $userId = session('user_id') ?? 0;
        $firstSiteId = $request->site_id ?? 0;
        $formColumns = [
            ['label' => '氏名','name' => 'name','type' => 'text','col' => 6],
            ['label' => '電話番号','name' => 'phone','type' => 'text','col' => 6],
            ['label' => 'メールアドレス','name' => 'email','type' => 'text','col' => 6],
            ['label' => '店舗名','name' => 'site_id','type' => 'select','col' => 6],
            ['label' => '接客者(スタッフ名)','name' => 'cast_id','type' => 'select','col' => 6],
            ['label' => 'ご利用日','name' => 'date','type' => 'date','col' => 6],
            ['label' => 'ご利用時刻','name' => 'time','type' => 'select','col' => 6],
            ['label' => 'コース名','name' => 'title','type' => 'text','col' => 6],
            ['label' => '内容(500文字以内)','name' => 'content','type' => 'textarea','col' => 12],
        ];
        // $firstSiteId = 0;
        $siteData = M_Site::fetchPublicData(['template' => [1,2,3,4,5,6,7],'site_id' => $firstSiteId]);
        if($siteData->isEmpty()) {
            $siteData = M_Site::fetchPublicData(['template' => [1,2,3,4,5,6,7],'site_id' => 0]);
        }
        foreach($siteData as $index => $data) {
            if(empty($firstSiteId)) {
                $firstSiteId = $data->id;
            }
        }
        $castData = M_Cast::fetchAll();
        $formatCastData = [];
        if($siteData->isNotEmpty()) {
            foreach($castData as $index => $data) {
                if($data->is_public == 0) {
                    // unset($castData[$index]);
                    continue;
                }
                $formatCastData[$data->site_id][$data->id] = $data;
            }
        }
        $timeAry  = [];
        for($i=0;$i<24;$i++) {
            $timeAry[$i] = $i."時";
        }
        return view('mypages.contact',compact('userId','formColumns','siteData','formatCastData','firstSiteId','timeAry'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'email' => 'required|email',
            'title' => 'required',
            'content' => 'required',
        ]);
        $date = date('Y-m-d',strtotime($request->date));
        $nowDate = date('Y-m-d');
        if (strtotime($date) >= strtotime($nowDate)) {
            $isDateCheck = true;
            if(strtotime($date) == strtotime($nowDate) && $request->time < date('H')) {
                $isDateCheck = false;
            }
            if($isDateCheck) {
                $previousUrl = app('url')->previous();
                session()->flash('status', '未来日での登録はできません。');
                return redirect()->to($previousUrl);    
            }
        }
        if(empty($request->cast_id)) {
            $previousUrl = app('url')->previous();
            session()->flash('status', 'スタッフを選択してください。');
            return redirect()->to($previousUrl);    
        }

        $parameter = [
            'created_at' => time(),
            'user_id' => $request->user_id ?? 0,
            'site_id' => $request->site_id ?? 0,
            'cast_id' => $request->cast_id ?? 0,
            'date' => $request->date,
            'time' => $request->time,
            'name' => $request->name ?? null,
            'site_name' => $request->site_name ?? null,
            'cast_name' => $request->cast_name ?? null,
            'phone' => $request->phone,
            'email' => $request->email,
            'title' => $request->title,
            'content' => $request->content,
        ];
        try {
            D_Contact::insert($parameter);
        } catch(\Exception $ex) {
            $previousUrl = app('url')->previous();
            session()->flash('status', 'お問い合わせ登録に失敗しました。');
            return redirect()->to($previousUrl);    
        }
        $previousUrl = app('url')->previous();
        session()->flash('status', 'お問い合わせ登録に完了しました。');
        session()->flash('error', true);
        return redirect()->to($previousUrl);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTabs($id)
    {
        $tabs = [
            ['url' => route('mypage.top'), 'name' => 'マイページTOP'],
            ['url' => route('mypage.favorite'), 'name' => 'お気に入り'],
            ['url' => route('mypage.message'), 'name' => '受信トレイ'],
            ['url' => route('mypage.history'), 'name' => '利用履歴'],
            ['url' => route('mypage.point'), 'name' => 'ポイント履歴'], 
            ['url' => route('mypage.review'), 'name' => '口コミ投稿'],
            ['url' => route('mypage.setting'), 'name' => '設定'],
        ];
        foreach($tabs as $index => $tab) {
            $tabs[$index]['is_active'] = false;
            if($index == $id) {
                $tabs[$index]['is_active'] = true;
            }
        }
        return $tabs;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(0);
        $data = D_User::findOrFail(session('user_id'));

        $favoriteCast = User_Like_Cast::fetchFilteringData(['user_id' => $data->id,'limit' => 1]);
        foreach($favoriteCast as $index => $cast) {
            $favoriteCast[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->cast_id]);
            if($castImage) {
                $favoriteCast[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
        }

        $favoriteShop = User_Like::fetchJoinUserLimitData($data->id,6);

        $reserveData = D_Reserve::fetchFilteringData(['user_id' => $data->id,'status' => 4,'start_time' => 0,'limit' => 3]);

        $reserveNowData = D_Reserve::fetchFilteringData(['user_id' => $data->id,'status' => [1,2,3],'start_time' => date('Y-m-d 00:00:00'),'limit' => 0]);
        foreach($reserveNowData as $index => $reseveNow) {
            $date = date('Y年m月d日',strtotime($reseveNow->start_time)); 
            $hour = date('H時',strtotime($reseveNow->start_time)); 
            $week = config('constant.week')[date('w',strtotime($reseveNow->start_time))]; 
            $reserveNowData[$index]->format_date = $date . "(" . $week .")" . $hour;
            $reserveNowData[$index]->image = '/no-image.jpg';
            if(!empty($reseveNow->cast_id)) {
                $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $reseveNow->cast_id]);
                if($castImage) {
                    $reserveNowData[$index]->image = $castImage->directory . "SM_" . $castImage->path;
                }
            }
        }

        $totalPoint = Point_User::fetchValidPoint(session('user_id'));
        return view('mypages.top',compact('data','tabs','favoriteCast','reserveData','reserveNowData','favoriteShop','totalPoint'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function favorite(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $type = $request->type ?? 0;
        $tabs = $this->getTabs(1);
        $data = D_User::findOrFail(session('user_id'));

        $favoriteCast = User_Like_Cast::fetchFilteringData(['user_id' => $data->id,'limit' => 0]);
        $genre = config('constant.genre');
        $favoriteCastId = [];
        foreach($favoriteCast as $index => $cast) {
            $favoriteCast[$index]->genre_category = false;
            if(isset($genre[$cast->template])) {
                $favoriteCast[$index]->genre_category = $genre[$cast->template];
            }
            $favoriteCast[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->cast_id]);
            if($castImage) {
                $favoriteCast[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
            $favoriteCastId[] = $cast->cast_id;
        }
        $formatNewData = [];
        if($favoriteCastId) {
            $messageData = Member_Message::fetchFilterData(['user_id' => $userId,'cast_id' => $favoriteCastId]);
            $messageDataId = [];
            foreach($messageData as $message) {
                $messageDataId[] = $message->id;
            }
            if($messageDataId) {
                $messageIsRead = Member_Message_Replies::fetchFilterIsNotReadData(['status' => ['apporve','close'],'message_id' => $messageDataId,'author_flag' => 1]);
                if($messageIsRead) {
                    foreach($messageIsRead as $isReadData) {
                        $formatNewData[$isReadData->cast_id] = $isReadData;
                    }
                }
            }
        }

        $favoriteShop = User_Like::fetchJoinUserDataIsPublic($data->id);
        $favoriteShopId = [];
        foreach($favoriteShop as $index => $shop) {
            $favoriteShopId[] = $shop->site_id;
        }
        $formatNewShopData = [];
        $shopImages = [];
        if($favoriteShopId) {
            $messageData = User_Message::filteringUserData(['user_id' => $userId,'site_id' => $favoriteShopId]);
            $messageDataId = [];
            foreach($messageData as $message) {
                if($message->is_read == 0 && $message->author_flag == 1) {
                    $formatNewShopData[$message->site_id] = $message;
                    continue;
                }
                $messageDataId[] = $message->id;
            }
            if($messageDataId) {
                $messageIsRead = User_Message_Replies::fetchFilterIsNotReadData(['user_message_id' => $messageDataId,'author_flag' => 1]);
                if($messageIsRead) {
                    foreach($messageIsRead as $isReadData) {
                        $formatNewShopData[$isReadData->site_id] = $isReadData;
                    }
                }
            }
            $favoriteShopImages = Site_Info::FetchFilterSiteAll(['site_id' => $favoriteShopId]);
            foreach($favoriteShopImages as $favoriteShopImage) {
                $shopImages[$favoriteShopImage->site_id] = $favoriteShopImage;
            }
        }
        return view('mypages.favorite',compact('data','tabs','favoriteCast','favoriteShop','type','formatNewData','formatNewShopData','shopImages'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function favoriteDelete($id)
    {
        // $userId = session('user_id');
        // if(empty($userId)) {
        //     return false;
        // }
        if(empty($id)) {
            $resArray = [
                'result' => 1,
                'message' => '参照するデータがありません。',
            ];
            return response()->json($resArray);
        }
        $resArray = $this->resArray;
        User_Like_Cast::deleteLike($id);
        return response()->json($resArray);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function favoriteShopDelete($id)
    {
        if(empty($id)) {
            $resArray = [
                'result' => 1,
                'message' => '参照するデータがありません。',
            ];
            return response()->json($resArray);
        }
        $resArray = $this->resArray;
        User_Like::deleteLike($id);
        return response()->json($resArray);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function history(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(3);
        $data = D_User::findOrFail(session('user_id'));
        $reviewIdAry = D_Review::fetchBookCastIdAry(['user_id' => $data->id]);
        $historyData = D_Reserve::fetchFilteringData(['user_id' => $data->id,'status' => 4,'start_time' => 0,'limit' => 0]);
        foreach($historyData as $index => $history) {
            $historyData[$index]->image = '/no-image.jpg';
            $date = date('Y年m月d日',strtotime($history->start_time)); 
            $hour = date('H時',strtotime($history->start_time)); 
            $week = config('constant.week')[date('w',strtotime($history->start_time))]; 
            $historyData[$index]->format_date = $date . "(" . $week .")" . $hour;
            if(!$history->source_name) {
                $history->source_name = 'データがありません';
                $history->age = '-';
                $history->height = '-';
                $history->bust = '-';
                $history->cup = '-';
                $history->waist = '-';
                $history->hip = '-';
            }
            if(!empty($history->cast_id)){
                $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $history->cast_id]);
                if($castImage) {
                    $historyData[$index]->image = $castImage->directory . "SM_" . $castImage->path;
                }
            }
            $history->is_review = 0;
            if(in_array($history->id,$reviewIdAry)) {
                $history->is_review = 1;
            }
        }
        $reviewCriterials = config('constant.cast.criterials');
        return view('mypages.history',compact('data','tabs','historyData','reviewCriterials'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function review(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(5);
        $data = D_User::findOrFail(session('user_id'));
        $reviewData = D_Review::fetchAll();
        // dd($reviewData);
        $reviewIdAry = [];
        foreach($reviewData as $rd) {
            $reviewIdAry[] = $rd->id;
        }
        $reviewCriterialData = Review_Criterial::fetchFilterIdAryData(['review_id' => $reviewIdAry]);
        // dd($reviewCriterialData);
        $formatReviewCriterialData = [];
        $startMaster = config('constant.cast.criterials');
        foreach($reviewCriterialData as $criterialData) {
            $formatReviewCriterialData[$criterialData->review_id][$criterialData->criterial_id]['name'] = isset($startMaster[$criterialData->criterial_id])  ? $startMaster[$criterialData->criterial_id] : "";
            $formatReviewCriterialData[$criterialData->review_id][$criterialData->criterial_id]['evaluate'] = $criterialData->evaluate;
        }
        // review_idごとに評価の合計とカウントを集計する配列を初期化
        $sum_reviews = [];
        $count_reviews = [];

        // データを走査して各review_idごとに評価の合計とカウントを計算
        foreach ($reviewCriterialData as $criterialData) {
            $review_id = $criterialData["review_id"];
            $evaluate = $criterialData["evaluate"];
            
            if (!isset($sum_reviews[$review_id])) {
                $sum_reviews[$review_id] = 0;
                $count_reviews[$review_id] = 0;
            }
            
            $sum_reviews[$review_id] += $evaluate;
            $count_reviews[$review_id] += 1;
        }
        // 各review_idごとの平均評価を計算
        $averageReviews = [];
        foreach ($sum_reviews as $review_id => $total) {
            $averageReviews[$review_id] = $total / $count_reviews[$review_id];
        }
        foreach($reviewData as $index => $review) {
            if($review->display != 1) {
                unset($reviewData[$index]);
                continue;
            }
            if($review->cast_delete != 0) {
                unset($reviewData[$index]);
                continue;
            }
            if($review->cast_delete === null) {
                unset($reviewData[$index]);
                continue;
            }
            if($review->site_delete != 0) {
                unset($reviewData[$index]);
                continue;
            }
            $review->created_at = date('Y年m月d日 H:i',strtotime($review->created_at));
            $review->time_play = date('Y年m月d日',strtotime($review->time_play));
            $reviewData[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $review->cast_id]);
            if($castImage) {
                $reviewData[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
        }
        
        return view('mypages.review',compact('data','tabs','reviewData','formatReviewCriterialData','averageReviews'));
    }
    public function reviewCreate(Request $request) 
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $id = $request->id;
        $castId = $request->cast_id;
        $title = $request->title ?? null;
        $content = $request->content ?? null;
        $timePlay = $request->time_play ?? null;
        $siteId = $request->site_id ?? null;
        DB::beginTransaction();
        $createdAt = now();
        try {
            $parameter = [
                'user_id' => $userId,
                'cast_id' => $castId,
                'bookcast_id' => $id,
                'title' => $title,
                'content' => $content,
                'time_play' => $timePlay,
                'site_id' => $siteId,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
            $reviewId = D_Review::insertGetId($parameter);
            $reviewCriterials = config('constant.cast.criterials');
            $reviewParams = [];
            $criterialContent = [];
            foreach($reviewCriterials as $key => $rc) {
                $reviewParams[$key]['review_id'] = $reviewId;
                $reviewParams[$key]['criterial_id'] = $key; 
                $reviewParams[$key]['evaluate'] = $request->input("criterial-$key") ?? 0.0; 
                $criterialContent[] = [
                    'label' => $rc,
                    'evaluate' => $request->input("criterial-$key") ?? 0.0
                ];
            }
            Review_Criterial::insert($reviewParams);
            // 店舗へメール送信
            $siteData = M_Site::findOrFail($request->site_id);
            $shopData = M_Shop::findOrFail($siteData->shop_id);
            if(!empty($shopData->mail)) {
                $userData = D_User::findOrFail($userId);
                $castData = false;
                if($castId) {
                    $castData = M_Cast::findOrFail($request->cast_id);
                }
				Mail::to($shopData->mail)->send(new ReviewAdminMail($userData, $shopData, $siteData,$castData, $title, $content, $timePlay, $createdAt,$criterialContent));
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $previousUrl = app('url')->previous();
            session()->flash('error', '口コミ投稿に失敗しました。');
            return redirect()->to($previousUrl);
        }
        $previousUrl = app('url')->previous();
        session()->flash('success', '口コミ投稿に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function point(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(4);
        $data = D_User::findOrFail(session('user_id'));
        $pointHistoryData = Point_User::fetchFilteringData(['user_id' => $userId,'card_no' => 0]);
        $totalPoint = 0;
        foreach($pointHistoryData as $index => $history) {
            $category_name = '-';
            $category_color = '';
            if($history['category_id'] == 1) {
                $category_name = '取得';
                $category_color = 'btn-primary';
            }
            if($history['category_id'] == 2) {
                $category_name = '利用';
                $category_color = 'btn-danger';
            }
            if($history['category_id'] == 3) {
                $category_name = '引き継ぎ';
                $category_color = 'btn-info';
            }
            if($history['category_id'] == 4) {
                $category_name = '引き継ぎ';
                $category_color = 'btn-info';
            }
            if($history['category_id'] == 5) {
                $category_name = '追加';
                $category_color = 'btn-primary';
            }
            if($history['category_id'] == 6) {
                $category_name = '削減';
                $category_color = 'btn-danger';
            }
            $pointHistoryData[$index]->category_name = $category_name;
            $pointHistoryData[$index]->category_color = $category_color;
            $pointHistoryData[$index]->date_time = date('Y年m月d日 H時', strtotime($history['date'].$history['time']));
            $totalPoint += $history->point;
        } 
        return view('mypages.point',compact('data','tabs','pointHistoryData','totalPoint'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setting(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(6);
        $data = D_User::findOrFail(session('user_id'));

        return view('mypages.setting',compact('data','tabs'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settingEdit(Request $request)
    {
        // 名前の変更廃止に伴い、非使用
        // $userId = session('user_id');
        // if(empty($userId)) {
        //     return redirect()->route('mypage.login');
        // }
        // $request->validate([
        //     'name_show' => 'required',
        //     // 'birth_day' => 'required|date',
        // ]);
        // $nameShow = $request->name_show;
        // try {
        //     D_User::findOrFail($userId)->fill(['updated_at' => now(),'name_show' => $nameShow])->save();
		// 	M_Point_User::where('user_id',$userId)->update(['updated_at' => time(), 'name' => $nameShow]);
        // } catch (\Exception $e) {
        //     $previousUrl = app('url')->previous();
        //     session()->flash('error', true);
        //     session()->flash('status', '設定情報の編集に失敗しました。');
        //     return redirect()->to($previousUrl);
        // }
        // session()->flash('status', '登録情報の変更に成功しました。');
        $previousUrl = app('url')->previous();
        return redirect()->to($previousUrl);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settingEditBirthDay(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $request->validate([
            // 'name_show' => 'required',
            'birth_day' => 'required|date',
        ]);
        $birthDay = date('Y-m-d',strtotime($request->birth_day));
        try {
            D_User::findOrFail($userId)->fill(['updated_at' => now(),'birth_day' => $birthDay])->save();
        } catch (\Exception $e) {
            $previousUrl = app('url')->previous();
            session()->flash('error', true);
            session()->flash('status', '設定情報の編集に失敗しました。');
            return redirect()->to($previousUrl);
        }
        $previousUrl = app('url')->previous();
        session()->flash('status', '登録情報の変更に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function phoneAuth(Request $request)
    {
        $resArray = $this->resArray;
        $userId = session('user_id');
        if(empty($userId)) {
            $resArray = [
                'result' => 1,
                'message' => '参照するデータがありません。',
            ];
        }
        $phone = $request->phone;
        $codeArea = $request->code_area;
        $result = sendCode($codeArea.$phone);
        if(!$result['result']) {
            $resArray = [
                'result' => 1,
                'message' => 'SMS送信に失敗しました。',
            ];
        }
        if($result['result']) {
            $currentTime = time();
            $parameter = [
                'created_at' => $currentTime,
                'user_id' => session('user_id'),
                'category_id' => 2,
                'expiration_time' => $currentTime + 180,
                'phone' => $codeArea . intval($phone),
                'code' => $result['code'],
            ];
            $phoneId = D_Opt_Phone::insertGetId($parameter);
            $resArray = [
                'id' => $phoneId,
                'result' => 0,
                'message' => '処理に成功しました',
            ];
        }
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailAuth(Request $request)
    {
        $resArray = $this->resArray;
        $userId = session('user_id');
        if(empty($userId)) {
            $resArray = [
                'result' => 1,
                'message' => '参照するデータがありません。',
            ];
        }
        try {
            $email = $request->email;
            $code = generateRandomCode();
            $url = route('mypage.email.auth.page');
            //メール送信ロジック
            Mail::to($email)->send(new OptMail($email,$code,$url));
            $currentTime = time();
            $parameter = [
                'created_at' => $currentTime,
                'user_id' => session('user_id'),
                'category_id' => 2,
                'expiration_time' => $currentTime + 180,
                'email' => $email,
                'code' => $code,
            ];
            $phoneId = D_Opt_Email::insertGetId($parameter);
            $resArray = [
                'id' => $phoneId,
                'result' => 0,
                'message' => '処理に成功しました',
            ];
        } catch (\Exception $ex) {
            \Log::debug($ex);
            $resArray = [
                'result' => 1,
                'message' => 'メール送信処理に失敗しました',
            ];
        }
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function phoneConfirm(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $code = $request->code;
        if(empty($code)) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '認証コードを入力してください。');
            return redirect()->to($previousUrl);
        }
        $optData = D_Opt_Phone::fetchFilterCodeData(['user_id' => $userId,'code' => $code,'category_id' => 2]);
        if(!$optData) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '認証コードが違います。');
            return redirect()->to($previousUrl);
        }
        if($optData->is_confirm == 1) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '既に変更認証済みの番号です。');
            return redirect()->to($previousUrl);
        }
        if($optData->expiration_time <= time()) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '有効期限切れです。再度変更フォームから登録してください。');
            return redirect()->to($previousUrl);
        }
        try {
            D_User::findOrFail($userId)->fill(['phone' => $optData->phone,'updated_at' => now()])->save();
            $optData->fill(['is_confirm' => 1,'updated_at' => time()])->save();
        } catch (\Exception $ex) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '登録に失敗しました。');
            return redirect()->to($previousUrl);    
        }

        session()->flash('status', '電話番号の変更に成功しました。');
        return redirect()->route('mypage.setting');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailConfirm(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $code = $request->code;
        if(empty($code)) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '認証コードを入力してください。');
            return redirect()->to($previousUrl);
        }
        $optData = D_Opt_Email::fetchFilterCodeData(['user_id' => $userId,'code' => $code,'category_id' => 2]);
        if(!$optData) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '認証コードが違います。');
            return redirect()->to($previousUrl);
        }
        if($optData->is_confirm == 1) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '既に変更認証済みのメールアドレスです。');
            return redirect()->to($previousUrl);
        }
        if($optData->expiration_time <= time()) {
            $previousUrl = app('url')->previous();
            session()->flash('status', '有効期限切れです。再度変更フォームから登録してください。');
            return redirect()->to($previousUrl);
        }
        try {
            D_User::findOrFail($userId)->fill(['email' => $optData->email,'updated_at' => now()])->save();
            $optData->fill(['is_confirm' => 1,'updated_at' => time()])->save();
        } catch (\Exception $ex) {
            \Log::debug($ex);
            $previousUrl = app('url')->previous();
            session()->flash('status', '登録に失敗しました。');
            return redirect()->to($previousUrl);    
        }
        session()->flash('status', '電話番号の変更に成功しました。');
        return redirect()->route('mypage.setting');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function phoneAuthPage(Request $request)
    {
        $resArray = $this->resArray;
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(6);
        $data = D_User::findOrFail(session('user_id'));
        return view('mypages.phoneAuth',compact('data','tabs'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordAuth(Request $request)
    {
        $resArray = $this->resArray;
        $userId = session('user_id');
        if(empty($userId)) {
            $resArray = [
                'result' => 1,
                'message' => '参照するデータがありません。',
            ];
        }
        $email = $request->email;
        if(empty($email)) {
            $resArray = [
                'result' => 1,
                'message' => '参照するデータがありません。',
            ];
        }
        $title = 'パスワード変更認証メール';
        $url = route('mypage.password.confirm');
		session([
            'forget_user_id' => $userId
        ]);
        //メール送信ロジック
        Mail::to($email)->send(new UrlMail($title,$url));
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordEndPage(Request $request)
    {
        $resArray = $this->resArray;
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(6);
        $data = D_User::findOrFail(session('user_id'));
        
        return view('mypages.passwordEnd',compact('data','tabs'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordConfirmPage(Request $request)
    {
        $resArray = $this->resArray;
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(6);
        $data = D_User::findOrFail(session('user_id'));
        
        return view('mypages.passwordConfirm',compact('data','tabs'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function passwordConfirm(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $request->validate([
            'password' => 'required|min:6|confirmed:password',
            'password_confirmation' => 'required|min:6',
        ]);
        try {
            $password = $request->password;
            $hashPassword = Hash::make($password);
            D_User::findOrFail($userId)->fill(['password' => $hashPassword,'updated_at' => now()])->save();
        } catch (\Exception $ex) {
            \Log::debug($ex);
            $previousUrl = app('url')->previous();
            session()->flash('status', '登録に失敗しました。');
            return redirect()->to($previousUrl);    
        }
        session()->flash('status', 'パスワードの変更に成功しました。');
        return redirect()->route('mypage.setting');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function emailAuthPage(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(6);
        $data = D_User::findOrFail(session('user_id'));
        return view('mypages.emailAuth',compact('data','tabs'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settingEditPage(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $type = $request->type ?? 0;//1=電話番号,2=メアド,3=パスワード
        if(empty($type)) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl);
        }
        $tabs = $this->getTabs(6);
        $data = D_User::findOrFail(session('user_id'));
        
        return view('mypages.settingEdit',compact('data','tabs','type'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reserveCoursePage(Request $request)
    {
        $userId = session('user_id');
        if($request->user_id) {
            $userId = $request->user_id;
            session(['user_id' => $userId]);
        }
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $castId = $request->cast_id ?? 0;
        $siteId = $request->site_id ?? 0;
        $isFree = false;
        if(empty($castId)) {
            $isFree = true;
        }
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail($userId);
        $castData = [];
        $indicateData = [];
        if(!empty($castId)){
            $castData = M_Cast::fetchFilterId($castId);
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castId]);
            $castData->image = '/no-image.jpg';
            if($isCastImage) {                
                $castData->image = $isCastImage->directory . "SM_" . $isCastImage->path;
            }  
            $siteId = $castData->site_id;
            $indicateData = Site_Nomination_Fee::fetchFilterSiteData($siteId);
        }

        $courseData = Site_Course::fetchFilterSiteData($siteId,0);
        return view('mypages.reserveCourse',compact('castId','siteId','userId','data','tabs','castData','courseData','indicateData','isFree'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reserveCalenderPage(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $courseId = $request->course_id;
        $siteId = $request->site_id ?? 0;
        $castId = $request->cast_id ?? 0;
        $castData = [];
        if(!empty($castId)){
            $castData = M_Cast::fetchFilterId($castId);
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castId]);
            $castData->image = '/no-image.jpg';
            if($isCastImage) {                
                $castData->image = $isCastImage->directory . "SM_" . $isCastImage->path;
            }
            $siteId = $castData->site_id;
        }
        $siteData = M_Site::findOrFail($siteId);
        
        $siteBreaksRaw = M_SiteBreak::where('site_id', $siteId)->get();
        $siteBreaks = [];

        foreach ($siteBreaksRaw as $break) {
            $weekday = (int) $break->weekday;
            $siteBreaks[$weekday][] = [
                'start' => (int) $break->break_start,
                'end' => (int) $break->break_end,
            ];
        }

        $firstDate = $request->first_date;
        $isLaterSite = false;
        if(!empty($siteData->close_time)) {
            $closeTime = $siteData->close_time;
            if($siteData->close_time >= 2400) {
                $closeTime = $closeTime - 2400;
                if($closeTime >= date('Hi')) {
                    $isLaterSite = true;
                }
            }
        }
        if(empty($firstDate)){
            if($isLaterSite) {
                $firstDate = date('Ymd',strtotime('-1 day'));
            } else {
                $firstDate = date('Ymd');
            }
        }
        $feeType = $request->fee_type ?? -1;
        $endDate = date('Ymd',strtotime($firstDate . '+1 week'));
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail($userId);

        $formatDate = [];
        $formatShedule = [];
        $formatReserve = [];
        
        $castStartTime = $siteData->open_time;
        $castEndTime = $siteData->close_time;
        $reserveApprovalDate = $siteData->reserve_approval_date;
        $reserveBufferTime = $siteData->reserve_buffer_time;
        $reserveCloseBranch = $siteData->reserve_close_branch;
        $reserveClose = $siteData->reserve_close;

        $courseData = [];
        $courseTime = 0;
        if(!empty($courseId)) {
            $courseData = Site_Course::findOrFail($courseId);
            if(!empty($courseData)) {
                $courseTime = $courseData->time;
            }
            if(!empty($reserveBufferTime)) {
                $courseTime = $courseTime + $reserveBufferTime;
            }
        }

        $castSchedule = [];
        $formatTel = [];
        $formatTime = [];
        $defaultNowDateTime = date('Y-m-d H:i:s');
        $nowDateTime = date('Y-m-d H:i:s');
        //時間
        if($reserveCloseBranch == 0) {
            if(!empty($reserveClose)) {
                $nowDateTime = date('Y-m-d H:i:s',strtotime($nowDateTime."+$reserveClose hour"));
            }
        }
        // 日数
        if($reserveCloseBranch == 1) {
            if(!empty($reserveClose)) {
                $nowDateTime = date('Y-m-d H:i:s',strtotime("+$reserveClose day"));
            }
        }
        $limitApprovalDate = null;
        if(!empty($reserveApprovalDate)) {
            $limitApprovalDate = date('Ymd',strtotime("+$reserveApprovalDate day"));
        }
        if($castStartTime && $castEndTime) {
            $castSchedule = Cast_Schedule::fetchFilteringBetweenData(['first_date' => $firstDate,'end_date' => $endDate,'cast_id' => $castId,'is_work' => 1,'site_id' => $siteId]);
            foreach($castSchedule as $schedule) {
				//LASTだったら、店舗の閉店時間とする。
                if($schedule->end_time == 'LAST') {
                    $endHours = substr($castEndTime, 0, 2);
                    $endMinutes = substr($castEndTime, 2, 2);
                    // 時間と分を":"で結合
                    $schedule->end_time = $endHours . ":" . $endMinutes;
                }
                //コース時間分の時間の短縮
                if($courseData){
                    list($hour, $minute) = explode(':', $schedule->end_time);
                    // 時間と分を数値に変換
                    $hour = (int)$hour;
                    $minute = (int)$minute;

                    $addHours = floor($courseData->time / 60); // 時間部分
                    $addMinutes = $courseData->time % 60; // 分部分
					// 追加の分を加算し、時間に加算する
                    $minute -= $addMinutes;
                    // 分が負になった場合の処理
                    if ($minute < 0) {
                        $minute += 60;
                        $hour -= 1;
                    }
                    // 時間から追加の時間を引く
                    $hour -= $addHours;                    // 新しい時間をフォーマット
                    $schedule->end_time = sprintf('%02d:%02d', $hour, $minute);
                }
                $formatShedule[date('Ymd',strtotime($schedule['date']))][$schedule->cast_id] = $schedule;
            }
            $reserveData = D_Reserve::fetchFilterCastData(['start_time' => $firstDate, 'end_time' => $endDate,'cast_id' => $castId,'site_id' => $siteId,'status' => [1,2,3,4]]);
            foreach($reserveData as $reserve) {
                $reserveFormatDate = date('Ymd',strtotime($reserve['start_time']));
                if(date('Hi',strtotime($reserve['start_time'])) < $castStartTime){
                    $reserveFormatDate = date('Ymd',strtotime($reserve->start_time.'-1 day'));
                }
                if(!empty($reserveBufferTime)) {
                    $reserve['end_time'] = date('Y-m-d H:i:s',strtotime($reserve['end_time'] . "+$reserveBufferTime minute"));
                }
                $formatReserve[$reserveFormatDate][] = $reserve;
            }
            $castTelData = Cast_Schedule_Setting::fetchFilteringBetweenData(['date' => [date('Y-m-d',strtotime($firstDate)),date('Y-m-d',strtotime($endDate))],'cast_id' => $castId,'site_id' => $siteId]);
            foreach($castTelData as $tel) {
                // $formatTel[date('Ymd',strtotime($tel['date']))][$tel->time][$tel->cast_id] = $tel;
                $formatTel[date('Ymd',strtotime($tel['date']))][$tel->time] = $tel->status;
            }
            // $formatStartTime = strtotime($castStartTime);
            // if(strtotime($castEndTime) >= $formatStartTime) {
            //     $formatEndTime = strtotime($castEndTime);
            // } else {
            //     $formatEndTime = strtotime($castEndTime."+1 day");
            // }

            $formatSlotTime = [];
            for ($time = $castStartTime; $time <= $castEndTime; $time += 30) {
                if ($time % 100 >= 60) {
                    $time += 40; // 60 - 20 分を追加して 100 分にする
                }
                // 時間を"hh:mm"形式にフォーマットして配列に追加
                $hours = floor($time / 100); // 時間部分
                $minutes = $time % 100; // 分部分
                // 時間を"hh:mm"形式にフォーマットして配列に追加
                $timeSlot = sprintf('%02d:%02d', $hours, $minutes);
                $formatSlotTime[] = $timeSlot;
            }
            
            for($i=0;$i<=7;$i++) {
                $loop = 0;
                $date = date('Ymd',strtotime($firstDate . "+$i day"));
                $formatDate[$date] = date('d日',strtotime($date))."(". config('constant.week')[date('w',strtotime($date))] .")";
                // $currentStartTime = $formatStartTime; // 初期化
                // while($currentStartTime <= $formatEndTime) {
                foreach($formatSlotTime as $time) {
                    $isResult = 0; //0=スケジュールなし,1=出勤,2=Tel,3=「-」
                    $reserveCount = 0;
                    $castScheduleCount = 0;
                    $formattedDateTime = \DateTime::createFromFormat('Ymd H:i', $date . ' ' . $time);
                    // フォーマットした日付を取得
                    $dateTime = $formattedDateTime->format('Y-m-d H:i:s');
                    $formatEndTime = strtotime(\DateTime::createFromFormat('Ymd H:i', $date . ' ' . $time)->format('Y-m-d H:i:s'));
                    //スケジュールの確認
                    if(isset($formatShedule[$date])) {
                        foreach($formatShedule[$date] as $key => $val) {
                            if($val->start_time <= $time) {
                                $endTime = $val->end_time;
                                // 店舗終了時間が最終4時までなので
                                if($endTime < '04:00') {
                                    list($endhour, $endminute) = explode(':', $endTime);
                                    $endhour = $endhour + 24;
                                    // 新しい時間をフォーマット
                                    $endTime = sprintf('%02d:%02d', $endhour, $endminute);
                                }
                                if($endTime >= $time) {
                                    $isResult = 1;
                                    $castScheduleCount++;
                                    continue;
                                }
                            }
                        }
                    }
                    //予約の確認
                    if(isset($formatReserve[$date])) {
                        foreach($formatReserve[$date] as $key => $val) {
                            $formatReserveDateTime = $dateTime;
                            if($courseData) {
                                $formatReserveDateTime =  date('Y-m-d H:i:s',strtotime($dateTime . "+$courseTime minute"));
                            }
                            if($val->start_time < $formatReserveDateTime) {
                                //ここに無理やり挟めばいける？(バッファ)
                                if($val->end_time > $dateTime) {
                                    $reserveCount++;
                                    if($castScheduleCount <= $reserveCount) {
                                        $isResult = 0;
                                    }
                                }
                            }
                        }
                    }
                    //TELの確認
					if (isset($formatTel[$date])) {
                        foreach ($formatTel[$date] as $telTime => $status) {
                            $couserAddTelTime = date('H:i', strtotime($time . "+$courseTime minute"));
                            if ($couserAddTelTime < '04:00') {
                                list($endTelhour, $endTelminute) = explode(':', $couserAddTelTime);
                                $endTelhour = $endTelhour + 24;
                                // 新しい時間をフォーマット
                                $couserAddTelTime = sprintf('%02d:%02d', $endTelhour, $endTelminute);
                            }
                            if ($telTime < $couserAddTelTime && $telTime > $time) {
                                $isResult = 2;
                                continue;
                            }
                            if ($telTime == $time) {
                                // TEL
                                if ($status == 1) {
                                    $isResult = 2;
                                    continue;
                                }
                                // Not Work
                                if ($status == 2) {
                                    $isResult = 3;
                                    continue;
                                }
                            }
                        }
                    }
                    //今の時間からdisabled
                    if($nowDateTime >= $dateTime) {
                        // Not Work
                        $isResult = 3;
                        if($nowDateTime >= $dateTime && $defaultNowDateTime <= $dateTime) {
                            //設定で〆切超えた場合は、TEL
                            $isResult = 2;
                        }
                    }
                    //予約受付日より大きければ止める
                    if(!empty($limitApprovalDate)){
                        if($limitApprovalDate <= $date) {
                            $isResult = 3;
                        }
                    }
                    
                    $formatTime[$time][$date]['dateTime'] = $dateTime;
                    $formatTime[$time][$date]['result'] = $isResult;
                    // $currentStartTime += 1800; // 30分 (60秒 × 30分)
                    $loop++;
                }
            }
        }
        return view('mypages.reserve',compact('data','tabs','firstDate','endDate','castId','castData','castSchedule','formatDate','formatTime','courseId','courseData','feeType','siteId', 'siteData', 'siteBreaks'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reserveConfirmPage(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $siteId = $request->site_id ?? 0;
        $courseId = $request->course_id;
        $feeType = $request->fee_type;
        $courseData = Site_Course::findOrFail($courseId);
        $castId = $request->cast_id ?? 0;
        $castData = [];
        if(!empty($castId)) {
            $castData = M_Cast::fetchFilterId($castId);
            $siteId = $castData->site_id;
        }
        $indicateData = Site_Nomination_Fee::fetchFilterSiteData($siteId);
        $startTime = date('Y/m/d H:i',strtotime($request->date_time));
        $endTime = date('Y/m/d H:i',strtotime($startTime . "+$courseData->time min"));
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail($userId);
        $amount = 0;
        if($courseData) {
            $amount += $courseData->fee;
        }
        if($indicateData) {
            if($feeType == 1) {
                $amount += $indicateData->fee;
            }
            if($feeType == 2) {
                $amount += $indicateData->nomination_fee;
            }
        }

        return view('mypages.reserveConfirm',compact('tabs','courseData','castData','startTime','endTime','indicateData','amount','feeType','siteId','castId','courseId','startTime'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reserveConfirm(Request $request)
    {

        try {
            DB::beginTransaction();
            $userId = session('user_id');
            if(empty($userId)) {
                return redirect()->route('mypage.login');
            }

            $guestUser = D_User::where('email', 'guest@example.com')->firstOrFail();
            $isGuest = $userId == $guestUser->id;

            $castId = $request->cast_id;
            if(empty($castId)) {
                $castId = null;
            }
            $previousUrl = app('url')->previous();
            $userData = D_User::findOrFail($userId);
            //NGは弾く
            if($userData->block == 1) {
                session()->flash('error', '現在ネット予約を行うことができません。');
                return redirect()->to($previousUrl);
            }
            $siteData = M_Site::findOrFail($request->site_id_reserve);
            $shopData = M_Shop::findOrFail($siteData->shop_id);
            $castData = [];
            if(!empty($request->cast_id)) {
                $castData = M_Cast::findOrFail($request->cast_id);
            }
            $reserveBufferTime = $siteData->reserve_buffer_time;
            //被り予約の制御
            if(!empty($castId)) {
                $endTime = $request->end_time;
                if(!empty($reserveBufferTime)) {
                    $endTime = date('Y-m-d H:i:s',strtotime($request->end_time . "+$reserveBufferTime min"));
                }
                $filter = [
                    'cast_id' => $castId,
                    'start_time' =>  date('Y-m-d H:i:s',strtotime($request->start_time)),
                    'end_time' =>  $endTime,
                    'site_id' => $request->site_id_reserve,
                    'status' => [1,2,3,4]
                ];
                $isReserveData = D_Reserve::isReserveData($filter);
                if($isReserveData) {
                    if($isReserveData->start_time != $endTime) {
                        session()->flash('error', '選択された時間内には、既に予約が入っています。');
                        return redirect()->to($previousUrl);
                    }
                }
            }
            // Site_Nomination_Fee:: nomination_fee = 本指名, 指名 = fee
            $parameter = [
                'user_id' => $userId,
                'cast_id' => $castId,
                'status'  => 1,
                'type'  => $request->type,
                'type_reserve'  => $request->type_reserve,
                'indicate_fee1' => $request->indicate_fee1 ?? 0,
                'indicate_fee1_flg' => isset($request->indicate_fee1) ? 1 : 0,
                'indicate_fee2' => $request->indicate_fee2 ?? 0,
                'indicate_fee2_flg' => isset($request->indicate_fee2) ? 1 : 0,
                'site_id_reserve' => $request->site_id_reserve,
                'amount' => $request->amount,
                // 'start_time' => date('Y-m-d H:i:s',strtotime($request->start_time)),
                // 'end_time' => date('Y-m-d H:i:s',strtotime($request->end_time)),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'memo' => $request->memo,
                'address' => $request->address,
                'course_money' => $request->course_money,
                'course_time' => $request->course_time,
                'course_name' => $request->course_name,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($isGuest && session()->has('guest_data')) {
                $guestData = session('guest_data');
                $parameter['is_guest'] = $isGuest ? 1 : 0;
                if (!empty($guestData['name'])) {
                    $parameter['guest_name'] = $guestData['name'];
                }
                if (!empty($guestData['phone'])) {
                    $parameter['guest_phone'] = $guestData['phone'];
                }
            }


            D_Reserve::insert($parameter);

            if ($isGuest) {
                session()->forget('guest_data');
                session()->forget('user_id');
            }
            
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
        }
        try {
            if(!empty($userData->email)) {
                Mail::to($userData->email)->send(new ReserveMail($userData,$request->start_time,$shopData,$castData,$request->course_name,$request->amount));
            }
            if(!empty($shopData->mail)) {
				Mail::to($shopData->mail)->send(new ReserveAdminMail($userData, $request->start_time, $shopData, $siteData,$castData, $request->course_name, $request->amount, $request->address, $request->memo));
            }
        } catch (\Exception $ex) {
            \Log::debug($ex);
        }
        session()->flash('status', '仮予約の登録に成功しました。');
        if (session()->has('redirect_to_admin_order') && session('redirect_to_admin_order') === true) {
            session()->forget('redirect_to_admin_order');
            return redirect()->route('reserve');
        } else {
            return redirect()->route('mypage.top');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawalPage(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail(session('user_id'));
        return view('mypages.withdrawal',compact('data','tabs'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawalConfirmPage(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail(session('user_id'));
        return view('mypages.withdrawalConfirm',compact('data','tabs'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawal(Request $request)
    {
        $resArray = [
            'result' => 0,
            'message' => '処理に成功しました。',
        ];
        try {
            $userId = session('user_id');
            if(empty($userId)) {
                return redirect()->route('mypage.login');
            }
            D_User::findOrFail(session('user_id'))->fill(['deleted_at' => now()])->save();
            $request->session()->forget('user_id');
        } catch (\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => '参照するデータがありません。',
            ];
        }
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function withdrawalEnd(Request $request)
    {        
        return view('mypages.withdrawalEnd');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function messageCastPage(Request $request)
    {        
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail(session('user_id'));

        $castId = $request->cast_id;
        $url = $request->url ?? '';
        $filter = [
            'user_id' => $userId,
            'cast_id' => $castId,
        ];
        $fetchMessageReplis = [];
        $messageId = 0;
        $messageData = Member_Message::filteringUserFirstData($filter);
        if($messageData) {
            $messageId = $messageData->id;
            $fetchMessageReplis = Member_Message_Replies::fetchJoinUserData($messageId);
            Member_Message_Replies::where(['message_id' => $messageId,'author_flag' => 1,'status' => 'apporve','is_read' => 0])->update(['updated_at' => now(),'is_read' => 1]);
        }
        $castData = M_Cast::findOrFail($castId);
        if($castData->approval_status == 3) {
            session()->flash('warning', '選択された女の子は現在メッセージを行うことができません。');
            return redirect()->route('mypage.favorite');
        }
        //NGは弾く
        if($data->block == 1) {
            session()->flash('error', '現在メッセージを行うことができません。');
            return redirect()->route('mypage.favorite');
        }
        
        return view('mypages.message_cast',compact('tabs','data','messageData','castData','fetchMessageReplis','messageId','castId','url'));
    }
    public function messageCastCreate(Request $request) 
    {
        try {
            $userId = session('user_id');
            $previousUrl = app('url')->previous();
            if(empty($userId)) {
                return redirect()->route('mypage.login');
            }
            $userData = D_User::findOrFail($userId);
            //NGは弾く
            if($userData->block == 1) {
                session()->flash('error', '現在メッセージを行うことができません。');
                return redirect()->to($previousUrl); 
            }
            $messageId = $request->message_id;
            $castId = $request->cast_id;
            $content = $request->content;
            $castData = M_Cast::findOrFail($castId);
            if($castData->approval_status == 3) {
                session()->flash('warning', '選択された女の子は現在メッセージを行うことができません。');
                return redirect()->to($previousUrl); 
            }
            $ngWords = Cast_Message_Ngword::fetchFilterSiteId($castData->site_id);
            foreach($ngWords as $ngWord) {
                if (false !== strstr($content, $ngWord->content)) {
                    session()->flash('error', '不適切な文言が含まれているため、送信できません。');
                    return redirect()->to($previousUrl); 
                }
            }
            if(empty($messageId)) {
                //新規登録
                $parameter = [
                    'user_id' => $userId,
                    'cast_id' => $castId,
                    'content' => $content,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'user_last_visited' => now(),
                ];
                $messageId = Member_Message::insertGetId($parameter);
            }
            $status = 'close';
            if($castData->approval_status == 2) {
                $status = 'apporve';
            }
            $parameter = [
                'message_id' => $messageId,
                'content' => $content,
                'created_at' => now(),
                'updated_at' => now(),
                'status' => $status,
            ];
            Member_Message_Replies::insert($parameter);
        } catch (\Exception $e) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl); 
        }
        session()->flash('success', '登録に成功しました。');
        return redirect()->to($previousUrl); 
    }  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function messageSitePage(Request $request)
    {        
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail(session('user_id'));
        
        $siteId = $request->site_id;
        $filter = [
            'user_id' => $userId,
            'site_id' => $siteId,
        ];
        
        $messageData = User_Message::filteringUserData($filter);
        // foreach($messageData as $index => $md) {
        //     $fetchMessageReplis = User_Message_Replies::fetchJoinUserData($md->id);
        //     $messageData[$index]['replies'] = $fetchMessageReplis;
        // }
        $siteData = M_Site::findOrFail($siteId);

        return view('mypages.message_site',compact('tabs','data','messageData','siteData','siteId'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function messageSiteDetailPage(Request $request)
    {
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $messageId = $request->id;
        $url = $request->url ?? '';
        if(empty($messageId)) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl); 
        }
        $tabs = $this->getTabs(-1);
        $data = D_User::findOrFail(session('user_id'));
        $fetchMessages = User_Message::fetchFilterIdJoinData($messageId);
        User_Message::where(['id' => $messageId,'author_flag' => 1,'is_read' => 0])->update(['updated_at' => now(),'is_read' => 1]);
        $fetchMessageReplis = User_Message_Replies::fetchJoinUserData($messageId);
        User_Message_Replies::where(['user_message_id' => $messageId,'author_flag' => 1,'is_read' => 0])->update(['updated_at' => now(),'is_read' => 1]);
        return view('mypages.message_site_detail',compact('tabs','data','messageId','fetchMessages','fetchMessageReplis','url'));
    }
    public function messageSiteCreate(Request $request)
    {
        try {
            $userId = session('user_id');
            $previousUrl = app('url')->previous();
            if(empty($userId)) {
                return redirect()->route('mypage.login');
            }
            $siteId = $request->site_id;
            $title = $request->title;
            $content = $request->content;
            $parameter = [
                'user_id' => $userId,
                'site_id' => $siteId,
                'title' => $title,
                'content' => $content,
                'user_last_visited' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            User_Message::insert($parameter);
        } catch (\Exception $e) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl); 
        }
        session()->flash('success', '登録に成功しました。');
        return redirect()->to($previousUrl); 
    }
    public function messageSiteReplies(Request $request)
    {
        try {
            $userId = session('user_id');
            $previousUrl = app('url')->previous();
            if(empty($userId)) {
                return redirect()->route('mypage.login');
            }
            $messageId = $request->message_id;
            $content = $request->content;
            $parameter = [
                'user_message_id' => $messageId,
                'content' => $content,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            User_Message_Replies::insert($parameter);
            User_Message::findOrFail($messageId)->fill(['updated_at' => now(),'is_read' => 0,'user_last_visited' => now()])->save();
        } catch (\Exception $e) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl); 
        }
        session()->flash('success', '登録に成功しました。');
        return redirect()->to($previousUrl); 
    }
    public function messagePage(Request $request) 
    {
        $type = $request->type ?? 0;
        $resArray = $this->resArray;
        $userId = session('user_id');
        if(empty($userId)) {
            return redirect()->route('mypage.login');
        }
        $tabs = $this->getTabs(2);
        $data = D_User::findOrFail(session('user_id'));
        $siteId = $data->site_id;
        $siteMessageDatas = [];
        $formatNewData = [];
        $messageDataId = [];
        $castMessageDatas = Member_Message::filteringUserData(['user_id' => $userId,'cast_id' => 0]);
        foreach($castMessageDatas as $message) {
            $messageDataId[] = $message->id;
        }
        if($messageDataId) {
            $messageIsRead = Member_Message_Replies::fetchFilterIsNotReadData(['status' => ['apporve','close'],'message_id' => $messageDataId,'author_flag' => 1]);
            if($messageIsRead) {
                foreach($messageIsRead as $isReadData) {
                    $formatNewData[$isReadData->message_id] = $isReadData;
                }
            }
        }
        $filter = [
            'user_id' => $userId,
            'site_id' => 0,
        ];
        $siteMessageDatas = User_Message::filteringUserData($filter);
        $formatNewShopData = [];
        $messageDataId = [];
        foreach($siteMessageDatas as $message) {
            if($message->is_read == 0 && $message->author_flag == 1) {
                $formatNewShopData[$message->id] = $message;
                continue;
            }
            $messageDataId[] = $message->id;
        }
        if($messageDataId) {
            $messageIsRead = User_Message_Replies::fetchFilterIsNotReadData(['user_message_id' => $messageDataId,'author_flag' => 1]);
            if($messageIsRead) {
                foreach($messageIsRead as $isReadData) {
                    $formatNewShopData[$isReadData->user_message_id] = $isReadData;
                }
            }
        }
        $siteDatas = M_Site::FetchFilterAryId($siteId);
        return view('mypages.message',compact('data','tabs','type','siteId','castMessageDatas','siteMessageDatas','formatNewData','formatNewShopData','siteDatas'));
    }
}
