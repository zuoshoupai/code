<?php
class craw{
	public $root_url;
	public $root_dir ;
	public $root_path;
	public $host_path;
	public function __construct($root_url){
		$this->root_url = $root_url;
		$fileinfo = pathinfo($root_url);
		$this->root_path = $fileinfo['dirname']; //网站文件目录
		
		$url_info = explode('/',$root_url);
		$this->host_path = $url_info[0].'//'.$url_info[2];
		$this->root_dir = time();
	}
	public function run(){
		
		$data = $this->parseDom($this->root_url);
		$this->downloadFile($data['js'],1);
		$this->downloadFile($data['css'],2);
		$this->downloadFile($data['img'],3);
		var_dump($data);die();
		$data = $this->parseloadFile($data['js'],1,$data['content']);
		$data = $this->parseloadFile($data['css'],2,$data['content']);
		$data = $this->parseloadFile($data['img'],3,$data['content']);
		//写入index.html
		$file_dir = $this->root_dir;
		if(!is_dir($file_dir)){
			mkdir($file_dir,'0777',true);
		}
		file_put_contents($file_dir.'/index.html',$data);
	}
	public function parseDom($root_url){
		echo $root_url;
		$ch = curl_init();//初始化一个资源
		curl_setopt($ch,CURLOPT_URL,$root_url);//设置我们要获取的网页
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//关闭直接输出
		$data = curl_exec($ch);
		curl_close($ch);
		$encoding = mb_detect_encoding($data, array("ASCII","UTF-8","GB2312","GBK","BIG5")); 
		$keytitle = iconv($encoding,"UTF-8",$data); 
		//js
		$rule_js = "/<[\s]*script[^>]+src=[\s]*[\'\"](.*).js/";
		preg_match_all($rule_js,$data,$result_js); 
		$js_arr = $result_js[1];
		
		//css
		$rule_css = "/<[\s]*link[^>]+href=[\s]*[\'\"](.*?).css/";
		preg_match_all($rule_css,$data,$result_css); 
		$css_arr = $result_css[1];
		
		//image
		$rule_image = "/<[\s]*img[^>]+src=[\s]*[\'\"](.*?)[\'\"]/";
		preg_match_all($rule_image,$data,$result_image);
		$img_arr = $result_image[1]; 
		
		$datas = array();
		$datas['js'] = $js_arr;
		$datas['css'] = $css_arr;
		$datas['img'] = $img_arr;
		$datas['content'] = $data;
		return $datas;
	}
	/**
	*** 下载文件函数
	*** $url_arr 文件数组，不带拓展名
	*** $type 文件类型 1：js 2: css 3: image
	*/
	public function downloadFile($url_arr,$type)
	{
		
		global $root_path,$host_path,$Dir;
		if($type == 1){
			$extension_name = '.js';
			$file_dir = $Dir.'/js';
		}elseif($type==2){
			$extension_name = '.css';
			$file_dir = $Dir.'/css';
		}elseif($type ==3){
			$extension_name = '';
			$file_dir = $Dir.'/images';
		}
		foreach($url_arr as $url)
		{
			if(strpos($url,'http')!==false){
				$file_target = $url.$extension_name;
			}elseif(substr($url,0,1)=='/'){
				$file_target = $host_path.$url.$extension_name;
			}else{
				$file_target = $root_path.'/'.$url.$extension_name;
			}
			$fileinfo = pathinfo($url.$extension_name);
			$fileName = $fileinfo['basename'];
			$file = file_get_contents($file_target);
			if(!is_dir($file_dir)){
				mkdir($file_dir,'0777',true);
			}
			file_put_contents($file_dir.'/'.$fileName,$file);
		}
	}
	/**
	*** 解析文件函数
	*** $url_arr 文件数组，不带拓展名
	*** $type 文件类型 1：js 2: css 3: image
	*** 解析文件内容
	*/
	public function parseloadFile($url_arr,$type,$content)
	{
		
		global $root_path,$host_path;
		if($type == 1){
			$extension_name = '.js';
			$file_dir = 'js';
		}elseif($type==2){
			$extension_name = '.css';
			$file_dir = 'css';
		}elseif($type ==3){
			$extension_name = '';
			$file_dir = 'images';
		}
		foreach($url_arr as $url)
		{
			$fileinfo = pathinfo($url.$extension_name);
			$fileName = $fileinfo['basename'];
			$content = str_replace($url.$extension_name,$file_dir.'/'.$fileName,$content);
		}
		return $content;
	}
	public function creatzip()
	{
		$Dir = $this->root_dir;
		$files = list_dir($Dir.'/');
		$filename = $Dir.".zip"; //最终生成的文件名（含路径）   
		if(!file_exists($filename)){   
		//重新生成文件   
			$zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释   
			if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) 
			{   
				exit('无法打开文件，或者文件创建失败');
			}   
			foreach( $files as $val){   
				if(file_exists($val)){   
					$zip->addFile( $val, str_replace($Dir.'/','',$val));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下   
				}   
			}   
			$zip->close();//关闭
		}
	}

	//获取文件列表
	public function list_dir($dir){
			$result = array();
			if (is_dir($dir)){
				$file_dir = scandir($dir);
				foreach($file_dir as $file){
					if ($file == '.' || $file == '..'){
						continue;
					}
					elseif (is_dir($dir.$file)){
						$result = array_merge($result, list_dir($dir.$file.'/'));
					}
					else{
						array_push($result, $dir.$file);
					}
				}
			}
			return $result;
	}	
   
}   
$craw = new craw('http://www.w3school.com.cn/php/func_directory_getcwd.asp');
$craw->run();
	
	
	