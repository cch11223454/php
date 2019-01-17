<?php
echo get_rand_hb();

function get_rand_hb($min=50,$max=500){
	
	
	$count=10;
	$cha=floor(($max-$min)/$count);
	if($cha<=$count){
		return mt_rand($min,$max);
	}
	
	$weight=array(30,20,10,10,10,10,4,3,2,1);   //随机到的区间所占比重
		

	for($i=0;$i<$count;$i++){	
		$arr[$i]['min']=$min+$i*$cha;
		$arr[$i]['v']=$weight[$i];
		$arr[$i]['id']=$i+1;
		$arr[$i]['name']='第'.($i+1).'区间';
		$arr[$i]['max']=$min+($i+1)*$cha;
			
	}

	$qujian=get_rand($arr);

	return mt_rand($qujian['min'],$qujian['max']);
}

function get_rand($proArr) {   
    $result = array();
    foreach ($proArr as $key => $val) { 
        $arr[$key] = $val['v']; 
    } 
    // 概率数组的总概率  
    $proSum = array_sum($arr);        
    asort($arr);
    // 概率数组循环   
    foreach ($arr as $k => $v) {   
        $randNum = mt_rand(1, $proSum);   
        if ($randNum <= $v) {   
            $result = $proArr[$k];   
            break;   
        } else {   
            $proSum -= $v;   
        }         
    }     
    return $result;   
}
?>