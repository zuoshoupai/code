<?php 
	require('conn.php');
	session_start();
	if(isset($_GET['page']))
	{
		$page_now = $_GET['page'];
	}else{
		$page_now = 1;
	} 
	if(isset($_SESSION['user_id']))
	{
		$user_url = '&user_id='.$_SESSION['user_id'];
	}else{
		$user_url = '';
	}
//获取当前用户消息
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/msg/list');
	$post = 'user_id='.$_SESSION['user_id'].'&token='.$_SESSION['token'].'&count_show=1&page='.$page_now;
	//$post = 'user_id=55&token='.$_SESSION['token'];
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
	$ListDate = curl_exec($ch);//这里会返回token，需要处理一下。
	$ListDate = json_decode($ListDate,true);
	curl_close($ch);
	if($ListDate['code']!=0){
		alert_msg($ListDate['msg']);
		header("Location: login.php");
	}else{
		$page_str = page_cut($ListDate['count'],10);
		$ListDates = $ListDate['data'];
	} 
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>我的消息</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<style type="text/css">
		.news{width: 94%;padding-left: 3%;padding-right: 3%;}
	</style>
</head>
<body>
	<div class="ad-title clearfix"> 
		<p class="fl">我的消息</p>
	</div>
	<?php
	foreach($ListDates as $k=>$ListDate)
	{
		echo '
		<div class="news">
	        <p class="in-p2" style="text-align:center;margin-top:0.2rem;margin-bottom:0.2rem;"><span>'.$ListDate['created_at'].'</span></p>
			<a href="article.php?article_id='.$ListDate['id'].$user_url.'">
				<div class="in-mid1 bgc1 clearfix" style="padding-bottom:0.15rem;">
					<span class="in-img1 fl"><img src="'.$ListDate['logo'][0].'" alt=""></span>
					<div class="fl in-wz" style="width:68%;">
						<p class="in-p1">'.$ListDate['title'].'</p>
						<p class="in-p2">阅读<span>'.$ListDate['views'].'</span></p>
					</div>
				</div>
			</a>
		</div>';
	}
	echo $page_str;
	?>
	
</body>
</html>