<?php

namespace App\Http\Controllers;

use App\Models\M_Corp;
use App\Models\M_Genre;
use App\Models\M_Genre_Group;
use App\Models\M_Shop;
use App\Models\X459x_Shop;
use Illuminate\Http\Request;

class MasterShopController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->parameter = config("parameter.shop.create");
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
        $title = '店舗設定';
        $defaultUrl = route('master.shop');
        $headers = [
            'id' => 'ID',
            'corp_name' => '会社名',
            'short_name' => '店舗名',
            'genre_name' => '業態',
            'responsible_name' =>'代表者',
            // 'cosmo_name' =>'関連グループ',
            'tel' =>'電話番号',
            'fax' =>'FAX番号',
            'is_public' =>'公開フラグ',
        ];
        $formColums = [
            ['label' => '会社名','name' => 'corporate_id','type' => 'select'],
            ['label' => '店舗名','name' => 'name','type' => 'text'],
            ['label' => '店舗名(カナ)','name' => 'kana','type' => 'text'],
            ['label' => '短縮店舗名','name' => 'short_name','type' => 'text'],
            ['label' => '短縮店舗名(カナ)','name' => 'short_kana','type' => 'text'],
            ['label' => '店舗業態','name' => 'style','type' => 'select'],
            ['label' => '業態','name' => 'genre_id','type' => 'select'],
            ['label' => '代表者','name' => 'responsible_name','type' => 'text'],
            ['label' => '郵便番号','name' => 'postal_code','type' => 'text'],
            ['label' => '住所1(都道府県郡市)','name' => 'address1','type' => 'text'],
            ['label' => '住所2(町村番地)','name' => 'address2','type' => 'text'],
            ['label' => '住所3(建物名称等)','name' => 'address3','type' => 'text'],
            ['label' => 'メールアドレス','name' => 'mail','type' => 'text'],
            ['label' => 'TEL番号','name' => 'tel','type' => 'text'],
            ['label' => 'FAX番号','name' => 'fax','type' => 'text'],
            ['label' => '関連グループ','name' => 'is_cosmo','type' => 'select'],
            ['label' => '風営法適応区分','name' => 'applying','type' => 'select'],
            // ['label' => '求人TEL番号','name' => 'recruit_tel','type' => 'text'],
            // ['label' => '求人メールアドレス','name' => 'recruit_mail','type' => 'text'],
            ['label' => '営業時間','name' => 'opening_text','type' => 'text'],
            ['label' => '休業日','name' => 'holiday_text','type' => 'text'],
            // "workday_start_time",
            // "workday_end_time",
            // "friday_start_time",
            // "friday_end_time",
            // "saturday_start_time",
            // "saturday_end_time",
            // "sunday_start_time",
            // "sunday_end_time",
            // "holiday_start_time",
            // "holiday_end_time",
            ['label' => '並び順','name' => 'sort','type' => 'text'],
            ['label' => '備考','name' => 'remarks','type' => 'textarea'],
            ['label' => '届出確認','name' => 'is_notification','type' => 'switch'],
            ['label' => '公開','name' => 'is_public','type' => 'switch']
        ];
        
        $selectColums = [
            'corporate_id' => [],
            'style' => config('constant.shop.style'),
            'genre_id' => [],
            'is_cosmo' => config('constant.is_cosmo'),
            'applying' => config('constant.shop.applying'),
        ];
        
        $defaultColums = [
            'id' => 0,
            'corporate_id' => 1,
            'name' => '',
            'kana' => '',
            'short_name' => '',
            'short_kana' => '',
            'style' => 1,
            'genre_id' => 1,
            'responsible_name' => '',
            'postal_code' => '',
            'address1' => '',
            'address2' => '',
            'address3' => '',
            'mail' => '',
            'tel' => '',
            'fax' => '',
            'is_cosmo' => 1,
            'applying' => 'none',
            // 'recruit_tel' => '',
            // 'recruit_mail' => '',
            'opening_text' => '',
            'holiday_text' => '',
            'sort' => '',
            'remarks' => '',
            'is_notification' => 0,
            'is_public' => 0,
        ];
        $validateRules = [
            'name'  => [
                'required' => true,
            ],
            'short_name'  => [
                'required' => true,
            ],
            // 'short_kana'  => [
            //     'required' => true,
            // ],
            'style'  => [
                'required' => true,
            ],
            'genre_id'  => [
                'required' => true,
            ],
            // 'postal_code'  => [
            //     'number' => true,
            // ],
            // 'tel'  => [
            //     'number' => true,
            // ],
            // 'fax'  => [
            //     'number' => true,
            // ],
            'sort'  => [
                'number' => true,
            ],
        ];

        $bodys = [];
        $fetchSiteAdmin = [];
        $formatFetchData = [];
        $fetchData = M_Shop::fetchJoinData()->toArray();
        $fetchCorpData = M_Corp::fetchAll();
        $fetchGenreData = M_Genre::fetchAll();
        foreach($fetchCorpData as $index => $corp) {
            $selectColums['corporate_id'][$index]['value'] = $corp->id;
            $selectColums['corporate_id'][$index]['name'] = $corp->name;
        }
        foreach($fetchGenreData as $index => $genre) {
            $selectColums['genre_id'][$index]['value'] = $genre->id;
            $selectColums['genre_id'][$index]['name'] = $genre->name;
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
        $oldStyle = [
            1=> 'shop',
            2=> 'unshop',
            3=> 'office',
        ];
        try {
            \DB::beginTransaction();
            if (empty($parameter["id"])) {
                // 作成
                unset($parameter['id']);
                $parameter['created_at'] = time();
                M_Shop::insert($parameter);
            } else {
                //編集
                // M_Shop::saveData($parameter);
                M_Shop::findOrFail($parameter['id'])->fill($parameter)->save();
            }
            $parameter['style'] = isset($oldStyle[$parameter['style']]) ? $oldStyle[$parameter['style']] : 'shop';
            $parameter['genre'] = M_Genre::findOrFail($parameter['genre_id'])->old_name;
            X459x_Shop::upsertData($parameter);
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
            M_Shop::findOrFail($id)->fill(['updated_at' => time(),'deleted_at' => time()])->save();
            X459x_Shop::where('shopid',$id)->fill(['fd79' => time(),'fd78' => 'del'])->save();
        } catch (\Exception $e) {
            $resArray = [
                'result' => 1,
                'message' => $e->getMessage(),
            ];
        }
        return response()->json($resArray);
    }
}
