<?php
header("Access-Control-Allow-Origin: *");
	/**
     * 哆啦宝接口
	 *  zhaoyz
     */
class dlbpayController {
	public $dlbpay = array(
		"dlb_access_key"=>"e84fab7878194925a28f5ee8e926e77f913cc646",
		"dlb_secret_key"=>"d9bb7fc0493647a48e937ded073e342280dd45ac",
		"dlb_path_createorder"=>"/v1/agent/order/payurl/create",
		"dlb_path_scaneorder"=>"/v1/agent/passive/create",
		"dlb_path_payresult"=>"/v1/agent/order/payresult",
		'dlb_path_wholePayResult'=>"/v1/agent/order/customer/batch/query",
		'dlb_path_orderRefund'=>"/v1/agent/order/refund",
		"dlb_agent_num"=>"10001014472963095391100", 
		'machineNum'=>"10011014696701293260007", 
	);
    public function index(){
        
    }
    public function config($str)
	{
		return $this->$str;
	} 
    /**
     * 创建订单接口
     */
    public function createDLBPay($customer_num,$shop_num,$request_num,$amount,$callback_url=''){
		
        $data['dlb_customer_num'] = $customer_num;//$_POST['customer_num'];
        $data['dlb_shop_num'] = $shop_num;//$_POST['shop_num'];
        $data['request_num'] = $request_num;//$_POST['request_num']; //注：必须为18-32位纯数字
        $data['amount'] = $amount;//$_POST['amount'];
        $data['callback_url'] = $callback_url;//$_POST['callback_url'];
        $req_bak = $this->request_createpay($data);
        echo json_encode($req_bak);
    }
    /**
     * 微信(支付宝)主动扫
     */
    public function creatScanPay($customer_num,$shop_num,$request_num,$amount,$authCode){ 
        $data['dlb_customer_num'] = $customer_num;//$_POST['customer_num'];
        $data['dlb_shop_num'] = $shop_num;//$_POST['shop_num'];
        $data['request_num'] = $request_num;//$_POST['request_num'];  
        $data['amount'] = $amount;//$_POST['amount']; 
		$data['authCode'] = $authCode;
        $req_bak = $this->request_createScanePay($data);
        echo json_encode($req_bak);
    }
	/**
	* 查询支付结果调用
	*/
	public function checkPayResult($customer_num,$shop_num,$request_num,$type=0)
	{
		$data['dlb_customer_num'] = $customer_num;//$_POST['customer_num'];
        $data['dlb_shop_num'] = $shop_num;//$_POST['shop_num'];
        $data['request_num'] = $request_num;//$_POST['request_num'];  
		$data['type'] = $type;
        $req_bak = $this->request_checkPayResult($data);
        return $req_bak;
	}
	/**
	* 批量查询订单
	*/
	public function wholePayResult($customer_num,$shop_num,$status,$start_time,$end_time,$page_num)
	{
		$data['dlb_customer_num'] = $customer_num;//$_POST['customer_num'];
        $data['dlb_shop_num'] = $shop_num;//$_POST['shop_num'];
        $data['status'] = $status;//$_POST['request_num'];  
		$data['startTime'] = $start_time;
		$data['endTime'] = $end_time;
		$data['pageNum'] = $page_num; 
        $req_bak = $this->request_wholePayResult($data);
        return $req_bak;
	}
	/**代理商退款
	*
	*/
	public function orderRefund($customer_num,$shop_num,$requestNum)
	{
		$data['dlb_customer_num'] = $customer_num;//$_POST['customer_num'];
        $data['dlb_shop_num'] = $shop_num;//$_POST['shop_num'];
        $data['requestNum'] = $requestNum;//$_POST['request_num'];  
		$req_bak = $this->request_orderRefund($data);
		return $req_bak;
	}
    /**
     * 退款接口
     */
    public function refundDLBPay(){
        $data['dlb_customer_num'] = $_POST['customer_num'];
        $data['dlb_shop_num'] = $_POST['shop_num'];
        $data['request_num'] = $_POST['request_num']; //注：必须为18-32位纯数字
        $req_bak = $this->request_refundpay($data);
        echo json_encode($req_bak);
    }
    
