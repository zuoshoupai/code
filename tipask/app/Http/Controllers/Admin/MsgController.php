<?php

namespace App\Http\Controllers\Admin;

use App\Models\Msg;
use App\Models\User;
use App\Models\Article;
use App\Models\XingeApp;
use App\Models\MessageIOS;
use App\Models\TimeInterval;
use App\Models\Message;
use App\Models\Style;
use App\Models\ClickAction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
//require_once ('XingeApp.php');
class MsgController extends AdminController
{
    /*权限验证规则*/
    protected $validateRules = [
        'title' => 'required|max:128',
        //'description' => 'sometimes|max:65535',
        'type' => 'required|integer',
        //'remnants' => 'required|integer',
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $msgs = Msg::orderBy('id','DESC')->paginate(10);
	   foreach($msgs as $v)
	   {
		   if($v->type==1)
		   {
			   $v->content = Article::where('id',$v->content)->value('title');
			   
		   }
		   if($v->to_user==0)
		   {
			  $v->to_user='全部';
		   }else{
			   $v->to_user = User::where('id',$v->to_user)->value('name');
		   }
	   }
	   return view('admin.msg.index')->with('msgs',$msgs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$article = Article::select('id','title')->orderBy('id','desc')->get();
		$users    = User::select('id','name')->orderBy('id','desc')->get();
        return view('admin.msg.create')->with('article',$article)->with('user',$users);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
        $request->flash();
        $this->validate($request,$this->validateRules);
        $msgs = msg::create($request->all());
		$msgs->title = $request->input('title');
        $msgs->type = $request->input('type');
		$type = $request->input('type');
		if($type==1)
		{
			$msgs->content = $request->input('select_article');
		}else{
			$msgs->content = $request->input('content');
		}
        $msgs->to_user = $request->input('to_user');
        $msgs->create_time = date('Y-m-d H:i:s',time());
        $msgs->save();
        return $this->success(route('admin.msg.index'),'新增消息成功');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $msgs = Msg::find($id);
        if(!$msgs){
            return $this->error(route('admin.msg.index'),'消息不存在，请核实');
        }
		$article = Article::select('id','title')->orderBy('id','desc')->get();
		$users    = User::select('id','name')->orderBy('id','desc')->get();
        return view('admin.msg.edit')->with('msgs',$msgs)->with('article',$article)->with('user',$users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $msgs = msg::find($id);
        if(!$msgs){
            return $this->error(route('admin.msg.index'),'消息不存在，请核实');
        }

        $this->validate($request,$this->validateRules);
        $msgs->title = $request->input('title');
        $msgs->type = $request->input('type');
		$type = $request->input('type');
		if($type==1)
		{
			$msgs->content = $request->input('select_article');
		}else{
			$msgs->content = $request->input('content');
		}
        $msgs->to_user = $request->input('select_user');
        $msgs->update_time = date('Y-m-d H:i:s',time());
        
        $msgs->save();
        return $this->success(route('admin.msg.index'),'消息修改成功');

    }

    /**
     * 删除商品
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        msg::destroy($request->input('ids'));
        return $this->success(route('admin.msg.index'),'消息删除成功');
    }
	//发布消息
	public function postmsg(Request $request)
	{
		$id = $request->input('id');
		$msg = Msg::where('id',$id)->first();
		$title   = $msg->title;
		$content = $msg->content;
		$custom  = '';
		$push_id = '';
		if(is_numeric($content))
		{
			$article = Article::where('id',$content)->select('id','title','logo','content')->first();
			$article->desc    = str_limit($this->format_html($article->content), $limit = 40, $end = '');
			$article->logo    = 'https://www.stpaulsfriends.club/image/show'.$article->logo;
			$custom = array('id'=>$article->id,'title'=>$article->title,'logo'=>$article->logo,'desc'=>$article->desc);
		}
		$to_user = $msg->to_user;
		var_dump($to_user);
		if(!empty($to_user)){
			$error = 0;
			$error_str = '发送成功';
			$user = User::where('id',$to_user)->select('device_token','device_type')->first();
			if($user->device_type==1){
				//Androd给单个设备下发通知
				//$error_msg = XingeApp::PushTokenAndroid(2100259224, "46dc9b997f1f3db3bbab8ed057a8959a", $title, $content,$user->device_token);
				$error_msg = $this->DemoPushSingleDeviceNotification($title,$user->device_token,$content,$custom);
				if($error_msg['ret_code']!=0){
					$error++;
					$error_str = $error_msg['err_msg'];
				} 
			}elseif($user->device_type==2){
				//IOS开发环境下 给单个设备下发通知
				/* $error_msg = XingeApp::PushTokenIos(2200259225, 'e93553fa967e5a698af8e6505372abee', $content, $user->device_token, XingeApp::IOSENV_DEV); */
				$error_msg = $this->DemoPushSingleDeviceIOS($title,$user->device_token,$custom);
				if($error_msg['ret_code']!=0){
					$error++;
					$error_str = $error_msg['err_msg'];
				}
			}
			if($error==0){
				Msg::where('id','=',$id)->update(['post_time'=>date('Y-m-d H:i:s',time())]);
			return $this->success(route('admin.msg.index'),'发布成功');
				return $this->success(route('admin.msg.index'),'发布成功');
			}else{
				return $this->error(route('admin.msg.index'),$error_str);
			}
			
		}
		//给所有设备发送
		 //IOS
		$ios_callback = $this->DemoPushAllDevicesIOS($title,$content,$custom);
		$error = 0;
		if($ios_callback['ret_code']!=0)
		{
			$error++;
			$message = '错误码:'.$ios_callback['ret_code'].',错误信息：'.$ios_callback['err_msg'];
			return $this->error(route('admin.msg.index'),$message);
		}else{
			$push_id = $ios_callback['result']['push_id'];
		} 
		
