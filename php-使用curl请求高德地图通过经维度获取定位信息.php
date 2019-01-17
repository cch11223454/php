<?php
function redis_test(){
        //申请Web服务API类型Key；
        //https://lbs.amap.com/api/webservice/guide/api/georegeo/
        $url='https://restapi.amap.com/v3/geocode/regeo?key=af5c7e3b9f6f501d63b8a70c4f25e2b0&location=116.473195,39.993253';
        $result=http_get($url);
        $result=json_decode($result);
        $result=object_array($result);
        $address_content=$result['regeocode']['addressComponent'];
        var_dump($address_content);
 }

 function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
             }  
     }  
     return $array;  
}
?>
