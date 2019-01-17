<?php

$base_img=$_POST['baseimg'];
$url="./Public/upload/roomlogo/";
$res=upload_base64_img($base_img,$url);

function upload_base64_img($base64,$url){
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
        $type = $result[2];
        $new_file = $url;   //形式:./Public/upload/roomlogo/
        if(!file_exists($new_file)){
            //检查是否有该文件夹，如果没有就创建，并给予最高权限
         mkdir($new_file, 0644);
        }
        $new_file = $new_file.time().".{$type}";
        file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)));
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64)))){
            return array("re"=>1,'path'=>ltrim($new_file,'.'));
                //$this->ajaxReturn(array('re'=>1,'path'=>ltrim($new_file,'.')));
        }else{
            return array("re"=>2,'msg'=>'upload failed！');
        }
    }
}
?>