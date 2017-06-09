<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Question;
use App\Models\Tag;
use App\Models\UserData;
use App\Models\UserTag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    /***
    *文章列表
    *
    ***/
    public function lists(Request $request)
    {
        $page = $request->input('page');
        if(empty($page)){
            $page = 1;
        } 
        //$list = Category::orderBy('id','desc')->get(['id','title','summary','logo','views','created_at'])->chunk(10);
		$list = Category::orderBy('sort')->where('type','articles')->select('id','name')->get();
        return response()->json(array('code'=>0,'msg'=>'成功','data'=>$list));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
}
