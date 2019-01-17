<?php
/**
    ////////////////////////////////////////////////////////
    //                       _oo0oo_                      //
    //                      o8888888o                     //
    //                      88" . "88                     //
    //                      (| ^_^ |)                     //
    //                      0\  =  /0                     //
    //                       /`---`\                      //
    //                   ----        ----                 //
    //                  `  \\|       |// `                //
    //                 `    \\       //   `               //
    //                /   \\||   :   ||//   \             //
    //               /   _||||  -:-  ||||_   \            //
    //               |    | \\   -   // |    |            //
    //               |  \_|  '\ --- /'  |    |            //
    //               \  '-\___  `-`  ___/ -. /            //
    //                `   '   /--.--\  `.                 //
    //              ___` .                 .___           //
    //             ""  '<  `._\_<|>_/__.`  >   ""         //
    //           | | :  `- \`.;`\ - /`;./  - : .| |       //
    //           \ \ `       \_ _\ /_ _/     ,  / /       //
    //        ====``___``______\___/______,`___,,====     //
    //                       `==----==`                   //
    //        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^     //
    //                    佛祖保佑  永无BUG                //
    ////////////////////////////////////////////////////////
 */
/**
* Sql Server(2005+) 数据库操作类
* @date: 2019年1月17日 下午8:45:23
* @author: 端木凌风
* 
*/
/**
$config=array(
    'user'=>"sa",
    'pwd'=>"dfgh1234",
    'perfix'=>"lf_",
    'dbname'=>"student"
);
$db=SQLSRV::GetInstance($config);
$map['age']=array("neq",40);
$result=$db->table("student_info")->where($map)->select();
*/
class SQLSRV{
	  private $host;
	  private $port;
	  private $user;
	  private $pwd;
	  private $charset;
	  private $dbname;
	  private $pk;	  
	  private $link=null;
	  private $fields_type;
	  private $fields;
	  private $table;
	  private $perfix="";
	  private $allow_fields=false;
	  private $sql=array(
	  		"limit"=>"",
	  		"fields"=>"*",
	  		"from"=>"",
	  		"alias"=>"",
	  		"join"=>"",
            "where"=>"",
            "group"=>"",
            "having"=>"",                     
            "order"=>"",           
        	);	 
	  private static $instance=null;     
	  static function GetInstance($config){
		 if(!(self::$instance instanceof self)){ 
		  self::$instance=new self($config); 
		}
		return self::$instance;
	  }
	  private function __clone(){}
	  
	  private function __construct($config){
		  $this->host=!empty($config['host']) ? $config['host']:"127.0.0.1";
		  $this->port=!empty($config['port']) ? $config['port']:"1433";
		  $this->user=!empty($config['user']) ? $config['user']:"sa";
		  $this->pwd=!empty($config['pwd']) ? $config['pwd']:"";
		  $this->perfix=!empty($config['perfix']) ? $config['perfix']:"";
		  $this->charset=!empty($config['charset']) ? $config['charset']:"UTF-8";
		  $this->dbname=!empty($config['dbname']) ? $config['dbname']:"";
		  
		  $serverName = "{$this->host}, {$this->port}"; 
		  $connectionInfo = array( "Database"=>$this->dbname, "UID"=>$this->user, "PWD"=>$this->pwd,'CharacterSet' => $this->charset);
		  $conn = @sqlsrv_connect( $serverName, $connectionInfo);
		  if(!$conn){
		  	if( ($errors = sqlsrv_errors() ) != null) {
		        $this->Errors($errors);
		    }
		  	die();
		  }else{
		  	$this->link=$conn;
		  }		  
	  }

	  //涓诲姩鍏抽棴杩炴帴
      private function CloseDB(){
	    sqlsrv_close($this->link);
	  }


