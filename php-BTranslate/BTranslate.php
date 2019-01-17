<?php

/*
$obj=new BTranslate();
$obj->url="http://api.fanyi.baidu.com/api/trans/vip/translate"; //请求地址
$obj->appid="20180508000155289"; //应用ID
$obj->seckey="1Vtf6tztPdcPYP7SVcE8"; //密钥
$obj->curl_timeout=20;
$result=$obj->translate('人生若只如初见','zh','en');

var_dump($result);
*/
/*
auto    自动检测
zh  中文
en  英语
yue 粤语
wyw 文言文
jp  日语
kor 韩语
fra 法语
spa 西班牙语
th  泰语
ara 阿拉伯语
ru  俄语
pt  葡萄牙语
de  德语
it  意大利语
el  希腊语
nl  荷兰语
pl  波兰语
bul 保加利亚语
est 爱沙尼亚语
dan 丹麦语
fin 芬兰语
cs  捷克语
rom 罗马尼亚语
slo 斯洛文尼亚语
swe 瑞典语
hu  匈牙利语
cht 繁体中文
vie 越南语
*/
/*
52000   成功  
52001   请求超时    
52002   系统错误    
52003   未授权用户   
54000   必填参数为空  
54001   签名错误    
54003   访问频率受限  
54004   账户余额不足  
54005   长query请求频繁  
58000   客户端IP非法   
可前往管理控制平台修改
IP限制，IP可留空
58001   译文语言方向不支持
*/   
class BTranslate{
	public $url='';
	public $appid='';
	public $seckey='';
	public $curl_timeout=10;

//翻译入口
public function translate($query, $from, $to)
{
    $args = array(
        'q' => $query,
        'appid' => $this->appid,
        'salt' => rand(10000,99999),
        'from' => $from,
        'to' => $to,

    );
    $args['sign'] = $this->buildSign($query, $this->appid, $args['salt'], $this->seckey);
    //dump($args);
    $ret = $this->call($this->url, $args);
    //dump($ret);
    $ret = json_decode($ret, true);
    return $ret; 
}

//加密
private function buildSign($query, $appID, $salt, $secKey)
{/*{{{*/
    $str = $appID . $query . $salt . $secKey;
    $ret = md5($str);
    return $ret;
}/*}}}*/

//发起网络请求
private function call($url, $args=null,$method="post", $testflag = 0,$headers=array())
{/*{{{*/
    $ret = false;
    $i = 0; 
    while($ret === false) 
    {
        if($i > 1)
            break;
        if($i > 0) 
        {
            sleep(1);
        }
        $ret = $this->callOnce($url, $args, $method, false, $headers);
        //dump($ret);
        $i++;
    }
    return $ret;
}/*}}}*/

private function callOnce($url, $args=null, $method="post", $withCookie = false,  $headers=array())
{/*{{{*/
    $ch = curl_init();
    if($method == "post") 
    {
        $data = $this->convert($args);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, 1);
    }
    else 
    {
        $data = $this->convert($args);
        if($data) 
        {
            if(stripos($url, "?") > 0) 
            {
                $url .= "&$data";
            }
            else 
            {
                $url .= "?$data";
            }
        }
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(!empty($headers)) 
    {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    if($withCookie)
    {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
    }
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}/*}}}*/

private function convert(&$args)
{/*{{{*/
    $data = '';
    if (is_array($args))
    {
        foreach ($args as $key=>$val)
        {
            if (is_array($val))
            {
                foreach ($val as $k=>$v)
                {
                    $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                }
            }
            else
            {
                $data .="$key=".rawurlencode($val)."&";
            }
        }
        return trim($data, "&");
    }
    return $args;
}/*}}}*/

}



?>