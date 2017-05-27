<?php
	require('conn.php');
	
	session_start();
	if(empty($_SESSION['user_id']))
	{
		go_url($login_url,'请先登录');
	}
	//获取当前用户广告
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/adv/list');
	$post = 'user_id='.$_SESSION['user_id'].'&token='.$_SESSION['token'];
	//$post = 'user_id=55&token='.$_SESSION['token'];
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
	$ListDate = curl_exec($ch);//这里会返回token，需要处理一下。
	$ListDate = json_decode($ListDate,true);
	//curl_close($ch);
	if($ListDate['data']!='')
	{
		$first = 1;
	}else{
		$first = 0;
	}
	//获取自定义链接
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/link/list');
	$post = 'user_id='.$_SESSION['user_id'].'&token='.$_SESSION['token'];
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
	$linkList = curl_exec($ch);//这里会返回token，需要处理一下。
	$linkList = json_decode($linkList,true);
	curl_close($ch);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加广告</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<style type="text/css">
	.adbg{width: 100%;height:100%;background: url("images/dibuimg.png") repeat top left;}
	
	.ad-banner{width: 100%;height:4rem; overflow: hidden;}
	.ad-dipt{width:90%;margin-left:4%;border:2px solid #fff;padding-left: 0.15rem;font-size: 0.325rem;color:#c3c3c3;line-height: 0.925rem;height:0.925rem;margin-top: 0.4rem;}
	.ad-iput1{font-size: 0.325rem;color:#c3c3c3;line-height: 0.925rem;height:0.925rem;background-color:transparent;}
	.ad-iput2{font-size: 0.325rem;color:#c3c3c3;line-height: 0.925rem;height:0.925rem;background-color:transparent;width: 83%;}
	.ad-p1{font-size: 0.275rem;color:#c3c3c3;position: absolute;right:0.15rem;top:0rem;}
	.ad-p2{font-size: 0.325rem;color:#c3c3c3;line-height: 0.5rem;padding-left: 4%;margin-top: 0.2rem;margin-bottom: 0.2rem;}
	.ad-imgchge{background: url("images/add.png") no-repeat 0 0;width:1.8rem;height: 1.8rem; margin-left: 4%;position: relative;}
	.ad-imgchge input{background-color:transparent;width:1.6rem;height: 1.6rem;position: absolute;top:0;left:0;z-index: 1;border: 0;}
	.ad-form{width:90%;margin-left:4%;border:2px solid #fff;padding-left: 0.15rem;font-size: 0.325rem;color:#c3c3c3;line-height: 0.925rem;height:0.925rem;margin-top: 2.4rem;text-align: center;}
	/*弹窗*/
	.popup{width: 100%;height: 100%;background: rgba(0,0,0,0.5);position: fixed;top:0;left:0;z-index: 99;display: none;}
	.popup-window{position: absolute;width:6.25rem;height: 3.375rem;border-radius: 8px;top:30%;left:50%;margin-left: -3.125rem;background-color: #fff;text-align: center;color:#000;}
	.pop-p1{font-size: 0.4rem;line-height: 0.5rem;font-weight: bold;padding-top: 0.5rem;}
	.pop-p2{font-size: 0.325rem;line-height: 0.4rem;padding-top: 0.15rem;}
	.pop-choose{width: 100%;border-top: 1px solid #e8e8e8;height: 1rem;margin-top: 0.8rem;}
	.pop-p3{font-size: 0.325rem;line-height: 1rem;text-align: center;width: 49.5%;height: 1rem;}
	</style>
</head>
<body class="adbg"> 
	<form name="advert" method="post" enctype="multipart/form-data" action="http://www.askme.com//api/adv/add">
	<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'];?>"/>
	<input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>"/>
	<div class="ad-banner"><img src="images/banner-ad.png" alt=""></div>
	<div>
		<div class="ad-dipt">
			<input type="text" class="ad-iput1" placeholder="广告标题" name="title" <?php if($first) echo 'value=\''.$ListDate['data']['title'].'\''; ?>>
		</div>
		<div class="ad-dipt">
			<input type="text" class="ad-iput1" placeholder="广告描述" name="descri" <?php if($first) echo 'value=\''.$ListDate['data']['descri'].'\''; ?>>
		</div>
		<div class="ad-dipt">
			<input type="text" class="ad-iput1" placeholder="联系电话" name="tel" <?php if($first) echo 'value=\''.$ListDate['data']['tel'].'\''; ?>>
		</div>
		<div class="ad-dipt potire">
			<select   class="ad-iput2"   name="link_id">
			<?php
				foreach($linkList['data'] as $k => $v)
				{
					if($v['id'] ==$ListDate['data']['link_id'])
					{
						echo "<option value=".$v['id']." selected>".$v['jump_url']."</option>";
					}else{
						echo "<option value=".$v['id'].">".$v['jump_url']."</option>";
					}
				}
			?>
			</select> 
		</div>
		<div>
			<p class="ad-p2">广告图片</p>
			<div class="ad-imgchge"><input type="file" id="upload" class="ad-iput3" name="file" style="opacity: 0;"></div>
		</div>
		<p class="ad-form" id="generalize">保存并推广</p>
	</div>
	</form>
	<!-- 弹窗 -->
	<div class="popup" id="popwiow">
		<div class="popup-window">
			<p class="pop-p1">前往推广</p>
			<p class="pop-p2">让您的生意传遍全国</p>
			<div class="pop-choose clearfix"><span class="fl pop-p3 borrigt" id="popcancel">取消</span><span onclick="beforesubmit()" class="fl pop-p3">前往</span></div>
		</div>
	</div>
	<script type="text/javascript">
		$("#generalize").click(
			function(){
				$("#popwiow").show();
			});
		$("#popcancel").click(
			function(){
				$("#popwiow").hide();
			});
		function beforesubmit()
		{
			e = document.advert;
			var formData = new FormData();
			formData.append("file",$("#upload")[0].files[0]);
			formData.append("title",e.title.value);
			formData.append("descri",e.descri.value);
			formData.append("tel",e.tel.value);
			formData.append("link_id",e.link_id.value);
			formData.append("user_id",e.user_id.value);
			formData.append("token",e.token.value);
			Url = 'http://shop.m9n.com/api/adv/add';
			$("#popwiow").hide();
			  $.ajax({  
			  		type:"POST", 
			        url:Url,  
			        dataType: "json",   
			        data:formData, 
			       // 告诉jQuery不要去处理发送的数据
					processData : false, 
					// 告诉jQuery不要去设置Content-Type请求头
					contentType : false,
			        jsonp:'callback',  
			        success:function(result) {  
			            alert(result['msg']); 
			        }
			    });  
		}
	</script>
</body>
</html>
<?php 
if(isset($_GET['msg']))
	{
		alert_msg($_GET['msg']);
	}
?>