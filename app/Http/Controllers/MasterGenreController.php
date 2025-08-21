<?php

namespace App\Http\Controllers;

use App\Models\M_Genre;
use App\Models\M_Genre_Group;
use Illuminate\Http\Request;

class MasterGenreController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->parameter = config("parameter.genre.create");
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
        $title = 'ジャンル設定';
        $defaultUrl = route('master.genre');
        $headers = [
            'id' => 'ID',
            'group_name' => 'グループ名',
            'name' =>'ジャンル名',
            'content' =>'詳細',
            'remarks' =>'備考',
            'is_public' =>'公開フラグ',
        ];
        $formColums = [
            ['label' => 'グループ名','name' => 'group_id','type' => 'select'],
            ['label' => 'ジャンル名','name' => 'name','type' => 'text'],
            ['label' => '詳細','name' => 'content','type' => 'textarea'],
            ['label' => '並び順','name' => 'sort','type' => 'text'],
            ['label' => '備考','name' => 'remarks','type' => 'textarea'],
            ['label' => '公開','name' => 'is_public','type' => 'switch']
        ];
        $selectColums = [
            'group_id' => [],
        ];
        $defaultColums = [
            'id' => 0,
            'group_id' => 1,
            'name' => '',
            'content' => '',
            'sort' => '',
            'remarks' => '',
            'is_public' => 0
        ];
        $validateRules = [
            'group_id'  => [
                'required' => true,
            ],
            'name'  => [
                'required' => true,
            ],
            'sort'  => [
                'number' => true,
            ],
        ];

        $bodys = [];
        $fetchSiteAdmin = [];
        $formatFetchData = [];
        $formatGenreGroup = [];
        $fetchData = M_Genre::fetchAll()->toArray();
        $fetchAreaGroup = M_Genre_Group::fetchAll();
        foreach($fetchAreaGroup as $index => $areaGroup) {
            $selectColums['group_id'][$index]['value'] = $areaGroup->id;
            $selectColums['group_id'][$index]['name'] = $areaGroup->name;
            $selectColums['group_id'][$index]['parent']['value'] = $areaGroup->category_id;
            // $selectColums['group_id'][$index]['parent']['value'] = $site->c;
        }
        
        foreach($fetchData as $data){
            $formatFetchData[$data['id']] = $data;
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
        try {
            \DB::beginTransaction();
            if (empty($parameter["id"])) {
                // 作成
                unset($parameter['id']);
                $parameter['created_at'] = time();
                M_Genre::insert($parameter);
                
            } else {
                //編集
                M_Genre::saveData($parameter);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        $resArray = $this->resArray;
        try {
            M_Genre::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time()])->save();
        } catch (\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
}
