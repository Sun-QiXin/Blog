<?php
//声明命名空间
namespace Frame\Vendor;

//包含原始的Smarty类文件
require_once(ROOT_PATH . "Frame" . DS . "Vendor" . DS . "Smarty" . DS . "libs" . DS . "Smarty.class.php");

//定义自己的Smarty类，并继承原始的Smarty类
final class Smarty extends \Smarty
{
	//升级和扩展的代码
}