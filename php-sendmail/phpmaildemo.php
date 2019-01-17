<?php
require './PHPMailer/PHPMailerAutoload.php';
$mail = new \PHPMailer(); //实例化
$mail->IsSMTP(); // 启用SMTP
$mail->Host='smtp.163.com'; //smtp服务器的名称（这里以163邮箱为例）
$mail->SMTPAuth = TRUE; //启用smtp认证
$mail->Username = 'm17685908141@163.com'; //你的smtp邮箱名
$mail->Password = 'dfgh1234' ; //网易直接输入邮箱密码即可【有授权码输授权码】，QQ请输授权码
$mail->Port = 465;
if($mail->Port !=25) {  //
$mail->SMTPSecure = "ssl"; //使用ssl协议
}
$mail->From = 'm17685908141@163.com'; //发件人地址（也就是你的邮箱地址）
$mail->FromName = '端木凌风'; //发件人姓名
$mail->AddAddress('1214047290@qq.com','尊敬的客户');  //收件人邮箱 ，昵称
//$mail->AddAttachment('a.php','a.php');  // 添加附件,并指定名称
$mail->WordWrap = 50; //设置每行字符长度
$mail->IsHTML(TRUE); // 是否HTML格式邮件
$mail->CharSet='utf-8'; //设置邮件编码
$mail->Subject ='SSL协议测试'; //邮件主题
$mail->Body = '这是一封使用ssl协议发送的测试邮件'; //邮件内容
//$mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
var_dump($mail->Send());
?>