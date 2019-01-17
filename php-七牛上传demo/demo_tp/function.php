<?php
/*
*上传文件到七牛
*/
function up_file_qiniu($file){
        vendor("Qiniu.autoload");
                
        $bucket = 'lingfengveeker'; //存储空间
        $accessKey = 'QYY2IuLVIS24RcUdOZYKBj7LV5quSBGElDXWT6cD'; //AK
        $secretKey = 'uynh6uharXS2AhNmxYE6YPk5S_9sbLcwrryNdFB9';  //SK

        $expires = 6000;
        $auth = new \Qiniu\Auth($accessKey, $secretKey);


        $policy = array(
            //'callbackUrl' => 'http://localhost/qiniuyun/examples/upload_verify_callback.php',
            'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)&fsize=$(fsize)&name=$(x:name)',
            'callbackBodyType' => 'application/json'
        );
        $token = $auth->uploadToken($bucket, null, $expires, $policy, true);
        // 构建 UploadManager 对象
        $uploadMgr = new \Qiniu\Storage\UploadManager();

        // 要上传文件的本地路径
        $filePath = $file;

        // 上传到七牛后保存的文件名
        $key = date("YmdHis");

        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        //echo "\n====> putFile result: \n";
        if ($err !== null) {
            return $err;
        }else {
            return $ret;
        }
}

/*
*获取七牛token
*/
function get_qiniu_token(){
    vendor("Qiniu.autoload");    
    $bucket = 'lingfengveeker';  //存储空间
    $accessKey = 'QYY2IuLVIS24RcUdOZYKBj7LV5quSBGElDXWT6cD';  //AK 
    $secretKey = 'uynh6uharXS2AhNmxYE6YPk5S_9sbLcwrryNdFB9';  //SK

    $expires = 6000;
    $auth = new \Qiniu\Auth($accessKey, $secretKey);


    $policy = array(
        //'callbackUrl' => 'http://localhost/qiniuyun/examples/upload_verify_callback.php',
        'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)&fsize=$(fsize)&name=$(x:name)',
        'callbackBodyType' => 'application/json'
    );
    $token = $auth->uploadToken($bucket, null, $expires, $policy, true);

    return $token;


}
?>