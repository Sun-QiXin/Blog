<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;

//定义文章分类模型类，并继承基础模型类
class CategoryModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "category";

	//获取原始的文章分类连表数据(连接文章数量)
	public function fetchAllWithJoin()
	{
		//构建连表查询的SQL语句
		$sql = "SELECT category.*,count(article.id) as article_count FROM {$this->table} ";
		$sql .= "LEFT JOIN article ON category.id=article.category_id ";
		$sql .= "GROUP BY category.id ";
		$sql .= "ORDER BY category.id ASC";
		//执行SQL语句，并返回结果二维数组
		return $this->pdo->fetchAll($sql);
	}

	//获取无限级分类数据的方法
	public function categoryList($arrs,$level=0,$pid=0)
	{
		static $categorys = array();
		//循环原始的分类数组
		foreach($arrs as $arr)
		{
			//查找下级菜单
			if($arr['pid']==$pid)
			{
				$arr['level'] = $level;
				$categorys[] = $arr;
				//递归调用
				$this->categoryList($arrs,$level+1,$arr['id']);
			}
		}
		//返回结果
		return $categorys;
	}
}