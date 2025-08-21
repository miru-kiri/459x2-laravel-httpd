<?php

namespace App\Http\Controllers;

use App\Models\D_Movie;
use App\Models\M_Cast;
use App\Models\M_Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'source_name' => 'キャスト名',
            'title' => 'タイトル',
            // 'content' => '内容',
            // 'tag_name' => 'タグ名称',
            'display_name' => '表示',
            'url' => '動画用直URL',
            'iframe_url' => '外部SNS用貼り付けタグ',
            'action' => '',
        ];
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl); 
        $siteId = $request->site_id ?? 0;
        foreach($siteData as $data) {
            if(empty($siteId)) {
                $siteId = $data->id;
                break;
            }
        }
        $filter = [
            'site_id' => $siteId
        ];
        $fetchData = D_Movie::fetchFilterSiteIdData($filter);   
        $bodys = [];
        foreach($fetchData as $index => $value) {
            if($value['deleted_at'] != 0) {
                continue;
            }
            $url = asset('storage' . $value['file_path'] .'/' . $value['file_name'] . '.mp4');
            $bodys[] = [
                'id' => $value['id'],
                'site_name' => $value['site_name'],
                'source_name' => $value['source_name'] ?? 'フリー',
                'title' => $value['title'],
                'url' => $url,
                'iframe_url' => "<iframe id='mrtplayer' type='text/html' width='360' height='270' src='$url' frameborder='0' scrolling='no' allowfullscreen></iframe>",
                // 'content' => $value['content'],
                // 'tag_name' => $value['tag_name'],
                'display_name' => $value['is_display'] == 1 ? '公開中' : '非公開',
                'action' => "<button class='btn btn-warning edit_btn mr-1'  data-id='".$value['id']."'><i class='fas fa-edit'></i></button><button class='btn btn-danger delete_btn' data-id='".$value['id']."'><i class='fas fa-trash'></i></button>",
            ];
        }
        return view('admin.movie.index',compact('siteId','headers','siteData','bodys'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->id ?? 0;
        $siteId = 0;
        $castId = 0;
        $isDisplay = 0;
        $data = false;
        $siteControl = [];
        if(session('role') != 1) {
            $siteControl = session('site_control');
        }
        $explodeTagName = [];
        if(!empty($id)) {
            $data = D_Movie::findOrFail($id);
            // $data->tag_name = explode(',',$data->tag_name);
            $explodeTagName = explode(',',$data->tag_name);
            $siteId = $data->site_id;
            $castId = $data->cast_id;
            $isDisplay = $data->is_display;
            $siteData = M_Site::fetchFilterAryId($data->site_id); 
            $castData = M_Cast::fetchFilterAll(['site_id' => $data->site_id,'cast_id' => 0]);
        } else {
            $siteData = M_Site::fetchFilterAryId($siteControl); 
            $castData = M_Cast::fetchFilterAll(['site_id' => $siteControl, 'cast_id' => 0]);
        }
        $firstSiteId = 0;
        foreach($siteData as $index => $site) {
            if(empty($firstSiteId)) {
                $firstSiteId = $site->id;
            }
        }
        $formatCastData = [];
        foreach($castData as $cd) {
            $formatCastData[$cd->site_id][$cd->id] = $cd;
        }
        return view('admin.movie.detail',compact('id','siteId','castId','isDisplay','data','siteData','castData','formatCastData','firstSiteId','explodeTagName'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upsert(Request $request)
    {
        $id = $request->id;
        if(!empty($id)) {
            $request->validate([
                'id' => 'required',
                // 'site_id' => 'required',
            ]);
        } else {
            $request->validate([
                'id' => 'required',
                'site_id' => 'required',
            ]);
        }
        $castName = 'フリー';
        if(!empty($request->cast_id)) {
            $castData = M_Cast::findOrFail($request->cast_id);
            $castName = $castData->source_name;
        }
       ini_set('memory_limit', -1);
        $parameter = [
            'id' => $request->id ?? 0,
            'site_id' => $request->site_id ?? 0,
            'cast_id' => $request->cast_id ?? 0,
            'cast_name' => $castName,
            'title' => $request->title ?? null,
            'content' => $request->content ?? null,
            'tag_name' => $request->tag_name ?? null,
            'is_display' => $request->is_display ? 1 : 0,
        ];
        $video = $request->file('video');
        $previousUrl = app('url')->previous();
        try {
            if(!empty($id)) {
                $data = D_Movie::findOrFail($id);
                unset($parameter['id']);
                unset($parameter['site_id']);
                unset($parameter['cast_id']);
                unset($parameter['cast_name']);
                $parameter['updated_at'] = time();
                $data->fill($parameter)->save();
            } else {
				if(!$video) {
					session()->flash('error', '動画を添付してください。');
					return redirect()->to($previousUrl);
				}
				$maxSize = 1024 * 1024 * 1024; // 1GB (1,024MB)
				if ($video->getSize() > $maxSize) {
					session()->flash('error', 'ファイルサイズが1GBを超えています。');
					return redirect()->to($previousUrl);
				}
                unset($parameter['id']);
                $siteData = M_Site::findOrFail($request->site_id);
                $parameter['shop_id'] = $siteData->shop_id;
                $parameter['created_at'] = time();
                $lastId = D_Movie::max('id') + 1;
                $file_path = null;
                $file_name = null;
                if(empty($parameter['cast_id'])) {
                    $file_path = '/csm_movie/douga/'. $parameter['site_id'].'/free';
                    // $file_path = '/movie/'. $parameter['site_id'].'/free';
                    $file_name = $parameter['site_id']."_free_".$lastId;
                    $path = $parameter['site_id']."/free/".$parameter['site_id']."_free_".$lastId;
                } else {
                    $file_path = '/csm_movie/douga/'. $parameter['site_id']."/".$parameter['cast_id'];
                    // $file_path = '/movie/'. $parameter['site_id']."/".$parameter['cast_id'];
                    $file_name = $parameter['site_id']."_".$parameter['cast_id']."_".$lastId;
                    $path = $parameter['site_id']."/".$parameter['cast_id']."/".$parameter['site_id']."_".$parameter['cast_id']."_".$lastId;
                }
                $parameter['file_path'] = $file_path;
                $parameter['file_name'] = $file_name;
                // $videoPath = $request->file('video')->store($path); // 'videos'は保存先ディレクトリ
                $originalFilename = $video->getClientOriginalName();
                // 入力ファイルパス
                // 一時的なファイルをFFmpegがアクセスできる場所に移動
                $tempPath = $video->getRealPath();
                $newPath = storage_path('app/public/tmp/' . $originalFilename);
                $directoryPath = storage_path('app/public/tmp/'); // 作成したいディレクトリのパス
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0755, true); // 第三引数の true は親ディレクトリを作成するためのオプション
                }
                //move_uploaded_file($tempPath, $newPath);
				// ファイルの移動
			    $video->move($directoryPath, $originalFilename);
                // $media = \FFMpeg::fromDisk('tmp_videos')->open($originalFilename);
                $media = \FFMpeg::fromDisk('tmp_videos')->open($originalFilename);
                $time = $media->getDurationInSeconds();
                if(!empty($time)) {
                    $time = floor($time / 60);
                }
                $parameter['time'] = $time;
        
                // 2. 動画を保存する
                $media->addFilter(function ($filters) {
                    $filters->resize(new \FFMpeg\Coordinate\Dimension(720, 480));
                })
                ->export()
                ->toDisk('videos')
				->inFormat(
                    (new \FFMpeg\Format\Video\X264('aac'))
                        ->setKiloBitrate(500) // ビデオビットレートを500kbpsに設定
                        ->setAudioKiloBitrate(128) // オーディオビットレートを128kbpsに設定
                )
                ->save($path.'.mp4');
        
                // // 元の動画を削除する場合は以下を実行
                \Storage::delete($newPath);
                D_Movie::insert($parameter);
            }
        } catch (\Exception $e) {
			\Log::debug($e);
            $previousUrl = app('url')->previous();
            session()->flash('error', '処理に失敗しました。');
            return redirect()->to($previousUrl);
        }
        $previousUrl = app('url')->previous();
        session()->flash('success', '処理に成功しました。');
        return redirect()->route('movie');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        $previousUrl = app('url')->previous();
        if(empty($id)) {
            session()->flash('error', '処理に失敗しました。');
            return redirect()->to($previousUrl);
        }
        $data = D_Movie::findOrFail($id);
        $file_path = $data->file_path;
        $file_name = $data->file_name;
        Storage::delete('public/'.$file_path . '/'.$file_name . '.mp4');
        $data->fill(['updated_at' => time(),'deleted_at' => 1])->save();
        $previousUrl = app('url')->previous();
        session()->flash('success', '処理に成功しました。');
        return redirect()->route('movie');
    }
    //2025_6_10 krn all_movies 追加
    /*
    public function allMovies()
    {
        // 公開中で削除されていない動画を取得（最新順）
        $movieData = \App\Models\D_Movie::where('is_display', 1)
            ->where('deleted_at', 0)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('sitePages.all_movies', compact('movieData'));
    }
    */

}
