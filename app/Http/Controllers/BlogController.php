<?php

namespace App\Http\Controllers;

use App\Mail\BlogTransferMail;
use App\Models\D_Cast_Blog;
use App\Models\D_Cast_Blog_Image;
use App\Models\D_Shop_Blog;
use App\Models\D_Shop_Manager_Blog;
use App\Models\M_Admin;
use App\Models\M_Cast;
use App\Models\M_Site;
use App\Models\X459x_Shop_Blog;
use App\Models\X459x_Shop_Manager_Blog;
use App\Models\Site_Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\TemplateRequest;
use App\Models\Template;

class BlogController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->shopMessageCreateParameter = config("parameter.blog.shop.create");
        $this->shopManagerMessageCreateParameter = config("parameter.blog.shop.manager.create");
        $this->castMessageCreateParameter = config("parameter.blog.cast.create");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop(Request $request)
    {
        //
        $id = 0;
        $siteId = $request->site_id ?? 0;
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'title' => 'タイトル',
            'published_at' => '公開日時',
            'delete' => '',
        ];
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        // $siteData = M_Site::fetchAll();
        $castData = M_Cast::fetchAll();
        return view('admin.blog.shop_list', compact('id', 'siteId', 'headers', 'siteData', 'siteData', 'castData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchFilteringShopData(Request $request)
    {
		ini_set('memory_limit', -1);
        $siteId = $request->site_id ?? 0;
        if (session('role') != 1 && $siteId == 0) {
            $siteId = session('site_control');
        }
        $filter = [
            'site_id' => $siteId,
        ];
        $data = D_Shop_Blog::fetchFilterSiteIdData($filter);
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopMessage(Request $request)
    {
        $blogId = $request->id;
        $title = "";
        $content = "";
        $publishedAt = date('Y/m/d H:i');
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        //編集の場合
        $siteId = $request->site_id ?? 0;
        $blogData = [];
        if (!empty($blogId)) {
            $blogData = D_Shop_Blog::findOrFail($blogId);
            $siteId = $blogData->site_id;
            $title = $blogData->title;
            $content = $blogData->content;
            $publishedAt = $blogData->published_at;
        }
        if (old('title')) {
            $title = old('title') ?? NUll;
        }
        if (old('content')) {
            $content = old('content') ?? NUll;
        }
        if (old('published_at')) {
            $publishedAt = old('published_at');
        }
        if (empty($siteId)) {
            foreach ($siteData as $site) {
                $siteId = $site->id;
                break;
            }
        }
        $siteImages = Site_Image::fetchSiteCategoryData($siteId, 2);
        $formatImageAry = [];
        $loop = 0;
        $loopIndex = 1;
        foreach ($siteImages as $siteImage) {
            $formatImageAry[$loopIndex][] = $siteImage;
            if ($loop == 5) {
                $loopIndex++;
                $loop = 0;
            } else {
                $loop++;
            }
        }
        $templates = Template::where('type', 3)->where('user_id', session('id'))->get();
        return view('admin.blog.shop_message', compact('blogId', 'siteId', 'siteData', 'blogData', 'siteImages', 'formatImageAry', 'title', 'content', 'publishedAt', 'templates'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopMessageImageUpload(Request $request)
    {
        $siteId = $request->site_id;
        $file = $request->file;

        $fileExtension = $file->getClientOriginalExtension();
        $formatFile = ['jpeg', 'png', 'gif', 'jpg', 'bmp', 'webp', 'tiff'];

        if (!in_array(strtolower($fileExtension), $formatFile)) {
			return response()->json([
                'result' => 1,
                'message' => 'ファイルは形式が正しくありません。'
            ]);
        }
        try {
            $defaultPath = "shop/articles/{$siteId}/images";
            // $file->storeAs($defaultPath, time() . '_' . $file->getClientOriginalName(),'public');
            $path = $defaultPath . '/' . time() . '_' . $file->getClientOriginalName(); // 圧縮された画像を保存するパス
            $filePath = $file->path(); // もしくは $file->getRealPath();
            $fileSize = filesize($filePath);

            $image = \Image::make($file)->orientate(); // Exifデータを読み取り画像の向きを修正
            if ($fileExtension == 'gif') {
                \Storage::disk('public')->put($path, file_get_contents($file)); // 画像をストレージに保存
            } else {
                //リサイズしたいなら
                $image->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if ($fileSize > 1000000) { // 1MBより大きい場合100 * 
                    // JPEGファイルの場合の追加処理
                    if (strtolower($fileExtension) === 'jpg' || strtolower($fileExtension) === 'jpeg') {
                        $image->encode('jpg', 50); // JPEGフォーマットで70%の品質でエンコード
                    } else {
                        $image->encode($fileExtension, 50); // その他のフォーマットでエンコード
                    }
                } else {
                    $image->encode($fileExtension); // その他のフォーマットでエンコード
                }
                \Storage::disk('public')->put($path, (string)$image); // 画像をストレージに保存
            }
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json([
                'result' => 1,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json(asset('/storage/' . $path));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopMessageImageDelete(Request $request)
    {
        //更新
        // $id = $request->id;
        $imageUrl = mb_strstr($request->image_url, 'storage');
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
    public function shopMessageCreate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $parameter = $request->only($this->shopMessageCreateParameter);
        $blogId = $parameter['id'];
        try {
            $oldParameter = [
                "fd1" => $parameter['site_id'],
                "fd2" => "site_news_all",
                "fd3" => $parameter['published_at'] ? date('Y-m-d H:i:s',strtotime($parameter['published_at'])) : NULL,
                "fd4" => $parameter['title'],
                "fd5" => $parameter['content'],
                "fd29" => time()
            ];
            \DB::beginTransaction();
            if ($parameter['id'] > 0) {
                //更新
                D_Shop_Blog::saveData($parameter);
                X459x_Shop_Blog::where('kjid', $parameter['id'])->update($oldParameter);
            } else {
                unset($parameter['id']);
                $parameter['created_at'] = time();
                $parameter['updated_at'] = time();
                $parameter['delete_flg'] = 0;
                $blogId = D_Shop_Blog::insertGetId($parameter);
                unset($parameter['id']);
                $oldParameter['kjid'] = $blogId;
                $oldParameter['fd30'] = time();
                $oldParameter['fd28'] = 'none';
                X459x_Shop_Blog::insert($oldParameter);
            }
            if ($request->hasFile('files')) {
                // $files = $request->file('files');
                // $imagePrameter = [];
                // D_Cast_Blog_Image::where(['article_id' => $blogId])->update(['deleted_at'=> now()]);
                // foreach ($files as $file) {
                //     $mimeType = $file->getClientMimeType();

                //     if (strstr($mimeType, "image/")) {
                //         $rootFolder = "articles/$blogId/images";
                //     }

                //     $path = $file->storeAs($rootFolder,$file->getClientOriginalName(),'public');

                //     if (strstr($mimeType, "image/")) {
                //         $imagePrameter[] = [
                //             'created_at' => now(),
                //             'article_id' => $blogId,
                //             'image_url' => "/".$path,
                //         ];
                //     }
                // }
                // if($imagePrameter) {
                //     D_Cast_Blog_Image::insert($imagePrameter);
                // }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e);
            $previousUrl = app('url')->previous();
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
            // return $e->getMessage();
        }
        // , ['site_id' => $parameter['site_id']]
        session()->flash('success', '登録に成功しました。');
        return redirect()->route('blog.shop');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function blogDelete(Request $request)
    {
        $blogId = $request->id;
        $categoryId = $request->category_id;
        $previousUrl = app('url')->previous();
        try {
            //ショップニュースの削除
            if ($categoryId == 1) {
                $blogData = D_Shop_Blog::findOrFail($blogId);
                $blogData->fill(['delete_flg' => 1, 'updated_at' => time()])->save();
                X459x_Shop_Blog::where('kjid', $blogId)->update(['fd28' => 'del']);
                \Storage::deleteDirectory("public/shop/articles/$blogData->site_id/images");
                // \Storage::deleteDirectory("public/shop/articles/$blogId/images");
                session()->flash('success', '削除に成功しました。');
                return redirect()->route('blog.shop');
            }
            if ($categoryId == 2) {
                $blogData = D_Shop_Manager_Blog::findOrFail($blogId);
                $blogData->fill(['delete_flg' => 1, 'updated_at' => time()])->save();
                X459x_Shop_Manager_Blog::where('sibid', $blogData->old_id)->update(['fd31' => 'del', 'fd32' => time()]);
                \Storage::deleteDirectory("public/shop_manager/articles/$blogData->site_id/images");
                // \Storage::deleteDirectory("public/shop_manager/articles/$blogId/images");
                session()->flash('success', '削除に成功しました。');
                return redirect()->route('blog.shop.manager');
            }
            if ($categoryId == 3) {
                $blogData = D_Cast_Blog::findOrFail($blogId);
                $blogData->fill(['deleted_at' => now(), 'updated_at' => now()])->save();
                \Storage::deleteDirectory("public/articles/$blogId/images");
                \Storage::deleteDirectory("public/articles/content/$blogData->cast_id/images");
                session()->flash('success', '削除に成功しました。');
                return redirect()->route('blog.cast');
            }
        } catch (\Exception $e) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->route('blog.cast');
        }
        session()->flash('error', '処理に失敗しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopManager(Request $request)
    {
        //
        $id = 0;
        $siteId = $request->site_id ?? 0;
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'title' => 'タイトル',
            'published_at' => '公開日時',
            'delete' => '',
        ];
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        // $siteData = M_Site::fetchAll();
        $castData = M_Cast::fetchAll();
        return view('admin.blog.shop_manager_list', compact('id', 'siteId', 'headers', 'siteData', 'siteData', 'castData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchFilteringShopManagerData(Request $request)
    {
		ini_set('memory_limit', -1);
        $siteId = $request->site_id ?? 0;
        if (session('role') != 1 && $siteId == 0) {
            $siteId = session('site_control');
        }
        $filter = [
            // 'site_id' => $request->site_id ?? [],
            'site_id' => $siteId,
        ];
        $data = D_Shop_Manager_Blog::fetchFilterSiteIdData($filter);
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopManagerMessage(Request $request)
    {
        $blogId = $request->id;
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        // $castData = M_Cast::fetchAll();
        //編集の場合
        $siteId = 0;
        $blogData = [];
        if (!empty($blogId)) {
            $blogData = D_Shop_Manager_Blog::findOrFail($blogId);
            $siteId = $blogData->site_id;
            // $blogData->image = D_Cast_Blog_Image::fetchFilterIdData($blogId);
        } else {
            $siteId = $request->site_id ?? 0;
        }
        if (empty($siteId)) {
            foreach ($siteData as $site) {
                $siteId = $site->id;
                break;
            }
        }
        $siteImages = Site_Image::fetchSiteCategoryData($siteId, 2);
        $formatImageAry = [];
        $loop = 0;
        $loopIndex = 1;
        foreach ($siteImages as $siteImage) {
            $formatImageAry[$loopIndex][] = $siteImage;
            if ($loop == 5) {
                $loopIndex++;
                $loop = 0;
            } else {
                $loop++;
            }
        }
        $templates = Template::where('type', 2)->where('user_id', session('id'))->get();

        return view('admin.blog.shop_manager_message', compact('blogId', 'siteId', 'siteData', 'blogData', 'formatImageAry', 'templates'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopManagerMessageImageUpload(Request $request)
    {
        $siteId = $request->site_id;
        $file = $request->file;
        $fileExtension = $file->getClientOriginalExtension();
        $formatFile = ['jpeg', 'png', 'gif', 'jpg', 'bmp', 'webp', 'tiff'];

        if (!in_array(strtolower($fileExtension), $formatFile)) {
			return response()->json([
                'result' => 1,
                'message' => 'ファイルは形式が正しくありません。'
            ]);
        }
        $defaultPath = "shop_manager/articles/{$siteId}/images";

        $path = $defaultPath . '/' . time() . '_' . $file->getClientOriginalName(); // 圧縮された画像を保存するパス
        try {
            $filePath = $file->path(); // もしくは $file->getRealPath();
            $fileSize = filesize($filePath);
            if ($fileExtension == 'gif') {
                \Storage::disk('public')->put($path, file_get_contents($file)); // 画像をストレージに保存
            } else {
                $image = \Image::make($file)->orientate(); // Exifデータを読み取り画像の向きを修正
                $image->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if ($fileSize > 1000000) { // 1MBより大きい場合100 * 
                    // JPEGファイルの場合の追加処理
                    if (strtolower($fileExtension) === 'jpg' || strtolower($fileExtension) === 'jpeg') {
                        $image->encode('jpg', 50); // JPEGフォーマットで70%の品質でエンコード
                    } else {
                        $image->encode($fileExtension, 50); // その他のフォーマットでエンコード
                    }
                } else {
                    $image->encode($fileExtension); // その他のフォーマットでエンコード
                }
                \Storage::disk('public')->put($path, (string)$image); // 画像をストレージに保存
            }
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json([
                'result' => 1,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json(asset('/storage/' . $path));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopManagerMessageImageDelete(Request $request)
    {
        //更新
        // $id = $request->id;
        $imageUrl = mb_strstr($request->image_url, 'storage');
        if (file_exists($imageUrl)) {
            unlink($imageUrl);
            echo "画像を削除しました。";
        }

        return response()->json('success');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopManagerMessageCreate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $parameter = $request->only($this->shopManagerMessageCreateParameter);
        $blogId = $parameter['id'];
        try {

            \DB::beginTransaction();
            $oldParameter = [
                // "sibid" => $request->old_id,
                "fd1" => $parameter['site_id'],
                "fd4" => $parameter['published_at'] ? date('Y-m-d H:i:s',strtotime($parameter['published_at'])) : NULL,
                "fd5" => $parameter['title'],
                "fd6" => $parameter['content'],
                "fd7" => strip_tags($parameter['content']),
                "fd8" => $parameter['content'],
                "fd14" => 'pc',
                "fd15" => 'shop',
                "fd32" => time(),
                "fd31" => 'none',
            ];
            $parameter['content2'] = strip_tags($parameter['content']);
            $parameter['old_category'] = "pc";
            $parameter['category_name'] = "shop";
            if ($parameter['id'] > 0) {
                //更新
                D_Shop_Manager_Blog::findOrFail($parameter['id'])->fill($parameter)->save();
                // $oldParameter["fd2"] = $request->old_kiji_id;
                // $oldParameter["fd3"] = $adminData->login_id;//admin.login_id
                X459x_Shop_Manager_Blog::where('sibid', $request->old_id)->update($oldParameter);
            } else {
                unset($parameter['id']);
                $parameter['created_at'] = time();
                $parameter['updated_at'] = time();
                $parameter['delete_flg'] = 0;
                $lastId = X459x_Shop_Manager_Blog::max('fd2');
                $lastId  = $lastId + 1;
                $adminData = M_Admin::findOrFail(session('id'));
                $oldParameter["sibid"] = $parameter['site_id'] . '_' . $lastId;
                $oldParameter["fd2"] = $lastId;
                $oldParameter["fd3"] = $adminData->login_id; //admin.login_id
                X459x_Shop_Manager_Blog::insert($oldParameter);
                $parameter['old_id'] = $parameter['site_id'] . '_' . $lastId;
                $parameter['old_kiji_id'] = $lastId;
                $parameter['mail'] = $adminData->login_id;
                D_Shop_Manager_Blog::insert($parameter);
            }
            if ($request->hasFile('files')) {
                // $files = $request->file('files');
                // $imagePrameter = [];
                // D_Cast_Blog_Image::where(['article_id' => $blogId])->update(['deleted_at'=> now()]);
                // foreach ($files as $file) {
                //     $mimeType = $file->getClientMimeType();

                //     if (strstr($mimeType, "image/")) {
                //         $rootFolder = "articles/$blogId/images";
                //     }

                //     $path = $file->storeAs($rootFolder,$file->getClientOriginalName(),'public');

                //     if (strstr($mimeType, "image/")) {
                //         $imagePrameter[] = [
                //             'created_at' => now(),
                //             'article_id' => $blogId,
                //             'image_url' => "/".$path,
                //         ];
                //     }
                // }
                // if($imagePrameter) {
                //     D_Cast_Blog_Image::insert($imagePrameter);
                // }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            $previousUrl = app('url')->previous();
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
            // return $e->getMessage();
        }

        session()->flash('success', '登録に成功しました。');
        return redirect()->route('blog.shop.manager');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cast(Request $request)
    {
        $id = 0;
        $siteId = $request->site_id ?? 0;
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'source_name' => 'キャスト名',
            'title' => 'タイトル',
            'is_public' => '公開状況',
            'published_at' => '公開日時',
            'type' => '送信元',
            'is_image' => '添付',
            'delete' => '',
        ];
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $siteData = M_Site::fetchFilterAryId($siteControl);
        // $siteData = M_Site::fetchAll();
        $castData = M_Cast::fetchAll();
        return view('admin.blog.cast_list', compact('id', 'siteId', 'headers', 'siteData', 'siteData', 'castData'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchFilteringCastData(Request $request)
    {
		ini_set('memory_limit', -1);
        $filter = [
            'site_id' => $request->site_id ?? 1,
            // 'site_id' => $request->site_id ??],
            'cast_id' => session('role') == 0 ? session('id') : 0,
        ];

        if (session('role') != 0) {
            $castIdAry = M_Cast::fetchFilterIdAry(['site_id' => $filter['site_id']]);
            if ($castIdAry) {
                $filter['cast_id'] = $castIdAry;
            }
        }

        $datas = D_Cast_Blog::filteringMultiSiteData($filter);
        $blogIdAry = [];
        foreach ($datas as $data) {
            $blogIdAry[] = $data->id;
        }
        $blogImageDatas = [];
        if ($blogIdAry) {
            $blogImageDatas = D_Cast_Blog_Image::fetchFilterIdData($blogIdAry);
        }
        $formatBlogImageData = [];
        foreach ($blogImageDatas as $blogImageData) {
            $formatBlogImageData[$blogImageData->article_id] = $blogImageData->image_url;
        }
        foreach ($datas as $index => $d) {
            $datas[$index]->is_image = 0;
            $datas[$index]->is_public = 0;
            if (isset($formatBlogImageData[$d->id])) {
                $datas[$index]->is_image = 1;
            }
            $targetDate = new \DateTime($d->published_at);
            $currentDate = new \DateTime(); // 現在の日時を取得
            if ($targetDate <= $currentDate) {
                $datas[$index]->is_public = 1;
            }
        }
        return response()->json($datas, 200, [], JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessage(Request $request)
    {
        $blogId = $request->id;
        $siteControl = [];
        if (session('role') != 1) {
            $siteControl = session('site_control');
        }
        $filter = [
            'site_id' => $siteControl,
            'cast_id' => session('role') == 0 ? session('id') : 0,
        ];
        $castData = M_Cast::fetchFilterAll($filter);
        //編集の場合
        $castId = 0;
        $blogData = [];
        if ($blogId > 0) {
            $blogData = D_Cast_Blog::findOrFail($blogId);
            $castId = $blogData->cast_id;
            $blogData->image = D_Cast_Blog_Image::fetchFilterIdData($blogId);
        }
        $templates = Template::where('type', 1)->where('user_id', session('id'))->get();
        return view('admin.blog.cast_message', compact('blogId', 'castId', 'castData', 'blogData', 'templates'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessageImageUpload(Request $request)
    {
        $castId = $request->cast_id;
        $file = $request->file;

        $fileExtension = $file->getClientOriginalExtension();
        $formatFile = ['jpeg', 'png', 'gif', 'jpg', 'bmp', 'webp', 'tiff'];

        if (!in_array(strtolower($fileExtension), $formatFile)) {
			return response()->json([
                'result' => 1,
                'message' => 'ファイルは形式が正しくありません。'
            ]);
        }
        $defaultPath = "articles/content/$castId/";
        try {
            $filename = time() . "." . $fileExtension; // 圧縮された画像を保存するパス
            $filePath = $file->path(); // もしくは $file->getRealPath();
            $fileSize = filesize($filePath);
            if ($fileExtension == 'gif') {
                \Storage::disk('public')->put($defaultPath . $filename, file_get_contents($file)); // 画像をストレージに保存
            } else {
                $image = \Image::make($file)->orientate(); // Exifデータを読み取り画像の向きを修正
                $image->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
                if ($fileSize > 1000000) { // 1MBより大きい場合100 * 
                    // JPEGファイルの場合の追加処理
                    if (strtolower($fileExtension) === 'jpg' || strtolower($fileExtension) === 'jpeg') {
                        $image->encode('jpg', 50); // JPEGフォーマットで70%の品質でエンコード
                    } else {
                        $image->encode($fileExtension, 50); // その他のフォーマットでエンコード
                    }
                } else {
                    $image->encode($fileExtension); // その他のフォーマットでエンコード
                }
                \Storage::disk('public')->put($defaultPath . $filename, (string)$image);
            }
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json([
                'result' => 1,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json(asset('/storage/' . $defaultPath . $filename));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessageImageDelete(Request $request)
    {
        //更新
        // $id = $request->id;
        $imageUrl = mb_strstr($request->image_url, 'storage');
        if (file_exists($imageUrl)) {
            unlink($imageUrl);
            echo "画像を削除しました。";
        }

        return response()->json('success');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castMessageCreate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $parameter = $request->only($this->castMessageCreateParameter);
        $blogId = $parameter['id'];
        try {
            \DB::beginTransaction();
            if ($parameter['id'] > 0) {
                //更新
                D_Cast_Blog::saveData($parameter);
            } else {
                unset($parameter['id']);
                $parameter['created_at'] = now();
                $parameter['updated_at'] = now();
                $blogId = D_Cast_Blog::insertGetId($parameter);
            }
            $path = "";
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $imagePrameter = [];
                $rootFolder = "";
                $blogImageData = D_Cast_Blog_Image::fetchFilterIdFirstData($blogId);
                if ($blogImageData) {
                    D_Cast_Blog_Image::where(['article_id' => $blogId])->update(['deleted_at' => now()]);
                }
                foreach ($files as $file) {
                    $mimeType = $file->getClientMimeType();
                    $fileExtension = $file->getClientOriginalExtension();
                    if (strstr($mimeType, "image/")) {
                        $rootFolder = "articles/$blogId/images";
                    }
                    // $path = $file->storeAs($rootFolder,$file->getClientOriginalName(),'public');

                    if (strstr($mimeType, "image/")) {
                        if (empty($rootFolder)) {
                            continue;
                        }
                        $path = $rootFolder . '/' . time() . '_' . $file->getClientOriginalName(); // 圧縮された画像を保存するパス
                        $filePath = $file->path(); // もしくは $file->getRealPath();
                        $fileSize = filesize($filePath);
                        if ($fileExtension == 'gif') {
                            \Storage::disk('public')->put($path, file_get_contents($file)); // 画像をストレージに保存
                        } else {
                            $image = \Image::make($file)->orientate(); // Exifデータを読み取り画像の向きを修正
                            $image->resize(null, 800, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            if ($fileSize > 1000000) { // 1MBより大きい場合100 * 
                                // JPEGファイルの場合の追加処理
                                if (strtolower($fileExtension) === 'jpg' || strtolower($fileExtension) === 'jpeg') {
                                    $image->encode('jpg', 50); // JPEGフォーマットで70%の品質でエンコード
                                } else {
                                    $image->encode($fileExtension, 50); // その他のフォーマットでエンコード
                                }
                            } else {
                                $image->encode($fileExtension); // その他のフォーマットでエンコード
                            }
                            \Storage::disk('public')->put($path, (string)$image); // 画像をストレージに保存
                        }

                        $imagePrameter[] = [
                            'created_at' => now(),
                            'article_id' => $blogId,
                            'image_url' => "/" . $path,
                        ];
                    }
                }
                if ($imagePrameter) {
                    D_Cast_Blog_Image::insert($imagePrameter);
                }
            }
            //転送メール設定
            $castData = M_Cast::findOrFail($parameter['cast_id']);
            if ($castData->transfer_mail) {
                $formatMail = str_replace(array("\r\n", "\r"), "\n", $castData->transfer_mail);
                $explodeMails = explode("\n", $formatMail);
                if ($explodeMails) {
                    foreach ($explodeMails as $explodeMail) {
                        if (empty($explodeMail)) {
                            continue;
                        }
                        //メール送信
                        Mail::to($explodeMail)->send(new BlogTransferMail($parameter['title'], $parameter['content'], $castData->post_email, $path));
                    }
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \Log::debug($e);
            \DB::rollback();
            $previousUrl = app('url')->previous();
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
            // return $e->getMessage();
        }
        // , ['site_id' => $parameter['site_id']]
        session()->flash('success', '登録に成功しました。');
        return redirect()->route('blog.cast');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shopCheckDelete(Request $request)
    {
        $idAry = $request->id;
        try {
            \DB::beginTransaction();
            foreach ($idAry as $id) {
                $blogData = D_Shop_Blog::findOrFail($id);
                \Storage::deleteDirectory("public/shop/articles/$blogData->site_id/images");
                $blogData->fill(['delete_flg' => 1])->save();
                X459x_Shop_Blog::where('kjid', $id)->update(['fd28' => 'del', 'fd30' => time()]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
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
    public function shopManagerCheckDelete(Request $request)
    {
        $idAry = $request->id;
        try {
            \DB::beginTransaction();
            foreach ($idAry as $id) {
                $blogData = D_Shop_Manager_Blog::findOrFail($id);
                X459x_Shop_Manager_Blog::where('sibid', $blogData->old_id)->update(['fd31' => 'del', 'fd33' => time()]);
                \Storage::deleteDirectory("public/shop_manager/articles/$blogData->site_id/images");
                $blogData->fill(['delete_flg' => 1])->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
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
    public function castCheckDelete(Request $request)
    {
        $idAry = $request->id;
        try {
            \DB::beginTransaction();
            foreach ($idAry as $id) {
                $blogData = D_Cast_Blog::findOrFail($id);
                \Storage::deleteDirectory("public/articles/content/$blogData->cast_id/images");
                \Storage::deleteDirectory("public/articles/$id/images");
                $blogData->fill(['deleted_at' => now()])->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'result' => 1,
                'message' => '処理に失敗しました。'
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => '処理が成功しました。'
        ]);
    }

    /**
     * Show list template
     *
     * @return void
     */
    public function template()
    {
        $user_id = session('id');
        $templates = Template::where('user_id', $user_id)->orderBy('id', 'desc')->paginate(5);
        return view('admin.blog.template_list')->with('templates', $templates);
    }

    /**
     * Store template
     *
     * @param Request $request
     * @return void
     */
    public function storeTemplate(TemplateRequest $request)
    {
        $adminData = M_Admin::findOrFail(session('id'));
        Template::create(array_merge($request->validated(), [
            'user_id' => $adminData->id,
        ]));
        return redirect()->back()->with('success', 'テンプレートが正常に作成されました');
    }

    /**
     * Edit template
     *
     * @param [type] $id
     * @return void
     */
    public function editTemplate($id)
    {
        $user_id = session('id');
        $template = Template::where('id', $id)->where('user_id', $user_id)->first();

        if (!$template) {
            return redirect()->back()->with('error', 'テンプレートが存在しません。');
        }

        return view('admin.blog.template_edit', compact('template'));
    }

    /**
     * Update template
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function updateTemplate(TemplateRequest $request, $id)
    {
        $user_id = session('id');
        $template = Template::where('id', $id)->where('user_id', $user_id)->first();

        if (!$template) {
            return redirect()->back()->with('error', 'テンプレートが存在しません。');
        }

        $template->update([
            'title'   => $request->title,
            'content' => $request->content,
            'type'    => $request->type,
        ]);

        return redirect()->route('blog.template.list')->with('success', 'テンプレートが更新されました。');
    }

    /**
     * Delete template
     *
     * @param [type] $id
     * @return void
     */
    public function deleteTemplate($id)
    {
        $user_id = session('id');
        $template = Template::where('id', $id)->where('user_id', $user_id)->first();

        if (!$template) {
            return redirect()->back()->with('error', 'テンプレートが存在しません。');
        }

        $template->delete();

        return redirect()->back()->with('success', 'テンプレートは削除されました。');
    }

    /**
     * Use template
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function useTemplate($id)
    {
        $template = Template::find($id);

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'テンプレートが存在しません。',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $template
        ]);
    }

}
