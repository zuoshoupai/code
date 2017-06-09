<?php 
	require('conn.php');
	session_start();
	if(empty($_SESSION['user_id']))
	{
		go_url($login_url,'请先登录');
	}
	//删除链接
	if(isset($_GET['optr'])&&$_GET['optr']=='del')
	{
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$root_url.'/api/link/delete');
		$post = 'user_id='.$_SESSION['user_id'].'&token='.$_SESSION['token'].'&link_id='.$_GET['link_id'];
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
		$ListDate = curl_exec($ch);//这里会返回token，需要处理一下。
		$ListDate = json_decode($ListDate,true);
		curl_close($ch);
		go_url('diylink.php',$ListDate['msg']);

	}
//获取当前用户自定义链接
    if(isset($_GET['page']))
	{
		$page_now = $_GET['page'];
	}else{
		$page_now = 1;
	} 
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/link/list');
	$post = 'user_id='.$_SESSION['user_id'].'&token='.$_SESSION['token'].'&count_show=1&page='.$page_now;
	//$post = 'user_id=55&token='.$_SESSION['token'];
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
	$ListDate = curl_exec($ch);//这里会返回token，需要处理一下。
	$ListDate = json_decode($ListDate,true);
	curl_close($ch);
	if($ListDate['code']!=0){
		go_url('login.php',$ListDate['msg']); 
	}else{
		$page_str = page_cut($ListDate['count'],10);
		$ListDates = $ListDate['data'];
	} 
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>自定义链接</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<style type="text/css">
		.link{width: 94%;margin: 0 auto;}
		.link1{padding-top: 0.2rem;padding-bottom: 0.15rem;}
		.linkp1{font-size: 0.35rem;color:#999;line-height: 0.45rem;margin-right: 0.25rem;}
		.linkipt3{font-size: 0.35rem;color:#999;height:0.45rem;width: 80%;overflow: hidden; }
		.linkipt4{font-size: 0.35rem;color:#999;max-height:0.9rem;width: 80%;overflow: hidden; }
		.linkimg1{width: 0.7rem;height:0.7rem;margin-top: 0.8rem;margin-left: 0.15rem;background: url(images/moren.png) no-repeat 0 0;}
		.linktutu{background:  url(images/xuanzhong.png) no-repeat 0 0;}
		.linkchoose{width: 26%;padding-top: 0.15rem;padding-bottom: 0.15rem;}
		.lksp1{width:0.95rem;height: 0.4rem;color:#f70f2f;font-size:0.275rem;line-height: 0.4rem;text-align: center; border:1px solid #f70f2f; border-radius: 3px; margin-right: 0.3rem;}
		.lksp2{width:0.95rem;height: 0.4rem;color:#999;font-size:0.275rem;line-height: 0.4rem;text-align: center; border:1px solid #999;border-radius: 3px; }
		.linksave{width: 100%;background-color: #fff;padding-top: 0.2rem;padding-bottom: 0.25rem;position: fixed;bottom: 0;left:0;z-index: 1;}
		.linksave p{font-size: 0.3rem;color:#000;text-align: center;width: 94%;border:1px solid #000;line-height: 0.925rem;
    height: 0.925rem;margin: 0 auto; }
    .martop1{margin-top: 0.2rem;}
    /*弹窗*/
	.popup{width: 100%;height: 100%;background: rgba(0,0,0,0.5);position: fixed;top:0;left:0;z-index: 99;display: none;}
	.popup-window{position: absolute;width:6.25rem;height: 3.375rem;border-radius: 8px;top:30%;left:50%;margin-left: -3.125rem;background-color: #fff;text-align: center;color:#000;}
	.pop-p1{font-size: 0.4rem;line-height: 0.5rem;font-weight: bold;padding-top: 0.5rem;}
	.pop-p2{font-size: 0.325rem;line-height: 0.4rem;padding-top: 0.15rem;}
	.pop-choose{width: 100%;border-top: 1px solid #e8e8e8;height: 1rem;margin-top: 0.8rem;}
	.pop-p3{font-size: 0.325rem;line-height: 1rem;text-align: center;width: 49.5%;height: 1rem;}
	</style>
</head>
<body>
	<div class="ad-title clearfix"> 
		<p class="fl">自定义链接</p>
	</div>
	<?php 
		foreach ($ListDates as $key => $ListDate) {
			echo '<div class="bgc1 wid1">
					<div class="link ">
						<div class="clearfix">
							<div style="width:90%;" class="fl">
							<div class="clearfix link1 borbottm1">
								<p class="fl linkp1">链接标题</p>
								<input type="text" value="'.$ListDate['title'].'" class="fl linkipt3">
							</div>
							<div class="clearfix link1 borbottm1">
								<p class="fl linkp1">链接地址</p>
								<textarea   class="fl linkipt4">'.$ListDate['jump_url'].'</textarea>
							</div>
						</div>
						<div class="fl linkimg1 linkpick"></div>
						</div>
						<div class="clearfix">
							<div class="fr linkchoose clearfix"><span class="fl lksp1 remove" onclick="show_remove('.$ListDate['id'].')">删除</span><a href="uplink.php?title='.$ListDate['title'].'&url='.$ListDate['jump_url'].'&link_id='.$ListDate['id'].'"><span class="fl lksp2">编辑</span></a></div>
						</div>
					</div>
					
				</div>';
		}



	 ?>
	<?php echo $page_str; ?>
	<div style="height:1.5rem;"></div>
	<div class="linksave"><a href="addlink.php"><p>添加链接</p></a></div>
	<!-- 弹窗 -->
	<div class="popup" id="popwiow" >
		<div class="popup-window">
			<p class="pop-p1" style="padding-top:1rem;">确认删除</p>
			
			<div class="pop-choose clearfix"><span class="fl pop-p3 borrigt popcancel" >取消</span><span class="fl pop-p3" onclick="go_delete()">确认</span></div>
		</div>
	</div>
	<script type="text/javascript">
		$(".linkpick").click(
			function(){
				$(this).toggleClass("linktutu");
			}
		); 
		$(".popcancel").click(
			function(){
				$("#popwiow").hide();
			});
		function show_remove(j)
		{
			$("#popwiow").attr('optr',j);
			$("#popwiow").show();
		}
		function go_delete()
		{
			var link = $("#popwiow").attr('optr');
			window.location.href='?optr=del&link_id='+link;
		}
	</script>
</body>
</html>