<?php

pdf();

function pdf(){
    //引入类库
    //Vendor('mpdf.mpdf'); //tp3.2.3  (mpdf文件夹放于 ThinkPHP\Library\Vendor目录)
    require_once './mpdf/mpdf.php';	
	//设置中文编码
    $mpdf=new \mPDF('zh-cn','A4', 0, '宋体', 0, 0);
    //html内容
    $html='<h1><a name="top"></a>一个PDF文件</h1>';
    $mpdf->WriteHTML($html);
    $mpdf->Output();
    exit;
}
?>