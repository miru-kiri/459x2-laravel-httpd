<?php

namespace App\Http\Controllers;

use App\Models\M_Admin;
use App\Models\M_Site;
use App\Models\Site_Admin;
use App\Models\X459x_Admin;
use Illuminate\Http\Request;

class MasterAdminController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->parameter = config("parameter.admin.create");
    }
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
    public function index()
    {
        //
        $title = 'ログインユーザ管理';
        $defaultUrl = route('master.admin');
        $headers = [
            'id' => 'ID',
            'name' => '名前',
            'login_id' =>'ログインID',
            'password' =>'パスワード',
            'mail' =>'メールアドレス',
            'role_name' =>'権限',
            'is_public' =>'公開フラグ',
        ];
        $formColums = [
            ['label' => '管理者名','name' => 'name','type' => 'text'],
            ['label' => 'ログインID','name' => 'login_id','type' => 'text'],
            ['label' => 'パスワード','name' => 'password','type' => 'text'],
            ['label' => 'メールアドレス','name' => 'mail','type' => 'text'],
            ['label' => '役職','name' => 'role','type' => 'select'],
            ['label' => '管理サイト','name' => 'site','type' => 'multiySelect'],
            ['label' => '公開','name' => 'is_public','type' => 'switch'],
        ];
        $selectColums = [
            'role' => [
                ['value'=> 1,'name' => '管理者'],
                ['value'=> 2,'name' => 'エリア管理者'],
                ['value'=> 3,'name' => '店舗管理者'],
                ['value'=> 4,'name' => '機密管理者'],
                // ['value'=> 3,'name' => 'キャスト']
            ],
            'site' => [],
        ];
        $defaultColums = [
                'id' => 0,
                'name' => '',
                'login_id' => '',
                'password' => '',
                'mail' => '',
                'role' => '',
                'site' => [],
                'is_public' => 0
        ];
        $validateRules = [
            'name'  => [
                'required' => true,
            ],
            'login_id'  => [
                'required' => true,
            ],
            'password'  => [
                'required' => true,
            ],
            'role'  => [
                'required' => true,
            ]
        ];
        $fetchSite = M_Site::fetchAll();
        foreach($fetchSite as $index => $site) {
            $selectColums['site'][$index]['value'] = $site->id;
            $selectColums['site'][$index]['name'] = $site->name;
        }

        $bodys = [];
        $fetchSiteAdmin = [];
        $formatFetchData = [];
        $formatSiteAdmin = [];
        $fetchData = M_Admin::fetchAll()->toArray();
        $fetchSiteAdminData = Site_Admin::fetchAll();
        
        foreach($fetchSiteAdminData as $siteAdmin) {
            $formatSiteAdmin[$siteAdmin->admin_id][] = $siteAdmin->site_id;
        }

        foreach($fetchData as $data){
            $formatFetchData[$data['id']] = $data;            
            $formatFetchData[$data['id']]['site'] = isset($formatSiteAdmin[$data['id']]) ? $formatSiteAdmin[$data['id']] :[];
        }
        
        foreach($formatFetchData as $data){
            $row = [];
            foreach($headers as $key => $value) {
                $bodyValues = $data[$key];
                if($key == 'id') {
                    $i = $data[$key];
                } 
                if($key == 'is_public') {
                    $bodyValues = $data[$key] == 1 ? '公開中' : '非公開';
                }
                $row[$key] = $bodyValues;
            }
            $bodys[$i] = $row;
        }
        return view('admin.master.template',compact('title','defaultUrl','headers','bodys','formColums','selectColums','fetchData','formatFetchData','defaultColums','validateRules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parameter = $request->only($this->parameter);
        $resArray = $this->resArray;
        $oldRole = [
            1 => 'admin',
            2 => 'area_user',
            3 => 'site_user',
            4 => 'st50m_admin',
        ];
        try {
            \DB::beginTransaction();
            if (empty($parameter["id"])) {
                // 作成
                unset($parameter['id']);
                $parameter['created_at'] = time();
                $adminId = M_Admin::insertGetId($parameter);
                $siteIdCoron = "";
                if($request->site) {
                    Site_Admin::softDelete($adminId);
                    foreach($request->site as $siteId) {
                        $siteAdminParameter[] = [
                            'created_at' => time(),
                            'admin_id' => $adminId,
                            'site_id' => $siteId,
                        ];
                        if(empty($siteIdCoron)) {
                            $siteIdCoron = $siteId;
                        } else {
                            $siteIdCoron .= ",".$siteId;
                        }
                    }
                    Site_Admin::insert($siteAdminParameter);
                }
                $parameter['adm5'] = $siteIdCoron; 
                $parameter['role'] = isset($oldRole[$parameter['role']]) ? $oldRole[$parameter['role']] : 'site_user'; 
                $parameter['adm47'] = $parameter['is_public'] == 1 ? 'start' : 'stop';
            } else {
                //編集
                M_Admin::saveData($parameter);
                $siteIdCoron = "";
                if($request->site) {
                    Site_Admin::softDelete($parameter['id']);
                    foreach($request->site as $siteId) {
                        $siteAdminParameter[] = [
                            'created_at' => time(),
                            'admin_id' => $parameter["id"],
                            'site_id' => $siteId,
                        ];
                        if(empty($siteIdCoron)) {
                            $siteIdCoron = $siteId;
                        } else {
                            $siteIdCoron .= ",".$siteId;
                        }
                    }
                    Site_Admin::insert($siteAdminParameter);
                }
                $parameter['adm5'] = $siteIdCoron; 
                $parameter['role'] = isset($oldRole[$parameter['role']]) ? $oldRole[$parameter['role']] : 'site_user'; 
                $parameter['adm47'] = $parameter['is_public'] == 1 ? 'start' : 'stop';
            }
            X459x_Admin::upsertData($parameter);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        $resArray = $this->resArray;
        try {
            \DB::beginTransaction();
            M_Admin::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time()])->save();
            X459x_Admin::where('admid',$id)->update(['adm49' => time(),'adm48' => 'del']);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
}
