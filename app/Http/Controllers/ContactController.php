<?php

namespace App\Http\Controllers;

use App\Models\D_Contact;
use App\Models\M_Cast;
use App\Models\M_Site;
use Illuminate\Http\Request;

class ContactController extends Controller
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
            'date_time' => '日付',
            'site_name' => 'サイト名',
            'cast_name' => 'キャスト名',
            'name' => '氏名',
            'title' => 'タイトル',
            'content' => '内容',
            'action' => '',
        ];
        $formatSiteData = [];
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        // $siteData = M_Site::get();
        $siteData = M_Site::fetchFilterAryId($siteControl);
        foreach($siteData as $data) {
            $formatSiteData[$data->id] = $data->name;
        }
        $fetchData = D_Contact::fetchFilterData(['site_id' => $siteControl]);
        $bodys = [];
        $formatCastData = [];
        $castData = M_Cast::get();
        foreach($castData as $data) {
            $formatCastData[$data->id] = $data->source_name;
        }
        foreach($fetchData as $index => $value) {
            $bodys[$index] = $value;
            $bodys[$index]['date_time'] = $value['date'] ." ". $value['time']."時";
            $bodys[$index]['action'] = "<div class='d-flex'><button class='btn btn-default edit_btn mr-1' style='white-space: nowrap;'  data-id='".$value['id']."'>詳細</button><button class='btn btn-danger delete_btn' data-id='".$value['id']."'><i class='fas fa-trash'></i></button></div>";
            $bodys[$index]['site_name'] = isset($formatSiteData[$value['site_id']]) ? $formatSiteData[$value['site_id']] : '-';
            $bodys[$index]['cast_name'] = isset($formatCastData[$value['cast_id']]) ? $formatCastData[$value['cast_id']] : '-';
            // <button class='btn btn-danger delete_btn' data-id='".$value['id']."'><i class='fas fa-trash'></i></button>
        }
        return view('admin.contact.index',compact('headers','bodys'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $id = $request->id;
        if(empty($id)) {
            $previousUrl = app('url')->previous();
            session()->flash('error', '不正なパラメータです。');
            return redirect()->to($previousUrl);
        }
        $data = D_Contact::findOrFail($id);
        $siteName = "-";
        if(!empty($data->site_id)) {
            $siteName = M_Site::findOrFail($data->site_id)->name;
        }
        $castName = "-";
        if(!empty($data->cast_id)) {
            $castName = M_Cast::findOrFail($data->cast_id)->source_name;
        }
        $parameter = [
            '投稿日時' => date('Y年m月d日 H:i:s',$data->created_at),
            'サイト名' => $siteName,
            'キャスト名' => $castName,
            'ユーザー名' => $data->name,
            '対応日時' => $data->date ." ". $data->time . "時",
            'タイトル' => $data->title,
            '内容' => $data->content,
            '電話番号' => $data->phone,
            'メールアドレス' => $data->email,
        ];
        return view('admin.contact.detail',compact('parameter'));
    }
    /**
     * Display a listing of the resource.
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
        D_Contact::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time()])->save();
        $resArray = [
            'result' => 0,
            'message' => '処理に成功しました。',
        ];
        return response()->json($resArray);
    }
}
