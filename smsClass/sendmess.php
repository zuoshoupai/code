<?php 
	session_start();
	ini_set('date.timezone','Asia/Shanghai');
	$conn =@mysql_connect("localhost","root",'5ervers;');
    mysql_select_db('kaka'); 
	if(isset($_POST['goto']) && $_POST['goto']=='send')
	{
		$telArr = explode(',',$_POST['tel']);
		$content = trim($_POST['content']);
		if($_SESSION['code'] == $_POST['validate'])
		{
			$num = 0;
			$error = 0;
			foreach($telArr as $k=>$v)
			{
				require_once('sendSMSClass.php');
				$SMS = new SMS();
				$mobile = trim($v);
				$result = $SMS->sendMessage($mobile,$content);  //进行发送
				//var_dump($result);
				if(strpos($result,"success")>-1) {
					$num++;
				} else {
					$error++;
				}
				  //输出result内容，查看返回值，成功为success，错误为error，（错误内容在上面有显示）
			}
			echo "success:$num;error:$error;";
			$_SESSION['code'] = '';
		}else{
			echo "nnd,please don't old post!";
		}
	} 
	$validate = rand(1,10000);
	$_SESSION['code'] = $validate;
?>
<!DOCTYPE html>
<html>
<head>
    <title>卡卡：你的随身金融专家</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="full-screen" content="yes"> 
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
   
    <style type="text/css">
        * {margin: 0;padding: 0;}
        html,body {
          width: 80%;
          height: 100%;
        }
        body {
          font-family: "微软雅黑, arial, sans-serif";
          font-family: PingFangSC-Regular, sans-serif;
          background: #efeff4;
		  margin:20px auto;
        }
        a {
          -webkit-tap-highlight-color: transparent;
          text-decoration: none;
          color: #000;
        }
        
    </style>
    
</head>
<body>
    <div class="head">
		<h3 >短信发送</h3>
    </div>
    <div class="body">
		<form method="post">
			<label>短信内容</label><br/>
			<textarea name="content"><?php echo isset($content)?$content:'';?></textarea><br/><br/><br/>
			<label>收信手机（以,间隔）</label><br/>
			<textarea name="tel"></textarea>
			<br/>
			<input type="hidden" name="validate" value="<?php echo $validate;?>">
			<input type="submit" name="goto" value="send">
		</form>
	</div>
	<script>
 
</script>

</body>
</html>