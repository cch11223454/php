<?php
session_start();
require './QQlogin.php';
$config=array(
	'appid'=>"101453029",
	'appkey'=>"bd85ea158e715c8b3eaaba499a5b9caa",
	'callback'=>"http://www.lingfengveeker.top/qqlogin.php"
);
$qq_login = new \QQlogin($config);    //引入此类文件即可 


//var_dump($qq_login);
if(empty($_REQUEST['code'])){
		$qq_login->qq_login();	
		}else{
			
			$acs = $qq_login->qq_callback();    //access_token 
			$oid=$qq_login->get_openid();     //openid 
			$qq_login =new \QQlogin($config,$acs,$oid);
			$user_data = $qq_login->get_user_info();

			var_dump($user_data);
		}

 
?>