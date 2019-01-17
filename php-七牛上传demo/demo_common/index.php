<?php 
include "../Qiniu/autoload.php";
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;


$bucket = 'lingfengveeker';  //存储空间 
$accessKey = 'QYY2IuLVIS24RcUdOZYKBj7LV5quSBGElDXWT6cD';  //AK
$secretKey = 'uynh6uharXS2AhNmxYE6YPk5S_9sbLcwrryNdFB9';  //SK

$expires = 6000;
$auth = new Auth($accessKey, $secretKey);


$policy = array(
    //'callbackUrl' => 'http://localhost/qiniuyun/examples/upload_verify_callback.php',
    'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)&fsize=$(fsize)&name=$(x:name)',
    'callbackBodyType' => 'application/json'
);
$token = $auth->uploadToken($bucket, null, $expires, $policy, true);
// 构建 UploadManager 对象
//$uploadMgr = new UploadManager();

 ?>


<form method="post" action="up.php" enctype="multipart/form-data">
    <input name="token" type="hidden" value="<?php echo $token;?>">
    <input name="file" type="file" />
    <input type="submit" value="上传"/>
</form>







