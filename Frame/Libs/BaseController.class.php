<?php
//声明命名空间
namespace Frame\Libs;

//定义抽象的基础控制器类
abstract class BaseController
{
	//受保护的Smarty对象属性
	protected $smarty = null;

	//构造方法
	public function __construct()
	{
		//创建Smarty类的对象
		$smarty = new \Frame\Vendor\Smarty();
		//配置Smarty的左右定界符
		$smarty->left_delimiter = "<{";
		$smarty->right_delimiter = "}>";
		//指定新的编译目录：c:\windows\temp\view\
		$smarty->setCompileDir(sys_get_temp_dir().DS.'view_c'.DS);
		//指定视图目录：./Home/View/Student/
		$smarty->setTemplateDir(VIEW_PATH);
		//给$this->smarty属性赋值
		$this->smarty = $smarty;
	}

	//用户登录判断
	protected function denyAccess()
	{
		//判断用户是否存在
		if(empty($_SESSION['username']))
		{
			$this->jump("请先登录！","?c=User&a=login");
		}		
	}

	//跳转方法
	protected function jump($message,$url='?',$time=3)
	{
		echo "<h2>{$message}</h2>";
		header("refresh:{$time};url={$url}");
		die();
	}
}