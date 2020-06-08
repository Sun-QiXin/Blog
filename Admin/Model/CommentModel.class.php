<?php
//声明命名空间
namespace Admin\Model;
use \Frame\Libs\BaseModel;

//定义分类模型类，并继承基础模型类
class CommentModel extends BaseModel
{
    //受保护的数据表名称
    protected $table = "comment";

    //多表联查获取评论信息
    public function fetchALLComment($where = '2>1', $startrow = 0, $pagesize = 8){
        //构建连表查询的SQL语句
        $sql = "SELECT c.id,c.content,c.addate,a.title,u.`name`,a.`read`,a.praise,cat.classname FROM article a ";
        $sql .= "RIGHT JOIN `comment` c ON a.id = c.article_id ";
        $sql .= "LEFT JOIN `user` u ON u.id = c.user_id ";
        $sql .= "LEFT JOIN category cat ON cat.id = a.category_id ";
        $sql .= "WHERE {$where} ";
        $sql .= "ORDER BY a.orderby ASC,a.id DESC ";
        $sql .= "LIMIT {$startrow},{$pagesize}";

        //执行SQL语句，并返回一维数组
        return $this->pdo->fetchAll($sql);
    }

    //多表联查获取评论条数
    public function myRowCount($where = '2>1'){
        //构建连表查询的SQL语句
        $sql = "SELECT COUNT(c.id) FROM article a ";
        $sql .= "RIGHT JOIN `comment` c ON a.id = c.article_id ";
        $sql .= "LEFT JOIN `user` u ON u.id = c.user_id ";
        $sql .= "LEFT JOIN category cat ON cat.id = a.category_id ";
        $sql .= "WHERE {$where} ";

        //执行SQL语句，并返回记录数
        return $this->pdo->fetchOne($sql)["COUNT(c.id)"];
    }
}