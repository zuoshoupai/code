<?php
	require('conn.php');
	session_start();
	if(isset($_SESSION['user_id']))
	{
		$user_id = $_SESSION['user_id'];
	}else{
		$user_id = 0;
	}
	if(isset($_GET['user_id']))
	{
		$user_id = $_GET['user_id'];
	}
	
	if(empty($_GET['article_id']))
	{
		echo "数据错误！";
		die();
	}
	$post = 'article_id='.$_GET['article_id'].'&user_id='.$user_id;
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/article/detail');
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
	$listData = curl_exec($ch);//这里会返回token，需要处理一下。
	$listData = json_decode($listData,true);
	curl_close($ch);
	if($listData['code']==0)
	{ 
		$listData = $listData['data'];
		if($listData['is_favorite']){
			$is_favorite = 'images/favorited.png';
		}else{
			$is_favorite = 'images/shoucang.png';
		}
		if($listData['isadv'])
		{
			$display = "";
			$display2 = "style='display:none;'";
			$advert  = $listData['advert'];
		}else{
			$display2 = '';
			$display = "style='display:none;'";
		}
		$advert  = $listData['advert'];
		$str = file_get_contents($listData['content']);
		   if(preg_match_all("/<body\s{0,10}id=\"content\">([\w\W]*)<\/body>/",$str,$new)){
		   }else{
			   echo '未找到';
		   }
		
	}else{
		echo '获取失败！';
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $listData['title'];?></title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<style type="text/css">
		img{max-width:97% !important;height:auto !important;}
		.arttab{width: 100%;padding-top: 0.3rem;padding-bottom: 0.2rem;border: 1px solid #e8e8e8;}
		.arttab1{width: 20%;}
		.arttab2{width: 60%;text-align: center;font-size: 0.425rem;color: #252525;height: 0.5rem;overflow: hidden;}
		.artimg1{width: 0.5rem;margin-left: 0.25rem;display: block;}
		.artimg2{width: 0.5rem;}
		.artimg3{width: 0.475rem;margin-left: 0.65rem;margin-top: 0.05rem;}
		.artpt1{width: 94%;padding-left: 2%;padding-right: 2%;padding-top: 0.25rem;}
		.artpt1-p1{font-size: 0.425rem;color:#252525;line-height: 0.55rem;max-height: 1.1rem;overflow: hidden;}
		.artpt1-p2{font-size: 0.35rem;color:#8c8c8c;line-height: 0.45rem;margin-top: 0.25rem;margin-bottom: 0.25rem;}
		  #news{font-size: 0.32rem;color:#3e3e3e;line-height: 0.55rem;margin: 0 auto;margin-top: 0.35rem;margin-bottom: 0.35rem;width:95%;font-family:黑体}
		.artimg4{width: 100%;height: 3.5rem;}
		.artbtm{width: 97%;padding-left: 3%;border-top: 1px solid #e8e8e8;padding-top: 0.2rem;padding-bottom: 0.2rem;position: fixed;bottom:0;left:0;z-index: 1;background-color: #fff;}
		.artimg5{width:0.9rem;margin-right: 0.5rem;}
		.artbtp1{font-size:0.325rem;color:#252525; line-height: 0.45rem;}
		.artbtp2{font-size:0.3rem;color:#8c8c8c; line-height: 0.45rem;}
		.artbtp3{font-size:0.3rem;color:#1792e8; line-height: 0.45rem;border-bottom: 1px solid #1792e8;}
		.artimg6{width: 0.6rem;margin-left: 1.8rem;margin-top: 0.25rem;}

		/*弹窗*/
	.popup1{width: 100%;height: 100%;position: fixed;top:0;left:0;z-index: 99;display: none;}
	.popup2{width: 100%;height: 100%;background: rgba(0,0,0,0.5);position:absolute;top:0;left:0;z-index: 100;}
	.share{width: 80%; height: 1.75rem;;position:absolute;top:30%;left:10%;z-index: 101;}
	.sharept1{width: 25%;}
	.shareimg{width:1.2rem;height: 1.2rem;margin:0 auto;display: block;}
	.sharep{color:#fff;font-size: 0.3rem;line-height: 1rem;text-align: center;}
	
	/*推广*/
	.ad-rate{width: 92%;margin: 0 auto;border: 1px solid #000;padding: 0.15rem;margin-top: 0.35rem;position: relative;}
	.ratesimg1{width: 1.8rem;height: 2rem;margin-right: 0.25rem;}
	.rateprt1{width: 56%;}
	.ratep1{font-size: 0.35rem;color:#252525;line-height: 0.8rem;}
	.ratep2{font-size: 0.3rem;color:#666;line-height:0.5rem;}
	.ratep3{font-size: 0.3rem;color:#1792e8;line-height:0.55rem;width:1.125rem;height: 0.55rem;border: 1px solid #87CEFA;border-left:1px solid #1792e8;text-align: center;margin-left: 0.25rem;margin-top: 0.7rem;}
	.ratesimg2{width:0.8625rem;height:0.3875rem; position: absolute;right:0.65rem;top:0;}
	</style>
</head>
<body>
	 <div class="clearfix arttab">
	 	<a href="##" onclick="history.back();"><div class="fl arttab1"><span class="artimg1"><img src="images/fanhui.png" alt=""></span></div></a>
	 	<p class="fl arttab2"><!--<?php echo $listData['source'];?>--></p>

	 	<div   class="fl clearfix arttab1">
	 		<span class="fl artimg2" onclick="add_favorite(<?php echo $listData['id'].','.$listData['is_favorite'].','.$user_id;?>)"><img src="<?php echo $is_favorite;?>" alt=""></span> <!--收藏-->
	 		<span class="fl artimg3" id="sharetxt"><img src="images/fenxiang.png" alt=""></span><!--分享-->
	 	</div>
	 </div>
	 <div class="artpt1">
	 	<p class="artpt1-p1"><?php echo $listData['title'];?></p>
	 	<div class="clearfix artpt1-p2"><p class="fl marr2"><?php echo $listData['created_at'];?></p><p class="fl marr2"></p><p class="fl"><?php echo $listData['source'];?></p></div>
	 	<!-- 推广 -->
		
	 	<div class="clearfix ad-rate" <?php echo $display;?>>
			<span style="display:inline-block;" class="fl ratesimg1"><img style="max-height:99%;" src="<?php echo $advert['img'];?>" alt=""><span style="display:inline-block;height:100%;width:0%;"></span></span>
			
	 		<div class="fl rateprt1">
	 			<p class="ratep1"><?php echo $advert['title'];?></p>
	 			<p class="ratep2"><?php echo $advert['descri'];?></p>
	 		</div>
	 		<p class="fl ratep3"><a href="tel:<?php echo $advert['tel'];?>">电话</a></p>
	 		<div class="ratesimg2"><img src="images/tuiguang.png" alt=""></div>
	 	</div>
		
		
	 	<div id="news"><?php echo $new[1][0];?></div>
	 </div>
	 <div class="artbtm clearfix" <?php echo $display2;?>>
	 	<a href="addAD.php">
	 	<span class="artimg5 fl"><img src="images/shouji.png" alt=""></span>
	 	<div class="fl">
	 		<p class="artbtp1">点击这里，免费植入你自己的广告</p>
	 		<p class="artbtp2">迅速将您的生意传遍到全国&nbsp<a href="addAD.php" class="artbtp3">立即体验</a></p>
	 	</div>
	 	<span class="fl artimg6"><img src="images/more.png" alt=""></span>
	 	</a>
	 </div>
	 <!-- 分享弹窗 -->
	 <div class="popup1" id="popwiow1">
	 	<div class="popup2" id="popwiow2">
	 		
	 	</div>
	 	<div class="clearfix share">
	 		<div class="fl sharept1">
	 			<span class='shareimg'><img src="images/weixin.png" alt=""></span>
	 			<p class="sharep">微信</p>
	 		</div>
	 		<div class="fl sharept1">
	 			<span class='shareimg'><img src="images/pengyou.png" alt=""></span>
	 			<p class="sharep">朋友圈</p>
	 		</div>
	 		<div class="fl sharept1">
	 			<span class='shareimg'><img src="images/facebook.png" alt=""></span>
	 			<p class="sharep">Facebook</p>
	 		</div>
	 		<div class="fl sharept1">
	 			<span class='shareimg'><img src="images/tuite.png" alt=""></span>
	 			<p class="sharep">Twitter</p>
	 		</div>
	 	</div>
	 </div>
	 <script type="text/javascript">
	 	$("#sharetxt").click(
			function(){
				$("#popwiow1").show();
			});
		$("#popwiow2").click(
			function(){
				$("#popwiow1").hide();
			});
		function add_favorite(id,is_favorite,user_id)
		{
			if(user_id==0){
				alert('请先登录!');
			}else{
				$.get("./ajax/ajax_favorite.php?aid="+id+'&isfav='+is_favorite,function(data,status){
					data = eval('(' + data + ')');
					alert(data.msg);
					document.location.reload();
				});
			}
		}
	 </script>
</body>
</html>