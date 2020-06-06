<?php
//声明命名空间
namespace Frame\Libs;

//定义最终的单例的数据库工具类
class Db
{
	//私有的静态的保存对象的属性
	private static $obj = null;

	//私有的数据库配置信息
	private $db_host; //主机名
	private $db_user; //用户名
	private $db_pass; //密码
	private $db_name; //数据库名
	private $charset; //字符集
	private $link; 	  //连接对象	

	//私有的构造方法：阻止类外new对象
	private function __construct()
	{
		$this->db_host = $GLOBALS['config']['db_host'];
		$this->db_user = $GLOBALS['config']['db_user'];
		$this->db_pass = $GLOBALS['config']['db_pass'];
		$this->db_name = $GLOBALS['config']['db_name'];
		$this->charset = $GLOBALS['config']['charset'];
		$this->connectDb(); //连接MySQL服务器
		$this->selectDb();  //选择数据库
		$this->setCharset();//设置字符集
	}

	//私有的克隆方法：阻止类外clone对象
	private function __clone(){}

	//公共的静态的创建对象的方法
	public static function getInstance()
	{
		//判断当前对象是否存在
		if(!self::$obj instanceof self)
		{
			//如果对象不存在，创建并保存它
			self::$obj = new self();
		}
		//返回对象
		return self::$obj;
	}

	//私有的连接MySQL服务器方法
	private function connectDb()
	{
		if(!$this->link = @mysqli_connect($this->db_host, $this->db_user, $this->db_pass))
		{
			echo "连接MySQL服务器失败！";
			die();
		}
	}

	//私有的选择数据库的方法
	private function selectDb()
	{
		if(!mysqli_select_db($this->link, $this->db_name))
		{
			echo "选择数据库{$this->db_name}失败！";
			die();
		}
	}

	//私有的设置字符集
	private function setCharset()
	{
		mysqli_set_charset($this->link, $this->charset);
	}

	//公共的执行SQL语句的方法：insert、update、delete、set、drop等
	//执行成功返回true，执行失败返回false
	public function exec($sql)
	{
		//例如：update student set salary=salary+500 where id=5
		//将SQL语句转成全小写
		$sql = strtolower($sql);

		//判断是不是SELECT语句
		if(substr($sql,0,6)=='select')
		{
			echo "不能执行SELECT语句！";
			die();
		}

		//返回执行的结果(布尔值)
		return mysqli_query($this->link, $sql);
	}

	//私有的执行SQL语句的方法：select
	//执行成功返回结果集对象，执行失败返回false
	private function query($sql)
	{
		//例如：SELECT * FROM student
		//将SQL语句转成全小写
		$sql = strtolower($sql);

		//判断是不是SELECT语句
		if(substr($sql,0,6)!='select')
		{
			echo "不能执行非SELECT语句！";
			die();
		}

		//返回执行的结果(结果集对象)
		return mysqli_query($this->link, $sql);	
	}

	//公共的获取单行数据的方法
	public function fetchOne($sql,$type=3)
	{
		//执行SQL语句，并返回结果集对象
		$result = $this->query($sql);

		//定义返回数组类型的常量
		$types = array(
				1 => MYSQLI_NUM,
				2 => MYSQLI_BOTH,
				3 => MYSQLI_ASSOC,
			);

		//返回一维数组
		return mysqli_fetch_array($result, $types[$type]);
	}

	//公共的获取多行数据的方法
	public function fetchAll($sql, $type=3)
	{
		//执行SQL语句，并返回结果集对象
		$result = $this->query($sql);

		//定义返回数组类型的常量
		$types = array(
				1 => MYSQLI_NUM,
				2 => MYSQLI_BOTH,
				3 => MYSQLI_ASSOC,
			);

		//返回二维数组
		return mysqli_fetch_all($result, $types[$type]);		
	}

	//获取记录数
	public function rowCount($sql)
	{
		//执行SQL语句，并返回结果集对象
		$result = $this->query($sql);
		//返回记录数
		return mysqli_num_rows($result);
	}

	//公共的析构方法
	public function __destruct()
	{
		mysqli_close($this->link); //断开MySQL连接
	}
}