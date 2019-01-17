<?php
return array(
	// 配置邮件发送服务器
    'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
    //'MAIL_HOST' =>'smtp.qq.com',
    //'Port' => 25,    //网易为25,QQ为465
    'Port' => 465,
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'m17685908141@163.com',//qq此处为邮箱前缀名  163为邮箱名
    'MAIL_FROM' =>'m17685908141@163.com',//发件人地址
    'MAIL_FROMNAME'=>'端木凌风',//发件人姓名
    'MAIL_PASSWORD' =>'dfgh1234',//网易直接输入邮箱密码即可【有授权码输授权码】，QQ请输授权码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
);