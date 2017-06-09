<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LinkController extends Controller
{
    /***
    *添加链接
    *
    ***/
    public function add(Request $request)
    {	
		$user_id = $request->input('user_id');
		$title   = $request->input('title');
		$url     = $request->input('url');
		$token      = $request->input('token');
		if(empty($title))
		{
			return response()->json(array('code'=>1,'msg'=>'缺少标题','data'=>array()));
		}
		if(empty($url))
		{
			return response()->json(array('code'=>1,'msg'=>'缺少链接地址','data'=>array()));
		}
        if(empty($user_id)||empty($token))
		{
			return response()->json(array('code'=>1,'msg'=>'缺少参数','data'=>array()));
		}
		//验证token
		$user = User::where('id',$user_id)->select('password','sort')->first();
		if(md5(($user->password).($user->sort)) != $token)
		{
			return response()->json(array('code'=>3,'msg'=>'token验证失败','data'=>array()));
		}
        $com_point = ['title'=>$title,'jump_url'=>$url,'user_id'=>$user_id,'create_time'=>date('Y-m-d H:i:s',time())];
		Link::insert($com_point);
        return response()->json(array('code'=>0,'msg'=>'添加成功','data'=>array()));
    }
	/***
    *修改链接
    *
    ***/
    public function update(Request $request)
    {	
		$link_id = $request->input('link_id');
		$title   = $request->input('title');
		$url     = $request->input('url');
		$token      = $request->input('token');
		$user_id      = $request->input('user_id');
        if(empty($link_id)||empty($title)||empty($url)||empty($token)||empty($user_id))
		{
			return response()->json(array('code'=>1,'msg'=>'缺少参数','data'=>array()));
		}
		//验证token
		$user = User::where('id',$user_id)->select('password','sort')->first();
		if(md5(($user->password).($user->sort)) != $token)
		{
			return response()->json(array('code'=>3,'msg'=>'token验证失败','data'=>array()));
		}
        $com_point = ['title'=>$title,'jump_url'=>$url,'update_time'=>date('Y-m-d H:i:s',time())];
		Link::where('id',$link_id)->update($com_point);
        return response()->json(array('code'=>0,'msg'=>'修改成功','data'=>array()));
    }
	/***
    *删除链接
    *
    ***/
    public function delete(Request $request)
    {	
		$link_id = $request->input('link_id');
		$token      = $request->input('token');
		$user_id      = $request->input('user_id');
        if(empty($link_id)||empty($token)||empty($user_id))
		{
			return response()->json(array('code'=>1,'msg'=>'缺少参数','data'=>array()));
		}
		//验证token
		$user = User::where('id',$user_id)->select('password','sort')->first();
		if(md5(($user->password).($user->sort)) != $token)
		{
			return response()->json(array('code'=>3,'msg'=>'token验证失败','data'=>array()));
		}
        Link::where('id',$link_id)->delete();
        return response()->json(array('code'=>0,'msg'=>'删除成功','data'=>array()));
    }
	/***
    *链接列表
    *
    ***/
    public function lists(Request $request)
    {	
		$user_id = $request->input('user_id');
		$page    = $request->input('page');
		$token      = $request->input('token');
		$count_show = $request->input('count_show');
		if(empty($count_show))
		{
			$count_show = 0;
		}else{
			$count_show = 1;
		}
		if(empty($page)){
            $page = 1;
        }
		if(empty($user_id)||empty($token))
		{
			return response()->json(array('code'=>1,'msg'=>'缺少参数','data'=>array()));
		}
		//验证token
		$user = User::where('id',$user_id)->select('password','sort')->first();
		if(md5(($user->password).($user->sort)) != $token)
		{
			return response()->json(array('code'=>3,'msg'=>'token验证失败','data'=>array()));
		}
		$take = 10;
		$skip = ($page-1)*$take;
        $list = Link::orderBy('id','desc')->where('user_id',$user_id)->skip($skip)->take($take)->select('title','jump_url','id')->get();
		if($count_show)
		{
			$count = Link::where('user_id',$user_id)->count('id');
			return response()->json(array('code'=>0,'msg'=>'成功','count'=>$count,'data'=>$list));
		}
        return response()->json(array('code'=>0,'msg'=>'成功','data'=> $list));
    }
 
}
