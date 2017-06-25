<?php
if($_GET['optr']=='del')
{
	$data['api'] = $_POST['api'];
	$fp=fopen('url.txt','a+');
	$str = '';
	while(!feof($fp))
	{
		$buffer=fgets($fp,4096);
		$web = explode(',',$buffer);
		if($web[0] != $data['api'])
		{
			$str = $str.$buffer."\r\n";
		}
		
	}
	$fp=fopen('url.txt','w');
	fwrite($fp,$str);
	fclose($fp);
	header("Location: index.php"); 
	
}
if($_GET['optr']=='add')
{
	$data['url'] = $_POST['url'];
	$data['name'] = $_POST['name'];
	$fp=fopen("url.txt",'a+');
	fwrite($fp,"\r\n".$data['url'].','.$data['name']);
	fclose($fp);
	// 销毁文件资源句柄变量
	unset($fp);
	header("Location: index.php"); 
	
}
$web_arr = array();
$i = 0;
if (file_exists('url.txt')) 
{ 
	$fp=fopen('url.txt','a+');
	while(!feof($fp))
	{
		$i++;
		$buffer=fgets($fp,4096);
		$web = explode(',',$buffer);
		if(!empty($web[1]))
		{
			$web_arr[$i]['url'] = $web[0];
			$web_arr[$i]['name'] = $web[1];
		}
	}
	fclose($fp);
	// 销毁文件资源句柄变量
	unset($fp);
} 
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>VIP online</title>  
<link href="bootstrap.min.css" rel="stylesheet" type="text/css">

<style>
	body{
		margin: 0 auto;
		text-align: center;
	}
	.container {
	  max-width: 580px;
	  padding: 15px;
	  margin: 0 auto;
	}
	

</style>
</head>
 <script language="javascript">
   var ppp = '';
   function url_select(){
	 add = document.movie.api.value;
	 if(add==-99)
	 {
	 	document.getElementById('search_button').style.display="none"; 
	 	document.getElementById('add_button').style.display="block"; 
	 	document.getElementById('url_befor').innerHTML = "新接口";
		document.getElementById('add_web').style.display="table"; 
	 	
	 }else{
	 	document.getElementById('search_button').style.display="block"; 
		document.getElementById('delete_button').style.display="none";
		document.getElementById('add_web').style.display="none"; 
	 	document.getElementById('add_button').style.display="none";
	 	document.getElementById('url_befor').innerHTML = "链接";  
	 }
   }
   function show_del(){
	   var j = document.getElementById('search_button').style.display; 
	   if (j=='none')
	   {
		   document.getElementById('delete_button').style.display="none"; 
		   document.getElementById('search_button').style.display="block"; 
		}else{
		   document.getElementById('delete_button').style.display="block"; 
		   document.getElementById('search_button').style.display="none"; 
	   }
	    
   }
   function showing(){
      url = document.movie.url.value;
	  api = document.movie.api.value;
	  document.movie.action = api+url;
	  document.movie.target = '_self';
	  if(url==''){
		alert('地址为空');
	  }else{
		document.movie.submit();
	  } 
   }
   function adding(){
	  url = document.movie.url.value;
	  name = document.movie.name.value;
	  if(url==''){
		  alert('地址都不填，咋个意思啊！');
		  return false;
	  }
	  if(name==''){
		  alert('名字也不写，还添加个毛啊！');
		  return false;
	  }
	  var clo=window.confirm('确定要添加新接口吗？');//确定关闭
      if(clo){
		   this.ppp = 'add';
           document.getElementById('add_password').style.display = 'block';
	  }
	}
	function delete_web(){
	  document.movie.action = '?optr=del';
	  document.movie.target = '_self';
	  var clo=window.confirm('确定要删除这个接口吗？');//确定关闭
      if(clo){
		   this.ppp = 'del';
		   document.getElementById('add_password').style.display = 'block';
	  }
	}
   function beforesubmit(k){
	  if(k==0)
	  {
		document.getElementById('password').value = '';
		document.getElementById('add_password').style.display = 'none';
		return false;
	  }
	  var pass = document.getElementById('password').value;
	  if(pass!='lai258')
	  {
		  document.getElementById('add_password').style.display = 'none';
		  document.getElementById('password').value = '';
		  alert('汝非阿泽，请闪一边！');
		  return false;
	  }
	  document.movie.action = "?optr="+this.ppp;
	  document.movie.target = '_self';
	  if(url==''){
		alert('地址为空');
	  }else{
		document.movie.submit();
	  } 
   }
 
 </script>

