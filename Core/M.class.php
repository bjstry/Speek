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
	public function select($a=null,$b=null){
		$sql=null;
		$num=null;
		$row=null;
		$num = !is_null($a)?' limit '.$a:'';
		if(is_null($b)){
			$b = ' * ';
		}else{
			$b = ' `'.$b.'` ';
		}
		if(!is_null($this->where))
			$sql = $this->where;
		if(!is_null($this->order))
			$sql.=$this->order;
		$query = $this->query('select'.$b.'from '.$this->table.$sql.$num);
		while($srow = $this->fetch($query)){
			$row[]=$srow;
		}
		return $row;
	}
	public function insert($a=null,$b=null){
		$sql=null;
		if(is_null($b)){
			$sql = "insert into `$this->table` values ($a)";
		}else{
			$sql = "insert into `$this->table` ($a) values ($b)";
		}
		print_r($sql);
		$query = $this->query($sql) or die(mysql_error());
		return mysql_insert_id();
	}
	public function update($a=null){
		$where = $this->where;
		$sql = "update `$this->table` set $a$where";
		print_r($sql);
		$query = $this->query($sql) or die(mysql_error());
		return $query;
	}
	public function fetch($a){
		return mysql_fetch_array($a);
	}
}
?>
