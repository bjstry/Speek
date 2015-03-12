<?php
class C{
	protected $view = null;
	//protected $model = null;
	public function __construct(){
		$this->view = new V();
	}
	protected function assign($a=null,$b=null){
		if(is_array($a)){
			if($b==null){
				//echo 'ok';
				$this->view->assign($a);
			}else{
				exit('参数非法,多余的参数:'.$b);
			}
		}else{
			if($a==null)){
				exit('参数非法:'.$a.' 不能为空');
			}else{
				//echo 'ok2';
				$this->view->assign($a,$b);
			}
		}
	}
	protected function display($a=null,$b=null,$c=null){
		if(is_file(C('PRJ_VDIR').C('DT_THEME').'/theme/head'.C('DT_V_EXT')))
			//echo C('PRJ_VDIR').C('DT_THEME').'/theme/head'.C('DT_V_EXT');
			$this->view->display(C('PRJ_VDIR').C('DT_THEME').'/theme/head'.C('DT_V_EXT'));
		$type = empty($a)?1:0;
		if($type == 1){
			$err = is_file(C('PRJ_VDIR').C('DT_THEME').'/'.$_GET['c'].$_GET['m'].C('DT_V_EXT'))?$this->view->display(C('PRJ_VDIR').C('DT_THEME').'/'.$_GET['c'].$_GET['m'].C('DT_V_EXT'),$b,$c):1;
			if($err==1){
				exit(C('PRJ_VDIR').C('DT_THEME').'/'.$_GET['c'].$_GET['m'].C('DT_V_EXT').'- 模板文件不存在!');
			}
		}else{
			$err = is_file(C('PRJ_VDIR').C('DT_THEME').'/'.$a.C('DT_V_EXT'))?$this->view->display(C('PRJ_VDIR').C('DT_THEME').'/'.$a.C('DT_V_EXT'),$b,$c):1;
			if($err==1){
				exit(C('PRJ_VDIR').C('DT_THEME').'/'.$a.C('DT_V_EXT').'- 模板文件不存在!');
			}
		}
		if(is_file(C('PRJ_VDIR').C('DT_THEME').'/theme/footer'.C('DT_V_EXT')))
			//echo C('PRJ_VDIR').C('DT_THEME').'/theme/footer'.C('DT_V_EXT');
			$this->view->display(C('PRJ_VDIR').C('DT_THEME').'/theme/footer'.C('DT_V_EXT'));
	}
	protected function url($a=null,$b=null){
		if(is_null($b)){
			echo "<script>alert('$a')</script>";
			echo "<script>history.go(-1)</script>";
		}else{
			echo "<script>alert('$a')</script>";
			echo "<script>location.href='$_SERVER[SCRIPT_NAME]$b'</script>";
		}
	}
}
?>
