<?php

namespace App\Http\Controllers;

use App\Models\Cast_Image;
use App\Models\Cast_Message_Ngword;
use App\Models\D_User;
use App\Models\M_Site;
use App\Models\Member_Message;
use App\Models\Member_Message_Replies;
use App\Models\User_Message;
use App\Models\User_Message_Replies;
use Illuminate\Http\Request;

class MessageController extends Controller
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
    public function shop()
    {
        $headers = [
            'id' => 'ID',
            'created_at' => '日時',
            'user_id' => 'ユーザーID',
            'user_name' => 'ユーザー名',
            // 'user_image' => 'ユーザー画像',
            'site_name' => 'サイト名',
            'title' => 'タイトル',
            // 'content' => '内容',
            'is_read' => '既読',
            // 'edit' => '詳細', 
        ];
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        // $siteData = M_Site::fetchAll();
        // $shopData = M_Shop::fetchAll();
        return view('admin.message.shop_list',compact('headers','siteData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopFiltering(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        if(session('role') != 1 && $siteId == 0) {
            $siteId = session('site_control');
        }
        $filter = [
            // 'site_id' => $request->site_id ?? [],
            'site_id' => $siteId,
        ];
        $data = User_Message::filteringMultiSiteData($filter);
        $userMessageId = [];
        foreach($data as $index => $d) {
            $userMessageId[] = $d->id;
        }
        $formatIsReadData = [];
        $messageIsRead = User_Message_Replies::fetchFilterIsNotReadData(['user_message_id' => $userMessageId,'author_flag' => 0]);
        foreach($messageIsRead as $isReadData) {
            $formatIsReadData[$isReadData->user_message_id] = $isReadData;
        }
        foreach($data as $index => $d) {
            if(isset($formatIsReadData[$d->id])) {
                if($formatIsReadData[$d->id]->author_flag == 0) {
                    $data[$index]->is_read = 0;
                }
            }
        }

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopMessage(Request $request)
    {
        $messageId = $request->id;
        $fetchMessages = User_Message::fetchFilterIdJoinData($messageId);
        $userId = $fetchMessages->user_id ?? 0;
        $userData = false;
        $RANKCLASS = [
            1 => 'success',
            2 => 'primary',
            3 => 'danger',
            4 => 'warning',
            5 => 'secondary',
            6 => 'secondary',
            7 => 'secondary',
        ];
        $NGCLASS = [
            0 => 'primary',
            1 => 'danger',
            2 => 'warning',
            3 => 'danger',
        ];
        $rank_text = config('constant.user.rank_text');
        $block_text = config('constant.user.block_text');
        if(!empty($userId)){
            $userData = D_User::findOrFail($userId);
            $userData['site_name'] = "-";
            $siteData = M_Site::findOrFail($userData['site_id']);
            if($siteData) {
                $userData['site_name'] = $siteData->name;
            }
            $userData['rank_class'] = "light";
            if(isset($RANKCLASS[$userData['rank']])) {
                $userData['rank_class'] = $RANKCLASS[$userData['rank']];
            }
            $userData['rank_text'] = "-";
            if(isset($rank_text[$userData['rank']])) {
                $userData['rank_text'] = $rank_text[$userData['rank']];
            }
            $userData['block_class'] = "light";
            if(isset($NGCLASS[$userData['block']])) {
                $userData['block_class'] = $NGCLASS[$userData['block']];
            }
            $userData['block_text'] = "-";
            if(isset($block_text[$userData['block']])) {
                $userData['block_text'] = $block_text[$userData['block']];
            }
            $userData['phone'] = str_replace('+81',0,$userData['phone']);
        }
        User_Message::where(['id' => $messageId,'author_flag' => 0,'is_read' => 0])->update(['updated_at' => now(),'is_read' => 1]);
        $fetchMessageReplis = User_Message_Replies::fetchJoinUserData($messageId);
        User_Message_Replies::where(['user_message_id' => $messageId,'author_flag' => 0,'is_read' => 0])->update(['updated_at' => now(),'is_read' => 1]);
        // User_Message_Replies::where(['user_message_id' => $messageId])->update(['updated_at' => now(),'is_read' => 1]);
        return view('admin.message.shop_message',compact('fetchMessages','fetchMessageReplis','messageId','userData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopMessageCreate(Request $request)
    {
        $messageId = $request->id;
        $content = $request->content;
        $parameter = [
            'created_at' => now(),
            'updated_at' => now(),
            'user_message_id' => $messageId,
            'content' => $content,
            'author_flag' => 1,
        ];
        $resArray = $this->resArray;
        try {
            \DB::beginTransaction();
            User_Message_Replies::insert($parameter);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cast()
    {
        $headers = [
            'id' => 'ID',
            'created_at' => '日時',
            'user' => 'ユーザー',
            'cast' => 'キャスト',
            'content' => '内容',
            // 'content' => '内容',
            'is_read' => '既読',
            'edit' => '', 
        ];
        $ngHeaders = [
            // 'id' => 'ID',
            'created_at' => '日時',
            'content' => '内容', 
            'delete' => '', 
        ];
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        return view('admin.message.cast_list',compact('headers','siteData','ngHeaders'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessage(Request $request)
    {
        $messageId = $request->id;
        $fetchMessages = Member_Message_Replies::fetchJoinUserData($messageId);
        //フォーマット
        $userId = 0;
        foreach($fetchMessages as $index => $data) {
            if(session('is_admin') == 1) {
                $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $data->cast_id]);
                if($isCastImage) {
                    $fetchMessages[$index]->cast_avatar = $isCastImage->directory . "SM_" . $isCastImage->path;
                } else {
                    $fetchMessages[$index]->cast_avatar = null;
                }
            }
            if(empty($userId)){
                $userId = $data->user_id;
            }
        }
        $userData = false;
        $RANKCLASS = [
            1 => 'success',
            2 => 'primary',
            3 => 'danger',
            4 => 'warning',
            5 => 'secondary',
            6 => 'secondary',
            7 => 'secondary',
        ];
        $NGCLASS = [
            0 => 'primary',
            1 => 'danger',
            2 => 'warning',
            3 => 'danger',
        ];
        $rank_text = config('constant.user.rank_text');
        $block_text = config('constant.user.block_text');
        if(!empty($userId)){
            $userData = D_User::findOrFail($userId);
            $userData['site_name'] = "-";
            $siteData = M_Site::findOrFail($userData['site_id']);
            if($siteData) {
                $userData['site_name'] = $siteData->name;
            }
            $userData['rank_class'] = "light";
            if(isset($RANKCLASS[$userData['rank']])) {
                $userData['rank_class'] = $RANKCLASS[$userData['rank']];
            }
            $userData['rank_text'] = "-";
            if(isset($rank_text[$userData['rank']])) {
                $userData['rank_text'] = $rank_text[$userData['rank']];
            }
            $userData['block_class'] = "light";
            if(isset($NGCLASS[$userData['block']])) {
                $userData['block_class'] = $NGCLASS[$userData['block']];
            }
            $userData['block_text'] = "-";
            if(isset($block_text[$userData['block']])) {
                $userData['block_text'] = $block_text[$userData['block']];
            }
            $userData['phone'] = str_replace('+81',0,$userData['phone']);
        }
        // 'close', 'reject', 'apporve'
        //既読状態へ
        // Member_Message_Replies::where(['message_id' => $messageId])->update(['updated_at' => now(),'is_read' => 1]);
        Member_Message_Replies::where(['message_id' => $messageId,'author_flag' => 0,'is_read' => 0])->update(['updated_at' => now(),'is_read' => 1]);
        return view('admin.message.cast_message',compact('fetchMessages','messageId','userData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessageCreate(Request $request)
    {
        $messageId = $request->id;
        $content = $request->content;
        $parameter = [
            'created_at' => now(),
            'updated_at' => now(),
            'message_id' => $messageId,
            'content' => $content,
            'author_flag' => 1,
            'status' => 'apporve'
        ];
        $resArray = $this->resArray;
        try {
            \DB::beginTransaction();
            Member_Message_Replies::insert($parameter);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castFiltering(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        if(session('role') != 1 && $siteId == 0) {
            $siteId = session('site_control');
        }
        $filter = [
            'site_id' => $siteId,
            'cast_id' => session('role') == 0 ? session('id') : 0,
        ];
        $data = Member_Message::filteringMultiSiteData($filter);
        foreach($data as $index => $d) {            
            $filter = [
                'message_id' => $d->id,
                'status' => 'apporve',
            ];
            if(session('is_admin') == 1) {
                $filter['status'] = 0;
            }
            //許可されているデータがあるか
            $isMessage = Member_Message_Replies::FetchFirstData($filter);
            if(!$isMessage) {
                unset($data[$index]);
                continue;
            }
            $data[$index]->is_read = $isMessage->is_read;
            if($isMessage->author_flag == 1) {
                //管理者のだったら既読にする。
                $data[$index]->is_read = 1;
            }
            if(session('is_admin') != 1) {
                if($isMessage->status == 'apporve') {
                    $data[$index]->user = "<small>ID:$d->user_id</small><br><span>$d->user_name</span>";
                    $data[$index]->cast = "<small>ID:$d->cast_id</small><br><span>$d->source_name</span>";
                    // $data[$index]->is_read = $isMessage->is_read;
                    $data[$index]->created_at = $isMessage->created_at;
                }
            } else {
                $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $d->cast_id]);
                if($isCastImage) {
                    $data[$index]->cast_avatar = $isCastImage->directory . "SM_" . $isCastImage->path;
                } else {
                    $data[$index]->cast_avatar = null;
                }
                $data[$index]->user = "<small>ID:$d->user_id</small><br><span>$d->user_name</span>";
                $data[$index]->cast = "<small>ID:$d->cast_id</small><br><span>$d->source_name</span>";
                // $data[$index]->is_read = $isMessage->is_read;
                $data[$index]->created_at = $isMessage->created_at;
            }
        }
		$formatData = [];
        foreach($data as $d) {
            $formatData[] = $d;
        }
        return response()->json($formatData, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castFilteringNgWord(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        if(session('role') != 1 && $siteId == 0) {
            $siteId = session('site_control');
        }
        $ngWord = Cast_Message_Ngword::fetchFilterSiteId($siteId);
        foreach($ngWord as $index => $word){
            $ngWord[$index]->created_at = date('Y-m-d: H:i:s',$word->created_at);
            $ngWord[$index]->delete = '';
        }
        return response()->json($ngWord, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castNgWordCreate(Request $request)
    {
        $siteId = $request->site_id;
        $content = $request->content;
        $parameter = [
            'created_at' => time(),
            'site_id' => $siteId,
            'content' => $content,
        ];
        Cast_Message_Ngword::insert($parameter);
        //datatableの再描画のために、インサートした後に取得する
        $ngWord = Cast_Message_Ngword::fetchFilterSiteId($siteId);
        foreach($ngWord as $index => $word){
            $ngWord[$index]->created_at = date('Y-m-d: H:i:s',$word->created_at);
            $ngWord[$index]->delete = '';
        }
        return response()->json($ngWord, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castNgWordDelete(Request $request)
    {
        $id = $request->id;
        $ngData = Cast_Message_Ngword::findOrFail($id);
        $siteId = $ngData->site_id;
        $ngData->fill(['deleted_at' => time()])->save();
        //datatableの再描画のために、インサートした後に取得する
        $ngWord = Cast_Message_Ngword::fetchFilterSiteId($siteId);
        foreach($ngWord as $index => $word){
            $ngWord[$index]->created_at = date('Y-m-d: H:i:s',$word->created_at);
            $ngWord[$index]->delete = '';
        }
        return response()->json($ngWord, 200, [], JSON_UNESCAPED_UNICODE);
        // return response()->json($resArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessageStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $resArray = $this->resArray;
        if(empty($id)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータ-です。',
            ];
            return response()->json($resArray, 400, [], JSON_UNESCAPED_UNICODE);
        }

        Member_Message_Replies::findOrFail($id)->fill([
            'updated_at' => now(),
            'status' => $status,
        ])->save();

        return response()->json($resArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessageStatusAll(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $resArray = $this->resArray;
        if(empty($id)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータ-です。',
            ];
            return response()->json($resArray, 400, [], JSON_UNESCAPED_UNICODE);
        }

        // Member_Message::findOrFail($id)->fill([
        //     'admin_check_status' => 1,
        // ])->save();
        //ステータス変更
        Member_Message_Replies::where(['message_id' => $id])->update(['updated_at' => now(),'status' => $status]);

        return response()->json($resArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessageDelete(Request $request)
    {
        $id = $request->id;
        $resArray = $this->resArray;
        if(empty($id)) {
            $resArray = [
                'result' => 1,
                'message' => '不正なパラメータ-です。',
            ];
            return response()->json($resArray, 400, [], JSON_UNESCAPED_UNICODE);
        }
        //削除
        Member_Message::findOrFail($id)->fill([
            'deleted_at' => now(),
        ])->save();
        //削除
        Member_Message_Replies::where(['message_id' => $id])->update(['deleted_at' => now()]);

        return response()->json($resArray, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
