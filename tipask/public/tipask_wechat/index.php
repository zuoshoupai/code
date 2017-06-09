<?php
	require('conn.php');
	session_start();
	if(!empty($_GET['categorie']))
	{
		$list_default = $_GET['categorie'];
	}
	
	//获取分类列表
	$ch = curl_init();//初始化一个资源
	curl_setopt($ch,CURLOPT_URL,$root_url.'/api/categorie/list');//设置我们要获取的网页
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
	$data = curl_exec($ch);
	curl_close($ch);
	$content = json_decode($data);
	if($content->code==0){
		$list = $content->data;
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>微信营销</title>
	<link rel="stylesheet" href="css/base.css">
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<style type="text/css">
	.in-tab{padding-left:4%;width: 96%;border-bottom: 1px solid #e8e8e8;}

	.in-tab li{float: left;font-size: 0.375rem;line-height:1rem;margin-right: 6%;}

	.fcolorb{color:#1792e8;border-bottom:3px solid #1792e8;padding-bottom:0.25rem; }
	
	
	.in-mid2{padding-bottom: 0.2rem;border-bottom:1px solid #e8e8e8;}
	.in-img2{width: 30%;height:2rem; }
	
	</style>
	
</head>
<body>
	<!-- 导航 -->
	<div class="in-tab">
		<ul class="clearfix">
			<?php
				foreach($list as $k=>$v)
				{
					if(empty($list_default)&&$k==0)
					{
						$list_default = $v->id;
					}
					if(($v->id)==$list_default){
						echo "<li ><a href='?categorie=".($v->id)."' class='fcolorb'>".($v->name)."</a></li>";
					}else{
						echo "<li><a href='?categorie=".($v->id)."'>".($v->name)."</a></li>";
					}
				}
			?>
		</ul>
	</div>
	<!-- 详细 -->
	<div class="mid">
		 <!-- 第一部分 -->
		<div class="in-mid">
			<ul>
				<?php
						if(isset($_GET['page']))
						{
							$page_now = $_GET['page'];
						}else{
							$page_now = 1;
						}
						$post = 'cate='.$list_default.'&count_show=1&page='.$page_now;
						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL,$root_url.'/api/article/list');
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
						curl_setopt($ch,CURLOPT_POST,1);//使用post提交数据
						curl_setopt($ch,CURLOPT_POSTFIELDS,$post);//设置 
						$listData = curl_exec($ch);//这里会返回token，需要处理一下。
						$listData = json_decode($listData,true);
						curl_close($ch);
						if($listData['code']==0)
						{
							$count = $listData['count'];
							$url = 'index.php?categorie='.$list_default;
							$page_str = page_cut($count,10,$url);
							$article_list = $listData['data'];
							foreach($article_list as $k=>$v)
							{
								echo '<a href="article.php?article_id='.$v['id'].'">
										<li class="clearfix">
											<span class="in-img1 fl"><img src="'.$v['logo'][0].'" alt=""></span>
											<div class="fl in-wz">
												<p class="in-p1">'.$v['title'].'</p>
												<p class="in-p2">'.$v['source'].'</p>
											</div>
										</li>
									</a>';

							}
						}
				?>
				
			</ul>
			
				
				<?php echo $page_str;?> 
			
		</div>
		<div style="height:0.5rem;"></div>
	</div>
	 <!-- 下面分页 -->
	<div class="in-binh clearfix">
		<div class="in-binh1 fl">
			<a href="index.php">
			<span class="binh-img1"><img src="images/wenzhang.png" alt=""></span>
			<p class="binhp">文章</p>
			</a>
		</div>
		<div class="in-binh1 fl">
			<a href="my.php">
			<span class="binh-img2"><img src="images/wode.png" alt=""></span>
			<p class="binhp">我的</p>
			</a>
		</div>
	</div>
</body>
</html>
 