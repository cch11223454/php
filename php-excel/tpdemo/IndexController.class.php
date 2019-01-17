<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

public function export_excel(){ //导出Excel
        $time = time();
        $xlsTitle  = "用户表"; //excel表格标题
        $xlsName=time();       //生成excel文件的名称
        //dump($xlsName);exit;
        // $xlsCell  = array(          //列名及对应字段的名称
        //     array('id','编号'),
        //     array('name','姓名'),
        //     array('tel','联系电话'),
        // ); 
        $fields=M('users')->getDbFields();
        $len=count($fields);
        $xlsCell=array();
        
        foreach ($fields as $k => $v) {
            $tem=array();
            $tem[]=$v;
            $tem[]=$v;
            $xlsCell[]=$tem;

        }
         //dump($xlsCell);exit;      
        //各列宽度
        $xlsCellWidth=array(10,'',50);

        // $per1=array('id'=>'001','name'=>'张三','tel'=>'电话');
        // $per2=array('id'=>'002','name'=>'张三','tel'=>'电话');
        // $per3=array('id'=>'003','name'=>'张三','tel'=>'电话');

        // $xlsData=array($per1,$per2,$per3);   //数据数组(二维关联数组)【数据库获取的数据】
        $xlsData=M('users')->select();
        //dump($xlsData);exit;
        exportExcel($xlsName,$xlsTitle,$xlsCell,$xlsData,$xlsCellWidth); //该函数写在公共函数库里
    }

    function import_excel(){
        set_time_limit(1200);
        $titleRow=1;  //标题行号
        $dataRow=2;   //数据开始行号
        $type="xls";  //文件类型
        $uploadfile="./Public/user1_1.xls";
        $result=importexcel($uploadfile,$type,$titleRow,$dataRow);
        dump($result);
    }





}