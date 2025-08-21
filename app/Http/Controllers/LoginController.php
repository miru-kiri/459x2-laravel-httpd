<?php

namespace App\Http\Controllers;

use App\Mail\CastPassword;
use App\Models\Admin_Action_Log;
use App\Models\Cast_Action_Log;
use App\Models\D_Action_Log;
use App\Models\M_Admin;
use App\Models\M_Cast;
use App\Models\Site_Admin;
use App\Models\Site_Area;
use App\Models\X459x_Cast;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required',
            'password' => 'required',
        ]);
        $loginId = $request->login_id;
        $password = $request->password;
        $isAdmin = $request->is_admin ?? 0;
        //管理者とキャストのログインがあるから
        $logParameter = [
            'created_at' => time(), 
            'category_id' => 1,
            'date' => date('Ymd'),
            'time' => date('His'),
            'content' => 'ログインしました。',
        ];
        
        if($isAdmin == 1) {
            try {
                $admin = M_Admin::AdminLogin($loginId,$password);
                if(!$admin) {
                    $previousUrl = app('url')->previous();
                    session()->flash('status', 'ログインIDもしくはパスワードが間違っています。');
                    return redirect()->to($previousUrl);
                }
                $siteAdmin = Site_Admin::fetchFilterAdminId($admin->id)->pluck('site_id')->toArray();
                if(!$siteAdmin) {
                    if($admin->role > 1) {
                        // $siteAdmin[] = 0;
                        $previousUrl = app('url')->previous();
                        session()->flash('status', '管理サイトの設定がされていません。ログインユーザー管理から管理サイトの設定を行なってください。');
                        return redirect()->to($previousUrl);
                    }
                }
                // セッションIDを再発行
                session()->regenerate(true);
        
                session([
                    "id"  => $admin->id,
                    "name"  => $admin->name,
                    "role"  => $admin->role,
                    "role_name"  => $admin->role_name,
                    "is_admin" => $isAdmin,
                    "site_control"  => $siteAdmin,
                    "is_mail_create"  => 1,
                ]);
                //管理者
                $logParameter['admin_id'] = session('id');
                Admin_Action_Log::insert($logParameter);
                return redirect()->route('dashboard.admin');
            } catch (Exception $e) {
                $previousUrl = app('url')->previous();
                session()->flash('status', '処理に失敗しました。');
                return redirect()->to($previousUrl);
            }
        } else {
            try {
                $admin = M_Cast::CheckLogin($loginId);
                if(!$admin) {
                    $previousUrl = app('url')->previous();
                    session()->flash('status', 'ログインIDが間違っています。');
                    return redirect()->to($previousUrl);
                }
                if (!Hash::check($password, $admin->password)) {
                    $previousUrl = app('url')->previous();
                    session()->flash('status', 'パスワードが間違っています。');
                    return redirect()->to($previousUrl);
                }
                $isMailCreate = $admin->register_at ? 1 : 0;
                // セッションIDを再発行
                session()->regenerate(true);
        
                session([
                    "id"  => $admin->id,
                    "name"  => $admin->source_name,
                    "role"  => 0,
                    "role_name"  => "キャスト",
                    "is_admin" => $isAdmin,
                    "site_control"  => $admin->site_id,
                    "is_mail_create"  => $isMailCreate,
                ]);
                $logParameter['cast_id'] = session('id');
                Cast_Action_Log::insert($logParameter);
                return redirect()->route('dashboard.cast');
            } catch (Exception $e) {
                $previousUrl = app('url')->previous();
                session()->flash('status', '処理に失敗しました。');
                return redirect()->to($previousUrl);
            }
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $logParameter = [
            'created_at' => time(), 
            'category_id' => 1,
            'date' => date('Ymd'),
            'time' => date('His'),
            'content' => 'ログアウトしました。',
        ];
        try {
            //管理者
            $role = session('role');
            if(session('is_admin') == 1) {
                $logParameter['admin_id'] = session('id');
                Admin_Action_Log::insert($logParameter);
            } else {
                $logParameter['cast_id'] = session('id');
                Cast_Action_Log::insert($logParameter);
            }
            session()->flush();
        } catch (Exception $e) {

        }
        if($role == 0) {
            return redirect()->to('/admin/cast_login');
        } else {
            return redirect()->to('/admin/login');
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAuth(Request $request)
    {
        $token = $request->token;
        if(!$token) {
            return redirect()->to('/admin/cast_login');
        }
        $castData = M_Cast::checkToken($token);
        if(!$castData) {
            return redirect()->to('/admin/cast_login');
        }
        if($castData->username) {
            //既に登録ずみだから
            return redirect()->to('/admin/cast_login');
        }
        return view('auth.cast_auth',compact('castData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAuthCreate(Request $request)
    {
        $request->validate([
            'cast_id' => 'required',
            'username' => 'required',
            'password' => 'required|confirmed:password',
            'password_confirmation' => 'required',
        ]);

        try {
            $previousUrl = app('url')->previous();
            $castId = $request->cast_id;
            $username = $request->username;

            if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
                session()->flash('error', 'ログインIDは半角英数字で入力してください。');
                return redirect()->to($previousUrl);
            }

            $password = $request->password;
            if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
                session()->flash('error', 'パスワードは半角英数字で入力してください。');
                return redirect()->to($previousUrl);
            }

            $isCast = M_Cast::where('username', $username)->exists();
            if ($isCast) {
                session()->flash('error', 'すでに使用されているログインIDです。');
                return redirect()->to($previousUrl);
            }

            $castData = M_Cast::findOrFail($castId);

            $randomPart = strtolower(Str::random(20));
            $uniqueEmail = strtolower($username) . '_' . $randomPart . '@459x.com';

            $castData->fill([
                'updated_at' => time(),
                'username' => $username,
                'password' => Hash::make($password),
                'register_at' => now(),
                'post_email' => $uniqueEmail,
            ])->save();

            $mailPass = env('MAIL_PASSWORD');

            $emailArg = escapeshellarg($uniqueEmail);
            $passArg = escapeshellarg($mailPass);

            $output = [];
            $returnVar = null;

            exec("sudo /usr/sbin/plesk bin mail --create $emailArg -passwd $passArg -mailbox true", $output, $returnVar);

            if ($returnVar !== 0) {
                Log::error("メール作成失敗: {$uniqueEmail}\n出力: " . implode("\n", $output));
                session()->flash('error', 'メール発行に失敗しました。');
                return redirect()->to($previousUrl);
            }

            exec("sudo /usr/sbin/postmap /var/spool/postfix/plesk/virtual");
            exec("sudo /bin/systemctl reload postfix");

            session()->regenerate(true);
            session([
                'id' => $castData->id,
                'name' => $castData->source_name,
                'role' => 0,
                'role_name' => 'キャスト',
                'is_admin' => 0,
                'site_control' => $castData->site_id,
                'is_mail_create' => 1,
            ]);

            Cast_Action_Log::insert([
                'created_at' => time(),
                'cast_id' => session('id'),
                'category_id' => 1,
                'date' => date('Ymd'),
                'time' => date('His'),
                'content' => 'ログインしました。',
            ]);
        } catch (Exception $e) {
            createLog(1, session('id'), 2, $e->getMessage());
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }

        session()->flash('success', 'メール発行に成功しました。');
        return redirect()->route('dashboard.cast');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMailCreate(Request $request)
    {
        try {
            $previousUrl = app('url')->previous();
            $castId = $request->cast_id;
            if(empty($castId)) {
                session()->flash('error', '不正なパラメータです。');
                return redirect()->to($previousUrl);
            }
            $castData = M_Cast::findOrFail($castId);
            $castData->fill([
                'updated_at' => time(),
                'register_at' => now(),
                "post_email" => "$castData->username@459x.com",
            ])->save();
            // //メール発行ロジック
            $pass=env('MAIL_PASSWORD'); //ここにメールパスワード
            $user=escapeshellarg($castData->username); //エスケープ絶対いる
            $pass=escapeshellarg($pass); //エスケープ絶対いる
            exec("sudo /usr/bin/sh /home/redadmin/create_plesk_mail.sh $user $pass");
			// 設定の再読み込み
            exec("sudo /usr/sbin/postmap /var/spool/postfix/plesk/virtual");
            exec("sudo /bin/systemctl reload postfix");
            session([
                "is_mail_create"  => 1,
            ]);
            //管理者とキャストのログインがあるから
            $logParameter = [
                'created_at' => time(), 
                'cast_id' => session('id'),
                'category_id' => 1,
                'date' => date('Ymd'),
                'time' => date('His'),
                'content' => 'メールを発行しました。',
            ];
            Cast_Action_Log::insert($logParameter);
        } catch (Exception $e) {
            createLog(1,session('id'),2,$e->getMessage());
            session()->flash('error', 'メール発行に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('success', 'メール発行に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * キャストログインページ
     *
     * @param Request $request
     * @return void
     */
    public function castLoginPage(Request $request)
    {
        return view('auth.cast_login');
    }
    /**
     * パスワード再発行ページ
     *
     * @param Request $request
     * @return void
     */
    public function castPasswordForgetPage(Request $request)
    {
        return view('auth.passwords.email');
    }
    /**
     * パスワード再発行ページ
     *
     * @param Request $request
     * @return void
     */
    public function castPasswordForget(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $previousUrl = app('url')->previous();
        $email = $request->email;
        $cast = M_Cast::checkPostMail($email);
        if(!$cast) {
            session()->flash('error', '入力されたメールアドレスは存在しません。');
            return redirect()->to($previousUrl);
        }
        while (true) {
            $token = Str::random(20);
            if (!M_Cast::checkToken($token)) {
                break;
            }
        }
        
        $cast->fill([
            "generate_link_register_at" => now(),
            "token_register" => $token,
        ])->save();
        $url = route('cast.password.confirm',['token' => $token]);
        Mail::to($cast->post_email)->send(new CastPassword($url));
        // return redirect()->route($url);
        return redirect()->route('cast.password.end');
    }
    /**
     * パスワード再発行ページ
     *
     * @param Request $request
     * @return void
     */
    public function castPasswordForgetEndPage(Request $request)
    {
        return view('auth.end');
    }
    /**
     * パスワード再発行ページ
     *
     * @param Request $request
     * @return void
     */
    public function castPasswordConfirmPage(Request $request)
    {
        $token = $request->token;
        return view('auth.passwords.reset',compact('token'));
    }
    /**
     * パスワード再発行ページ
     *
     * @param Request $request
     * @return void
     */
    public function castPasswordConfirm(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed:password',
            'password_confirmation' => 'required',
        ]);
        $previousUrl = app('url')->previous();
        $cast = M_Cast::checkToken($request->token);
        if(!$cast) {
            session()->flash('error', '使用不可能なURLです。');
            return redirect()->to($previousUrl);
        }
        $password = $request->password;
        $cast->fill([
            "password" => Hash::make($password),
            "generate_link_register_at" => null,
            "token_register" => null,
        ])->save();
        return redirect()->route('cast.login');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castPasswordReissuePage(Request $request)
    {
        $token = $request->token;
        if(!$token) {
            return redirect()->to('/admin/cast_login');
        }
        $castData = M_Cast::checkPasswordToken($token);
        if(!$castData) {
            return redirect()->to('/admin/cast_login');
        }
        return view('auth.cast_password_reissue',compact('token','castData'));
    }
    /**
     * パスワード再発行ページ
     *
     * @param Request $request
     * @return void
     */
    public function castPasswordReissue(Request $request)
    {
        $request->validate([
            'cast_id' => 'required',
            'password' => 'required|confirmed:password',
            'password_confirmation' => 'required',
        ]);
        $previousUrl = app('url')->previous();
        $cast = M_Cast::findOrFail($request->cast_id);
        if(!$cast) {
            session()->flash('error', '不正なパラメータです。');
            return redirect()->to($previousUrl);
        }
        $password = $request->password;
        $cast->fill([
            "updated_at" => time(),
            "password" => Hash::make($password),
            "password_reset_token" => null,
            "generate_link_register_at" => null,
            "token_register" => null,
        ])->save();
        return redirect()->route('cast.login');
    }
	/**
     * postfixの再起動
     *
     * @return void
     */
    public function reloadPostfix()
    {
        // postmap コマンドを実行
        $output1 = null;
        $return1 = null;
        exec("sudo /usr/sbin/postmap /var/spool/postfix/plesk/virtual 2>&1", $output1, $return1);

        // systemctl reload コマンドを実行
        $output2 = null;
        $return2 = null;
        exec("sudo /bin/systemctl reload postfix 2>&1", $output2, $return2);

        // 結果を確認
        if ($return1 === 0 && $return2 === 0) {
            return response()->json([
                'status' => 'success',
                'message' => 'Postfix has been reloaded successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reload Postfix.',
                'output' => [$output1, $output2],
                'return_code' => [$return1, $return2]
            ]);
        }
    }
}
