<?php

namespace App\Http\Controllers;

use App\Models\D_Review;
use App\Models\D_User;
use App\Models\M_Cast;
use App\Models\Review_Criterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = '口コミ管理';
        $siteId = $request->site_id ?? 0;
        if(session('role') != 1 && $siteId == 0) {
            $siteId = session('site_control');
        }
        $filter = [
            // 'site_id' => $request->site_id ?? [],
            'site_id' => $siteId,
        ];
        $headers = [
            'id' => 'ID',
            'site_name' => 'サイト名',
            'user_name' => 'ユーザー名',
            'source_name' => '源氏名',
            'title' => 'タイトル',
            'average' => '平均点',
            'time_play' => '投稿日',
            'display_name' => '表示',
            'action' => '',
        ];
        $fetchData = D_Review::fetchFilterData($filter)->toArray();
        $bodys = [];
        foreach($fetchData as $index => $value) {
            $bodys[$index] = $value;
            $bodys[$index]['display_name'] = $value['display'] == 1 ? '公開中' : '非公開';
            $bodys[$index]['average'] = Review_Criterial::fetchAverage($value['id']);
            $bodys[$index]['action'] = "<button class='btn btn-warning edit_btn mr-1'  data-id='".$value['id']."'><i class='fas fa-edit'></i></button><button class='btn btn-danger delete_btn' data-id='".$value['id']."'><i class='fas fa-trash'></i></button>";
        }
        
        return view('admin.review.index',compact('title','headers','bodys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        $id = $request->id ?? 0;
        $previousUrl = app('url')->previous();
        if(empty($id)) {
            return redirect()->to($previousUrl);
        }
        $data = D_Review::findOrFail($id);
        $castData = M_Cast::fetchFilterId($data->cast_id);
        // if(empty($castData)) {
        //     return redirect()->to($previousUrl);
        // }
        $userData = D_User::findOrFail($data->user_id);
        $formatStarData = [];
        $starData = Review_Criterial::fetchData($id);
        foreach($starData as $star) {
            $formatStarData[$star->criterial_id] = $star->evaluate;
        }
        $startMaster = config('constant.cast.criterials');
        return view('admin.review.detail',compact('id','data','startMaster','formatStarData','castData','userData'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $id = $request->id ?? 0;
        $previousUrl = app('url')->previous();
        if(empty($id)) {
            return redirect()->to($previousUrl);
        }
        try {
            D_Review::findOrFail($id)->fill(['deleted_at' => now()])->save();
        } catch (\Exception $e) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('success', '更新に成功しました。');
        return redirect()->route('review');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id ?? 0;
        $title = $request->title ?? 0;
        $content = $request->content ?? null;
        $adminFeedback = $request->admin_feedback ?? null;
        $display = $request->display ? 1 : 0;
        $previousUrl = app('url')->previous();
        if(empty($id)) {
            return redirect()->to($previousUrl);
        }
        try {
            D_Review::findOrFail($id)->fill([
                'updated_at' => now(),
                'title' => $title,
                'content' => $content,
                'admin_feedback' => $adminFeedback,
                'admin_feedback_time' => now(),
                'display' => $display,
            ])->save();
        } catch (\Exception $e) {
            session()->flash('error', '登録に失敗しました。');
            return redirect()->to($previousUrl);
        }
        session()->flash('success', '更新に成功しました。');
        return redirect()->to($previousUrl);
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'cast_id'  => 'required|integer',
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'time_play'  => 'required|date',
        ]);

        $guestUser = D_User::where('email', 'guest@example.com')->firstOrFail();
        $userId = session('user_id');
        DB::beginTransaction();

        try {
            $review = new D_Review();
            $review->cast_id = $request->cast_id;
            $review->site_id = $request->site_id;
            $review->user_id = $userId ? $userId : $guestUser->id;
            $review->title = $request->title;
            $review->content = $request->content;
            $review->time_play = $request->time_play;
            $review->created_at = now();
            $review->save();

            $reviewCriterials = config('constant.cast.criterials');
            $reviewParams = [];

            foreach ($reviewCriterials as $key => $rc) {
                $reviewParams[] = [
                    'review_id'    => $review->id,
                    'criterial_id' => $key,
                    'evaluate'     => $request->input("criterial-$key") ?? 0.0,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            Review_Criterial::insert($reviewParams);

            DB::commit();

            return response()->json(['message' => 'Review submitted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to submit review',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
