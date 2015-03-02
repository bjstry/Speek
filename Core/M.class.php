<?php
class M{
	protected $table = null;
	public function init($a){
		if(is_array($a)){
			$this->table = !empty($a['table'])?$a['table']:'';
			mysql_connect($a['host'],$a['user'],$a['pass']) or die('连接数据库失败！ - '.mysql_error());
		}else{
			$this->table = $a;
			$this->connect();
			mysql_select_db(C('DB_NAME')) or die('选择数据库失败！ - '.mysql_error());
			$this->query('set names utf-8');
		}
	}
	protected function query($sql){
		return mysql_query($sql);
	}
	protected function connect(){
		mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PASS')) or die('连接数据库失败！ - '.mysql_error());
		$this->query('set names '.C('DB_CHARSET'));
	}
}
?>
