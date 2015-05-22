<?php
/*
 * 模块基类
 */
class M{
	protected $table = null;  //定义表前缀，如未设置调用系统默认
	protected $where = null;  //存储定义条件
	protected $order = null;  //存储定义排序
	protected $_verarr;       //存储自动验证数组

	//-----初始化数据库-----//
	public function init($a){
		if(is_array($a)){

			//-----使用数组自定义连接数据库
			$this->table = !empty($a['table'])?C('DB_PREFIX').strtolower($a['table']):'';
			mysql_connect($a['host'],$a['user'],$a['pass']) or die('连接数据库失败！ - '.mysql_error());
		}else{

			//-----调用配置文件连接数据库
			$this->table = C('DB_PREFIX').strtolower($a);
			$this->connect();
			mysql_select_db(C('DB_NAME')) or die('选择数据库失败！ - '.mysql_error());
		}
	}
	public function query($sql){
		return mysql_query($sql);
	}

	//---调用配置文件连接数据库---//
	protected function connect(){
		mysql_connect(C('DB_HOST'),C('DB_USER'),C('DB_PASS')) or die('连接数据库失败！ - '.mysql_error());
		$this->query('set names '.C('DB_CHARSET'));
	}

	//---追加处理条件---//
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
	}

	//---追加排序条件---//
	public function order($a=null){
		$next = " order by ".$a." desc";
		$this->order = $next;
		$obj = $this;
		return $obj;
	}

	//---单条查询---//
	public function find($a=null){
		$sql=null;
		$row=null;
		if(is_null($a)){
			$a = ' * ';
		}else{
			$a = ' `'.$a.'` ';
		}
		if(!is_null($this->where))
			$sql = $this->where;
		if(!is_null($this->order))
			$sql.=$this->order;
		$sqltrl = 'select'.$a.'from '.$this->table.$sql.' limit 1';
		$query = $this->query($sqltrl);
		if(empty($query)){
			die('Find error - '.mysql_error().'<br>SQL : '.$sqltrl);
		}else{
			$row = $this->fetch($query);
		}
		if(empty($row))
			echo '<br>SQL : '.$sqltrl;
		return $row;
	}
	
	//---多条查询函数---//
	public function select($a=null,$b=null){
		//--$a 查询数量，默认查询所有--//
		//--$b 查询子段，默认为*通配符--//
		$sql=null;  //查询语句
		$num=null;  //查询数量
		$row=null;  //存储查询结果的二维数组
		$num = !is_null($a)?' limit '.$a:'';
		if(is_null($b)){
			$b = ' * ';
		}else{
			$b = ' `'.$b.'` ';
		}
		//--获取查询条件--//
		if(!is_null($this->where))
			$sql = $this->where;
		//--获取查询排序--//
		if(!is_null($this->order))
			$sql.=$this->order;
		$sql = 'select'.$b.'from '.$this->table.$sql.$num;
		$query = $this->query($sql) or die('Select error - '.mysql_error().'<br>SQL : '.$sql);
		while($srow = $this->fetch($query)){
			$row[]=$srow;
		}
		return $row; //返回查询结果二维数组
	}
	public function insert($a=null,$b=null){
		$sql=null;
		if(is_null($b)){
			$sql = "insert into `$this->table` values ($a)";
		}else{
			$sql = "insert into `$this->table` ($a) values ($b)";
		}
		$this->verify();
		$query = $this->query($sql) or die('Insert error - '.mysql_error().'<br>SQL : '.$sql);
		return mysql_insert_id();
	}
	public function update($a=null){
		$where = $this->where;
		$sql = "update `$this->table` set $a$where";
		$query = $this->query($sql) or die('Update error - '.mysql_error().'<br>SQL : '.$sql);
		return $query;
	}
	public function fetch($a){
		return mysql_fetch_array($a);
	}
	
	//---自动验证函数---//
	protected function verify(){
		foreach($this->_verarr as $arr){
			switch($arr[1]){
				case 1:
					if($_POST[$arr[0]] == null){
						die($arr[2]);
					}
					break;
				case 2:
					if($_POST[$arr[0]] < 0){
						die($arr[2]);
					}
			}
		}
	}

	//--分页函数--//
	/*
		$count 数据总条数
		$nums  单页数据条数
		$key   分页GET变量名
		$val   分页GET值，当前页
		$url   当前页面地址或者分页页面地址
	**/
	public function page($count,$nums,$key,$val,$url=null){
		$rtarr;           //返回数组
		$nowpage = 1;     //默认当前页
		$leftstatus = 0;  //上一页是否激活
		$rightstatus = 1; //下一页是否激活
		$pagenums = 4;    //最多显示页数
		$pages = ceil($count/$nums); //总页数
		$content = null;  //页码部分
		$activeclass = " class='uk-active' ";
		$url = empty($url)?$_SERVER['SCRIPT_NAME'].'/index/index':$url; //获取url
		if($val > 1 && $val < $pages+1){
			$nowpage = $val;
			$leftstatus = 1;
			if($val == $pages){
				$rightstatus = 0;
			}
		}
		$leftout = !$leftstatus?"class='uk-disabled'":"";	
		$rightout = !$rightstatus?"class='uk-disabled'":"";	
		$activeclass = null;
		$rows = $this->select(($nowpage-1)*$nums.",$nums");
		for($i=1;$i<=$pages;$i++){
			if($pages < 5){
				if($i == $nowpage){
					$content.="<li class='uk-active'><a href='$url/$key/$i'>$i</a></li>";
				}else{
					$content.="<li><a href='$url/$key/$i'>$i</a></li>";
				}
			}else{
				if($i == $nowpage-1 or $i == $nowpage+1){
					$content.="<li><a href='$url/$key/$i'>$i</a></li>";
				}else if($i == $nowpage){
					$content.="<li class='uk-active'><a href='$url/$key/$i'>$i</a></li>";
				}else if($i == $pages){
					$content.="...<li class='uk-active'><a href='$url/$key/$i'>$i</a></li>";
				}
			}
		}
		$pageout = "
			<ul class='uk-pagination'>
				<li $leftout><a href='$url/$key/".($nowpage-1)."'><i class='uk-icon-angle-double-left'></i></a></li>
				".$content."
				<li $rightout><a href='$url/$key/".($nowpage+1)."'><i class='uk-icon-angle-double-right'></i></a></li>
			</ul>";
		$rtarr = array($rows,$pageout);
		return $rtarr;
	}
}
?>
