<?php
namespace Home\Controller;
use Think\Controller;
use Tools\HomeController;
use Think\Model;
class IndexController extends HomeController {
	function qiniu(){
        if(IS_POST){
            $file=$_FILES['file']['tmp_name'];

            $result=up_file_qiniu($file);
            dump($result);
        }else{
            $token=get_qiniu_token();
            $this->assign("token",$token);
            $this->display();
        }
        
    }
}
?>
