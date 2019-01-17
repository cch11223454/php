<?php
use Think\Model\RelationModel;

class alipay extends RelationModel {
	public $tableName = 'plugin'; // 插件表 

	public $alipay_config = array();// 支付宝支付配置参数

	function __construct(){
		parent::__construct();

		include_once dirname(__FILE__)."/config.php";
		//include_once("config.php");
		//var_dump($config);
		//$config_value=$config;
		//应用ID,您的APPID。
		$this->alipay_config['app_id'] =$config['app_id'];

		//商户私钥
		$this->alipay_config['merchant_private_key'] =$config['merchant_private_key']; //密钥 工具生成的私钥文件
		
		// //异步通知地址
		$this->alipay_config['notify_url'] =SITE_URL.U('Payment/notifyUrl',array('pay_code'=>'alipay'));
		
		// //同步跳转
		$this->alipay_config['return_url'] = SITE_URL.U('Payment/returnUrl',array('pay_code'=>'alipay'));

		// //编码格式
		$this->alipay_config['charset'] = "UTF-8";

		// //签名方式
		$this->alipay_config['sign_type']="RSA2"; 
		

		// //支付宝网关
		$this->alipay_config['gatewayUrl'] = "https://openapi.alipaydev.com/gateway.do";

		// //沙箱 https://openapi.alipaydev.com/gateway.do
		// //正式上线 https://openapi.alipay.com/gateway.do

		// //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		$this->alipay_config['alipay_public_key'] =$config['alipay_public_key'];
		
		//$this->alipay_config["show_url"]='http://test.lingfengveeker.com';
		
	}

	function get_code($order,$config_value,$url=null){
		//echo dirname(__FILE__);exit;
		require_once dirname(__FILE__).'/pagepay/service/AlipayTradeService.php';
		require_once dirname(__FILE__).'/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

	    //商户订单号，商户网站订单系统中唯一订单号，必填
	    $out_trade_no = $order['order_sn'];

	    //订单名称，必填
	    $subject = "订单{$order['goods_name']}";

	    //付款金额，必填
	    $total_amount = $order['order_amount'];

	    //商品描述，可空
	    $body = $order['order_desc'];
	    $this->alipay_config['show_url'] =$url;

		//构造参数
		$payRequestBuilder = new AlipayTradePagePayContentBuilder();
		$payRequestBuilder->setBody($body);
		$payRequestBuilder->setSubject($subject);
		$payRequestBuilder->setTotalAmount($total_amount);
		$payRequestBuilder->setOutTradeNo($out_trade_no);

		$aop = new AlipayTradeService($this->alipay_config);
		//var_dump($aop);exit;

		/**
		 * pagePay 电脑网站支付请求
		 * @param $builder 业务参数，使用buildmodel中的对象生成。
		 * @param $return_url 同步跳转地址，公网可以访问
		 * @param $notify_url 异步通知地址，公网可以访问
		 * @return $response 支付宝返回的信息
	 	*/
		$response = $aop->pagePay($payRequestBuilder,$this->alipay_config['return_url'],$this->alipay_config['notify_url']);

		//输出表单
		var_dump($response);
	}

	function response(){       //异步         
        //require_once 'config.php';
		require_once dirname(__FILE__).'/pagepay/service/AlipayTradeService.php';

		$arr=$_POST;
		$alipaySevice = new AlipayTradeService($this->alipay_config); 
		$alipaySevice->writeLog(var_export($_POST,true));
		$result = $alipaySevice->check($arr);
		//var_dump($result);

		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		if($result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//商户订单号

			$out_trade_no = $_POST['out_trade_no'];

			//支付宝交易号

			$trade_no = $_POST['trade_no'];

			//交易状态
			$trade_status = $_POST['trade_status'];


		    if($_POST['trade_status'] == 'TRADE_FINISHED') {

				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序
					//update_pay_status($out_trade_no);	
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
		    }
		    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
					//如果有做过处理，不执行商户的业务程序			
				//注意：
				//update_pay_status($out_trade_no);
				//付款完成后，支付宝系统发送该交易状态通知
		    }
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			echo "success";	//请不要修改或删除
		}else {
		    //验证失败
		    echo "fail";

		}
    }


    function respond2(){  //同步
        //require_once("config.php");
		require_once dirname(__FILE__).'/pagepay/service/AlipayTradeService.php';


		$arr=$_GET;
		$alipaySevice = new AlipayTradeService($this->alipay_config); 
		//var_dump($arr);
		$result = $alipaySevice->check($arr);

		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/

		//var_dump($result);
		//exit;

		if($result) {//验证成功
			

			$order_sn = $out_trade_no = $_GET['out_trade_no']; //商户订单号
            $trade_no = $_GET['trade_no']; //支付宝交易号
            $trade_status = $_GET['trade_status']; //交易状态
          
            return array('status'=>1,'order_sn'=>$order_sn);//跳转至成功页面
            
		}
		else {
		    //验证失败
		    return array('status'=>0,'order_sn'=>$_GET['out_trade_no']);//跳转至失败页面
		}
    }

}
?>