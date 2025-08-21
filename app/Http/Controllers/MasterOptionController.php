<?php

namespace App\Http\Controllers;

use App\Models\M_Cast_Option;
use App\Models\M_Site;
use App\Models\X459x_Option;
use App\Models\X459x_Site;
use Illuminate\Http\Request;

class MasterOptionController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->parameter = config("parameter.option.create");
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
        $title = 'オプション設定';
        $defaultUrl = route('master.option');
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'name' => '名称',
        ];
        $formColums = [
            ['label' => 'サイト名', 'name' => 'site_id', 'type' => 'select'],
            ['label' => '名称', 'name' => 'name', 'type' => 'text'],
        ];
        $formatSites = [];
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $fetchSite = M_Site::fetchFilterAryId($siteControl);
        // $fetchSite = M_Site::fetchAll();
        $selectColums = [];
        foreach ($fetchSite as $index => $site) {
            $selectColums[$index]['value'] = $site->id;
            $selectColums[$index]['name'] = $site->name;
            $formatSites[$site->id] = $site->name;
        }
        $selectColums = [
            'site_id' => $selectColums,
        ];
        $defaultColums = [
            'id' => 0,
            'site_id' => 0,
            'name' => '',
        ];
        $validateRules = [
            'site_id'  => [
                'required' => true,
            ],
            'name'  => [
                'required' => true,
            ],
        ];

        $bodys = [];
        $formatFetchData = [];
        $fetchData = M_Cast_Option::fetchFilterData(['site_id' => $siteControl]);
        // $fetchData = M_Cast_Option::fetchAll()->toArray();
        foreach ($fetchData as $data) {
            $formatFetchData[$data['id']] = $data;
            $formatFetchData[$data['id']]['site_name'] = isset($formatSites[$data['site_id']]) ? $formatSites[$data['site_id']] : '';
        }

        foreach ($formatFetchData as $data) {
            $row = [];
            foreach ($headers as $key => $value) {
                $bodyValues = $data[$key];
                if ($key == 'id') {
                    $i = $data[$key];
                }
                $row[$key] = $bodyValues;
            }
            $bodys[$i] = $row;
        }
        return view('admin.master.template', compact('title', 'defaultUrl', 'headers', 'bodys', 'formColums', 'selectColums', 'fetchData', 'formatFetchData', 'defaultColums', 'validateRules'));
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
                M_Cast_Option::insert($parameter);
            } else {
                //編集
                $parameter['updated_at'] = time();
                M_Cast_Option::findOrFail($parameter['id'])->fill($parameter)->save();
            }
            X459x_Option::upsertData($parameter);
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
            M_Cast_Option::findOrFail($id)->fill(['updated_at' => time(), 'deleted_at' => time()])->save();
            X459x_Option::where('opid', $id)->update(['op19' => time(), 'op18' => 'del']);
        } catch (\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
}
