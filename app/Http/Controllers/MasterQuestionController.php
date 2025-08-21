<?php

namespace App\Http\Controllers;

use App\Models\M_Cast;
use App\Models\M_Cast_Question;
use App\Models\M_Site;
use App\Models\X459x_Cast_Question;
use Illuminate\Http\Request;

class MasterQuestionController extends Controller
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
    public function index(Request $request)
    {
        //
        $siteId = $request->site_id ?? 0;
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        $data = [];
        if($siteDatas->isNotEmpty()){
            if(empty($siteId)) {
                $siteId = $siteDatas[0]->id;
            }
            $data = M_Cast_Question::fetchFilteringSiteId($siteId);
        }
            
        return view('admin.master.question.index',compact('siteId','siteDatas','data'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $siteId = $request->site_id;
        $parameter = $request->parameter;
        $resArray = $this->resArray;
        $formatParameter = [];
        $time = time();
        if(!empty($parameter)) {
            $loop = 1;
            foreach($parameter as $index => $param) {
                $formatParameter[] = [
                    'id' => $param['id'],
                    'site_id' => $siteId,
                    'question' => $param['question'],
                    'default_answer' => $param['default_answer'],
                    'sort_no' => $loop,
                    'is_public' => $param['is_public'] ? 1 : 0,
                    'created_stamp' => $time,
                    'updated_stamp' => $time,
                    'deleted_at' => $param['deleted_at'] == 1 ? $time : 0,
                ];
                $loop++;
            }
            if(!empty($formatParameter)) {
                try {
                    \DB::beginTransaction();
                    $createParameters = [];
                    $oldCreateParameters = [];
                    foreach($formatParameter as $parameters) {
                        //編集
                        $oldParameter = [
                            'profid' => $parameters['id'],
                            'pr1' => $parameters['site_id'],
                            'pr2' => $parameters['question'],
                            'pr3' => $parameters['default_answer'],
                            'pr4' => $parameters['sort_no'],
                            'pr12' => $parameters['is_public'] == 1 ? 'start' : 'stop',
                            'pr13' => $parameters['deleted_at'] == 0 ? 'none' : 'del',
                            'pr14' => $parameters['updated_stamp'],
                            'pr15' => $parameters['created_stamp'],
                        ];
                        if($parameters['id'] > 0) {
                            unset($parameters['created_stamp']);
                            M_Cast_Question::findOrFail($parameters['id'])->fill($parameters)->save();
                            unset($oldParameter['pr15']);
                            X459x_Cast_Question::findOrFail($oldParameter['profid'])->fill($oldParameter)->save();
                        } else {
                            //登録
                            unset($parameters['id']);
                            unset($parameters['updated_stamp']);
                            $createParameters[] = $parameters;
                            unset($oldParameter['profid']);
                            unset($oldParameter['pr14']);
                            $oldCreateParameters[] = $oldParameter;
                        }
                    }
                    if($createParameters) {
                        M_Cast_Question::insert($createParameters);
                    }
                    if($oldCreateParameters) {
                        X459x_Cast_Question::insert($oldCreateParameters);
                    }
                    // M_Cast_Question::where(['site_id' => $siteId])->update(['deleted_at'=> 1]);
                    \DB::commit();
                } catch (\Exception $e) {
                    \DB::rollback();
                    $resArray = [
                        'result' => 1,
                        'message' => $e->getMessage(),
                    ];
                }
            }
        }
        return response()->json($resArray);
    }
    /**
     * 並び替え
     *
     * @param Request $request
     * @return
     */
    public function sort(Request $request)
    {
        try {
            $id = explode(',',$request->id);
            if(count($id) <= 0) {
                return response()->json([
                    'result' => 1,
                    'message' => '不正なパラメータです。'
                ]);
            }
            $sortNo = 1;
            $time = time();
            foreach($id as $i) {
                if($i < 0) {
                    continue;
                }
                M_Cast_Question::findOrFail($i)->fill(['sort_no' => $sortNo,'updated_stamp' => $time])->save();
                X459x_Cast_Question::findOrFail($i)->fill(['pr4' => $sortNo,'pr14' => $time])->save();
                $sortNo++;
            }
            return response()->json([
                'result' => 0,
                'message' => '処理が成功しました。'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => 1,
                'message' => '処理が失敗しました。'
            ]);
        }
    }
}
