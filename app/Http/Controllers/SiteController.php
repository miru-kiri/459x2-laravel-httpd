<?php

namespace App\Http\Controllers;

use App\Models\Site_Image;
use App\Models\Site_Info;
use Illuminate\Http\Request;

class SiteController extends Controller
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
     * サイト画像の登録
     *
     * @param Request $request
     * @return void
     */
    public function createImage(Request $request)
    {
        $siteId = $request->site_id;
        $file = $request->file;
        $title = $request->title ?? NULL;
        $content = $request->content ?? NULL;
        $published_at = $request->published_at ?? date('Y/m/d H:i');
        $previousUrl = app('url')->previous();
        $request->session()->flash('_old_input', [
            'title' => $title,
            'content' => $content,
            'published_at' => $published_at,
        ]);
        try {
            if ($file) {
                $isSiteImageData = Site_Image::fetchSiteCategoryData($siteId, 2);
                $sortNo = 0;
                if ($isSiteImageData->isNotEmpty()) {
                    $sortNo = Site_Image::filterSiteMaxSortNo($siteId, 2);
                }
                $fileExtension = $file->getClientOriginalExtension();
                // $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $formatFile = ['jpeg', 'png', 'gif', 'jpg', 'bmp', 'webp', 'tiff'];

                if (!in_array(strtolower($fileExtension), $formatFile)) {
                    session()->flash('error', '画像形式が対応できないです。');
                    return redirect()->to($previousUrl);
                }
                $defaultPath = "__image__/site/{$siteId}/";
                $time = time();
                $image = "/" . $defaultPath . $siteId . '_' . $time . '.' . $fileExtension;
                //なってなくない？
                $file->storeAs($defaultPath, $siteId . '_' . $time . '.' . $fileExtension, 'public');
                $parameter = [
                    'created_at' => time(),
                    'site_id' => $siteId,
                    'category_id' => 2,
                    'image' => $image,
                    'url' => env('APP_URL') . '/storage' . $image,
                    'sort_no' => $sortNo + 1,
                ];
                Site_Image::insert($parameter);
            }
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('success', '登録に成功しました。');
        return redirect()->to($previousUrl);
    }
    /**
     * サイト画像の登録
     *
     * @param Request $request
     * @return void
     */
    public function deleteImage(Request $request)
    {
        $idAry = $request->id;
        $idAry = json_decode($idAry, true);
        $title = $request->title ?? NULL;
        $content = $request->content ?? NULL;
        $published_at = $request->published_at ?? date('Y/m/d H:i');
        $previousUrl = app('url')->previous();
        $request->session()->flash('_old_input', [
            'title' => $title,
            'content' => $content,
            'published_at' => $published_at,
        ]);
        try {
            \DB::beginTransaction();
            foreach ($idAry as $id) {
                $siteImage = Site_Image::findOrFail($id);
                // ブログに貼り付けたのもなくなるから一旦
                // if (file_exists($siteImage->url)) {
                //     unlink($siteImage->url);
                // }
                $siteImage->fill(['deleted_at' => time()])->save();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::debug($e->getMessage());
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
            // return response()->json([
            //     'result' => 1,
            //     'message' => '処理に失敗しました。'
            // ]);
        }
        session()->flash('success', '登録に成功しました。');
        return redirect()->to($previousUrl);
        // return response()->json([
        //     'result' => 0,
        //     'message' => '処理が成功しました。'
        // ]);
    }
}
