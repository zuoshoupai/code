<?php       
//目标地址
$content=_get_contents('http://www.pigai.org/zt/2019/?act=downprint&t=2');  
$data=array();

//存储目录
$save_path=dirname(dirname(dirname(__FILE__)))."/upload/xuesheng";
if(!is_dir($save_path)){
	mkdir($save_path,0777,true);
	chmod($save_path,0777); 
}  
$rule_mean ="/<p>\s{0,4}<span>(.*?)<\/span>\s{0,4}<span>(.*?)<\/span>\s{0,4}<a href=\"(.*?)\">/";
if(preg_match_all($rule_mean,$content,$list_str)){
	$i=1;
	foreach($list_str[1] as $k=>$v){
		$data[]=array(0=>$v,1=>$list_str[2][$k],2=>$list_str[3][$k]);
		if(empty($v)){
			continue;
		}
		echo $i;echo "\r\n";
        $i++; 
        //拼接图片源地址
		echo $file_target='http://www.pigai.org/zt/2019/'.$list_str[3][$k];
		$fileName=iconv("UTF-8","GB18030",$v.'_'.$list_str[2][$k].'优秀指导教师奖.jpg');
		echo "\r\n";
		$file =_get_contents($file_target); 
		file_put_contents($save_path.'/'.$fileName,$file); 
	} 
}else{
    echo 'mean not found';
}
function _get_contents($root_url,$op=1){  
    if(strpos($root_url,'https')!==false || $op==2){
        $ch = curl_init();//初始化一个资源
        $headers = array(); 
        $headers[] = 'User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11';
        $headers[]='X-FORWARDED-FOR:111.222.333.9';
        $headers[] = 'CLIENT-IP:111.222.333.9';
        curl_setopt($ch,CURLOPT_URL,$root_url);//设置我们要获取的网页
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $headers);  
        curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER, false); //关闭ssl
        curl_setopt($ch, CURLOPT_REFERER, "http://www.test.com");   
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }else{
        return	file_get_contents($root_url); 
        
    }
}
