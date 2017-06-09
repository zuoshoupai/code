<?php 
	require('conn.php');
	session_start();
	if(empty($_SESSION['user_id']))
	{
		go_url($login_url,'请先登录');
	}
//获取当前用户收藏
	$ch = curl_init();
	if(isset($_GET['page']))
	{
		$page_now = $_GET['page'];
	}else{
		$page_now = 1;
	}
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/favorite/list');
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
	<title>我的收藏</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
</head>
<body>
	<div class="ad-title clearfix"> 
		<p class="fl">我的收藏</p>
	</div>
	 <!-- 第一部分 -->
	 <div class="mid bgc1">
		<div class="in-mid">
			<ul>
				<?php 
					foreach($ListDates as $k=>$ListDate)
					{
						echo '
						<a href="article.php?article_id='.$ListDate['id'].'">
							<li class="clearfix">
								<span class="in-img1 fl"><img src="'.$ListDate['logo'][0].'" alt=""></span>
								<div class="fl in-wz">
									<p class="in-p1">'.$ListDate['title'].'</p>
									<p class="in-p2">阅读<span>'.$ListDate['views'].'</span></p>
								</div>
							</li>
						</a>';
					}
				?>
				
				
			</ul>
		</div>
		<?php echo $page_str; ?>
	</div>	
</body>
</html>