		//给所有安卓设备发消息
		$androd_callback = $this->DemoPushAllDevicesAndroid($title,$content,$custom);
		
		if($androd_callback['ret_code']==0)
		{
			Msg::where('id','=',$id)->update(['post_time'=>date('Y-m-d H:i:s',time())]);
			$push_id = $push_id.'||'.$androd_callback['result']['push_id'];
			Msg::where('id','=',$id)->update(['push_id'=>$push_id,'post_time'=>date('Y-m-d H:i:s',time())]);
			return $this->success(route('admin.msg.index'),'发布成功');
		}else{
			$message = '错误码:'.$androd_callback['ret_code'].',错误信息：'.$androd_callback['err_msg'];
			return $this->error(route('admin.msg.index'),$message);
		}
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
	//下发单个IOS设备消息
	function DemoPushSingleDeviceIOS($title,$token,$custom)
	{
		$push = new XingeApp(2200259225, 'e93553fa967e5a698af8e6505372abee');
		$mess = new MessageIOS();
		$mess->setExpireTime(86400);
		//$mess->setSendTime("2014-03-13 16:00:00");
		//$mess->setAlert(array('key1'=>'value1'));
		$mess->setBadge(0);
		$mess->setSound("beep.wav");
		$mess->setAlert($title);
		if(is_array($custom))
		{
			$mess->setCustom($custom);
		}
		$acceptTime = new TimeInterval(0, 0, 23, 59);
		$mess->addAcceptTime($acceptTime);
		//$raw = '{"xg_max_payload":1,"accept_time":[{"start":{"hour":"20","min":"0"},"end":{"hour":"23","min":"59"}}],"aps":{"alert":"="}}';
		//$mess->setRaw($raw);
		$ret = $push->PushSingleDevice($token, $mess, XingeApp::IOSENV_PROD);
		return $ret;
	}
	//单个设备安卓下发通知消息
	function DemoPushSingleDeviceNotification($title,$token,$content,$custom)
	{
		$push = new XingeApp(2100259224, '46dc9b997f1f3db3bbab8ed057a8959a');
		$mess = new Message();
		$mess->setType(Message::TYPE_NOTIFICATION);
		$mess->setTitle($title);
		$mess->setContent($content);
		$mess->setExpireTime(86400);
		//$style = new Style(0);
		#含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
		$style = new Style(0,1,1,0,0);
		$action = new ClickAction();
		$action->setActionType(ClickAction::TYPE_ACTIVITY);
		//$action->setUrl("http://xg.qq.com");
		$action->setActivity('123');
		#打开url需要用户确认
		//$action->setComfirmOnUrl(1);
		
		//$custom = array('key1'=>'value1', 'key2'=>'value2');
		$mess->setStyle($style);
		$mess->setAction($action);
		if(is_array($custom))
		{
			$mess->setCustom($custom);
		}
		$acceptTime1 = new TimeInterval(0, 0, 23, 59);
		$mess->addAcceptTime($acceptTime1);
		$ret = $push->PushSingleDevice($token, $mess);
		return($ret);
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
	
}
