<?php
class M{
	protected $table = null;
	protected $where = null;
	protected $order = null;
	public function init($a){
		if(is_array($a)){
			$this->table = !empty($a['table'])?C('DB_PREFIX').strtolower($a['table']):'';
			mysql_connect($a['host'],$a['user'],$a['pass']) or die('连接数据库失败！ - '.mysql_error());
		}else{
			$this->table = C('DB_PREFIX').strtolower($a);
			$this->connect();
			mysql_select_db(C('DB_NAME')) or die('选择数据库失败！ - '.mysql_error());
		}
	}
	public function query($sql){
		return mysql_query($sql);
	}
	protected function connect(){
		mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PASS')) or die('连接数据库失败！ - '.mysql_error());
		$this->query('set names '.C('DB_CHARSET'));
	}
	public function where($a=null){
		$next;
		if(is_array($a)){
			$next = 'array';
		}else{
			$next = ' where '.$a;
		}
		$this->where = $next;
		$obj = $this;
		return $obj;
		//return $next;
	}
	public function order($a=null){
		$next = " order by ".$a." desc";
		$this->order = $next;
		$obj = $this;
		return $obj;
	}
	public function find($a=null){
		$sql=null;
		if(is_null($a)){
			$a = ' * ';
		}else{
			$a = ' `'.$a.'` ';
		}
		if(!is_null($this->where))
			$sql = $this->where;
		if(!is_null($this->order))
			$sql.=$this->order;
		return $this->fetch($this->query('select'.$a.'from '.$this->table.$sql.' limit 1'));
	}
	public function fetch($a){
		return mysql_fetch_array($a);
	}
}
?>
