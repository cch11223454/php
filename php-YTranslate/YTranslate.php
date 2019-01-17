<?php


// $obj=new YTranslate();
// $obj->url="http://openapi.youdao.com/api"; //请求地址
// $obj->appkey="7ef87d8f423e188c"; //应用ID
// $obj->seckey="CXOII14krOqyDl8ikdIxnqVoP8jExDBq"; //密钥
// $obj->curl_timeout=20;

// $result=$obj->translate('人生若只如初见','zh-CHS','EN');

// var_dump($result);
/*
中文  zh-CHS
日文  ja
英文  EN
韩文  ko
法文  fr
俄文  ru
葡萄牙文    pt
西班牙文    es
越南文 vi
*/
/*
101 缺少必填的参数，出现这个情况还可能是et的值和实际加密方式不对应
102 不支持的语言类型
103 翻译文本过长
104 不支持的API类型
105 不支持的签名类型
106 不支持的响应类型
107 不支持的传输加密类型
108 appKey无效，注册账号， 登录后台创建应用和实例并完成绑定， 可获得应用ID和密钥等信息，其中应用ID就是appKey（ 注意不是应用密钥）
109 batchLog格式不正确
110 无相关服务的有效实例
111 开发者账号无效
113 q不能为空
201 解密失败，可能为DES,BASE64,URLDecode的错误
202 签名检验失败
203 访问IP地址不在可访问IP列表
205 请求的接口与应用的平台类型不一致
301 辞典查询失败
302 翻译查询失败
303 服务端的其它异常
401 账户已经欠费
411 访问频率受限,请稍后访问
412 长请求过于频繁，请稍后访问
*/
class YTranslate{
        public $appkey="";
        public $seckey="";
        public $url="";
        public $curl_timeout=20;

    //翻译入口
        public function translate($query, $from, $to)
        {
            $args = array(
                'q' => $query,
                'appKey' => $this->appkey,
                'salt' => rand(10000,99999),
                'from' => $from,
                'to' => $to,

            );
            $args['sign'] = $this->buildSign($this->appkey, $query, $args['salt'], $this->seckey);
            $ret = $this->call($this->url, $args);
            //echo $ret;
            $ret = json_decode($ret, true);
            return $ret; 
        }

        //加密
        private function buildSign($appKey, $query, $salt, $secKey)
        {/*{{{*/
            $str = $appKey . $query . $salt . $secKey;
            $ret = md5($str);
            return $ret;
        }/*}}}*/

        //发起网络请求
        private function call($url, $args=null, $method="post", $testflag = 0, $headers=array())
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
                $ret = $this->callOnce($url, $args, $method, false, $this->curl_timeout, $headers);
                $i++;
            }
            return $ret;
        }/*}}}*/

        private function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = CURL_TIMEOUT, $headers=array())
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


//调用翻译

?>