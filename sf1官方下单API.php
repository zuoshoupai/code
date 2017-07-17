<?php
function doCurl($url,$data){	
		$ch=curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_HEADER,1);
                curl_setopt($ch, CURLOPT_TIMEOUT,5);
                curl_setopt($ch,CURLOPT_POST, true);
                $header = FormatHeader($url,$data);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
                $rs=curl_exec($ch);
				return $rs;
}
function FormatHeader($url,$data){ 
                $temp = parse_url($url); 
                $query = isset($temp['query']) ? $temp['query'] : ''; 
                $path = isset($temp['path']) ? $temp['path'] : '/'; 
                $header = array ( 
                "POST {$path}?{$query} HTTP/1.1", 
                "Host: {$temp['host']}", 
                "Content-Type: application/json",  
                "Content-length: ".strlen($data), 
                "Connection: Close" 
                );
                return $header; 
        } 
//请求token
/*
$url = 'https://open-sbox.sf-express.com/public/v1.0/security/access_token/sf_appid/00030953/sf_appkey/6EC4FD5313ADF4D400BB01FA7E0098D4';
$data='{"head":{"transType":"301","transMessageId":"201505141933000001"}}';
*/

//下单
$url = 'https://open-sbox.sf-express.com/rest/v1.0/order/access_token/DE29B6961A13481D54BF9C7FA4BE5928/sf_appid/00030953/sf_appkey/6EC4FD5313ADF4D400BB01FA7E0098D4';
$data = '{
 "head":{
  "transMessageId":"201404120000000001",
  "transType":200
 },
 "body":{
  "addedServices":[
   {
    "name":"CUSTID",
    "value":"7550010173"
   },
   {
    "name":"",
    "value":""
   }
  ],
  "cargoInfo":{
   "cargo":"iphone5s",
   "cargoAmount":"4670.00",
   "cargoCount":"1000",
   "cargoTotalWeight":121000,
   "cargoUnit":"部",
   "cargoWeight":"121.00",
   "parcelQuantity":1
  },
  "consigneeInfo":{
   "address":"世界第一广场",
   "city":"深圳",
   "company":"顺丰",
   "contact":"黄飞鸿",
   "country":"中国",
   "mobile":"18588413321",
   "province":"广东",
   "shipperCode":"518100",
   "tel":"075533915561"
  },
  "custId":"7550010173",
  "payArea":"755CQ",
  "deliverInfo":{
   "address":"上地",
   "city":"朝阳",
   "company":"京东",
   "contact":"李卡卡",
   "country":"中国",
   "mobile":"13612822894",
   "province":"北京",
   "shipperCode":"787564",
   "tel":"010-95123669"
  },
  "expressType":2,
  "isDoCall":2,
  "isGenBillNo":1,
  "needReturnTrackingNo":1,
  "orderId":"10000001201404120000000001",
  "payMethod":3,
  "remark":"易碎物品，小心轻放",
  "sendStartTime":"2014-08-26 07:31:55"
 }
}';
$ppp= doCurl($url,$data);
var_dump($ppp);