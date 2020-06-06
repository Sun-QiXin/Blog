<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;

//定义文章模型类，并继承基础模型类
class ArticleModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "article";

	//获取文章按月份归档数据
	public function fetchAllWithCount()
	{
		//构建查询的SQL语句
		$sql = "SELECT date_format(from_unixtime(addate),'%Y年%m月') as yearmonth,";
		$sql .= "count(id) as article_count FROM {$this->table} ";
		$sql .= "GROUP BY yearmonth ";
		$sql .= "ORDER BY yearmonth DESC";

		//执行SQL语句，并返回二维数组
		return $this->pdo->fetchAll($sql);
	}

	//获取文章连表查询的数据
	public function fetchAllWithJoin($where,$startrow,$pagesize)
	{
		//构建连表查询的SQL语句
		$sql = "SELECT article.*,user.name,category.classname FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY article.id DESC ";
		$sql .= "LIMIT {$startrow},{$pagesize}";

		//执行SQL语句，并返回二维数组
		return $this->pdo->fetchAll($sql);
	}

	//获取指定ID的连表查询的数据
	public function fetchOneWithJoin($id)
	{
		//构建连表查询的SQL语句
		$sql = "SELECT article.*,user.name,category.classname FROM {$this->table} ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "WHERE article.id={$id} ";

		//执行SQL语句，并返回一维数组
		return $this->pdo->fetchOne($sql);		
	}

	//根据ID连表查询当前文章的评论
    public function fetchAllComment($id)
    {
        //构建连表查询的SQL语句
        $sql = "SELECT u.`name`,c.addate,c.content FROM {$this->table} a ";
        $sql .= "LEFT JOIN `comment` c ON c.article_id = a.id L";
        $sql .= "EFT JOIN `user` u on u.id = c.user_id ";
        $sql .= "WHERE a.id ={$id}  ";
        $sql .= "ORDER BY c.addate DESC";

        //执行SQL语句，并返回二维数组
        return $this->pdo->fetchAll($sql);
    }

	//更新阅读数
	public function updateRead($id)
	{
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET `read` = `read` + 1 WHERE id={$id}";
		//执行SQL语句
		return $this->pdo->exec($sql);
	}

	//更新点赞数
	public function updatePraise($id)
	{
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET `praise` = `praise` + 1 WHERE id={$id}";
		//执行SQL语句
		return $this->pdo->exec($sql);
	}
}