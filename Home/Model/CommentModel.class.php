<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;

//定义评论模型类，并继承基础模型类
class CommentModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "comment";
}