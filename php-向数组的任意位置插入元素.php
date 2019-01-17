<?php
/*
*向数组中随机插入元素
*array 被插入元素的数组
*position 插入位置
*insert_array 插入的元素
*/
function array_insert(&$array, $position, $insert_array){
    $first_array = array_splice ($array, 0, $position);
    $insert[0]=$insert_array;
    $array=array_merge ($first_array, $insert, $array);
    //return $array;
}
?>