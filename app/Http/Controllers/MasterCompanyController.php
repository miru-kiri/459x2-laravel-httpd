<?php

namespace App\Http\Controllers;

use App\Models\M_Admin;
use App\Models\M_Corp;
use App\Models\M_Site;
use App\Models\Site_Admin;
use App\Models\X459x_Corp;
use Illuminate\Http\Request;

class MasterCompanyController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->parameter = config("parameter.corp.create");
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
        $title = '会社設定';
        $defaultUrl = route('master.company');
        $headers = [
            'id' => 'ID',
            'short_name' => '略称名',
            'responsible_name' =>'代表者',
            'type_name' =>'法人 / 個人',
            'cosmo_name' =>'関連グループ',
            'tel' =>'電話番号',
            // 'fax' =>'FAX番号',
            'is_public' =>'公開フラグ',
        ];
        $formColums = [
            ['label' => '会社名称','name' => 'name','type' => 'text'],
            ['label' => '会社略称','name' => 'short_name','type' => 'text'],
            ['label' => '法人/個人','name' => 'type','type' => 'select'],
            ['label' => '代表者','name' => 'responsible_name','type' => 'text'],
            ['label' => '郵便番号','name' => 'postal_code','type' => 'text'],
            ['label' => '住所1(都道府県郡市)','name' => 'address1','type' => 'text'],
            ['label' => '住所2(町村番地)','name' => 'address2','type' => 'text'],
            ['label' => '住所3(建物名称等)','name' => 'address3','type' => 'text'],
            ['label' => 'TEL番号','name' => 'tel','type' => 'text'],
            ['label' => 'FAX番号','name' => 'fax','type' => 'text'],
            ['label' => '関連グループ','name' => 'is_cosmo','type' => 'select'],
            ['label' => '並び順','name' => 'sort','type' => 'text'],
            ['label' => '備考','name' => 'remarks','type' => 'textarea'],
            ['label' => '公開','name' => 'is_public','type' => 'switch']
        ];
        $selectColums = [
            'type' => [
                ['value'=> 1,'name' => '法人'],
                ['value'=> 2,'name' => '個人'],
            ],
            'is_cosmo' => [
                ['value'=> 1,'name' => 'コスモグループ'],
                ['value'=> 2,'name' => '一般'],
            ],
        ];
        $defaultColums = [
            'id' => 0,
            'name' => '',
            'short_name' => '',
            'type' => 1,
            'responsible_name' => '',
            'postal_code' => '',
            'address1' => '',
            'address2' => '',
            'address3' => '',
            'tel' => '',
            'fax' => '',
            'is_cosmo' => 1,
            'sort' => '',
            'remarks' => '',
            'is_public' => 0
        ];
        $validateRules = [
            'name'  => [
                'required' => true,
            ],
            'short_name'  => [
                'required' => true,
            ],
            'type'  => [
                'required' => true,
            ],
            'postal_code'  => [
                'number' => true,
            ],
            'tel'  => [
                'number' => true,
            ],
            'fax'  => [
                'number' => true,
            ],
            'sort'  => [
                'number' => true,
            ],
        ];

        $bodys = [];
        $fetchSiteAdmin = [];
        $formatFetchData = [];
        $fetchData = M_Corp::fetchAll()->toArray();
        
        foreach($fetchData as $data){
            $formatFetchData[$data['id']] = $data;            
            $formatFetchData[$data['id']]['type_name'] = $data['type'] == 1 ? '法人' : '個人';           
            $formatFetchData[$data['id']]['cosmo_name'] = $data['is_cosmo'] == 1 ? 'コスモグループ' : '一般';           
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
                M_Corp::insert($parameter);
            } else {
                //編集
                M_Corp::saveData($parameter);
            }
            X459x_Corp::upsertData($parameter);
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
            M_Corp::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time()])->save();
            X459x_Corp::where('corpid',$id)->update(['fd59' => time(),'fd58' => 'del']);
        } catch (\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
}
