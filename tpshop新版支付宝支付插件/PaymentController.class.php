<?php
namespace Home\Controller;
use Think\Controller;
use Tools\HomeController;
use Think\Model;
class PaymentController extends HomeController {
	public $payment; //  具体的支付类
    public $pay_code; //  具体的支付code

    public function  __construct() {   
        parent::__construct();
        // tpshop 订单支付提交
        $pay_radio = $_REQUEST['pay_radio'];
        if(!empty($pay_radio)) 
        {                         
            $pay_radio = parse_url_param($pay_radio);
            $this->pay_code = $pay_radio['pay_code']; // 支付 code
        }
        else // 第三方 支付商返回
        {            
            $_GET = I('get.');            
            //file_put_contents('./a.html',$_GET,FILE_APPEND);    
            $this->pay_code = I('get.pay_code');
            unset($_GET['pay_code']); // 用完之后删除, 以免进入签名判断里面去 导致错误
        }                        
        //获取通知的数据
        //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];               
        // 导入具体的支付类文件                
        include_once  "plugins/payment/{$this->pay_code}/{$this->pay_code}.class.php"; // D:\wamp\www\svn_tpshop\www\plugins\payment\alipay\alipayPayment.class.php                       
        $code = '\\'.$this->pay_code; // \alipay
       // dump($code);exit;
        $this->payment = new $code();
        // echo $this->pay_code;exit;
         // $this->payment = new \weixin();
    }

    public function getCode(){     
        	//dump($this->pay_code);exit;
            //C('TOKEN_ON',false); // 关闭 TOKEN_ON
            header("Content-type:text/html;charset=utf-8"); 
            // 订单id  
            $type=I('type');
            //dump($type);exit;
            //$order_id = I('order_id',0);
            //if($type=="es"){
                //在线充值
                $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/index.php/Home/Index/index";
                // $order = M('es_order')->where("order_id = $order_id")->find();
                // //dump($order);exit;
                // if($order['pay_status'] == 1){
                //     //$this->error('此订单，已完成支付!');                  
                //     header('location:'.U('Mobile/Hpayment/order_msg',array('type'=>'es2')));
                //     die;
                // }
                // if ($order['pay_code'] == 'weixin') {
                //     $this -> H5wxpay($order['order_id'],I('get.type'));
                //     die;
                // }else{
                	$order['order_sn']='lf_'.date("y",time()).msectime();
					$order['goods_name']="商品";
					$order['order_amount']=mt_rand(1,100).'.'.mt_rand(10,99);
					$order['order_desc']=null;

                    $pay_radio = $_REQUEST['pay_radio'];
                    $config_value = parse_url_param($pay_radio);
                    $code_str = $this->payment->get_code($order,$config_value,$url);
                    echo $code_str;
                    // $this->assign('code_str', $code_str);
                    // $this->assign('order_id', $order_id);
                    // $this->assign("pay_code",$order['pay_code']);
                    // $this->display('payment');
                // }
                // die;
            //}
    }

     // 服务器点对点 // http://www.tp-shop.cn/index.php/Home/Payment/notifyUrl        
        public function notifyUrl(){      //异步       
            $this->payment->response();            
            exit();
        }

        // 页面跳转 // http://www.tp-shop.cn/index.php/Home/Payment/returnUrl        
        public function returnUrl(){  //同步
             $result = $this->payment->respond2(); // $result['order_sn'] = '201512241425288593';
              //dump($this->payment);
              dump($result);
             // if($result['status']==0){
             //    sleep(2);
             //    $result = $this->payment->respond2();
             //    dump($result);
             // }

             //  die;
             // if(stripos($result['order_sn'],'es_') !== false)
             // {
             //    // dump($result);
             //    // die;
             //    if ($result['status'] == 1) {
             //        //echo "123";
             //        update_pay_status($result['order_sn']);
             //    }
             //    header('location:'.U('Mobile/Hpayment/check_pay', array('id' =>$result['order_sn'],'type'=>'es')));
             // }

        }
}
?>