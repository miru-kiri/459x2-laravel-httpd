<?php

namespace App\Http\Controllers;

use App\Models\D_Cast_Blog;
use App\Models\D_User;
use App\Models\M_Cast;
use App\Models\User_Like;
use App\Models\User_Like_Cast;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Models\D_Cast_Blog_Image;
use App\Models\M_Site;

class PortalApiController extends Controller
{
    // public function loginUser(Request $request) {
    //     $request->validate([
    //         'nickname' => 'required',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('nickname', $request->nickname)->first();
    //     if (!$user) {
    //         return response()->error([
    //             'message' => 'ログインIDまたはパスワードが間違っています'
    //         ], 401);
    //     }

    //     if ($user->block == User::IS_BLOCK) {
    //         return response()->error([
    //             'message' => '会員がブロックしました。管理者に連絡してください。'
    //         ], 401);
    //     }

    //     if (!Hash::check($request->password, $user->password)) {
    //         return response()->error([
    //             'message' => 'ログインIDまたはパスワードが間違っています'
    //         ], 401);
    //     }

    //     //update last login
    //     $user->update([
    //         'last_login' => Carbon::now()
    //     ]);

    //     $token = $user->createToken('authToken')->plainTextToken;

    //     return response()->success([
    //         'access_token' => $token,
    //         'type' => 'Bearer'
    //     ]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccessToken(Request $request)
    {
        $ssoToken = $request->sso_token;
        if(empty($ssoToken)){
            return response()->json(["message" => "bad_request"], 400, []);
        }
        $userData = D_User::filterTokenData(['sso_token' => $ssoToken,'sso_token_expiration' => date('Y-m-d H:i:s')]);
        if(!$userData) {
            return response()->json(["message" => "not_exist"], 400, []);
        }
        return response()->json([
            'success' => 1,
            'data' => [
				'user_id' => $userData->id,
                'access_token' => $userData->sso_token
            ]
        ],200,[],JSON_UNESCAPED_UNICODE);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserToken(Request $request)
    {
        $headerAuth = $request->header('Authorization');
        if(empty($headerAuth)) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        // $explodeAry = explode('|',$headerAuth);
        // $userId = $explodeAry[0];
        // $userToken = $explodeAry[1];
        // $userId = $request->header('Authorization_ID');
        // if(empty($userId)) {
        //     return response()->json(["message" => "bad_request"], 400, []);
        // }
        // // $userToken = $request->header('Authorization');
        // if(empty($userToken)) {
        //     return response()->json(["message" => "bad_request"], 400, []);
        // }
        // $userData = D_User::findOrFail($userId);
        // if(Hash::check($userData->id,$userToken)) {
        //     session([
        //         "user_id"  => $userData->id,
        //         "user_token" => $userToken
        //     ]);
        //     // $resArray = $userData;
        // } else {
        //     return response()->json(["message" => "bad_request"], 400, []);
        // }
        $userData = D_User::filterTokenData(['sso_token' => $headerAuth,'sso_token_expiration' => 0]);
        if(!$userData) {
            return response()->json(["message" => "not_exits"], 400, []);
        }
        return response()->json($userData, 200, [], JSON_UNESCAPED_UNICODE);
    }
    public function unlikeSite(Request $request) {
        $siteId = $request->site_id;
        $headerAuth = $request->header('Authorization');
        if(empty($headerAuth)) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        $user = D_User::filterTokenData(['sso_token' => $headerAuth,'sso_token_expiration' => 0]);
        if(!$user) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        try {
            User_Like::where([
                'user_id' => $user->id,
                'site_id' => $siteId,
            ])
            ->delete();
            return response()->json([
                'success' => true,
                'message' => 'ブックマークしたサイトを外しました。',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $message) {
            return response()->json(["message" => "error"], 400, []);
        }
    }

    public function likeSite(Request $request){
        // $validated = $request->validated();

        // $user = $request->user();
        $headerAuth = $request->header('Authorization');
        if(empty($headerAuth)) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        $user = D_User::filterTokenData(['sso_token' => $headerAuth,'sso_token_expiration' => 0]);
        if(!$user) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        $siteId = $request->site_id;

        try {
            User_Like::updateOrCreate(
            [
                'user_id' => $user->id,
                'site_id' => $siteId,
            ],
            [
                // 'updated_at' => now(),
                'deleted_at' => null
            ]);
            return response()->json([
                'success' => true,
                'message' => 'サイトをブックマークしました。',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $message) {
            return response()->json(["message" => "error"], 400, []);
        }
    }

    public function checkLikedSite(Request $request)
    {
        $siteId = $request->site_id;
        // $userId = $request->header('Authorization_ID');
        $headerAuth = $request->header('Authorization');
        if(empty($headerAuth)) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        
        $user = D_User::filterTokenData(['sso_token' => $headerAuth,'sso_token_expiration' => 0]);
        if(!$user) {
            return response()->json(["message" => "bad_request"], 400, []);
        }

        try {
            $liked = User_Like::where([
                'user_id' => $user->id,
                'site_id' => $siteId,
            ])->exists();
            $message = $liked ? 'サイトをブックマークしました。' : 'サイトをブックマークしておりません。';
            
            return response()->json([
                'data' => [
                    'message' => $message,
                    'is_liked' => $liked,
                ]
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $message) {
            return response()->json(["message" => "error"], 400, []);
        }
    }
    public function likeCast(Request $request)
    {
        // $validated = $request->validated();

        // $user = $request->user();
        // $castId = $validated['cast_id'];
        $castId = $request->cast_id;
        $headerAuth = $request->header('Authorization');
        if(empty($headerAuth)) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        $user = D_User::filterTokenData(['sso_token' => $headerAuth,'sso_token_expiration' => 0]);
        if(!$user) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        try {
            User_Like_Cast::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'cast_id' => $castId,
                ],
                [
                    // 'updated_at' => now(),
                    'deleted_at' => null
                ]
            );
            return response()->json([
                'success' => true,
                'message' => 'キャストを気に入りました。',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $message) {
            return response()->json(["message" => "error"], 400, []);
        }
    }

    public function unlikeCast(Request $request)
    {
        // $validated = $request->validated();

        // $user = $request->user();
        $castId = $request->cast_id;
        $headerAuth = $request->header('Authorization');
        if(empty($headerAuth)) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        $user = D_User::filterTokenData(['sso_token' => $headerAuth,'sso_token_expiration' => 0]);
        if(!$user) {
            return response()->json(["message" => "bad_request"], 400, []);
        }

        try {
            User_Like_Cast::where([
                'user_id' => $user->id,
                'cast_id' => $castId,
            ])
            ->delete();
            return response()->json([
                'success' => true,
                'message' => '気に入ったキャストを外しました。',
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $message) {
            return response()->json(["message" => "error"], 400, []);
        }
    }

    // public function updateNotifyStatus(Request $request)
    // {
    //     // $validated = $request->validated();

    //     // $user = $request->user();
    //     // $userId = $request->header('Authorization_ID');
    //     $headerAuth = $request->header('Authorization');
    //     $explodeAry = explode('|',$headerAuth);
    //     $userId = $explodeAry[0];
    //     $user = D_User::findOrFail($userId);
    //     $castId = $request->cast_id;
    //     $notifyStatus = $request->status;

    //     try {
    //         User_Like_Cast::where([
    //             'user_id' => $user->id,
    //             'cast_id' => $castId,
    //         ])->update(['notify_status' => $notifyStatus]);

    //         return response()->json([
    //             'message' => '正常に更新しました。',
    //         ], 200, [], JSON_UNESCAPED_UNICODE);
    //     } catch (\Exception $message) {
    //         return response()->json(["message" => "error"], 400, []);
    //     }
    // }

    public function checkLikedCasts(Request $request) {
        $castIds = $request->cast_ids;
        // $user = $request->user();
        // $userId = $request->header('Authorization_ID');
        $headerAuth = $request->header('Authorization');
        if(empty($headerAuth)) {
            return response()->json(["message" => "bad_request"], 400, []);
        }
        $user = D_User::filterTokenData(['sso_token' => $headerAuth,'sso_token_expiration' => 0]);
        if(!$user) {
            return response()->json(["message" => "bad_request"], 400, []);
        }

        try {
            $castIds = explode(',', $castIds);
            foreach ($castIds as $key => $id) {
                if (!is_numeric($id)) {
                    return response()->json(["message" => "bad_request"], 400, []);
                }
                $castIds[$key] = (int)$id;
            }

            $castsCount = M_Cast::whereIn('id', $castIds)->count();
            if (!($castsCount == count($castIds))){
                return response()->json(["message" => "キャストが存在していません。"], 400, []);
            }

            $likedIds = User_Like_Cast::where('user_id', $user->id)
            ->whereIn('cast_id', $castIds)
            ->get('cast_id')
            ->toArray();

            $likedIds = Arr::flatten($likedIds);
            $notLikedIds = Arr::flatten(array_diff($castIds, $likedIds));

            return response()->json([
                'data' => [
                    'liked' => $likedIds,
                    'not_liked' => $notLikedIds,
                ],
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $message) {
            return response()->json(["message" => "error"], 400, []);
        }
    }

    function fetchArticles(Request $request)
    {
        $request->validate([
            'siteid'   => 'nullable|array',
            'castid'   => 'nullable|array',
            'since'    => 'nullable|date',
            'until'    => 'nullable|date',
            'per_page' => 'nullable|integer|min:1',
            'page'     => 'nullable|integer|min:1',
        ]);

        $filterSiteIDs = $request->siteid;
        $filterCastIDs = $request->castid;
        $perPage = $request->get('per_page', -1);
        $page = $request->get('page', 1);

        // $request->apiKey->creater->isFullSiteAdmin() && 
        if (!$filterSiteIDs && !$filterCastIDs) {
            $articlesQuery = D_Cast_Blog::where('published_at', '<=', date('Y-m-d H:i:s'))->orderBy('published_at', 'DESC');
            // ->with('site')
            if ($request->since) {
                $articlesQuery->where('published_at', '>=',  date('Y-m-d H:i:s',strtotime($request->since))->toDateTimeString());
            }

            if ($request->until) {
                $articlesQuery->where('published_at', '<=', date('Y-m-d H:i:s',strtotime($request->until))->toDateTimeString());
            }
            // $articlesQuery->with(['images', 'videos']);

            if ($perPage < 0) {
                $articles = $articlesQuery->get();
                $articleIds = $articlesQuery->pluck('id')->toArray();
                $articles = $this->reformArticlesData($articles,$articleIds);
                
                return response()->json([
                    'success' => 1,
                    'data' => [
                        'articles'     => $articles,
                        'total'        => $articles->count(),
                        'per_page'     => $articles->count(),
                        'total_page'   => 1,
                        'current_page' => 1,
                    ]
                ],200,[],JSON_UNESCAPED_UNICODE);
            }


            $articles = $articlesQuery->paginate($perPage, ['*'], 'page', $page);
            $articleIds = $articlesQuery->pluck('id')->toArray();
            return response()->json([
                'success' => 1,
                'data' => [
                    'articles'     => $this->reformArticlesData($articles->items(),$articleIds),
                    'total'        => $articles->total(),
                    'per_page'     => $articles->perPage(),
                    'total_page'   => $articles->lastPage(),
                    'current_page' => $articles->currentPage(),
                ]
            ],200,[],JSON_UNESCAPED_UNICODE);
        }

        // $siteids = $request->apiKey->creater->getSiteIDManager();
        $siteids = M_Site::where(['deleted_at'=>0,'is_public'=>1])->pluck('id')->all();
        if (!$filterSiteIDs) {
            // list all site of api key, if siteid wasn't pass to params
            $filterSiteIDs = $siteids;
        }
        // remove exceed site id
        $filterSiteIDs = array_intersect($filterSiteIDs, $siteids);

        // get all casts id in list sites
        $castIDs = M_Cast::whereIn('site_id', $filterSiteIDs)->where(['is_public'=>1,'deleted_at'=>0])->pluck('id')->all();
        // $castIDs = CastInfo::whereIn('st1', $filterSiteIDs)->notDelete()->notBlock()->pluck('staffid')->all();
        if (!$filterCastIDs) {
            $filterCastIDs = $castIDs;
        }
        // remove exceed cast id
        $filterCastIDs = array_intersect($filterCastIDs, $castIDs);

        // get published articles
        $articlesQuery = D_Cast_Blog::whereIn('cast_id', $filterCastIDs)
            ->where('published_at', '<=', date('Y-m-d H:i:s'))
            ->whereNull("d_cast_blog.deleted_at")
            ->orderBy('published_at', 'DESC')
            ->leftjoin('m_cast','m_cast.id','d_cast_blog.cast_id')
            ->leftjoin('m_site','m_site.id','m_cast.site_id')
            ->select('d_cast_blog.id','old_id','d_cast_blog.cast_id','title','d_cast_blog.content','d_cast_blog.created_at','d_cast_blog.updated_at','d_cast_blog.deleted_at','updated_by','deleted_by','published_at','type','is_draft','m_site.id as site_id','m_site.name as site_name','m_site.url as site_url','source_name');


        if ($request->since) {
            $articlesQuery->where('published_at', '>=', date('Y-m-d H:i:s',strtotime($request->since)));
        }

        if ($request->until) {
            $articlesQuery->where('published_at', '<=', date('Y-m-d H:i:s',strtotime($request->until)));
        }
        // $articlesQuery->with(['images', 'videos']);
        if ($perPage < 0) {
            $articles = $articlesQuery->get();
            $articleIds = $articlesQuery->pluck('id')->toArray();
            $articles = $this->reformArticlesData($articles,$articleIds);
            $articleCount = $articlesQuery->get()->count();
            return response()->json([
                'success' => 1,
                'data' => [
                    'articles'     => $articles,
                    'total'        => $articleCount,
                    'per_page'     => $articleCount,
                    'total_page'   => 1,
                    'current_page' => 1,
                ]
            ],200,[],JSON_UNESCAPED_UNICODE);
        }

        $articles = $articlesQuery->paginate($perPage, ['*'], 'page', $page);
        $articleIds = $articlesQuery->pluck('id')->toArray();

        return response()->json([
            'success' => 1,
            'data' => [
                'articles'     => $this->reformArticlesData($articles->items(),$articleIds),
                'total'        => $articles->total(),
                'per_page'     => $articles->perPage(),
                'total_page'   => $articles->lastPage(),
                'current_page' => $articles->currentPage(),
            ]
        ],200,[],JSON_UNESCAPED_UNICODE);
    }
    function reformArticlesData($articles,$articleIds)
    {
        $blogImageDatas = [];
        $formatBlogImageDatas = [];
        if($articleIds) {
            $blogImageDatas = D_Cast_Blog_Image::whereIn('article_id',$articleIds)->whereNull('deleted_at')->select('id','article_id','image_url','created_at','updated_at','deleted_at')->get();
        }
        if($blogImageDatas) {
            foreach($blogImageDatas as $blogImageData) {
                $blogImageData->image_url = env('APP_URL') .'/storage/'. $blogImageData->image_url;
                // $blogImageData->image_url = 'https://api459x.com/storage/' . $blogImageData->image_url;
                $formatBlogImageDatas[$blogImageData->article_id][] = $blogImageData;
            }
        }
        foreach($articles as $index => $art) {
            $articles[$index]->cast = [
                'id' =>  $art->cast_id,
                'name' => $art->source_name,
            ];
            $articles[$index]->site = [
                'id' =>  $art->site_id,
                'name' => $art->site_name,
                'url' => $art->site_url,
            ];
            $articles[$index]->images = isset($formatBlogImageDatas[$art->id]) ? $formatBlogImageDatas[$art->id] : [];
            $articles[$index]->videos = [];
        }
        return $articles;
    }
}
