<?php 
	require('conn.php');
	session_start();
	if(empty($_SESSION['user_id']))
	{
		go_url($login_url,'请先登录');
	}
//获取当前用户信息
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/user/info');
	$post = 'user_id='.$_SESSION['user_id'].'&token='.$_SESSION['token'];
	//$post = 'user_id=55&token='.$_SESSION['token'];
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
	$ListDate = curl_exec($ch);//这里会返回token，需要处理一下。
	$ListDate = json_decode($ListDate,true);
	curl_close($ch);
	if($ListDate['code']!=0){
		go_url('login.php','请先登录');
	}else{
		$ListDate = $ListDate['data'];
	}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>个人中心</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<style type="text/css">
		.my{width: 100%;height: 3.8rem;background-color: #000;color:#fff;text-align: center;padding-top: 0.7rem;}
		.touxiang{width:1.525rem;height: 1.525rem;border-radius: 50%;overflow: hidden;margin: 0 auto;background-color: red;display: block;margin-bottom: 0.3rem;}
		.name{font-size: 0.425rem;line-height: 0.65rem;}
		.address{font-size: 0.3rem;line-height: 0.5rem;}
		.myprt1{width: 100%;margin-top: 0.25rem;background-color: #fff;font-size: 0.3rem;color:#252525;text-align: center;line-height: 0.6rem;}
		.mysan{width:33%;border-right: 1px solid #e1e1e1;height: 3.125rem;}
		.myimg1{width:0.65rem;margin: 0 auto;display: block;padding-top: 0.8rem;}
		.myimg2{width:0.5625rem;margin: 0 auto;display: block;padding-top: 0.8rem;}
	</style>
</head>
<body>
	<div class="my">
		<span class="touxiang"><img src="<?php echo $ListDate['headimg'];?>" alt=""></span>
		<p class="name"><?php echo $ListDate['name'];?></p>
		<p class="address"><?php echo $ListDate['province'].$ListDate['city'];?></p>
	</div>
	<div class="myprt1">
		<div>
			<div class="clearfix">
				<div class="fl mysan">
					<a href="collect.php">
					<span class="myimg1"><img src="images/mysc.png" alt=""></span>
					<p>我的收藏</p>
					</a>
				</div>
				<div class="fl mysan">
					<a href="addAD.php">
					<span class="myimg1"><img src="images/guanggao.png" alt=""></span>
					<p>广告管理</p>
					</a>
				</div>
				<div class="fl mysan">
					<a href="diylink.php">
					<span class="myimg1" style="border:none;"><img src="images/lianjie.png" alt=""></span>
					<p>链接管理</p>
					</a>
				</div>
			</div>
			<div class="clearfix">
				<div class="fl mysan">
					<a href="news.php">
					<span class="myimg2"><img src="images/xiaoxi.png" alt=""></span>
					<p>我的消息</p>
					</a>
				</div>
				<div class="fl mysan">
					
				</div>
				<div class="fl mysan">
					
				</div>
			</div>
		</div>
	</div>
	 <!-- 下面分页 -->
	<div class="in-binh clearfix">
		<div class="in-binh1 fl">
			<a href="index.php">
			<span class="binh-img1"><img src="images/wenzhang1.png" alt=""></span>
			<p class="binhp">文章</p>
			</a>
		</div>
		<div class="in-binh1 fl">
			<a href="my.php">
			<span class="binh-img2"><img src="images/wode1.png" alt=""></span>
			<p class="binhp">我的</p>
			</a>
		</div>
	</div>
</body>
</html>