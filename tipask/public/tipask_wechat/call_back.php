<?php 
	if(isset($_GET['code']))
	{
		$code   = $_GET['code'];
		$appid  = 'wxc082f8480924d81d';
		$secret = '3575f1cdd963e4cc39a2ca733fc22682';
		
		//获取token及openid
		$token_get_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
		$ch = curl_init();//初始化一个资源
		curl_setopt($ch,CURLOPT_URL,$token_get_url);//设置我们要获取的网页
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		$data = curl_exec($ch);
		$content = json_decode($data); 
		$refresh_token = $content->refresh_token;
		//获取用户详情
		$get_userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".($content->access_token)."&openid=".($content->openid)."&lang=zh_CN";
		$ch = curl_init();//初始化一个资源
		curl_setopt($ch,CURLOPT_URL,$get_userinfo_url);//设置我们要获取的网页
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		$data = curl_exec($ch);
		$content = json_decode($data);
		//授权登录
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,'http://shop.m9n.com/api/user/login');
		$post = 'login_type=wx2&openid='.($content->openid).'&sex='.($content->sex).'&nickname='.($content->nickname).'&city='.($content->city).'&province='.($content->province).'&headimgurl='.($content->headimgurl).'&devicetoken=novaliable&device_type=2';
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
		$content = curl_exec($ch);//这里会返回token，需要处理一下。
		$content = json_decode($content,true);
		curl_close($ch);
		$data = $content['data'];
		/*
		$user_id = $content->user_id;
		$token   = $content->token;
		$name    = $content->name;
		 
		$nickname = $content->nickname;
		$sex      = $content->sex;
		$city     = $content->city;
		$province = $content->province;
		$country  = $content->country;
		$headimgurl = $content->headimgurl; */
		session_start();
		$_SESSION['user_id'] = $data['id'];
		$_SESSION['name']    = $data['name'];
		$_SESSION['token']   = $data['token'];
		header("Location: my.php"); //php方法
		
	}else{
		echo 'none';
	}
?>