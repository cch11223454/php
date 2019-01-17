<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
   

    function send_mail(){
        $to="1214047290@qq.com";
        $title='ssl协议测试邮件';
        $content="welcome!";
        $res=sendMail($to,$title,$content);
        dump($res);
    }
}