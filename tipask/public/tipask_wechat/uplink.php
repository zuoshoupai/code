<?php 
	require('conn.php');
	session_start();
	if(empty($_SESSION['user_id']))
	{
		go_url($login_url,'请先登录');
	}
	if(isset($_POST['title']))
	{
		$title = $_POST['title'];
		$jump_url = $_POST['jump_url'];
		$link_id  = $_POST['link_id'];
		//修改链接
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$root_url.'/api/link/update');
		$post = 'user_id='.$_SESSION['user_id'].'&token='.$_SESSION['token'].'&title='.$title.'&url='.$jump_url.'&link_id='.$link_id;  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
		$ListDate = curl_exec($ch);//这里会返回token，需要处理一下。
		$ListDate = json_decode($ListDate,true);
		curl_close($ch);
		if($ListDate['code']!=0){ 
			$alert_msg = $ListDate['msg']; 
		}else{
			go_url('diylink.php','修改成功'); 
		}
	}
 	$title = $_GET['title']?$_GET['title']:'';
 	$url   = $_GET['url']?$_GET['url']:'';
 	$link_id = $_GET['link_id']?$_GET['link_id']:'';


 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>修改链接</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<style type="text/css">
		.link{width: 94%;margin: 0 auto;}
		.link1{padding-top: 0.2rem;padding-bottom: 0.15rem;}
		.linkp1{font-size: 0.35rem;color:#999;line-height: 0.45rem;margin-right: 0.25rem;}
		.linkipt1{font-size: 0.35rem;color:#999;height:0.45rem;width: 80%;overflow: hidden; }
		.linkipt2{font-size: 0.35rem;color:#999;max-height:0.9rem;width: 80%;overflow: hidden; }
		.linksave{width: 100%;background-color: #fff;padding-top: 0.2rem;padding-bottom: 0.25rem;position: fixed;bottom: 0;left:0;z-index: 1;}
		.linksave p{font-size: 0.3rem;color:#000;text-align: center;width: 94%;border:1px solid #000;line-height: 0.925rem;
    height: 0.925rem;margin: 0 auto; }
	</style>
</head>
<?php
	if(isset($alert_msg))
	{
		alert_msg($alert_msg);
	}
?>
<body >
	<div class="ad-title clearfix"> 
		<p class="fl">修改链接</p>
	</div>
	<form method="post" name="link">
	<input type="hidden" name="link_id" value="<?php echo $link_id;?>">
	<div class="bgc1 wid1">
		<div class="link">
			<div>
				<div class="clearfix link1 borbottm1">
					<p class="fl linkp1">链接标题</p>
					<input type="text" name="title" placeholder="链接标题" class="fl linkipt1" value="<?php echo $title;?>">
				</div>
				<div class="clearfix link1 ">
					<p class="fl linkp1">链接地址</p>
					<textarea   name="jump_url" class="fl linkipt2" ><?php echo $url;?></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="linksave"><p onclick="document.link.submit()">保存</p></div>
	</form>
</body>
</html>