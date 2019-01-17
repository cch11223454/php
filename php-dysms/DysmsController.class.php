<?php
namespace Home\Controller;
use Think\Controller;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
class DysmsController extends Controller {
    public function index(){
    	$this->display();
        
    }
public function send_message(){
        //$phone=13276366421;
        $phone=I('get.phone');
        $this->send_phone($phone);
    }

    /**
     * 生成短信验证码
     * @param  integer $length [验证码长度]
     */
    public function createSMSCode($length = 4){
        $min = pow(10 , ($length - 1));
        $max = pow(10, $length) - 1;
        return rand($min, $max);
    }


    /**
     * 发送验证码
     * @param  [integer] $phone [手机号]
     */
    public function send_phone($phone){

        $code=$this->createSMSCode($length = 4);
        //echo $code;exit;
        require_once  './Api/dysms/vendor/autoload.php';    //此处为你放置API的路径
        Config::load();             //加载区域结点配置

        $accessKeyId = 'accessKeyId';  //accessKeyId
        $accessKeySecret = 'accessKeySecret'; //accessKeySecret
        $templateCode = '短信模板CODE';   //短信模板CODE

        //短信API产品名（短信产品名固定，无需修改）
        $product = "Dysmsapi";

        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";

        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);

        // 初始化AcsClient用于发起请求
        $acsClient = new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称
        $request->setSignName("签名名称");

        // 必填，设置模板CODE
        $request->setTemplateCode("模板CODE");

        $smsData = array('code'=>$code);    //所使用的模板若有变量 在这里填入变量的值  我的变量名为username此处也为username

        //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
        $request->setTemplateParam(json_encode($smsData));

        //发起访问请求
        $acsResponse = $acsClient -> getAcsResponse($request);
        //返回请求结果
        $result = json_decode(json_encode($acsResponse), true);
        $resp = $result['Code'];
        $this->sendMsgResult($resp,$phone,$code);
    }


    /**
     * 验证手机号是否发送成功  前端用ajax，发送成功则提示倒计时，如50秒后可以重新发送
     * @param  [json] $resp  [发送结果]
     * @param  [type] $phone [手机号]
     * @param  [type] $code  [验证码]
     * @return [type]        [description]
     */
    private function sendMsgResult($resp,$phone,$code){
        if ($resp == "OK") {
            $data['phone']=$phone;
            $data['code']=$code;
            $data['send_time']=time();
        //     $result=D("Smsverif")->add($data);
        //     if($result){
        //         $data="发送成功";
        //     }else{
        //         $data="发送失败";
        //     }
        // } else{
        //     $data="发送失败";
            echo "<script>alert('发送成功！');</script>";
        }
        // return $data;
    }


    /**
     * 验证短信验证码是否有效,前端用jquery validate的remote
     * @return [type] [description]
     */
    public function checkSMSCode(){
        $phone = $_POST['phone'];
        $code = $_POST['verify'];
        $nowTimeStr = time();
        $smscodeObj = D("Smsverif")->where("phone={$phone} and code = {$code}")->find();
        if($smscodeObj){
            $smsCodeTimeStr = $smscodeObj['send_time'];
            $recordCode = $smscodeObj['code'];
            $flag = $this->checkTime($nowTimeStr, $smsCodeTimeStr);
            if($flag!=true || $code !== $recordCode){
                echo 'no';
            }else{
                echo 'ok';
            }
        }
    }


    /**
     * 验证验证码是否在可用时间
    *  @param  [json] $nowTimeStr  [发送结果]
     * @param  [type] $smsCodeTimeStr [手机号]
     */
    public function checkTime ($nowTimeStr,$smsCodeTimeStr) {
        $time = $nowTimeStr - $smsCodeTimeStr;
        if ($time>900) {
            return false;
        }else{
            return true;
        }
    }
}