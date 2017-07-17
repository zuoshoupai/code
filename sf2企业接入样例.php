<?php
require_once('sf2.php');
    //==================================================================  
    //功能：   获取顺丰快递运单号  
    //参数：     
    /**  
     * 推送订单 （to 顺丰）  
     * orderId              订单号  
     * j_company            寄件方公司名称  
     * j_contact            寄件方联系人  
     * j_tel                寄件方联系电话  
     * j_address            寄件地址  
     * j_province           寄件方省份 (选填) 
     * j_city               寄件方城市 (选填) 
     * d_company            到件方公司名称 (选填) 
     * d_contact            到件方联系人  
     * d_tel                到件方联系电话  
     * d_address            到件方地址  
     * d_province           到件方省份 (选填) 
     * d_city               到件方城市 (选填) 
     * name                 商品名称(选填)  
     * mailno               运单号 (选填) 
     **/    
    //==================================================================  
    //顺丰物流  
    /*orderservice   routeservice*/  

     // $d_province           到件方省份 (选填) 
     // $d_city               到件方城市 (选填) 
     // $name                 商品名称(选填)  
     // $mailno               运单号 (选填) 
     // 
     class shunfeng{

		private $BSP_head = 'ZD01';                                                      //客户卡号,校验码  
		private $BSP_url = 'http://bspoisp.sit.sf-express.com:11080/bsp-oisp/sfexpressService';  //快递类服务接口url  
		private $BSP_checkWord = 'aankmi8Npfo3'; //checkbody  

		private $orderId     =        "BJ12345678";  
		private $j_company   =       "M9系统";            
		private $j_contact   =        "李先生"; 
		private $j_tel       =        11111111111; 
		private $j_address   =        "朝阳区四惠";  
		private $j_province  =         "";
		private $j_city      =        "";
		private $d_company   =       "";
		private $d_contact   =        "张三"; 
		private $d_tel       =       88888888; 
		private $d_address   =        "山东省青岛市";  
              
        public  function getBSPexpress( ){  
             $orderId     =        "BJ12345678";  
             $j_company   =       "M9系统";            
             $j_contact   =        "李先生"; 
             $j_tel       =        11111111111; 
             $j_address   =        "朝阳区四惠";  
             $j_province  =         "";
             $j_city      =        "";
             $d_company   =       "";
             $d_contact   =        "张三"; 
             $d_tel       =       88888888; 
             $d_address   =        "山东省青岛市";  
              
			$body = '<?xml version="1.0" encoding="UTF-8" ?><Request service="OrderService" lang="zh-CN"><Head>'.$this->BSP_head.'</Head><Body><Order orderid="'.$orderId.'" express_type="1" j_company="'.$j_company.'" j_contact="'.$j_contact.'" j_tel="'.$j_tel.'" j_address="'.$j_address.'" d_company="'.$d_company.'" d_contact="'.$d_contact.'" d_tel="'.$d_tel.'" d_address="'.$d_address.'" parcel_quantity="1" pay_method="" custid="" j_shippercode="" d_deliverycode="" cargo_total_weight="" sendstarttime="" mailno="" remark="测试" need_return_tracking_no="1" /></Order></Body></Request>';   
			$newbody = $body.$this->BSP_checkWord;         
            $md5 =  md5($newbody,true);      
			$verifyCode = base64_encode($md5);    
			$fields = array('xml'=>$body,'verifyCode'=>$verifyCode);    
			$parambody =  http_build_query($fields, '', '&');    
			$res = $this->post2($this->BSP_url,$parambody);  

			if(!$res){    
				return Message(400,'error','获取超时，请重试','');  
			}else{
				return $res;
			}
			$parseXml = parseXml::getInstance();  
			$newAry = $parseXml->xmlToArray($res);
        }  
              
           //顺丰快递发送xml报文使用    
           public  function post2($url,$params)     
           {     
                 $curlObj = curl_init();    
                 curl_setopt($curlObj, CURLOPT_URL, $url); // 设置访问的url    
                 curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //curl_exec将结果返回,而不是执行    
                 curl_setopt($curlObj, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded;charset=UTF-8"));    
                 curl_setopt($curlObj, CURLOPT_URL, $url);    
                 curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, FALSE);    
                 curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, FALSE);    
                 curl_setopt($curlObj, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);    
                    
                 curl_setopt($curlObj, CURLOPT_CUSTOMREQUEST, 'POST');          
                    
                 curl_setopt($curlObj, CURLOPT_POST, true);    
                 curl_setopt($curlObj, CURLOPT_POSTFIELDS, $params);           
                 curl_setopt($curlObj, CURLOPT_ENCODING, 'gzip');    
              
                 $res = @curl_exec($curlObj);    
                     
                 curl_close($curlObj);    
              
                 if ($res === false) {    
                       $errno = curl_errno($curlObj);    
                       if ($errno == CURLE_OPERATION_TIMEOUTED) {    
                           $msg = "Request Timeout:   seconds exceeded";    
                       } else {    
                           $msg = curl_error($curlObj);    
                       }    
                       echo $msg;    
                       $e = new XN_TimeoutException($msg);               
                       throw $e;    
                   }     
                return $res;    
            }    
        }

    $sf = new shunfeng;
    $result = $sf->getBSPexpress( );
	var_dump($result);

?>