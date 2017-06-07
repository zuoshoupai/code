<?php
	$root_url = "https://www.stpaulsfriends.club";
	$wechat_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxc082f8480924d81d&redirect_uri=https%3A%2F%2Fus.m9n.com%2FPublic%2Ftipask_wechat%2Fcall_back.php&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
	$login_url = "login.php";

	function alert_msg($msg)
	{
        echo "<script language=\"JavaScript\">alert('".$msg."');</script>";
	}
	function go_url($url,$msg='')
	{
		if(!empty($msg))
		{
			echo "<script language=\"JavaScript\">alert('".$msg."');</script>";
		}
		echo "<script>window.location.href='".$url."';</script>";
    }
    function page_cut($count,$num,$url='?')
    {
    	$page_num = ceil($count/$num);
    	if($page_num==1)
    	{
    		return '';
    	}
    	if(empty($_GET['page']))
    	{
    		$page_now = 1;
    	}else{
    	    $page_now = $_GET['page'];
    	}
    	if($page_now<=1)
    	{
    		$page_up = '';
    	}else{
    		$page_up = '<li style="margin:0 0.1rem;float:left;"><a href="'.$url.'&page='.($page_now-1).'">上一页</a></li>';
    	}
    	if($page_now>=$page_num)
    	{
    		$page_down = '';
    	}else{
    		$page_down = '<li style="margin:0 0.1rem;float:left;"><a href="'.$url.'&page='.($page_now+1).'">下一页</a></li>';
    	}
    	$page_str = '<div style="text-align: center"><ul style="list-style:none;display: inline-block;height:0.5rem">';
							$page_str = $page_str.$page_up;
		for($i=1;$i<=$page_num;$i++)
		{
			if($i==$page_now)
			{
				$color = ' style="color:red;" ';
			}else{
				$color = ' style="color:none;" ';
			}
			$page_str = $page_str.'<li style="width:0.4rem;margin:0 0.1rem;float:left;"><a '.$color.' href="'.$url.'&page='.$i.'">'.$i.'</a></li>';
		}
		$page_str = $page_str.$page_down.'</ul></div>';
		return $page_str;
    }
?>