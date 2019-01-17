<?php
function get_content_img($content){
    if(strpos($content,'<img')!==false){
     $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
     preg_match_all($pregRule,$content,$array,PREG_PATTERN_ORDER);
     foreach ($array[1] as $v) {
         if(strrpos($v, 'http')===false){
            $path[]='.'.$v;
         }
     }
     return array('status'=>1,'path'=>$path);
    }else{
        return array('status'=>0);
    }
}
?>