<body>
<body style="background-image: url('MAIN201411021554000148201871849.jpg');background-attachment: fixed;background-repeat: no-repeat;background-size: cover;-moz-background-size: cover">
<div class="container">
	<div class="header">
        <ul class="nav nav-pills pull-right" role="tablist"> 
        </ul>
        <h3 class="text-muted" align="left"></h3>
     </div>
	<hr>

	<div class="panel panel-primary" style="margin:1% 1% 1% 1%;background: rgba(255, 251, 251, 0.7)">
                <div class="panel-heading bk-bg-primary">
                    <h6>就在不远处</h6>
					<h6><img border="0" src="logo.png" width="298" height="75"></h6>
              </div>
				<div class="bar mgz">
     <div class="geren2 mgz cen"> 
                <div class="panel-body">
					<form id="search_form" method="post" name="movie" action='' >
					<div class="input-group">
						<span class="input-group-addon" id="url_befor">链接</span><input style="background: rgba(255, 251, 251, 0.7)" name="url" type="text" class="form-control" id="url" size="90" placeholder="输入视频地址"> <br/> 
					</div>
					<br/>
					<div class="input-group" id="add_web" style="display:none;">
						<span class="input-group-addon" >名&nbsp;&nbsp;&nbsp;称</span><input style="background: rgba(255, 251, 251, 0.7)" name="name" type="text" class="form-control" size="90" placeholder="起个好听的名称"> <br/> 
					</div>
					<br/>
				   <img id="loading" src='images/loading.gif' alt='Announcement' style="display: none;" />
				   <select name="api" style="height:22px" onchange="url_select()">
						
						<?php
						  foreach ($web_arr as $v)
						  {
						  	echo "<option value='".$v['url']."'>".$v['name']."</option>";
						  }
						?>
						<option value='-99'>添加新地址</option>
					</select>
					<div class="login-button">
					    <br/>
						 <input class="btn btn-primary btn-block bk-margin-top-10" type="butoon" id="search_button" onclick=showing()  value="观看">
						 <input class="btn btn-primary btn-block bk-margin-top-10" type="butoon" id="delete_button" onclick=delete_web() value="删除" style="display:none">
						 <input class="btn btn-primary btn-block bk-margin-top-10" type="butoon" id="add_button" onclick=adding() value="添加" style="display:none">
					</div>
				       </form>
             
			<br/>
			<br/>
				<div style="width: 95%; margin-left: auto;  margin-right: auto; text-align: center;">
				</div>
					


				<div class="alert alert-warning" role="alert"> <i class="glyphicon glyphicon-bullhorn"></i> <strong>公告</strong>:本站永久免费提供vip视频观看!</div>


				<hr><div class="container-fluid">
						<a target="_black" class="btn btn-default btn-sm" ondblclick="show_del()">
							阿泽改良版</a>  
				
				
				</div>
		</div>
		<div id="add_password" style="width:400px;height:120px;position:fixed;left:50%;top:50%;margin-top:-60px;margin-left:-200px;padding-top:10px;background:#ccc;z-index:999;display:none;">
			<a onclick="beforesubmit(0)" style="color:red;position:absolute;left:380px;top:0px;width:20px;height:20px;background:#fff;">X</a>
			<p>输入密码</p>
			<p><input type="password" id="password" ></p>
			<p><input type="submit"  onclick="beforesubmit(1)" value="确定"></p>
		<div>

					 
</body>
</html>