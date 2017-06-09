<?php

namespace App\Http\Controllers\Api;

use App\Models\Msg;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\XingeApp;
use App\Models\MessageIOS;
use App\Models\TimeInterval;
use App\Models\Message;
use App\Models\Style;
use App\Models\ClickAction;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MsgController extends Controller
{
    
	/***
    *我的消息
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
		$msg  = Msg::whereRaw('to_user in ('.$user_id.',0) and type=1')->skip($skip)->take($take)->select('id','content')->get();
		foreach($msg as $k=>$v)
		{
			$article = Article::where('id',$v->content)->select('id','title','summary','logo','views','created_at')->first();
			if($article)
			{
				$v->id = $article->id;
				$v->title = $article->title;
				$v->summary = $article->summary;
				if(substr($article->logo,0,1)=='/')
				{
					$logo = "https://www.stpaulsfriends.club/image/show".($article->logo);
				}else{
					$logo = "https://www.stpaulsfriends.club/image/show/".($article->logo);
				}
				$v->logo = [$logo];
				$v->views = $article->views;
				$v->created_at = $article->created_at;
			}else{
				$v->id = $v->title = $v->summary = $v->views = $v->created_at = '';
				$v->logo = array();
			}
			
			unset($v->content);
		}
		if($count_show)
		{
			$count = Msg::whereRaw('to_user in ('.$user_id.',0) and type=1')->count('id');
			return response()->json(array('code'=>0,'msg'=>'成功','count'=>$count,'data'=>$msg));
		}
		return response()->json(array('code'=>0,'msg'=>'成功','data'=> $msg));
    }
	//定时发送信息
	public function postmsg_auto(Request $request)
	{
		$check_validate = $request->input('kbak_validate');
		if($check_validate!='1333888999'){
			return 'error';
		}
		$hour = date('H');
		if($hour<10){
			$skip = 0;
		}elseif($hour<13){
			$skip = 1;
		}elseif($hour<19){
			$skip = 2;
		}
		$article = Article::whereRaw('category_id<>9')->select('id','title','logo','content')->orderBy('id','desc')->skip($skip)->first();
		//$article = Article::whereRaw('id',$content)->select('id','title','logo','content')->first();
		$article->desc    = str_limit($this->format_html($article->content), $limit = 40, $end = '');
		$article->logo    = 'https://www.stpaulsfriends.club/image/show'.$article->logo;
		$title = $article->title;
		$content = $article->title;
		$custom = array('id'=>$article->id,'title'=>$article->title,'logo'=>$article->logo,'desc'=>$article->desc);
		
		//给所有设备发送
		 //IOS
		$ios_callback = $this->DemoPushAllDevicesIOS($title,$content,$custom);
		$ios_pushid = 'error';
		$error = 0;
		if($ios_callback['ret_code']!=0)
		{
			$error++;
			$message = '错误码:'.$ios_callback['ret_code'].',错误信息：'.$ios_callback['err_msg'];
			return $this->error(route('admin.msg.index'),$message);
		}else{
			$ios_pushid = $ios_callback['result']['push_id'];
		}
		
		//给所有安卓设备发消息
		$and_pushid = 'error';
		$androd_callback = $this->DemoPushAllDevicesAndroid($title,$content,$custom);
		if($androd_callback['ret_code']!=0)
		{
			$message = '错误码:'.$androd_callback['ret_code'].',错误信息：'.$androd_callback['err_msg'];
			return $this->error(route('admin.msg.index'),$message);
		}else{
			$and_pushid = $androd_callback['result']['push_id'];
		}
		$post_data = ['title'=>$article->title,'article_id'=>$article->id,'post_time'=>date('Y-m-d H:i:s'),'ios_pushid'=>$ios_pushid,'and_pushid'=>$and_pushid];
		DB::table('postlog')->insert($post_data);
	}
	////下发所有IOS设备消息
	function DemoPushAllDevicesIOS($title,$content,$custom)
	{
		$push = new XingeApp(2200259225, 'e93553fa967e5a698af8e6505372abee');
		$mess = new Message();
		$mess->setType(Message::TYPE_NOTIFICATION);
		$mess->setTitle($title);
		$mess->setContent($content);
		$mess->setExpireTime(86400);
		$mess->setCustom($custom);
		$style = new Style(0);
		#含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style(0,1,1,0,0);
		$action = new ClickAction();
		$action->setActionType(ClickAction::TYPE_ACTIVITY);
		$action->setActivity('123');
		$mess->setStyle($style);
		$mess->setAction($action);
		
		$ret = $push->PushAllDevices(0, $mess,XingeApp::IOSENV_PROD);
		return ($ret);
	}
	//下发给所有Android设备
	function DemoPushAllDevicesAndroid($title,$content,$custom)
	{
		//androd
		$push = new XingeApp(2100259224, '46dc9b997f1f3db3bbab8ed057a8959a');
		$mess = new Message();
		$mess->setType(Message::TYPE_NOTIFICATION);
		$mess->setTitle($title);
		$mess->setContent($content);
		$mess->setExpireTime(86400);
		$style = new Style(0);
		$action = new ClickAction();
		$action->setActionType(ClickAction::TYPE_ACTIVITY);
		$action->setActivity('123');
		$mess->setStyle($style);
		$mess->setAction($action);
		$ret = $push->PushAllDevices(0, $mess);
		return $ret;
	}
	function format_html($str){
		$str = strip_tags($str);
		$str = str_replace(array("\r\n", "\r", "\n","\t"), "", $str); 
		$str = str_replace('&ldquo;', '“',$str);
		$str = str_replace('&rdquo;', '”',$str);
		$str = str_replace('&middot;', '·',$str);
		$str = str_replace('&lsquo;', '‘',$str);
		$str = str_replace('&rsquo;', '’',$str);
		$str = str_replace('&hellip;', '…', $str);
		$str = str_replace('&mdash;', '—', $str);
		return $str;
	}
}
