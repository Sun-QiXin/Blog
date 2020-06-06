<?php
//声明命名空间
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CategoryModel;
use \Admin\Model\ArticleModel;

//定义文章控制器类，并继承基础控制器类
class ArticleController extends BaseController
{
	//显示文章列表
	public function index()
	{
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAll("id ASC")
		);

		//构建搜索条件：$where = "2>1 AND category_id=2 AND title LIKE '%中国%'"
		$where = "2>1";
		if(!empty($_REQUEST['category_id'])) $where .= " AND category_id=".$_REQUEST['category_id'];
		if(!empty($_REQUEST['keyword'])) $where .= " AND title LIKE '%".$_REQUEST['keyword']."%'";
		echo $where;

		//分页参数
		$pagesize 	= 5; //每页显示条数
		$page 		= isset($_GET['page']) ? $_GET['page'] : 1; //当前页码
		$startrow	= ($page-1)*$pagesize; //开始行号
		$records	= ArticleModel::getInstance()->rowCount($where);
		$params		= array('c'=>CONTROLLER,'a'=>ACTION); //附加参数
		if(!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id'];
		if(!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];		

		//创建分页类对象
		$pageObj = new \Frame\Vendor\Pager($records,$pagesize,$page,$params);
		$pageStr = $pageObj->showPage();

		//获取连表查询的分页文章数据
		$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$startrow,$pagesize);

		//向视图赋值，并显示视图
		$this->smarty->assign(array(
				'categorys'	=> $categorys,
				'articles'	=> $articles,
				'pageStr'	=> $pageStr,
			));
		$this->smarty->display("index.html");
	}

	//显示添加文章的表单
	public function add()
	{
		//获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAll("id ASC")
		);
		//向视图赋值，并显示视图
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("add.html");
	}

	//插入文章数据
	public function insert()
	{
		//获取表单提交数据
		$data['user_id']		= $_SESSION['uid'];
		$data['category_id']	= $_POST['category_id'];
		$data['title']			= $_POST['title'];
		$data['orderby']		= $_POST['orderby'];
		$data['top']			= isset($_POST['top']) ? 1 : 0;
		$data['content']		= $_POST['content'];
		$data['addate']			= time();

		//判断记录是否插入成功
		if(ArticleModel::getInstance()->insert($data))
		{
			$this->jump("文章添加成功！","?c=Article");
		}else
		{
			$this->jump("文章添加失败！","?c=Article");
		}
	}
}