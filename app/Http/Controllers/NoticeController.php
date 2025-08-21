<?php

namespace App\Http\Controllers;

use App\Models\D_Notice;
use App\Models\D_Notice_Admin_Detail;
use App\Models\D_Notice_Admin_Header;
use App\Models\M_Admin;
use App\Models\M_Cast;
use App\Models\M_Site;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headers = [
            'id' => 'ID',
            'title' => 'タイトル',
            // 'content' => '内容',
            // 'display_name' => '表示',
            'display_date' => '公開日',
            'action' => '',
        ];
        $fetchData = D_Notice::fetchAll();
        $bodys = [];
        foreach($fetchData as $index => $value) {
            $bodys[$index] = $value;
            $bodys[$index]['display_name'] = $value['display'] == 1 ? '公開中' : '非公開';;
            $bodys[$index]['action'] = "<button class='btn btn-warning edit_btn mr-1'  data-id='".$value['id']."'><i class='fas fa-edit'></i></button><button class='btn btn-danger delete_btn' data-id='".$value['id']."'><i class='fas fa-trash'></i></button>";
        }
        return view('admin.notice.index',compact('headers','bodys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form(Request $request)
    {
        $noticeId = $request->id;
        $data = false;
        if(!empty($noticeId)) {
            $data = D_Notice::findOrFail($noticeId);
        }
        return view('admin.notice.form',compact('noticeId','data'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'display_date' => 'required',
        ]);
        $parameter = [
            'id' => $request->id,
            'title' => $request->title,
            'content' => $request->content,
            'display_date' => $request->display_date,
        ];
        try {
            \DB::beginTransaction();
            if (empty($parameter["id"])) {
                // 作成
                unset($parameter['id']);
                $parameter['created_at'] = time();
                $parameter['created_user'] = session('id');
                D_Notice::insert($parameter);
                
            } else {
                //編集
                $parameter['updated_user'] = session('id');
                D_Notice::saveData($parameter);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $previousUrl = app('url')->previous();
            session()->flash('error', true);
            session()->flash('status', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('status', '登録に成功しました。');
        return redirect()->route('notice');

    }
    /**
     * Show the form for delete a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if(empty($id)){
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        D_Notice::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time(),'updated_user' => session('id')])->save();
        $resArray = [
            'result' => 0,
            'message' => '処理に成功しました。',
        ];
        return response()->json($resArray);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUpload(Request $request)
    {
        $adminId = session("id");
        $file = $request->file;
        $fileExtension = $file->getClientOriginalExtension();
        $formatFile = ['jpeg','png','gif','jpg', 'bmp', 'webp', 'tiff'];

        if (!in_array(strtolower($fileExtension), $formatFile)) {
			return response()->json([
                'result' => 1,
                'message' => 'ファイルは形式が正しくありません。'
            ]);
        }
        $defaultPath = "notice/{$adminId}";
        $path = $defaultPath.'/'.time() . '_' . $file->getClientOriginalName(); // 圧縮された画像を保存するパス
        if ($file->getSize() > 1024 * 1024) { // 100MBより大きい場合100 * 
            $image = \Image::make($file);
            $image->encode(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION), 50); // フォーマットを取得して指定
            \Storage::disk('public')->put($path, $image->stream()); // 画像をストレージに保存
        } else {
            \Storage::disk('public')->put($path, file_get_contents($file)); // 画像をストレージに保存
            // $file->storeAs($defaultPath, time() . '_' . $file->getClientOriginalName(),'public');
        }        

        return response()->json(asset('/storage/'.$path));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageDelete(Request $request)
    {
        //更新
        $imageUrl = mb_strstr($request->image_url,'storage');
        if (file_exists($imageUrl)) {
            unlink($imageUrl);
            echo "画像を削除しました。";
        }
        
        return response()->json('success');
    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'type' => 'タイプ',
            'created_user' => '作成者',
            'title' => 'タイトル',
            'display_date' => '公開日',
            'action' => '',
        ];

        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteId = $request->site_id ?? $siteControl;
        $adminDatas = M_Admin::fetchAll();
        $formatAdmin = [];
        foreach($adminDatas as $adminData) {
            $formatAdmin[$adminData->id] = $adminData->name;
        }
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        $fetchDatas = D_Notice_Admin_Header::fetchJoinAll(['site_id' => $siteId]);
        $formatDatas = [];
        foreach($fetchDatas as $data) {
            if(!isset($formatDatas[$data->header_id])) {
                $formatDatas[$data->header_id] = [
                    'id' => $data->header_id,
                    'title' => $data->title,
                    'content' => $data->content,
                    'type' => $data->type == 0 ? '記事' : '機能',
                    'display_date' => $data->display_date,
                    'created_user' => isset($formatAdmin[$data->created_user]) ? $formatAdmin[$data->created_user] : '削除済みユーザー',
                    'updated_user' => isset($formatAdmin[$data->created_user]) ? $formatAdmin[$data->created_user] : '削除済みユーザー',
                ];
            }
            if(!empty($data->site_id)) {
                if(isset($formatDatas[$data->header_id]['site_name'])) {
                    $formatDatas[$data->header_id]['site_name'] .= '/' . $data->site_name;
                } else {
                    $formatDatas[$data->header_id]['site_name'] = $data->site_name;
                }
            }
        }
        $fetchData = [];
        $bodys = [];
        $loop = 0;
        foreach($formatDatas as $headerId => $value) {
            $bodys[$loop] = $value;
            $bodys[$loop]['action'] = "<button class='btn btn-warning edit_btn mr-1'  data-id='".$headerId."'><i class='fas fa-edit'></i></button><button class='btn btn-danger delete_btn' data-id='".$headerId."'><i class='fas fa-trash'></i></button>";
            $loop++;
        }
        return view('admin.notice.adminIndex',compact('headers','bodys','siteDatas','siteId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminForm(Request $request)
    {
        $headerId = $request->id;
        $data = false;
        $isDisabled = '';
        $type = 0;
        $selectSiteId = [];
        $selectCastId = [];
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        $castDatas = M_Cast::FetchFilterAll(['site_id' => $siteControl,'cast_id' => 0]);
        $formatCastDatas = [];
        foreach($castDatas as $castData) {
            $formatCastDatas[$castData->site_id][] = $castData;
        }
        if(!empty($headerId)) {
            $data = D_Notice_Admin_Header::findOrFail($headerId);
            $selectSiteId = D_Notice_Admin_Detail::fetchFilterSiteIdAry(['header_id' => $headerId,'colums' => 'site_id']);
            $selectCastId = D_Notice_Admin_Detail::fetchFilterSiteIdAry(['header_id' => $headerId,'colums' => 'cast_id']);
            $type = $data->type;
            $adminData = M_Admin::findOrFail($data->created_user);
            
            if(session('role') > $adminData->role && $data->created_user != session('id')) {
                $isDisabled = 'disabled';
            }
        }
        return view('admin.notice.adminForm',compact('headerId','data','siteDatas','formatCastDatas','type','selectSiteId','selectCastId','isDisabled'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminCreate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'type' => 'required',
            'content' => 'required',
            'site' => 'required',
            // 'cast' => 'required',
            'display_date' => 'required',
        ]);
        $headerParameter = [
            'id' => $request->id,
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            // 'is_draft' => $request->is_draft,
            'display_date' => $request->display_date,
        ];
        try {
            \DB::beginTransaction();
            $time = time();
            if (empty($headerParameter["id"])) {
                // 作成
                unset($headerParameter['id']);
                $headerParameter['created_at'] = $time;
                $headerParameter['created_user'] = session('id');
                $headerId = D_Notice_Admin_Header::insertGetId($headerParameter);
                $siteIdAry = $request->site ?? [];
                $detailParameter = [];
                foreach($siteIdAry as $index => $siteId) {
                    $detailParameter[] = [
                        'created_at' => $time,
                        'header_id' => $headerId,
                        'site_id' => $siteId,
                        'cast_id' => 0,
                    ];
                }
                $castIdAry = $request->cast ?? [];
                // $castIdAry = $request->cast;
                foreach($castIdAry as $index => $castId) {
                    $detailParameter[] = [
                        'created_at' => $time,
                        'header_id' => $headerId,
                        'site_id' => 0,
                        'cast_id' => $castId,
                    ];
                }
                if($detailParameter) {
                    D_Notice_Admin_Detail::insert($detailParameter);
                }
            } else {
                //編集
                $headerParameter['updated_at'] = $time;
                $headerParameter['updated_user'] = session('id');
                D_Notice_Admin_Header::saveData($headerParameter);
                D_Notice_Admin_Detail::where('header_id',$headerParameter['id'])->update(['deleted_at' => time()]);
                $siteIdAry = $request->site  ?? [];
                $detailParameter = [];
                foreach($siteIdAry as $siteId) {
                    $detailParameter[] = [
                        'created_at' => $time,
                        'header_id' => $headerParameter['id'],
                        'site_id' => $siteId,
                        'cast_id' => 0
                    ];
                }
                $castIdAry = $request->cast ?? [];
                foreach($castIdAry as $castId) {
                    $detailParameter[] = [
                        'created_at' => $time,
                        'header_id' => $headerParameter['id'],
                        'site_id' => 0,
                        'cast_id' => $castId
                    ];
                }
                if($detailParameter) {
                    D_Notice_Admin_Detail::insert($detailParameter);
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            $previousUrl = app('url')->previous();
            session()->flash('error', true);
            session()->flash('status', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('status', '登録に成功しました。');
        return redirect()->route('notice.admin');

    }
    /**
     * Show the form for delete a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDelete(Request $request)
    {
        $id = $request->id;
        if(empty($id)){
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータです。',
            ];
            return response()->json($resArray);
        }
        D_Notice_Admin_Header::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time(),'updated_user' => session('id')])->save();
        $resArray = [
            'result' => 0,
            'message' => '処理に成功しました。',
        ];
        return response()->json($resArray);
    }
}
