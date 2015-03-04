<?php
	//beta2 start 15210-1026
	//author TRY
	session_start();
	defined('SYS') or define('SYS',__DIR__);
	defined('PRJ') or define('PRJ',dirname($_SERVER['SCRIPT_FILENAME']).'/SpeekHome');
	defined('ROOT') or define('ROOT',dirname($_SERVER['SCRIPT_NAME']));
	defined('EXT') or define('EXT','.php');
	defined('CEXT') or define('CEXT','.class.php');
	defined('SYS_CORE') or define('SYS_CORE',SYS.'/Core/');
	defined('SYS_CONF') or define('SYS_CONF',SYS.'/Conf/');
	defined('SYS_LIB') or define('SYS_LIB',SYS.'/Lib/');
	defined('SYS_LOG') or define('SYS_LOG',SYS.'/Log/');
	defined('SYS_VERSION') or define('SYS_VERSION','SpeekFrame-0.04');
	require SYS_LIB.'/functions.php';
	require SYS_LIB.'Tpl/Smarty'.CEXT;
	require SYS_CORE.'/Speek.class.php';
	Speek::Run();
?>
