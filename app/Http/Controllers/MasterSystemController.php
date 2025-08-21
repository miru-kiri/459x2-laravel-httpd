<?php

namespace App\Http\Controllers;

use App\Models\M_Site;
use App\Models\Site_Course;
use App\Models\Site_Nomination_Fee;
use App\Models\Site_Option;
use Illuminate\Http\Request;

class MasterSystemController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->nominationFeeParameter = config("parameter.system.nomination_fee.create");
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
    public function index(Request $request)
    {
        //
        $tabId = $request->id ?? 1;
        $siteId = $request->site_id ?? 0;
        $courseTypeId = $request->course_type_id ?? 0;
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteDatas = M_Site::fetchFilterAryId($siteControl);
        $courseTypes = [];
        $navTabs = [];
        $fetchDatas = [];
        if($siteDatas->isNotEmpty()){
            if(empty($siteId)){
                $siteId  = $siteDatas[0]->id;
            }
            // $siteDatas = M_Site::fetchAll();
            $courseTypes = config("constant.master.system.course_type");
            $navTabs = [
                ['id' => 1,'name' => 'コース','value' => 'course' ],
                ['id' => 2,'name' => 'オプション','value' => 'option' ],
                ['id' => 3,'name' => '指名料','value' => 'nomination_fee' ],
                // ['id' => 4,'name' => 'その他','value' => 'other' ],
            ];
            
            $fetchDatas['course'] = Site_Course::fetchFilterSiteData($siteId,$courseTypeId);
            
            $fetchDatas['option'] = Site_Option::fetchFilterSiteData($siteId);
    
            $fetchDatas['nomination_fee'] = Site_Nomination_Fee::fetchFilterSiteData($siteId);
        }
        
        return view('admin.master.system.index',compact('tabId','siteId','courseTypeId','courseTypes','siteDatas','navTabs','fetchDatas'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function courseCreate(Request $request)
    {
        $siteId = $request->site_id;
        $courseTypeId = $request->course_type_id;
        $course = $request->course;
        $resArray = $this->resArray;
        $parameter = [];
        if(!empty($course)) {
            foreach($course as $index => $crs) {
                $parameter[] = [
                    'site_id' => $siteId,
                    'name' => $crs['name'],
                    'time' => $crs['time'],
                    'fee' => $crs['fee'],
                    'type' => $courseTypeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if(!empty($parameter)) {
                try {
                    \DB::beginTransaction();
                    Site_Course::where(['site_id' => $siteId,'type' => $courseTypeId])->update(['deleted_at'=>now()]);
                    Site_Course::insert($parameter);
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function optionCreate(Request $request)
    {
        $option = $request->option;
        $siteId = $request->site_id;
        $resArray = $this->resArray;
        $parameter = [];
        if(!empty($option)) {
            $loop = 1;
            foreach($option as $index => $opt) {
                $parameter[] = [
                    'site_id' => $siteId,
                    'name' => $opt['name'],
                    'fee' => $opt['fee'],
                    'sort_no' => $loop,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $loop++;
            }
            try {
                \DB::beginTransaction();
                Site_Option::where('site_id',$siteId)->update(['deleted_at'=>now()]);
                Site_Option::insert($parameter);
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                $resArray = [
                    'result' => 1,
                    'message' => $e->getMessage(),
                ];
            }
        }
        return response()->json($resArray);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function nominationFeeCreate(Request $request)
    {
        $parameter = $request->only($this->nominationFeeParameter);
        $resArray = $this->resArray;
        try {
            \DB::beginTransaction();
            if (empty($parameter["id"])) {
                // 作成
                unset($parameter['id']);
                $parameter['created_at'] = now();
                Site_Nomination_Fee::insert($parameter);
            } else {
                //編集
                Site_Nomination_Fee::saveData($parameter);
            }
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
     * オプションの並び替え
     *
     * @param Request $request
     * @return
     */
    public function optionSort(Request $request)
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
            foreach($id as $i) {
                if($i < 0) {
                    continue;
                }
                $i = str_replace('option_','',$i);
                Site_Option::findOrFail($i)->fill(['sort_no' => $sortNo,'updated_at' => now()])->save();
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
