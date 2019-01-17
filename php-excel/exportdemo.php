<?php
       $time = time();
        $xlsTitle  = "通讯录"; //excel表格标题
        $xlsName=time();       //生成excel文件的名称
        //dump($xlsName);exit;
        $xlsCell  = array(			//列名及对应字段的名称
            array('id','编号'),
            array('name','姓名'),
            array('tel','联系电话'),
        );
		
		//各列宽度
        $xlsCellWidth=array(10,50,15);

        $per1=array('id'=>'001','name'=>'张三','tel'=>'电话');
        $per2=array('id'=>'002','name'=>'张三','tel'=>'电话');
        $per3=array('id'=>'003','name'=>'张三','tel'=>'电话');

        //$xlsData  = $xlsModel->Field('id,company_name,contants,nickname,phone,email,addtime')->order('id asc')->select();
		$xlsData=array($per1,$per2,$per3);   //数据数组(二维关联数组)【数据库获取的数据】
        exportExcel($xlsName,$xlsTitle,$xlsCell,$xlsData,$xlsCellWidth); //该函数写在公共函数库里








/*
*exportExcel() 导出excel
*@access public
*@param $expName  生成excel文件的名称
*@param $expTitle   excel表格第一行标题
*@param $expCellName   数据库字段对应的excel表格第二行的标题名 二维关联数组
                        eg: '数据表字段名'=>'excel表格标题名'
*@param $expTableData  需要导出成excel表格的数据   二维关联数组
                        eg:$xlsCell  = array( 
                                            array('id','编号'),
                                            array('name','姓名'),
                                            array('tel','联系电话'),
                                            );
*@param $expCellWidth  生成excel文件的每一列单元格的宽度  一维索引数组
                        eg：$xlsCellWidth=array(10,50,15);
*@param $type   生成的excel类型 
                 $type类型有：Excel5、Excel2007、Excel2003XML、OOCalc、SYLK、Gnumeric、CSV
*@rerurn void
*/
function exportExcel($expName,$expTitle,$expCellName,$expTableData,$expCellWidth=null,$type="xls"){
    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);
    switch($type){
            case 'xls':
            $outtype='Excel5'; 
            break;
            case 'xlsx':
            $outtype='Excel2007'; 
            break;
            //case 'csv':
            //$reader = \PHPExcel_IOFactory::createReader('CSV'); 
           // break;
            default:
            return array('code'=>1,'error'=>'Not supported file types!');
            exit;
        }
    $fileName = $expName; 
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    require './PHPExcel/PHPExcel.php';
    $objPHPExcel = new \PHPExcel();
    $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ');
    
    //合并单元格
    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//
    
    //设置标题
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle);
    //设置标题列高度
    $objPHPExcel->getActiveSheet()->getRowDimension('A1')->setRowHeight(40);
    //设置标题列字号
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(30);
    //设置标题文字居中
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    // 列名表头文字加粗 
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.$cellName[$cellNum-1].'2')->getFont()->setBold(true);

    //列名表头文字字号
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.$cellName[$cellNum-1].'2')->getFont()->setSize(15);

    // 列名表头文字居中
    $objPHPExcel->getActiveSheet()->getStyle('A2:'.$cellName[$cellNum-1].'2')->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    for($i=0;$i<$cellNum;$i++){
        if(!empty($expCellWidth) && !empty($expCellWidth[$i])){
        $objPHPExcel->getActiveSheet()->getColumnDimension($cellName[$i])->setWidth($expCellWidth[$i]);
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
    }
    // Miscellaneous glyphs, UTF-8
    for($i=0;$i<$dataNum;$i++){
        for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
        }
    }

    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.'.$type.'"');
    header("Content-Disposition:attachment;filename=$fileName.{$type}");//attachment新窗口打印inline本窗口打印
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $outtype);
    $objWriter->save('php://output');
    exit;
}



?>