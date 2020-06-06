<?php
//声明命名空间
namespace Home\Model;
use \Frame\Libs\BaseModel;

//定义友情链接模型类，并继承基础模型类
class UserModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "user";
}