<?php
/*
 * 系统公共函数库，全局调用
 */
 
 //-----C方法 获取和设置配置
function C($name=null,$value=null){
	static $_conf = array();
	if(empty($name)){
		return $_conf;
	}
	if(is_string($name)){
		if(!strpos($name,'.')){
			$name=strtolower($name);
			if(is_null($value))
				return isset($_conf[$name])?$_conf[$name]:null;
			$_conf[$name]=$vlaue;
			echo $_conf[$name];
			return;
		}
		$name = explode('.',$name);
		$name[0]=strtolower($name[0]);
		if(is_null($value))
			return isset($_conf[$name[0]][$name[1]])?$_conf[$name[0]][$name[1]]:null;
		$_conf[$name[0]][$name[1]]=$value;
		return;
	}
	if(is_array($name)){
		return $_conf = array_merge($_conf,array_change_key_case($name));
	}
	return null;
}

//-----M方法:生成带数据库操作的空模块
function M($a){
	$obj = new M();
	$obj->init($a);
	return $obj;
}

//-----D方法:模块加载
function D($a){
	if(empty($a)){
		echo '模块名未定义！';
		exit();
	}
	$modelfile = C('PRJ_MDIR').ucwords($a).C('DT_M_NAME').CEXT;
	if(!is_file($modelfile)){
		echo '模块:'.$a.'-不存在！';
		exit();
	}else{
		include_once $modelfile;
	}
	$class = $a.C('DT_M_NAME');
	if(!class_exists($class)){
		die('模块:'.$a.'-未定义!');
		exit();
	}
	$obj = new $class;
	$obj->init($a);
	return $obj;
}

//-----session方法：设置或获取session值
function session($a=null,$b=null){
	if(is_null($b)){
		$resut = isset($_SESSION[$a])?$_SESSION[$a]:null;
		return $resut;
	}
	$_SESSION[$a] = $b;
	if($b == 'null')
		unset($_SESSION[$a]);
	if($a == 'clear' and $b = null)
		session_destroy();
}
class SpeekFrameWorkSqlite3DB extends SQLite3{
	function __construct($name){
		 $this->open(C('PRJ_COM').$name.'.db');
	}
}
function SQ($name){
	$db = new SpeekFrameWorkSqlite3DB($name);	
	return $db;
}
?>
