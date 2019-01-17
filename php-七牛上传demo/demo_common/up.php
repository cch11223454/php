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
$uploadMgr = new UploadManager();

// 要上传文件的本地路径
$filePath = $_FILES['file']['tmp_name'];

// 上传到七牛后保存的文件名
$key = date("YmdHis");

list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}





