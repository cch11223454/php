<?php
set_time_limit(1200);
        $titleRow=1;  //标题行号
        $dataRow=2;   //数据开始行号
        $type="xls";  //文件类型
        $uploadfile="./user.xls";
        $result=importexcel($uploadfile,$type,$titleRow,$dataRow);
        var_dump($result);

/*
*importexcel() 读取excel文档
*@access public
*@param $uploadfile    excel文档的路径  （相对路径）
*@param $type         excel文档类型
                        //$type类型有：  
                                //Excel5';  
                                // 'Excel2007';  
                                // 'Excel2003XML';  
                                // 'OOCalc';  
                                // 'SYLK';  
                                // 'Gnumeric';  
                                //'CSV'; 
*@param $titleRow       excel文档内标题行行号
*@param $dataRow       excel文档内的数据开始行号
*@rerurn array
*/
function importexcel($uploadfile,$type,$titleRow=1,$dataRow=2){
        
        
         

        require "./PHPExcel/PHPExcel.php";
        switch($type){
            case 'xls':
            $reader = \PHPExcel_IOFactory::createReader('Excel5'); 
            break;
            case 'xlsx':
            $reader = \PHPExcel_IOFactory::createReader('Excel2007'); 
            break;
            //case 'csv':
            //$reader = \PHPExcel_IOFactory::createReader('CSV'); 
           // break;
            default:
            return array('code'=>1,'error'=>'Not supported file types!');
            exit;
        }
        
        $PHPExcel = $reader->load($uploadfile); // 文档名称

        //--------------------------------
        //获取行数与列数,注意列数需要转换
        $sheet = $PHPExcel->getSheet(0); //读取第一个工作表
        $highestRowNum = $sheet->getHighestRow();  //取得总行数
        $highestColumn = $sheet->getHighestColumn(); //取得总列数
        $highestColumnNum = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        
        //取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
        $field = array();
        for($i=0; $i<$highestColumnNum;++$i){
            $cellName = \PHPExcel_Cell::stringFromColumnIndex($i).$titleRow;
           
            $cellVal = $sheet->getCell($cellName)->getValue();//取得列内容
            if( !$cellVal ){
                break;
            }
            $field []= $cellVal;
        }
        $real_column=count($field);
        

        for($row=$dataRow;$row<=$highestRowNum;++$row){
            for($column=0;$column<$real_column;++$column){

                $cellName = \PHPExcel_Cell::stringFromColumnIndex($column).$row;
                
                $data[$row][$field[$column]]=$sheet->getCell($cellName)->getValue();
            }
            
        }
        return $data;
        //dump($data);exit;
}
?>