<?php
/**
 * sendMail()邮件发送函数
 @param  $to  收件人邮件
 @param  $title  邮件标题
 @param  $content  邮件正文
 @return boolean  返回一个布尔值
 */
    function sendMail($to, $title, $content) {
    
        Vendor('PHPMailer.PHPMailerAutoload');     
        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
        $mail->Port = C('Port');
        if(C('Port')!=25) {  //网易为25
            $mail->SMTPSecure = "ssl";
        }
        $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
        $mail->AddAddress($to,'尊敬的客户');  //收件人邮箱 ，昵称
//$mail->AddAttachment('a.php','a.php');  // 添加附件,并指定名称
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        return($mail->Send());
    }

?>