<?php
//声明命名空间
namespace Admin\Model;
use \Frame\Libs\BaseModel;

//定义分类模型类，并继承基础模型类
class CategoryModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "category";
}