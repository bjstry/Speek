<?php
/*  Speek.php 驱动文件
 *  定义相关参数
 *  调用Run方法运行框架
 *  加载公共函数库functions.php
 *  加载smarty模版引擎
**/
	session_start();
	header("Content-Type: text/html;charset=utf-8"); 
	date_default_timezone_set("Asia/Chongqing");
	defined('SYS') or define('SYS',__DIR__);
	defined('PRJ') or define('PRJ',dirname($_SERVER['SCRIPT_FILENAME']).'/SpeekHome');
	defined('ROOT') or define('ROOT',dirname($_SERVER['SCRIPT_NAME']));
	defined('URL') or define('URL',$_SERVER['SCRIPT_NAME']);
	defined('EXT') or define('EXT','.php');
	defined('CEXT') or define('CEXT','.class.php');
	defined('SYS_CORE') or define('SYS_CORE',SYS.'/Core/');
	defined('SYS_CONF') or define('SYS_CONF',SYS.'/Conf/');
	defined('SYS_LIB') or define('SYS_LIB',SYS.'/Lib/');
	defined('SYS_LOG') or define('SYS_LOG',SYS.'/Log/');
	defined('SYS_VERSION') or define('SYS_VERSION','Speek-1.27');
	require SYS_LIB.'/functions.php';
	require_once(SYS_LIB.'Tpl/Smarty'.CEXT);
	require SYS_CORE.'/Speek.class.php';
	Speek::Run();
?>