	  private function Errors($errors){
  		foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
	    }
	  }
	  private function GetPK(){
	  	$sql="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME='{$this->perfix}{$this->table}'";
	  	$this->pk=$this->GetOneData($sql);
	  }	 
	  
	  private function GetOneRow($sql,$params=array(),$options=array()){
	     $result=$this->query($sql,$params,$options);
		 $rec=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		 sqlsrv_free_stmt($result);
		 return $rec;
	  }
	  
	  private function GetRows($sql,$params=array(),$options=array()){
	     $result=$this->query($sql,$params,$options);
	     $arr=array();
		 while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ) {
		      $arr[]=$row;
		 }
		 sqlsrv_free_stmt($result);
		 return $arr;
	  }
	  
	  private function GetOneData($sql,$params=array(),$options=array()){
	     $result=$this->query($sql,$params,$options); 
		 sqlsrv_fetch($result);
		 $rec=sqlsrv_get_field($result,0);
		 sqlsrv_free_stmt($result); 
		 return $rec;                 
	  }

	  private function BuildSql(){
	  		$limit=$this->sql['limit'];
		  	$fields=$this->sql['fields'];
		  	if(!$this->sql['order']){
		  		if($this->pk){
		  			$this->sql['order']="ORDER BY {$this->pk} desc";
		  		}
		  		
		  	}
		  	unset($this->sql['limit']);
		  	unset($this->sql['fields']);		  	
		  	return "SELECT {$limit} {$fields} ".(implode(" ", array_filter($this->sql)));
	  }

	  private function AffectedRows($sql,$params=array(),$options=array()){
	  	$stmt=$this->query($sql,$params,$options);
	    return sqlsrv_rows_affected($stmt);
	  }

  
	  //鑾峰彇琛ㄤ腑鎵�鏈夊瓧娈靛強鍏剁被鍨�
	  private function GetFields(){
	  	$sql="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='{$this->perfix}{$this->table}'";
	  	$result=$this->GetRows($sql);
	  	$this->fields_type=array_column($result,"DATA_TYPE","COLUMN_NAME");
	  	$this->fields=array_column($result,"COLUMN_NAME");
	  }

	  public function allowField($params){
	  	if($params===true){
	  		$this->allow_fields=true;
	  	}else{
	  		$this->allow_fields=$params;
	  	}
	  	return $this;
	  }


	  private function setval($field,$val){
	  	if(strrpos($this->fields_type[$field], "int")!==false || strrpos($this->fields_type[$field], "float")!==false || strrpos($this->fields_type[$field], "decimal")!==false){
	  		return $val;
	  	}else{
	  		if(is_array($val)){
	  			array_walk(
				    $val,
				    function (&$s, $k, $prefix = "'") {
				        $s = str_pad($s, strlen($prefix) + strlen($s), $prefix, STR_PAD_LEFT);
				    }
				);
	  			array_walk(
				    $val,
				    function (&$s, $k, $prefix = "'") {
				        $s = str_pad($s, strlen($prefix) + strlen($s), $prefix, STR_PAD_RIGHT);
				    }
				);
				return $val;
	  		}else{
	  			return "'{$val}'";
	  		}
	  		
	  	}
	  }

	  private function query($sql,$params=array(),$options=array()){
		$result=sqlsrv_query($this->link,$sql,$params,$options);
		if( $result === false ) {
			if( ($errors = sqlsrv_errors() ) != null) {
		        $this->Errors($errors);
		    }
		    die();
		}else{			
			return $result;			
		}	
		
	  }

	  public function execute($sql,$params=array(),$options=array()){
	     $result=$this->query($sql,$params,$options);
		 if($result){
		 	return true;
		 }else{
		 	return false;
		 }
	  }

	  public function from($table){
	  	$this->table=$table;
	  	$this->GetFields();
	  	$this->GetPK();
	  	$this->sql['from']="FROM {$this->perfix}{$table}";
	  	return $this;
	  }



	  public function table($table){
	  	$this->table=$table;
	  	$this->GetFields();
	  	$this->GetPK();
	  	$this->sql['from']="FROM {$this->perfix}{$table}";
	  	return $this;
	  }

	  public function group($key){
	  	$this->sql['group']="GROUP BY {$key}";
	  	return $this;
	  }

	  public function having($where){
	  	if(is_array($where)){
	  		foreach ($where as $k => $v) {
	  			if(is_array($v)){
	  				switch (strtoupper($v[0])) {
	  					case 'LIKE':
	  						$op[]="{$k} {$v[0]} ".$this->setval($k,$v[1]);
	  						break;
	  					case "EQ":
	  						$op[]="{$k} = ".$this->setval($k,$v[1]);
	  					    break;
	  					case "NEQ":
	  						$op[]="{$k} <> ".$this->setval($k,$v[1]);
	  						break;
	  					case "BETWEEN":
	  						$params=explode(",", $v[1]);
	  					    $op[]="{$k} BETWEEN ".$this->setval($k,$params[0])." AND ".$this->setval($k,$params[1]);
	  					    break;
	  					case "IN":
	  					    if(is_array($v[1])){
	  					    	$str=implode(",", $this->setval($k,$v[1]));
	  					    }else{	  					    	
	  					    	$str=implode(",",$this->setval($k,explode(",", $v[1])));
	  					    }
	  						$op[]="{$k} IN ({$str})";
	  						break;
	  					case "NOTBETWEEN":
	  						$params=explode(",", $v[1]);
	  					    $op[]="{$k} NOT BETWEEN ".$this->setval($k,$params[0])." AND ".$this->setval($k,$params[1]);
	  						break;
	  					case "NOTIN":
	  						if(is_array($v[1])){
	  					    	$str=implode(",", $this->setval($k,$v[1]));
	  					    }else{	  					    	
	  					    	$str=implode(",",$this->setval($k,explode(",", $v[1])));
	  					    }
	  						$op[]="{$k} NOT IN ({$str})";
	  						break;
	  					case "GT":
	  						$op[]="{$k} > ".$this->setval($k,$v[1]);
	  						break;
	  					case "LT":
	  					    $op[]="{$k} < ".$this->setval($k,$v[1]);
	  					    break;
	  					case "EGT":
	  					    $op[]="{$k} >= ".$this->setval($k,$v[1]);
	  					    break;
	  					case "ELT":
	  					    $op[]="{$k} <= ".$this->setval($k,$v[1]);

	  					default:
	  						# code...
	  						break;
	  				}
	  		
	  				
	  			}else{
	  				$op[]="{$k} = {$v} ";
	  			}
	  		}
	  	$where=implode("AND ", $op);
	  	$this->sql['having']="HAVING {$where}";
	  	}else{
	  		$this->sql['having']="HAVING {$where}";
	  	}	  	
	  	return $this;
	  }


	  public function where($where='1=1'){
	  	if(is_array($where)){
	  		foreach ($where as $k => $v) {
	  			if(is_array($v)){
	  				switch (strtoupper($v[0])) {
	  					case 'LIKE':
	  						$op[]="{$k} {$v[0]} ".$this->setval($k,$v[1]);
	  						break;
	  					case "EQ":
	  						$op[]="{$k} = ".$this->setval($k,$v[1]);
	  					    break;
	  					case "NEQ":
	  						$op[]="{$k} <> ".$this->setval($k,$v[1]);
	  						break;
	  					case "BETWEEN":
	  						$params=explode(",", $v[1]);
	  					    $op[]="{$k} BETWEEN ".$this->setval($k,$params[0])." AND ".$this->setval($k,$params[1]);
	  					    break;
	  					case "IN":
	  					    if(is_array($v[1])){
	  					    	$str=implode(",", $this->setval($k,$v[1]));
	  					    }else{	  					    	
	  					    	$str=implode(",",$this->setval($k,explode(",", $v[1])));
	  					    }
	  						$op[]="{$k} IN ({$str})";
	  						break;
	  					case "NOTBETWEEN":
	  						$params=explode(",", $v[1]);
	  					    $op[]="{$k} NOT BETWEEN ".$this->setval($k,$params[0])." AND ".$this->setval($k,$params[1]);
	  						break;
	  					case "NOTIN":
	  						if(is_array($v[1])){
	  					    	$str=implode(",", $this->setval($k,$v[1]));
	  					    }else{	  					    	
	  					    	$str=implode(",",$this->setval($k,explode(",", $v[1])));
	  					    }
	  						$op[]="{$k} NOT IN ({$str})";
	  						break;
	  					case "GT":
	  						$op[]="{$k} > ".$this->setval($k,$v[1]);
	  						break;
	  					case "LT":
	  					    $op[]="{$k} < ".$this->setval($k,$v[1]);
	  					    break;
	  					case "EGT":
	  					    $op[]="{$k} >= ".$this->setval($k,$v[1]);
	  					    break;
	  					case "ELT":
	  					    $op[]="{$k} <= ".$this->setval($k,$v[1]);

	  					default:
	  						# code...
	  						break;
	  				}
	  		
	  				
	  			}else{
	  				$op[]="{$k} = {$v} ";
	  			}
	  		}
	  	$where=implode("AND ", $op);
	  	$this->sql['where']="WHERE {$where}";
	  	}else{
	  		$this->sql['where']="WHERE {$where}";
	  	}	  	
	  	return $this;
	  }

	  public function count(){
	  	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	  	$stmt=$this->query($this->BuildSql(),$params=[],$options);
	  	return sqlsrv_num_rows($stmt);
	  }

	  public function order($order="id DESC"){
	  	$this->sql['order']="ORDER BY {$order}";
	  	return $this;
	  }

	  public function limit($limit=30,$flag=false){
	  	if($flag){
	  		$this->sql['limit']="TOP {$limit} PERCENT ";
	  	}else{
	  		$this->sql['limit']="TOP {$limit}";
	  	}	  	
	  	return $this;
	  }

	  public function alias($name="A"){
	  	$this->sql['alias']="AS {$name}";
	  	return $this;
	  }

	  public function join($join="LEFT JOIN"){
	  	$this->sql['join']=$join;
	  	return $this;
	  }

	  public function field($fields){
	  	$this->sql['fields']=$fields;
	  	return $this;
	  }

	  public function page($page=1,$count=20){
	  	
	  	$p=$page-1;
	  	$sql=$this->BuildSql()." offset {$p}*{$count} rows fetch next {$count} rows only";
	  	return $this->GetRows($sql);
	  }

	  public function value($field){
	  		$result=$this->GetOneRow($this->BuildSql());
	  		return $result[$field];
	  }

	  public function column($fields){
	  	$f=explode(",", $fields);
	  	$count=count($f);
	  	switch ($count) {
	  		case 1:
	  			$this->sql['fields']=$fields;
	  			$result=$this->GetRows($this->BuildSql());
	  			$result=array_column($result, $f[0]);
	  			break;
	  		case 2:
		  		$this->sql['fields']=$fields;
				$result=$this->GetRows($this->BuildSql());
				$result=array_column($result, $f[1],$f[0]);
				break;
	  		default:
	  			$this->sql['fields']=$fields;
				$result=$this->GetRows($this->BuildSql());
	  			break;
	  	}
	  	
	  	return $result;
	  }

	  public function select(){ 
	  	return $this->GetRows($this->BuildSql());
	  }

	  public function find($key=0){
	  	if($key){
	  		$this->sql['where']="WHERE {$this->pk}=".$this->setval($this->pk,$key);
	  	}
	  	return $this->GetOneRow($this->BuildSql());
	  }

	  public function insert($arr){
	  	$sql="INSERT INTO {$this->perfix}{$this->table} ";
	  	if($this->allow_fields===true){
	  		foreach ($arr as $key => $value) {
		  		if(array_key_exists($key, $this->fields_type)){
		  			$fields[]=$key;
		  			$values[]=$this->setval($key,$value);
		  		}	  		
		  	}
	  	}else if($this->allow_fields){
	  		foreach ($arr as $key => $value) {
		  		if(strrpos($this->allow_fields, $key)!==false){
		  			$fields[]=$key;
		  			$values[]=$this->setval($key,$value);
		  		}	  		
		  	}
	  	}else{
	  		foreach ($arr as $key => $value) {
		  			$fields[]=$key;
		  			$values[]=$this->setval($key,$value);	  		
		  	}
	  	}	  	
	  	if($this->pk){
	  		$sql.="(".implode(',', $fields).") OUTPUT inserted.{$this->pk} VALUES (".implode(',', $values).")";
	  		return $this->GetOneData($sql);
	  	}else{
	  		$sql.="(".implode(',', $fields).") VALUES (".implode(',', $values).")";
	  		return $this->AffectedRows($sql);
	  	}
	  	
	  }

	  public function insertAll($arr){
	  	$keys=array_keys($arr[0]);
	  	 $sql="INSERT INTO {$this->perfix}{$this->table} ";
	  	 foreach ($arr as $key => $value) {
	  	 	$values=[];
	  		foreach ($value as $k => $v) {
	  			$values[]=$this->setval($k,$v);
	  		}
	  		$VL[]="(".implode(",", $values).")";
	  	}
	  	$sql.="(".implode(",", $keys).") VALUES ".implode(",", $VL);
	  	return $this->AffectedRows($sql);
	  }

	  public function update($arr){
	  		$fields=array_keys($arr);
	  		if(in_array($this->pk, $fields)){
	  			$this->sql['where']="WHERE {$this->pk}=".$this->setval($this->pk,$arr[$this->pk]);
	  			unset($arr[$this->pk]);
	  		}
	  		
	  		$sql="UPDATE {$this->perfix}{$this->table} SET ";
	  		if($this->allow_fields===true){
	  			foreach ($arr as $key => $value) {
	  			  if(array_key_exists($key, $this->fields_type)){
			  			$op[]="{$key}=".$this->setval($key,$value);
			  		}
	  			}
	  		}else if($this->allow_fields){
	  			foreach ($arr as $key => $value) {
			  		if(strrpos($this->allow_fields, $key)!==false){
			  			$op[]="{$key}=".$this->setval($key,$value);
			  		}	  		
			  	}
	  		}else{
	  			foreach ($arr as $key => $value) {
			  			$op[]="{$key}=".$this->setval($key,$value);	  		
			  	}
	  		}	  		
	  		unset($this->sql['limit']);
		  	unset($this->sql['fields']);
		  	unset($this->sql['from']);
	  		$sql.=implode(",", $op)." ".implode(",", array_filter($this->sql));
	  		return $this->AffectedRows($sql);
	  		
	  }

	  public function delete($key=0){
	  		if($key){
	  			$this->sql['where']="WHERE {$this->pk}=".$this->setval($this->pk,$key);
	  		}
	  		unset($this->sql['limit']);
		  	unset($this->sql['fields']);
		  	unset($this->sql['from']);
	  		$sql="DELETE FROM {$this->perfix}{$this->table} ".(implode(" ", array_filter($this->sql)));
	  		return $this->AffectedRows($sql);
	  }

	  public function startTrans(){
	  		if ( sqlsrv_begin_transaction($this->link) === false ) {
			     die( print_r( sqlsrv_errors(), true ));
			}
	  }

	  public function commit(){
	  		sqlsrv_commit($this->link);
	  }

	  public function rollback(){
	  	  sqlsrv_rollback($this->link);
	  }

}
?>