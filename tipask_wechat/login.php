<?php
	require('conn.php');
	session_start();
	if(@$_GET['optr'] == 'toto')
	{ 
		header("Location: ".$wechat_url); 
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>授权登陆</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<style type="text/css">
	.login-p1{width: 100%;text-align: center;font-size: 0.425rem;line-height: 1.1rem;height: 1.1rem;color:#252525;border-bottom: 1px solid #e1e1e1;}
	.login-prt1{width: 80%;margin: 0 auto;}
	.logon-img1{width: 1.5rem;margin:0 auto;display: block;margin-top: 0.75rem;}
	.login-p2{color:#000;font-size: 0.35rem;line-height: 0.8rem;text-align: center;}
	.login-p3{color:#252525;font-size: 0.325rem;line-height: 0.5rem;margin-top: 0.25rem;}
	.login-p4{color:#8e8e8e;font-size: 0.325rem;line-height: 0.5rem;margin-bottom: 0.8rem;}
	.sqaniu{width:7.5rem;height: 0.875rem;border: 1px solid #000;margin-bottom: 0.35rem;}
.login-p5{color:#000;font-size: 0.3rem;line-height: 0.875rem;}
.sqimg1{display: block;width:0.4rem;margin-left: 1.8rem;margin-right: 0.15rem;margin-top: 0.2rem;}
	</style>
</head>
<body>
	<div  class="login-p1">授权登陆</div>
	<div class="login-prt1">
		<div>
			<span class="logon-img1"><img src="images/logo.png" alt=""></span>
			<p class="login-p2">卡卡营销</p>
		</div>
		<div>
			<p class="login-p3">该功能由卡卡营销团队开发，请选择相应的账号登陆</p>
			<p class="login-p4">获取您的昵称和用户图像</p>
		</div>
		<div>
			<div class="sqaniu" style="" onclick="window.location.href='?optr=toto'"><span class="sqimg1 fl" style="margin-left:1.9rem;"><img src="images/lwx.png" alt=""></span><p class="login-p5">Wechat授权安全登录</p></div>
			<div style="display:none;" class="sqaniu" onclick="window.location.href='?optr=toto'"><span class="sqimg1 fl"><img src="images/sqfb1.png" alt=""></span><p class="login-p5">Facebook授权安全登录</p></div>
		</div>
	</div>
	<!-- 下面分页 -->
	<div class="in-binh clearfix">
		<div class="in-binh1 fl">
			<span class="binh-img1"><img src="images/wenzhang1.png" alt=""></span>
			<p class="binhp">文章</p>
		</div>
		<div class="in-binh1 fl">
			<span class="binh-img2"><img src="images/wode1.png" alt=""></span>
			<p class="binhp">我的</p>
		</div>
	</div>
</body>
</html>