<?php

namespace App\Http\Controllers;

use App\Models\D_Site_Detail_Tab;
use App\Models\D_Site_Tab;
use App\Models\M_Site;
use Illuminate\Http\Request;
use App\Http\Controllers\SiteDetailPageController;
use App\Models\Cast_Image;
use App\Models\Cast_Schedule;
use App\Models\D_Cast_Blog;
use App\Models\D_Cast_Blog_Image;
use App\Models\D_Movie;
use App\Models\D_Shop_Blog;
use App\Models\D_Shop_Manager_Blog;
use App\Models\D_Site_Top_Log;
use App\Models\M_Cast;
use App\Models\M_Shop;
use App\Models\M_Site_Tab;
use App\Models\Site_Course;
use App\Models\Site_Image;
use App\Models\Site_Info;
use App\Models\Site_Nomination_Fee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomePageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $siteId = $request->site_id ?? 0;
        $tabId = $request->tab_id ?? 0;
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        $mainColor = '';
        $mainText = '';
        $siteTabData = [];
        $contents = [];
        $selectTabData = false;
        $selectSiteTabData = false;
        $selectDetailData = false;
        $selectSiteImageData = false;
        $infoImage = false;
        $infoColor = false;
        if($siteData->isNotEmpty()) {
            foreach($siteData as $index => $site) {
                if(empty($site->template)) {
                    unset($siteData[$index]);
                }
                if(empty($siteId)) {
                    $siteId = $site->id;
                }
            }
            $selectSiteData = M_Site::findOrFail($siteId);
            $shops = M_Shop::findOrFail($selectSiteData->shop_id);
            $selectSiteInfoData = Site_Info::fetchSiteData($siteId);
            $selectSiteImageData = Site_Image::fetchSiteCategoryData($siteId,1);
            $siteController = app()->make('App\Http\Controllers\SiteDetailPageController');
            $genre = $siteController->getGenre($selectSiteData->template);
            $mainColor = '#FFF';
            $mainText = 'その他';
            if(isset($genre['color'])) {
                $mainColor = $genre['color'];
                $mainText = $genre['text'];
            }
            if($selectSiteInfoData) {
                $mainColor = $selectSiteInfoData->color;
                $infoImage = $selectSiteInfoData->image ?? false;
                $infoColor = $selectSiteInfoData->color ?? false;
            }
    
            $siteTabData = D_Site_Tab::fetchFilteringData(['site_id' => $siteId,'is_display' => 0 ]);
            $masterId = 0;
            // $isSort = false;
            $isMap = false;
            if(!empty($tabId)) {
                $selectTabData = M_Site_Tab::findOrFail($tabId);
                $selectSiteTabData = D_Site_Tab::fetchFilteringSiteData(['master_id' => $tabId,'site_id' => $siteId]);
                $selectDetailData = D_Site_Detail_Tab::fetchFilteringData(['master_id' => $tabId,'site_id' => $siteId,'is_display' => 0 ]);
                $masterId = $tabId;
                foreach($selectDetailData as $data) {
                    // if($data->event == 'top_today_work') {
                    //     $isSort = true;
                    // }
                    if($data->event == 'shop_gallery') {
                        $isMap = true;
                    }
                    $contents[$data->id] = $this->{$data->event}($data,$siteId,$mainColor);
                } 
            }
        }
        return view('admin.homePage.index',compact('infoColor','infoImage','mainColor','mainText','siteId','tabId','siteData','siteTabData','contents','selectTabData','selectSiteInfoData','masterId','selectSiteTabData','shops','isMap','selectSiteImageData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
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
            D_Site_Detail_Tab::findOrFail($i)->fill(['sort_no' => $sortNo,'updated_at' => time()])->save();
            $sortNo++;
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function infoUpdate(Request $request)
    {
        $siteId = $request->site_id ?? 0;
        $color = $request->color ?? '#F1747E';
        $file = $request->file ?? null;
        $url = $request->url ?? null;
        $editUrls = $request->edit_url ?? [];
        $image = null;
        $previousUrl = app('url')->previous();
        try {
            DB::beginTransaction();
            $data = Site_Info::fetchSiteData($siteId);
            if(!$data) {
                $siteData = M_Site::findOrFail($siteId);
                Site_Info::insert([
                    'created_at' => time(),
                    'site_id' => $siteId,
                    'title' => $siteData->name,
                ]);
            } else {
                $data->fill([
                    'updated_at' => time(),
                    'color' => $color,
                    // 'image' => $image,
                ])->save();
            }
            if($editUrls) {
                foreach($editUrls as $id => $editurl) {
                    $siteImageData = Site_Image::findOrFail($id);
                    $siteImageData->fill([
                        'updated_at' => time(),
                        'url' => $editurl,
                    ])->save();
                }
            }
            if($file) {
                $isSiteImageData = Site_Image::fetchSiteCategoryData($siteId,1);
                $sortNo = 0;
                if($isSiteImageData->isNotEmpty()) {
                    $sortNo = Site_Image::filterSiteMaxSortNo($siteId,1);
                }
                $fileExtension = $file->getClientOriginalExtension();
                // $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $formatFile = ['jpeg','png','gif','jpg', 'bmp', 'webp', 'tiff'];
        
                if (!in_array(strtolower($fileExtension), $formatFile)) {
                    session()->flash('error', '画像形式が対応できないです。');
                    return redirect()->to($previousUrl);
                }
                $defaultPath = "cms/{$siteId}/banner/";
                $time = time();
                $image = "/" . $defaultPath . $siteId . '_' . $time.'.'. $fileExtension;
                //なってなくない？
                $file->storeAs($defaultPath, $siteId . '_' . $time .'.'. $fileExtension,'public');

                $parameter = [
                    'created_at' => time(),
                    'site_id' => $siteId,
                    'category_id' => 1,
                    'image' => $image,
                    'url' => $url,
                    'sort_no' => $sortNo + 1,
                ];
                Site_Image::insert($parameter);
            } 
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => '登録に成功しました。',
                'image' => $image,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::debug($e);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $masterId = $request->master_id;
        $siteId = $request->site_id;
        $tabName = $request->tab_name;
        $tabIsDisplay = $request->tab_display ? 1 : 0;
        $addCount = $request->add_count ?? 0;
        $previousUrl = app('url')->previous();
        $time = time();
		if(empty($siteId) || empty($masterId)) {
            session()->flash('error', '不正なパラメータです。');
            return redirect()->to($previousUrl);
        }
        try {
            
            $siteTabData = D_Site_Tab::fetchFilteringSiteData(['master_id' => $masterId,'site_id' => $siteId]);    
            if($siteTabData) {
                $siteTabData->fill(['updated_at' => $time,'name' => $tabName,'is_display' => $tabIsDisplay])->save();
            }
            $maxSortNo = 0;
            $siteDetail = D_Site_Detail_Tab::fetchFilteringData(['master_id' => $masterId,'site_id' => $siteId,'is_display' => 0 ]);
            foreach($siteDetail as $detail) {
                if($maxSortNo < $detail->sort_no) {
                    $maxSortNo = $detail->sort_no;
                }
                $data = D_Site_Detail_Tab::findOrFail($detail->id);
                $title = $request->input($detail->id."-title") ?? null;
                $subTitle = $request->input($detail->id."-sub_title") ?? null;
                $content = $request->input($detail->id."-content") ?? null;
                $isDisplay = $request->input($detail->id."-is_display") ? 1 : 0; 
                $isDelete = $request->input($detail->id."-is_delete") ? $time : 0; 
                $data->fill([
                    'updated_at' => $time,
                    'deleted_at' => $isDelete,
                    'title' => $title,
                    'sub_title' => $subTitle,
                    'content' => $content,
                    'is_display' => $isDisplay,
                ])->save();
            }
            //新規登録ロジック
            if($addCount > 0) {
                for($i=1;$i<=$addCount;$i++) {
                    $title = $request->input("-".$i."-title") ?? null;
                    $subTitle = $request->input("-".$i."-sub_title") ?? null;
                    $content = $request->input("-".$i."-content") ?? null;
                    $isDisplay = $request->input("-".$i."-is_display") ? 1 : 0; 
                    $isDelete = $request->input("-".$i."-is_delete") ? $time : 0; 
                    if($isDelete > 0) {
                        continue;
                    }
                    $maxSortNo++;
                    $parameter[] = [
                        'created_at' => $time,
                        'master_id' => $masterId,
                        'master_detail_id' => 0,
                        'site_id' => $siteId,
                        'title' => $title,
                        'sub_title' => $subTitle,
                        'content' => $content,
                        'is_display' => $isDisplay,
                        'sort_no' => $maxSortNo,
                        'event' => 'add_content',
                    ];
                }
                if($parameter) {
                    D_Site_Detail_Tab::insert($parameter);
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);    
        }
        session()->flash('success', '登録に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_today_work($detailData,$siteId,$mainColor)
    {
        $html = "";
        // $sites = M_Site::findOrFail($siteId);
        //出勤キャストを取得
        $filter = [
            'date' => date('Y-m-d'),
            'site_id' => $siteId,
            'is_work' => 1,
            'sokuhime_sort' => 'ASC',
			'is_public' => 1
        ];
        $casts = Cast_Schedule::fetchFilteringData($filter);
		$imadakeStatusAry = config('constant.cast.imadake_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');
        //今日出勤のキャストデータ
		$castIdAry = [];
        foreach($casts as $index => $cast) {
            if(in_array($cast->cast_id,$castIdAry)) {
                unset($casts[$index]);
                continue;
            }
            array_push($castIdAry,$cast->cast_id);
            $casts[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->cast_id]);
            if($castImage) {
                $casts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
			if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $casts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $casts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $casts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $casts[$index]->sokuhime_status = "次回" . $cast->sokuhime_status . "~";
                    }
                }
            }
            $casts[$index]->exclusive_status_text = '';
            $casts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $casts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $casts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
        }

        // <p class='fw-bold fs-5 mb-0 mt-3' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>本日の出勤</p>
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .= 
        "<section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
        <div class='card'>
        <div class='card-body'>
                <div class='form-check mb-3'>
                    <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-title' class='form-label'>タイトル</label>
                    <input class='form-control' type='text' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title'/>
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' />
                </div>
                <label class='form-label'>内容</label>
                <div class='row'>";
        foreach($casts as $cast) {
            $imageUrl = asset('/storage' . $cast->image);
			$castName = $cast->source_name;
            if ($cast->age) {
                $castName = $cast->source_name . "(" . $cast->age . ")";
            }
            $castStyleHtml = "";
            if ($cast->bust || $cast->cup || $cast->waist || $cast->hip) {
                $castStyleHtml = "<small class='text-muted'>";
                if ($cast->bust) {
                    $castStyleHtml .= "B $cast->bust";
                }
                if ($cast->cup) {
                    $castStyleHtml .= "($cast->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='...'>";
            if ($cast->exclusive_status_image) {
                $stayStatusImage = asset('img/' . $cast->exclusive_status_image);
                $castImageHtml = "
                <div style='position: relative; display: inline-block;'>
                    <img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='...'>
                    <img src='$stayStatusImage' class='stay-status-img'></img>
                </div>
                ";
            }
			$html .= "
                <div class='col-6 col-md-2'>
                    <div class='card px-0'>
                        $castImageHtml
                        <p class='card-text text-white text-center mb-1' style='background: $mainColor'>$cast->sokuhime_status</p>
                        <div class='text-center' style='height: 5rem'>
                        <p class='mb-1'>$castName</p>
                        $castStyleHtml
                        </div>
                        <button class='btn btn-block cast-schedule-btn' type='button'>$cast->start_time ~ $cast->end_time</button>
                    </div>
                </div>";
        }
        if($casts->isEmpty()) {
            $html .= "<p>データがありません。</p>";
        }
        $html .="</div>
            </div>
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_shop_news($detailData,$siteId,$mainColor)
    {
        // //ショップニュース
        // dd($detailData);
        $html = "";
        $shopBlogs = D_Shop_Blog::fetchSiteIdLimitData([$siteId],3);
        // <p class='fw-bold fs-5 mb-0 mt-3' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>$detailData->title</p>
        // <span class='headline_text fw-bold mb-3'>$detailData->sub_title</span>";
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .="
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
        <div class='card'>
        <div class='card-body'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='form-group'>
                <label for='$detailData->id-title' class='form-label'>タイトル</label>
                <input class='form-control' type='text' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title'/>
            </div>  
            <div class='form-group'>
                <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                <input class='form-control' type='text' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title' />
            </div>  
                <div class='mb-3'>
                <label class='form-label'>内容</label>";
                foreach($shopBlogs as $shopBlog) {
                    $html .="<ul>
                    <li class='list_content'>
                        <p class='mb-2 text-muted'> " . date('Y年m月d日 H:i',strtotime($shopBlog->published_at)) ."</p>                    
                        <a class='list_title fw-bold'>$shopBlog->title</a>
                    </li>
                    </ul>";
                }
                if($shopBlogs->isEmpty()) {
                    $html .= "<p>データがありません。</p>";
                }
        $html .="</div>
            </div>
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_manager_news($detailData,$siteId,$mainColor)
    {
        // //店長ブログ
        $html = ""; 
        $managerBlogs = D_Shop_Manager_Blog::fetchSiteIdLimitData([$siteId],3);
        // <p class='fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>店長BLOG</p>
        // <span class='headline_text fw-bold mb-3'>店長しか知らない㊙︎な情報を配信</span>
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .="
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='card'>
            <div class='card-body'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='form-group'>
                <label for='$detailData->id-title' class='form-label'>タイトル</label>
                <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title'/>
            </div>
            <div class='mb-3'>
            <div class='form-group'>
                <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
            </div>
                <div class='mt-3'>
                <label class='form-label'>内容</label>";
                foreach($managerBlogs as $managerBlog){
                    $html .="<ul>
                        <li class='list_content'>
                            <p class='mb-2 text-muted'>" .date('Y年m月d日  H:i',strtotime($managerBlog->published_at)). "</p>
                            <a class='list_title fw-bold'>$managerBlog->title</a>
                        </li>
                    </ul>";
                }
                if($managerBlogs->isEmpty()) {
                    $html .= "<p>データがありません。</p>";
                }
        $html .="
                </div>
            </div>
            </div>
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_cast_news($detailData,$siteId,$mainColor)
    {
        // //キャストブログ
        $html = "";
        $castBlogs = D_Cast_Blog::fetchSiteIdLimitData([$siteId],6);
        
        foreach($castBlogs as $index => $castBlog) {
            $castBlogs[$index]->image = '/no-image.jpg';
            $isImage = D_Cast_Blog_Image::fetchFilterIdFirstData($castBlog->id);
            if($isImage) {
                $castBlogs[$index]->image = $isImage->image_url;
                continue;
            }
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castBlog->cast_id]);
            if($isCastImage) {                
                $castBlogs[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
                continue;
            }
        }

        // <p class='headline_title fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>写メ日記</p>
        // <span class='headline_text fw-bold'>在籍の女の子の日記をリアルタイムで紹介</span>
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .= "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='card'>
            <div class='card-body'>
                <div class='form-check mb-3'>
                    <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-title' class='form-label'>タイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
                </div>
                <div class='headline mb-3'>
                <label class='form-label'>内容</label>
                <div class='row my-3'>";
                foreach($castBlogs as $castBlog){
                    $imageUrl = asset('/storage' . $castBlog->image);
                    // $html .= "
                    // <div class='col-12 col-md-4'>
                    //     <div class='card text-center my-2'>
                    //         <span class='pt-2'>$castBlog->site_name</span>
                    //         <img class='py-3' src='$imageUrl' style='height: 200px; object-fit: contain;' alt='...'>
                    //         <p>$castBlog->source_name</p>
                    //         <a>$castBlog->title</a>
                    //         <p><small class='text-muted'> ".date('Y年m月d日',strtotime($castBlog->published_at)) ."</small></p>
                    //     </div>
                    // </div>";
                    $html .= "
                    <div class='col-4 col-md-2 mb-3'>
                        <div class='image-container'>
                            <img src='$imageUrl' alt='$castBlog->name $castBlog->source_name $castBlog->title'>              
                            <div class='text-overlay'>
                            <p class='cast-blog-title'>$castBlog->title</p>
                            <p class='cast-blog-name'>$castBlog->source_name</p>
                            <p class='cast-blog-date'>". date('Y年m月d日  H:i',strtotime($castBlog->published_at)) ."</p>
                            </div>
                        </div>
                    </div>";
                    // href="{{ route('site.detail.blog.detail',['category_id' => 3,'id' => $castBlog->id]) }}"
                }
                if($castBlogs->isEmpty()) {
                    $html .= "<p>データがありません。</p>";
                }
        $html .= "
                </div>
                </div>
                </div>
                </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_ranking($detailData,$siteId,$mainColor)
    {
        $html = "";
        $loop = 1;
        // //指名数ランキング
        $castReserveRanks = M_Cast::fetchReserveCountCast(['site_id' =>$siteId,'limit' => 1]);
        foreach($castReserveRanks as $index => $castAccessRank) {
            $castReserveRanks[$index]->image = '/no-image.jpg';
            $castReserveRanks[$index]->age =  $castAccessRank->age ?? '-' ;
            $castReserveRanks[$index]->bust =  $castAccessRank->bust ??  '-';
            $castReserveRanks[$index]->cup =  $castAccessRank->cup ?? '-';
            $castReserveRanks[$index]->waist =  $castAccessRank->waist ?? '-';
            $castReserveRanks[$index]->hip =  $castAccessRank->hip ?? '-';
            $isCastImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $castAccessRank->id]);
            if($isCastImage) {                
                $castReserveRanks[$index]->image = $isCastImage->directory . "SM_" . $isCastImage->path;
            }
        }
        // <p class='headline_title fw-bold fs-5 mb-0' style='color: $mainColor; border-bottom: 2px solid #d3d3d3;'>指名数ランキング</p>
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .= "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='card'>
            <div class='card-body'>
                <div class='headline'>
                    <div class='form-check  mb-3'>
                        <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
                    </div>
                    <div class='form-group'>
                        <label for='$detailData->id-title' class='form-label'>タイトル</label>
                        <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title'/>
                    </div>
                    <div class='form-group'>
                        <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                        <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title' />
                    </div>
                        </div>
                            <div class='container'>
                                <label class='form-label'>内容</label>";
                            foreach($castReserveRanks as $castReserveRank){
                                $imageUrl = asset('/storage' . $castReserveRank->image);
                            $html .= "
                                    <div class='card mb-3'>
                                    <div class='row g-0'>
                                    <div class='col-md-2 text-center'>
                                    <div class='triangle-number-$loop'></div>
                                    <div class='triangle-number-text'>$loop<span class='triangle-number-small-text'>位</span></div>
                                        <img src='$imageUrl' style='max-width: 100%;height: 200px; object-fit: contain;' alt='$castReserveRank->source_name'>
                                    </div>
                                    <div class='col-md-8'>
                                        <div class='card-body'>
                                            <p class='mb-0'>$castReserveRank->source_name($castReserveRank->age)</p>
                                            <small class='text-muted mb-3'>B$castReserveRank->bust($castReserveRank->cup)/W$castReserveRank->waist/H$castReserveRank->hip</small>
                                            <p class='my-3 pc-area'>$castReserveRank->shop_manager_pr</p>
                                        </div>
                                    </div>
                                        
                                    </div>
                                    </div>";
                                    $loop++;
                                    // <a href="{{ route('site.detail.cast.detail',['cast_id' => $castReserveRank->id]) }}" class="cast_list_card">
                                }
            if($castReserveRanks->isEmpty()) {
                $html .= "<p>データがありません。</p>";
            }
            $html .= "</div>
                </div>
                </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_recommend($detailData,$siteId,$mainColor)
    {
        $recommendCasts = M_Cast::fetchFilterRecommendCast(['site_id' => $siteId]);
		$imadakeStatusAry = config('constant.cast.imadake_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');
        foreach($recommendCasts as $index => $cast) {
            $recommendCasts[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->id]);
            if($castImage) {
                $recommendCasts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
			if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $recommendCasts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $recommendCasts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $recommendCasts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $recommendCasts[$index]->sokuhime_status = $cast->sokuhime_status . "~";
                    }
                }
            }
            $recommendCasts[$index]->exclusive_status_text = '';
            $recommendCasts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $recommendCasts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $recommendCasts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
        }
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "";
        $html .= "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='card'>
            <div class='card-body'>
                <div class='form-check mb-3'>
                    <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-title' class='form-label'>タイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
                </div>
                <div class='headline mb-3'>
                <label class='form-label'>内容</label>
                <div class='row my-3'>";
                foreach($recommendCasts as $recommendCast){
                    $imageUrl = asset('/storage' . $recommendCast->image);
					$castName = $recommendCast->source_name;
            if ($recommendCast->age) {
                $castName = $recommendCast->source_name . "(" . $recommendCast->age . ")";
            }
            $castStyleHtml = "";
            if ($recommendCast->bust || $recommendCast->cup || $recommendCast->waist || $recommendCast->hip) {
                $castStyleHtml = "<small class='text-muted'>";
                if ($recommendCast->bust) {
                    $castStyleHtml .= "B $recommendCast->bust";
                }
                if ($recommendCast->cup) {
                    $castStyleHtml .= "($recommendCast->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='...'>";
            if ($recommendCast->exclusive_status_image) {
                $stayStatusImage = asset('img/' . $recommendCast->exclusive_status_image);
                $castImageHtml = "
                <div style='position: relative; display: inline-block;'>
                    <img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='...'>
                    <img src='$stayStatusImage' class='stay-status-img'></img>
                </div>
                ";
            }
			$html .= "
                    <div class='col-6 col-sm-4 col-md-3 col-lg-2'>
                        <div class='card px-0'>
                            $castImageHtml
                            <div class='text-center' style='height: 5rem'>
                            <p class='mb-1'>$castName</p>
                            $castStyleHtml
                            </div>";
            if ($recommendCast->start_time) {
                $html .= "<button class='btn btn-block cast-schedule-btn' type='button'>$recommendCast->start_time ~ $recommendCast->end_time</button>";
            }
            $html .= "</div>
                </div>";
                }
            if($recommendCasts->isEmpty()) {
                $html .= "<p>データがありません。</p>";
            }
            $html .= "</div>
                </div>
                </div>
                </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function top_movie($detailData,$siteId,$mainColor)
    {
        $movieData = D_Movie::fetchFilterData(['site_id' => $siteId,'cast_id' => 0,'limit' => 3]);
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "";
        $html .= "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='card'>
            <div class='card-body'>
                <div class='form-check mb-3'>
                    <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-title' class='form-label'>タイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
                </div>
                <div class='headline mb-3'>
                <label class='form-label'>内容</label>
                <div class='row my-3'>";
                foreach($movieData as $data){
                    $imageUrl = asset('storage' . $data->file_path .'/' . $data->file_name . '.mp4');
                    $html .= "
                    <div class='col-6 col-md-4'>
                        <video class='cast-video' src='$imageUrl' controlsList='nodownload' oncontextmenu='return false;' controls></video>
                    </div>
                    ";
                }
            if(!$movieData) {
                $html .= "<p>データがありません。</p>";
            }
            $html .= "</div>
                </div>
                </div>
                </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function today_work($detailData,$siteId,$mainColor)
    {
        $html = "";
        $filterDate =  date('Ymd');
        
        
        $filter = [
            'date' => date('Y-m-d',strtotime($filterDate)),
            'site_id' => $siteId,
            'is_work' => 1,
            'sort' => 'ASC',
            'is_public' => 1,
        ];
        $casts = Cast_Schedule::fetchFilteringData($filter);
        $imadakeStatusAry = config('constant.cast.imadake_status');
		$exclusiveStatus = config('constant.cast.exclusive_status');
        //今日出勤のキャストデータ
		$castIdAry = [];
        foreach($casts as $index => $cast) {
            if(in_array($cast->cast_id,$castIdAry)) {
                unset($casts[$index]);
                continue;
            }
            array_push($castIdAry,$cast->cast_id);
			if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $casts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $casts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $casts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $casts[$index]->sokuhime_status = "次回" . $cast->sokuhime_status . "~";
                    }
                }
            }
            $casts[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->cast_id]);
            if($castImage) {
                $casts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
			$casts[$index]->exclusive_status_text = '';
            $casts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $casts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $casts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
        }
        $currentDate = time();
        $weeks = [
            '日', //0
            '月', //1
            '火', //2
            '水', //3
            '木', //4
            '金', //5
            '土', //6
        ];
        // 1週間後の日付を計算
        $oneWeekLater = strtotime('+1 week', $currentDate);
        $dateArray = array();
        $loop = 0;
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        while ($currentDate < $oneWeekLater) {
            $dateArray[$loop]['Ymd'] = date('Ymd', $currentDate);
            $dateArray[$loop]['date'] = date('n月j日', $currentDate);
            $dateArray[$loop]['week'] = $weeks[date('w', $currentDate)];
            $dateArray[$loop]['active'] = $filterDate == date('Ymd', $currentDate) ? true : false;
            $currentDate = strtotime('+1 day', $currentDate);
            $loop++;
        }
        $html = "<section id='$detailData->id' data-sort_no='$detailData->sort_no'> 
            <div class='container mt-3'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='row'>";
            foreach($dateArray as $index => $dateAry) {
                $cols = 4;
                $cols_md = 4;
                if($index == 0) {
                    $cols = 12; 
                    $cols_md = 12; 
                }
                $activeClass = ''; 
                if($dateAry['active']) {
                    $activeClass = 'date_btn_active'; 
                }
                $title = $dateAry['date']. "(".$dateAry['week'].")";
                $html .=  "
                    <div class='col-$cols col-md-$cols_md  text-center mb-3'>
                        <a class='btn btn-block date_btn $activeClass' style='white-space:nowrap;'>$title</a>
                    </div>";
            }
            foreach($casts as $cast) {
                    $imageUrl = asset('/storage' . $cast->image);
                    $castName = $cast->source_name;
            if ($cast->age) {
                $castName = $cast->source_name . "(" . $cast->age . ")";
            }
            $castStyleHtml = "";
            if ($cast->bust || $cast->cup || $cast->waist || $cast->hip) {
                $castStyleHtml = "<small class='text-muted'>";
                if ($cast->bust) {
                    $castStyleHtml .= "B $cast->bust";
                }
                if ($cast->cup) {
                    $castStyleHtml .= "($cast->cup)";
                }
                $castStyleHtml .= "</small>";
            }
            $castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='...'>";
            if ($cast->exclusive_status_image) {
                $stayStatusImage = asset('img/' . $cast->exclusive_status_image);
                $castImageHtml = "
                <div style='position: relative; display: inline-block;'>
                    <img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='...'>
                    <img src='$stayStatusImage' class='stay-status-img'></img>
                </div>
                ";
            }
            $html .= "
                    <div class='col-6 col-sm-4 col-md-3 col-lg-2'>
                        <div class='card px-0'>
                        $castImageHtml
                        <p class='card-text text-white text-center mb-1' style='background: $mainColor'>$cast->sokuhime_status</p>
                        <div class='text-center' style='height: 5rem'>
                            <p class='mb-1'>$castName</p>
                            $castStyleHtml
                        </div>
                        <button class='btn btn-block cast-schedule-btn' type='button'>$cast->start_time ~ $cast->end_time </button>
                        </div>
                    </div>";
                }
        $html .= "</div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cast($detailData,$siteId,$mainColor)
    {
        $html = "";
        $casts = M_Cast::fetchFilterAryId(['site_id' => $siteId]);
        $filter = [
            'date' => date('Y-m-d'),
            'site_id' => $siteId,
			'sokuhime_sort' => 'ASC',
			'is_public' => 1
        ];
        $formatScheduleDatas = [];
        $scheduleDatas = Cast_Schedule::fetchFilteringData($filter);
        foreach($scheduleDatas as $scheduleData) {
            $formatScheduleDatas[$scheduleData->cast_id] = $scheduleData;
        }
		$imadakeStatusAry = config('constant.cast.imadake_status');
        $exclusiveStatus = config('constant.cast.exclusive_status');
        //今日出勤のキャストデータ
        foreach($casts as $index => $cast) {
            $casts[$index]->image = '/no-image.jpg';
            $castImage = Cast_Image::fetchFilteringFirstData(['cast_id' => $cast->id]);
            if($castImage) {
                $casts[$index]->image = $castImage->directory . "SM_" . $castImage->path;
            }
            if(isset($formatScheduleDatas[$cast->id])) {
                $casts[$index]->is_work = $formatScheduleDatas[$cast->id]->is_work;
                $casts[$index]->start_time = $formatScheduleDatas[$cast->id]->start_time;
                $casts[$index]->end_time = $formatScheduleDatas[$cast->id]->end_time;
                $casts[$index]->rest_comment = $formatScheduleDatas[$cast->id]->rest_comment;
            }
			$casts[$index]->exclusive_status_text = '';
            $casts[$index]->exclusive_status_image = '';
            foreach ($exclusiveStatus as $exclusiveStatu) {
                if ($exclusiveStatu['value'] == $cast->exclusive_status) {
                    $casts[$index]->exclusive_status_text = $exclusiveStatu['name'];
                    $casts[$index]->exclusive_status_image = $exclusiveStatu['image'];
                }
            }
            if (isset($imadakeStatusAry[$cast->sokuhime_status])) {
                $casts[$index]->sokuhime_status = $imadakeStatusAry[$cast->sokuhime_status];
            } else {
                if (empty($cast->sokuhime_status)) {
                    $casts[$index]->sokuhime_status = '-';
                } else {
                    if($cast->sokuhime_status == 'LAST') {
                        $casts[$index]->sokuhime_status = $cast->sokuhime_status;
                    } else {
                        $casts[$index]->sokuhime_status = "次回" . $cast->sokuhime_status . "~";
                    }
                }
            }
        }
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .=
        "<section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container mt-3'>
			<div class='card'>
            <div class='card-body'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
                <div class='row'>";
            // <a href="{{ route('site.detail.cast.detail',['cast_id' => $cast->id]) }}" class="cast_list_card">
                foreach($casts as $cast){
                    $imageUrl =  asset('/storage' . $cast->image);
					$castName = $cast->source_name;
					if ($cast->age) {
						$castName = $cast->source_name . "(" . $cast->age . ")";
					}
					$castStyleHtml = "";
					if ($cast->bust || $cast->cup || $cast->waist || $cast->hip) {
						$castStyleHtml = "<small class='text-muted'>";
						if ($cast->bust) {
							$castStyleHtml .= "B $cast->bust";
						}
						if ($cast->cup) {
							$castStyleHtml .= "($cast->cup)";
						}
						$castStyleHtml .= "</small>";
					}
					$castImageHtml = "<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='...'>";
					if ($cast->exclusive_status_image) {
						$stayStatusImage = asset('img/' . $cast->exclusive_status_image);
						$castImageHtml = "
						<div style='position: relative; display: inline-block;'>
							<img class='text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: 100%; object-fit:contain' alt='...'>
							<img src='$stayStatusImage' class='stay-status-img'></img>
						</div>
						";
					}
                    $html .=
                "<div class='col-6 col-md-2'>
                        <div class='card px-0' style='height: 23rem'>
                            $castImageHtml
                            <p class='card-text text-white text-center mb-1' style='background: $mainColor'>";
            if ($cast->is_work == 1) {
                $html .= $cast->sokuhime_status;
            } else {
                $html .=  "お休み";
            }
            $html .=
                "</p>
                            <div class='text-center' style='height: 5rem'>
                                <p class='mb-1'>$castName</span></p>
                            </div>";
            if ($cast->is_work == 1) {
                $html .=  "<button class='btn btn-block cast-schedule-btn' type='button'>$cast->start_time ~ $cast->end_time</button>";
            }
                $html .="</div>
                    </div>";
                }
    $html .="</div>
            </div>
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function base_price($detailData,$siteId,$mainColor)
    {
        $html = "";
        $courses = Site_Course::fetchFilterSiteData($siteId,0);
        $nominationFees = Site_Nomination_Fee::fetchFilterSiteData($siteId);
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .="
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='card'>
            <div class='card-body'>
                <div class='form-check mb-3'>
                    <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-title' class='form-label'>タイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
                </div>
                <div class='form-group'>
                    <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                    <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
                </div>
                <label class='form-label'>内容</label>";
        if($courses->isNotEmpty()) {
            $html .="
                <table class='table table-bordered mt-3'>
            ";
        }
        foreach($courses as $course){
            $html .="
            <tr>
                <td class='text-center table-active'>
                    ".$course->time."分
                </td>
                <td class='text-center'>
                    ". number_format($course->fee)."円
                </td>
            </tr>";
        }
        if($nominationFees){
			$html .="
            <tr>
                <td class='text-center table-active'>
                    指名料
                </td>
                <td class='text-center'>
                    ".number_format($nominationFees->fee)."円
                </td>
            </tr>
            <tr>
                <td class='text-center table-active'>
                    本指名料
                </td>
                <td class='text-center'>
                    ".number_format($nominationFees->nomination_fee)."円
                </td>
            </tr>
			</table>";
        } else {
            if($courses->isNotEmpty()) {
                $html .="
                    </table>
                ";
            }
        }
        $html .="
            </div>
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function extension_price($detailData,$siteId,$mainColor)
    {
        $html = "";
        $nominationFees = Site_Nomination_Fee::fetchFilterSiteData($siteId);
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .="
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
        <div class='card'>
        <div class='card-body'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='form-group'>
                <label for='$detailData->id-title' class='form-label'>タイトル</label>
                <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
            </div>
            <div class='form-group'>
                <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
            </div>
            <label class='form-label'>内容</label>";
            if($nominationFees){
                $html .="
                <table class='table table-bordered mt-3'>";
                    if($nominationFees->extension_time_unit > 0&& $nominationFees->extension_fee > 0)
                    {
                        $html .="
                        <tr>
                        <td class='text-center table-active'>
                        " . number_format($nominationFees->extension_time_unit). "分
                        </td>
                        <td class='text-center'>
                            ". number_format($nominationFees->extension_fee). "円
                        </td>
                        </tr>";
                    }
                $html .="</table>";
            }
            $html .="
            </div>
            </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function other_price($detailData,$siteId,$mainColor)
    {
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
                <div class='card'>
                    <div class='card-body'>
                        <div class='form-check mb-3'>
                            <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
                        </div>
                        <div class='form-group'>
                            <label for='$detailData->id-title' class='form-label'>タイトル</label>
                            <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
                        </div>
                        <div class='form-group'>
                            <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                            <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
                        </div>
                        <label class='form-label'>その他の内容</label>
                        <textarea class='form-control' name='$detailData->id-content' id='content-other-price' >$detailData->content</textarea>
                    </div>
                </div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_info($detailData,$siteId,$mainColor)
    {
        $html = "";
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $sites = M_Site::findOrFail($siteId);
        $siteController = app()->make('App\Http\Controllers\SiteDetailPageController');
        $genre = $siteController->getGenre($sites->template);
        // $mainColor = $genre['color'];
        $mainText = $genre['text'];
        $shops = M_Shop::findOrFail($sites->shop_id);
        $html .="
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='card'>
        <div class='card-body'>
            <div class='form-check mb-3'>
            <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
        </div>
        <div class='form-group'>
            <label for='$detailData->id-title' class='form-label'>タイトル</label>
            <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
        </div>
        <div class='form-group'>
            <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
            <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
        </div>
        <label class='form-label'>その他の内容</label>";
        if($shops){
            $html .="<table class='table table-bordered mt-3'>
              <tr>
                <td class='text-center table-active' width='50%'>
                店舗名
                </td>
                <td class='text-center'>
                  $shops->name
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                業種
                </td>
                <td class='text-center'>
                    $mainText
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                営業時間
                </td>
                <td class='text-center'>
                    $shops->workday_start_time ~ $shops->workday_end_time
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                定休日
                </td>
                <td class='text-center'>
                  年中無休
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                住所
                </td>
                <td class='text-center'>
                    $shops->address1 $shops->address2 $shops->address3
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                電話番号
                </td>
                <td class='text-center'>
                    $shops->tel
                </td>
              </tr>
              <tr>
                <td class='text-center table-active'>
                オフィシャルURL
                </td>
                <td class='text-center'>
                    <a href='http://$sites->url' target='_blank'>$sites->url</a>
                </td>
              </tr>
              </table>";
              //   <tr>
              //     <td class='text-center table-active'>
              //     駐車場
              //     </td>
              //     <td class='text-center'>
              //       あり
              //     </td>
              //   </tr>
        }
        $html .="</div>
        </div>
        </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_access($detailData,$siteId,$mainColor)
    {
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "
		<section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
        <div class='card'>
        <div class='card-body'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='form-group'>
                <label for='$detailData->id-title' class='form-label'>タイトル</label>
                <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
            </div>
            <div class='form-group'>
                <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
                <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
            </div>
            <label class='form-label'>内容</label>
        
            <div class='col-12'>
                <div id='map' style='height: 200px; width: 100%;'></div>
            </div>
        </div>
        </div>
        </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_gallery($detailData,$siteId,$mainColor)
    {
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "
		<section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
            <div class='card'>
        <div class='card-body'>
            <div class='form-check mb-3'>
            <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
        </div>
        <div class='form-group'>
            <label for='$detailData->id-title' class='form-label'>タイトル</label>
            <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
        </div>
        <div class='form-group'>
            <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
            <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
        </div>
        <label class='form-label'>その他の内容</label>
        <textarea id='content-shop' name='$detailData->id-content'>$detailData->content</textarea>
        </div>
        </div>
        </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function event($detailData,$siteId,$mainColor)
    {
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "
		<section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
        <div class='form-check mb-3'>
            <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
        </div>
        <textarea id='content-event' name='$detailData->id-content'>$detailData->content</textarea>
        </div>
		</section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cast_news($detailData,$siteId,$mainColor)
    {
        $html = "";
        
        $castBlogs = D_Cast_Blog::fetchSiteIdLimitData([$siteId],6);
        
        $castBlogAryId = [];
        foreach($castBlogs as $index =>  $castBlog) {
            $castBlogAryId[] = $castBlog['id'];             
        }
        $formatBlogImages = [];
        if($castBlogAryId){
            $castBlogImages = D_Cast_Blog_Image::fetchFilterIdData($castBlogAryId);
            foreach($castBlogImages as $images) {
                $formatBlogImages[$images->article_id] = $images->image_url;
            }
        }
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .="
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='row my-3'>";
            // <a href="{{ route('site.detail.blog.detail',['category_id' => 3,'id' => $castBlog['id']]) }}" class="blog_card">
                foreach ($castBlogs as $castBlog){
                    $imageUrl = asset('storage/no-image.jpg');
                    if(isset($formatBlogImages[$castBlog['id']])) {
                        $imageUrl = asset('/storage' . $formatBlogImages[$castBlog['id']]);
                    }
                    if(isset($castImageAry[$castBlog['cast_id']])) {
                        $imageUrl = asset('/storage' . $castImageAry[$castBlog['cast_id']]);
                    }
                    $castName = $castBlog['source_name'];
                    if($castBlog['age']) {
                        $castName = $castBlog['source_name']."(". $castBlog['age'] .")";
                    }
                    // $html .="
                    // <div class='col-4 col-md-2'>
                    //     <div class='card px-0'>
                    //     <img class='pt-3 text-center' src='$imageUrl' class='card-img-top' style='height: 220px; width: auto; object-fit:contain' alt='...'>
                    //         <p class='card-text text-white text-center mb-1' style='background: $mainColor'>".$castBlog['source_name']."(".$castBlog['age'].")</p>
                    //         <div class='text-center' style='height: 5rem'>
                    //         <p class='pt-3'>".$castBlog['title']."</p>
                    //         </div>
                    //     </div>
                    // </div>";
                        // </a>
                        $html .= "
                        <div class='col-4 col-md-2 mb-3'>
                            <div class='image-container'>
                                <img src='$imageUrl'>
                                <div class='text-overlay'>
                                    <p class='cast-blog-title'>".$castBlog['title']."</p>
                                    <p class='cast-blog-name'>$castName</p>
                                    <p class='cast-blog-date'>". date('Y年m月d日  H:i',strtotime($castBlog['published_at'])) ."</p>
                                </div>
                            </div>
                        </div>";
                }
        $html .="</div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manager_news($detailData,$siteId,$mainColor)
    {
        $html = "";
        
        $shopManagerBlogs = D_Shop_Manager_Blog::fetchSiteIdLimitData([$siteId],3);
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .= "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='row my-3'>";
            foreach($shopManagerBlogs as $managerBlog){
                $html .= "
                <div class='col-12'>
                <ul>
                    <li class='list_content'>
                    <p class='mb-2 text-muted'>".date('Y年m月d日  H:i',strtotime($managerBlog['published_at'])). "</p>
                    <a class='list_title fw-bold'>".$managerBlog['title']."</a>
                    </li>
                </ul>
                </div>";
                    // <a href="{{ route('site.detail.blog.detail',['category_id' => 2,'id' => $managerBlog['id']]) }}" class="list_title fw-bold">{{ $managerBlog['title'] }}</a>
            }
            $html .= "</div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop_news($detailData,$siteId,$mainColor)
    {
        $html = "";
        
        $shopBlogs = D_Shop_Blog::fetchSiteIdLimitData([$siteId],3);
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html .= "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
            <div class='container'>
            <div class='form-check mb-3'>
                <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
            </div>
            <div class='row my-3'>";
            foreach($shopBlogs as $shopBlog){
                $html .= "
                <div class='col-12'>
                <ul>
                    <li class='list_content'>
                    <p class='mb-2 text-muted'>".date('Y年m月d日  H:i',strtotime($shopBlog['published_at'])). "</p>
                    <a class='list_title fw-bold'>".$shopBlog['title']."</a>
                    </li>
                </ul>
                </div>";
                    // <a href="{{ route('site.detail.blog.detail',['category_id' => 2,'id' => $managerBlog['id']]) }}" class="list_title fw-bold">{{ $managerBlog['title'] }}</a>
            }
            $html .= "</div>
            </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recruit($detailData,$siteId,$mainColor)
    {
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "
		<section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
        <div class='form-check mb-3'>
            <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
        </div>
        <textarea id='content-recruit' name='$detailData->id-content'>$detailData->content</textarea>
        </div>
		</section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_content($detailData,$siteId,$mainColor)
    {
        $isDisplay = $detailData->is_display == 1 ? 'checked' : '';
        $html = "
        <section id='$detailData->id' data-sort_no='$detailData->sort_no'>
        <div class='container'>
            <div class='card'>
        <div class='card-body'>
            <div class='form-check mb-3'>
            <input type='checkbox' class='form-check-input'  name='$detailData->id-is_display' $isDisplay>表示する</input>
        </div>
        <div class='form-group'>
            <label for='$detailData->id-title' class='form-label'>タイトル</label>
            <input type='text' class='form-control' name='$detailData->id-title' value='$detailData->title' id='$detailData->id-title' />
        </div>
        <div class='form-group'>
            <label for='$detailData->id-sub_title' class='form-label'>サブタイトル</label>
            <input type='text' class='form-control' name='$detailData->id-sub_title' value='$detailData->sub_title' id='$detailData->id-sub_title'/>
        </div>
        <label class='form-label'>その他の内容</label>
        <textarea class='add-content' name='$detailData->id-content'>$detailData->content</textarea>
        </div>
        <input type='hidden' name='$detailData->id-is_delete' id='$detailData->id-is_delete' value='0'/>
        <div class='text-center mb-3'>
            <button type='button' class='btn btn-danger delete-btn' data-event='$detailData->id'><i class='fas fa-trash'></i></button>
        </div>
        </div>
        </div>
        </section>";
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageUpload(Request $request)
    {
        $siteId = $request->site_id;
        $file = $request->file;
        $event = $request->event;
        //なってなくない？
        // $listPaths = [
        //     Admin::class => '/images/admin/' . $user->id,
        //     Cast::class  => '/images/cast/' . $user->id,
        //     User::class  => '/images/user/' . $user->id,
        // ];
        $fileExtension = $file->getClientOriginalExtension();
        $formatFile = ['jpeg','png','gif','jpg', 'bmp', 'webp', 'tiff'];

        if (!in_array(strtolower($fileExtension), $formatFile)) {
			return response()->json([
                'result' => 1,
                'message' => 'ファイルは形式が正しくありません。'
            ]);
        }
        $defaultPath = "cms/{$siteId}/{$event}";
        //なってなくない？
        $file->storeAs($defaultPath, time() . '_' . $file->getClientOriginalName(),'public');

        return response()->json(asset('/storage/'.$defaultPath.'/'. time() . '_' . $file->getClientOriginalName()));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function imageDelete(Request $request)
    {
        $imageUrl = mb_strstr($request->image_url,'storage');
        if (file_exists($imageUrl)) {
            unlink($imageUrl);
        }
        
        return response()->json('success');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function infoImageSort(Request $request)
    {
        $ids = explode(',',$request->id);
        if(count($ids) <= 0) {
            return response()->json([
                'result' => 1,
                'message' => '不正なパラメータです。'
            ]);
        }
        $sortNo = 1;
        foreach($ids as $i) {
            $id = str_replace('info-image-','',$i);
            if($id < 0) {
                continue;
            }
            Site_Image::findOrFail($id)->fill(['sort_no' => $sortNo,'updated_at' => time()])->save();
            $sortNo++;
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function infoImageDelete(Request $request)
    {
        $id = $request->id;
        if(empty($id)) {
            return response()->json([
                'result' => 1,
                'message' => '不正なパラメータです。'
            ]);
        }
        try {
            $siteImage = Site_Image::findOrFail($id);
            $siteImage->fill([
                'deleted_at' => time(),
                'sort_no' => 0,
                // 'image' => $image,
            ])->save();
    
            if (file_exists("storage".$siteImage->image)) {
                unlink("storage".$siteImage->image);
            }
        } catch(\Exception $e) {
            \Log::debug($e);
            return response()->json([
                'result' => 1,
                'message' => '処理が失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }
    /**
     * 
     *
     * @return \Illuminate\Http\Response
     * @return void
     */
    public function infoThumbnail(Request $request)
    {
        $siteId = $request->site_id;
        $file = $request->file;
        $previousUrl = app('url')->previous();
        try {
            if($file) {
                $siteData = Site_Info::fetchSiteData($siteId);
                $fileExtension = $file->getClientOriginalExtension();
                // $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $formatFile = ['jpeg','png','gif','jpg', 'bmp', 'webp', 'tiff'];
        
                if (!in_array(strtolower($fileExtension), $formatFile)) {
                    session()->flash('error', '画像形式が対応できないです。');
                    return redirect()->to($previousUrl);
                }
                $defaultPath = "cms/{$siteId}/thumbnail/";
                $time = time();
                $image = "/" . $defaultPath . $siteId . '_' . $time.'.'. $fileExtension;
                //なってなくない？
                $file->storeAs($defaultPath, $siteId . '_' . $time .'.'. $fileExtension,'public');

                $siteData->fill([
                    'updated_at' => $time,
                    'image' => $image
                ])->save();
            }
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);    
        }
        session()->flash('success', '登録に成功しました。');
        return redirect()->to($previousUrl);
    }
}
