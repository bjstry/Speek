<?php
class Speek{
	public static function Run(){
		set_error_handler('Speek::GetError');
		spl_autoload_register('Speek::AutoLoad');
		Speek::LoadConf();
		Speek::CreateDir();
		Speek::GetCm();
		Speek::LoadFile($_GET['c'],$_GET['m']);
	}
	static function AutoLoad($c){
		include_once SYS_CORE.$c.CEXT;	
	}
	public static function GetError($errno,$errstr,$errfile,$errline,$errcont){
		$err = $errline." 行 [$errno] $errstr 在文件 $errfile 中\r\n";
		error_log($err,3,SYS_LOG.'error.log');
		$err_echo = $errline."行 [$errno] $errstr 在文件 $errfile 中<br />";
		echo $err_echo;
		if($errno == 256 || $errno == 4096){
			echo $err_echo;
			exit();
		}
	}
	private static function CreateDir(){
		if(!file_exists(PRJ)) mkdir(PRJ);
		if(!file_exists(C('PRJ_CDIR'))) mkdir(C('PRJ_CDIR'));
		if(!file_exists(C('PRJ_MDIR'))) mkdir(C('PRJ_MDIR'));
		if(!file_exists(C('PRJ_VDIR'))) mkdir(C('PRJ_VDIR'));
		if(!file_exists(C('PRJ_VDIR').C('DT_THEME'))) mkdir(C('PRJ_VDIR').C('DT_THEME'));
		if(!file_exists(C('PRJ_VCDIR'))) mkdir(C('PRJ_VCDIR'));
		if(!file_exists(C('PRJ_VCACHE'))) mkdir(C('PRJ_VCACHE'));
		if(!file_exists(C('PRJ_COM'))) mkdir(C('PRJ_COM'));
		if(!file_exists(C('PRJ_CONF'))) mkdir(C('PRJ_CONF'));
		//if(!file_exists(C('PRJ_LOG'))) mkdir(C('PRJ_LOG'));
	}
	private static function GetCm(){
		if(C('DT_URLTYPE')==1){
			$_GET['c'] = !empty($_GET['c'])?$_GET['c']:C('DT_CONTROLLER');
			$_GET['m'] = !empty($_GET['m'])?$_GET['m']:C('DT_ACTION');
		}else if(C('DT_URLTYPE')==2){
			if(empty($_SERVER['PATH_INFO'])){
				$_GET['c'] = C('DT_CONTROLLER');
				$_GET['m'] = C('DT_ACTION');
			}else{
				$path = explode('/',trim($_SERVER['PATH_INFO']));
				$_GET['c'] = !empty($path[1])?$path[1]:'';
				$_GET['m'] = !empty($path[2])?$path[2]:'';
			}
		}else if(C('DT_URLTYPE')==3){
			if(!empty($_SERVER['PATH_INFO'])){
				$path = explode('/',trim($_SERVER['PATH_INFO']));
				$c = !empty($path[1])?$path[1]:'';
				$m = !empty($path[2])?$path[2]:'';
			}
			$_GET['c'] = !empty($_GET['c'])?$_GET['c']:C('DT_CONTROLLER');
			$_GET['m'] = !empty($_GET['m'])?$_GET['c']:C('DT_ACTION');
			$_GET['c'] = !empty($c)?$c:$_GET['c'];
			$_GET['m'] = !empty($m)?$m:$_GET['m'];
			
		}
		$_GET['c'] = ucwords($_GET['c']);
		$_GET['m'] = ucwords($_GET['m']);
	}
	private static function LoadConf(){
		if(is_file(C('PRJ_COM').'functions'.EXT)){
			include_once C('PRJ_COM').'functions'.EXT;
		}
		if(is_file(SYS_CONF.'Config'.EXT)){
			C(include(SYS_CONF.'Config'.EXT));
			is_file(C('PRJ_CONF').'Config'.EXT)?C(array_merge(include(SYS_CONF.'Config'.EXT),include(C('PRJ_CONF').'Config'.EXT))):C(include(SYS_CONF.'Config'.EXT));
		}
	}
	private static function LoadFile($c,$m){
		$cfile = C('PRJ_CDIR').$c.C('DT_C_NAME').CEXT;
		if(!is_file($cfile)){
			exit($c.' 控制器不存在！');
		}else{
			include_once $cfile;
		}
		$class = $c.C('DT_C_NAME');
		if(!class_exists($class)){
			exit('控制器未定义！');
		}
		$pram = new $class;
		if(!method_exists($pram,$m)){
			exit('接口未定义！');
		}
		$pram->$m();
	}
}
?>