    /**
    * 创建哆啦宝微信支付
    */
    protected function request_createpay($data){
        $PayConfig = $this->config('dlbpay');
        if(is_array($PayConfig)){
            $pay_data['accesskey'] = $PayConfig['dlb_access_key'];
            $pay_data['secretkey'] = $PayConfig['dlb_secret_key'];
            $pay_data['timestamp'] = $this->getMillisecond();
            $pay_data['path'] = $PayConfig['dlb_path_createorder']; //'/v1/agent/order/payurl/create';
            $sign_data = array(
                'agentNum'=>$PayConfig['dlb_agent_num'],            // 代理商编号
                'amount'=>$data['amount'],                          // 金额--请求传递
                'callbackUrl'=>$data['callback_url'],               // 回调服务器链接--请求传递
                'customerNum'=>$data['dlb_customer_num'],           // 哆啦宝商户号--请求传递
                'requestNum'=>$data['request_num'],                 // 订单号--请求传递  注：必须为18-32位纯数字
                'shopNum'=>$data['dlb_shop_num'],                   // 哆啦宝店铺号--请求传递
                'source'=>'API',
            );
            $pay_data['body'] = json_encode($sign_data);
            $infoArr = $this->creatTokenPost($pay_data);
            switch ($infoArr['result']) {
                case 'success'://成功
                    $payurl = $infoArr['data']['url'];
                    return  array('code'=>200,'msg'=>'订单支付创建成功','url'=>array('payurl'=>$payurl));
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>'订单支付创建失败');
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，支付调用失败');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单支付创建失败');
        }
    }
    /**
    * 主动扫描创建哆啦宝微信支付
    */
    protected function request_createScanePay($data){
        $PayConfig = $this->config('dlbpay');
        if(is_array($PayConfig)){
            $pay_data['accesskey'] = $PayConfig['dlb_access_key'];
            $pay_data['secretkey'] = $PayConfig['dlb_secret_key'];
            $pay_data['timestamp'] = $this->getMillisecond(); 
            $pay_data['path'] = $PayConfig['dlb_path_scaneorder']; //'/v1/agent/order/payurl/create';
            $sign_data = array(
                'agentNum'=>$PayConfig['dlb_agent_num'],            // 代理商编号
                'amount'=>$data['amount'], // 金额--请求传递
				'authCode'=>$data['authCode'],//用户付款码
				
				'machineNum'=>$PayConfig['machineNum'], //机具号
                'customerNum'=>$data['dlb_customer_num'],           // 哆啦宝商户号--请求传递
                'requestNum'=>$data['request_num'],                 // 订单号--请求传递  注：必须为18-32位纯数字
                'shopNum'=>$data['dlb_shop_num'],                   // 哆啦宝店铺号--请求传递
                'source'=>'API'
            );
            $pay_data['body'] = json_encode($sign_data);
            $infoArr = $this->creatTokenPost($pay_data);
			var_dump($infoArr);
            switch ($infoArr['result']) {
                case 'success'://成功 
                    return  array('code'=>200,'msg'=>'支付处理，请检查订单状态。','requestNum'=>$data['request_num']);
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>'订单支付创建失败');
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，支付调用失败');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单支付创建失败');
        }
    }
    /**
    * 查询支付结果
    */
    protected function request_checkPayResult($data){
        $PayConfig = $this->config('dlbpay');
        if(is_array($PayConfig)){
            $pay_data['accesskey'] = $PayConfig['dlb_access_key'];
            $pay_data['secretkey'] = $PayConfig['dlb_secret_key'];
            $pay_data['timestamp'] = $this->getMillisecond(); 
            $pay_data['path'] = $PayConfig['dlb_path_payresult'];
			//'/v1/agent/order/payurl/create';
			if($data['type']=='order')
			{
				$pay_data['path'].='/'.$PayConfig['dlb_agent_num'].'/'.$data['dlb_customer_num'].'/'.$data['dlb_shop_num'].'/with/'.$data['request_num'];
			}else{
				$pay_data['path'].='/'.$PayConfig['dlb_agent_num'].'/'.$data['dlb_customer_num'].'/'.$data['dlb_shop_num'].'/'.$data['request_num'];
			} 
            $infoArr = $this->creatTokenGet($pay_data); 
			var_dump($infoArr);
            switch ($infoArr['result']) {
                case 'success'://成功 
					$status_arr = array('INIT'=>"待支付","SUCCESS"=>"成功","CANCEL"=>"已取消","REFUND"=>"已退款","REFUNDFAIL"=>"退款失败");
					$payWay_arr = array("WX"=>"微信支付","WXSCAN"=>"微信被扫","JD"=>"京东","RED_PACKET"=>"补贴","ACCOUNT"=>"账户","GZ_SPDB_ALIPAY_SCAN"=>"浦发支付宝被扫");
					if(isset($infoArr['data']['payRecordList']))
					{
						foreach($infoArr['data']['payRecordList'] as $k=>$v)
						{
							$infoArr['data']['payRecordList'][$k]['payWay'] = $payWay_arr[$v['payWay']];
						}
						return array('code'=>200,'msg'=>'查询结果',
						'status'=>$status_arr[$infoArr['data']['status']],
						'requestNum'=>$infoArr['data']['requestNum'],
						'orderAmount'=>$infoArr['data']['orderAmount'],
						'completeTime'=>$infoArr['data']['completeTime'],
						'payRecordList'=>$infoArr['data']['payRecordList']
						); 
					}else{
						return array('code'=>200,'msg'=>'查询结果',
								'message'=>$infoArr['data']['message']
								); 
					}
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>'查询失败');
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，查询调用失败');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单支付创建失败');
        }
    }
	/**代理商批量查询结果
	*
	*/
	protected function request_wholePayResult($data)
	{ 
		$PayConfig = $this->config('dlbpay');
        if(is_array($PayConfig)){
            $pay_data['accesskey'] = $PayConfig['dlb_access_key'];
            $pay_data['secretkey'] = $PayConfig['dlb_secret_key'];
            $pay_data['timestamp'] = $this->getMillisecond(); 
            $pay_data['path'] = $PayConfig['dlb_path_wholePayResult']; 
			 $sign_data = array(
                'agentNum'=>$PayConfig['dlb_agent_num'],            // 代理商编号
				'customerNum'=>$data['dlb_customer_num'],           // 哆啦宝商户号--请求传递
				'shopNum'=>$data['dlb_shop_num'],                   // 哆啦宝店铺号--请求传递
                'status'=>$data['status'], // 状态
				'startTime'=>$data['startTime'],//开始时间
				'endTime'=>$data['endTime'], //结束时间
                'pageNum'=>$data['pageNum'] //页码
            );
            $pay_data['body'] = json_encode($sign_data);
            $infoArr = $this->creatTokenPost($pay_data);   
            switch ($infoArr['result']) {
                case 'success'://成功 
					 
                    return $ppp = array('code'=>200,'msg'=>'查询结果',
					'orderList'=>$infoArr['data']['orderList'] 
					); 
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>'查询失败');
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，查询调用失败');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单查询失败');
        }
	}
	/**
	**
	*/
	protected function request_orderRefund($data)
	{
		$PayConfig = $this->config('dlbpay');
        if(is_array($PayConfig)){
            $pay_data['accesskey'] = $PayConfig['dlb_access_key'];
            $pay_data['secretkey'] = $PayConfig['dlb_secret_key'];
            $pay_data['timestamp'] = $this->getMillisecond(); 
            $pay_data['path'] = $PayConfig['dlb_path_orderRefund']; 
			 $sign_data = array(
                'agentNum'=>$PayConfig['dlb_agent_num'],            // 代理商编号
				'customerNum'=>$data['dlb_customer_num'],           // 哆啦宝商户号--请求传递
				'shopNum'=>$data['dlb_shop_num'],                   // 哆啦宝店铺号--请求传递
                'requestNum'=>$data['requestNum'], // 流水号 
            );
            $pay_data['body'] = json_encode($sign_data);
            $infoArr = $this->creatTokenPost($pay_data);   
            switch ($infoArr['result']) {
                case 'success'://成功 
					 
                    return $ppp = array('code'=>200,'msg'=>'退款成功',
					'orderNum'=>$infoArr['data']['orderNum'],
					'refundAmount'=>$infoArr['data']['refundAmount']
					); 
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>$infoArr['error']['errorMsg']);
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，查询调用失败');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单退款失败');
        }
	}
    /**
    * 申请哆啦宝微信退款
    */
    protected function request_refundpay($data){
        echo "**".$data['dlb_customer_num']."**".$data['dlb_shop_num']."**".$data['request_num']."**";
        $PayConfig = $this->config('dlbpay');
        if(is_array($PayConfig)){
            $pay_data['accesskey'] = $PayConfig['dlb_access_key'];
            $pay_data['secretkey'] = $PayConfig['dlb_secret_key'];
            $pay_data['timestamp'] = $this->getMillisecond();
            $pay_data['path'] = $PayConfig['dlb_path_refund'];      //'/v1/agent/order/payurl/create';
            $sign_data = array(
                'agentNum'=>$PayConfig['dlb_agent_num'],            // 代理商编号
                'customerNum'=>$data['dlb_customer_num'],           // 哆啦宝商户号--请求传递
                'requestNum'=>$data['request_num'],                 // 订单号--请求传递  注：必须为18-32位纯数字
                'shopNum'=>$data['dlb_shop_num'],                   // 哆啦宝店铺号--请求传递
            );
            $pay_data['body'] = json_encode($sign_data);
            $infoArr = $this->creatTokenPost($pay_data);
            switch ($infoArr['result']) {
                case 'success'://成功
                    $payurl = $infoArr['data']['url'];
                    return array('code'=>200,'msg'=>'订单退款成功','url'=>array('payurl'=>$payurl));
                    break;
                case 'fail'://失败
                    return array('code'=>502,'msg'=>'订单退款失败');
                    break;
                case 'error'://异常
                    return array('code'=>501,'msg'=>'服务器繁忙，退款失败，请稍后再试试');
                    break;
                default:
                    break;
            }
        }else{
            return array('code'=>502,'msg'=>'订单退款失败');
        }
    }
    
    //生成token并提交
    protected function creatTokenPost($data) {
        $str = "secretKey={$data['secretkey']}&timestamp={$data['timestamp']}&path={$data['path']}&body={$data['body']}";
        $token = strtoupper(sha1($str));
        $url = 'http://openapi.duolabao.cn'.$data['path'];
        $post_data = $data['body']; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'accesskey: ' . $data['accesskey'],
            'timestamp: ' . $data['timestamp'],
            'token: ' . $token)
        );
        $info = curl_exec($ch);
        $infoArr = json_decode($info,true);
    //    put_contents('log/payurl_result',$infoArr,1);
		var_dump($infoArr);
        curl_close($ch);
        return $infoArr;
    }
    //生成token并提交
    protected function creatTokenGet($data) {
        $str = "secretKey={$data['secretkey']}&timestamp={$data['timestamp']}&path={$data['path']}";
		$token = strtoupper(sha1($str));
        $url = 'http://openapi.duolabao.cn'.$data['path']; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'accesskey: ' . $data['accesskey'],
            'timestamp: ' . $data['timestamp'],
            'token: ' . $token)
        );
        $info = curl_exec($ch);
        $infoArr = json_decode($info,true);
    //    put_contents('log/payurl_result',$infoArr,1); 
        curl_close($ch);
        return $infoArr;
    }
    /**18位时间戳**/
    function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		$str = $t2.ceil( ($t1 * 1000) );
		//$str.=rand(10000,99999);
		return $str;
    }
	function getRequestNum() {
		list($t1, $t2) = explode(' ', microtime());
		$str = $t2.ceil( ($t1 * 1000) );
		$str.=rand(10000,99999);
		return $str;
    }
	
}
	$duoduopay = new dlbpayController();
	//主动扫描
	//$duoduopay->creatScanPay('10001114533598995853210','10001214696702060035149',$duoduopay->getRequestNum(),'0.01','286272780172923028');
	//创建订单
	//$duoduopay->createDLBPay('10001114533598995853210','10001214696702060035149',$duoduopay->getRequestNum(),'2.33');
	//查看支付结果
	$result = $duoduopay->checkPayResult('10001114533598995853210','10001214696702060035149','10021015090721858350430','order');//订单号
	//$result = $duoduopay->checkPayResult('10001114533598995853210','10001214696702060035149','1509072187597');//流水号
	
	//批量查询订单
	/*INIT(待支付)/SUCCESS(成功)/CANCEL(已取消)/REFUND(已退款)/REFUNDING(退款中)/REFUNDFAIL(退款失败)*/
	//$result = $duoduopay->wholePayResult('10001114533598995853210','10001214696702060035149','INIT','2017-10-22 10:00:22','2017-10-29 10:00:00','1');
	//代理商退款
	//$result = $duoduopay->orderRefund('10001114533598995853210','10001214696702060035149','1509072187597');
	var_dump($result);