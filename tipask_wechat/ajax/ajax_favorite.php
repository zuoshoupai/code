<?php
	require('../conn.php');
	session_start();
	$user_id     = $_SESSION['user_id'];
	$token       = $_SESSION['token'];
	$is_favorite = $_GET['isfav'];
	$article_id  = $_GET['aid'];

	$ch = curl_init();
	if($is_favorite == 1){
		curl_setopt($ch,CURLOPT_URL,$root_url.'/api/favorite/delete');
	}else{
		curl_setopt($ch,CURLOPT_URL,$root_url.'/api/favorite/add');
	}

	$post = 'article_id='.$article_id.'&user_id='.$user_id.'&token='.$token;
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
	curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
	$listData = curl_exec($ch);//这里会返回token，需要处理一下。
	$listData = json_decode($listData,true);
	curl_close($ch);

	if($listData['code'] ==6)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$root_url.'/api/favorite/delete');
		$post = 'article_id='.$article_id.'&user_id='.$user_id.'&token='.$token;
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
		$listData = curl_exec($ch);//这里会返回token，需要处理一下。
		$listData = json_decode($listData,true);
		curl_close($ch);

	}
	if($listData['code'] ==4)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$root_url.'/api/favorite/add');
		$post = 'article_id='.$article_id.'&user_id='.$user_id.'&token='.$token;
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
		$listData = curl_exec($ch);//这里会返回token，需要处理一下。
		$listData = json_decode($listData,true);
		curl_close($ch);

	}
	echo  json_encode($listData);
?>