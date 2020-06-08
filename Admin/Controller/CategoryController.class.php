<?php
//声明命名空间
namespace Admin\Controller;

use \Frame\Libs\BaseController;
use \Admin\Model\CategoryModel;

//定义文章分类控制器类，并继承基础控制器类
class CategoryController extends BaseController
{
    //显示分类列表
    public function index()
    {
        //获取分类数据
        $categorys = CategoryModel::getInstance()->fetchAll("id ASC");
        //向视图赋值，并显示视图
        $this->smarty->assign("categorys", $categorys);
        $this->smarty->display("index.html");
    }

    //显示添加分类的视图文件
    public function add()
    {
        //获取分类数据
        $categorys = CategoryModel::getInstance()->fetchAll("id ASC");
        //向视图赋值，并显示视图
        $this->smarty->assign("categorys", $categorys);
        $this->smarty->display("add.html");
    }

    //插入数据
    public function insert()
    {
        //获取表单提交数据
        $data['classname'] = $_POST['classname'];
        $data['orderby'] = $_POST['orderby'];
        $data['pid'] = 0;
        //判断记录是否写入成功
        if (CategoryModel::getInstance()->insert($data)) {
            $this->jump("分类添加成功！", "?c=Category");
        } else {
            $this->jump("分类添加失败！", "?c=Category");
        }
    }

    //删除记录
    public function delete()
    {
        //权限验证
        $this->denyAccess();
        //获取地址栏传递的id
        $id = $_GET['id'];
        //判断是否删除成功
        if (CategoryModel::getInstance()->delete($id)) {
            $this->jump("id={$id}的记录删除成功", "?c=Category");
        } else {
            $this->jump("id={$id}的记录删除失败", "?c=Category");
        }
    }

    //显示修改视图页面
    public function edit()
    {
        //权限验证
        $this->denyAccess();
        //获取地址栏传递的id
        $id = $_GET['id'];
        //获取当前要修改的分类数据
        $currentCategory = CategoryModel::getInstance()->fetchOne("id={$id}");
        $currentCategory['id'] = $id;
        //向视图赋值，并显示视图
        $this->smarty->assign("currentCategory", $currentCategory);
        $this->smarty->display("edit.html");
    }

    //修改数据
    public function update()
    {
        //获取表单提交数据
        $data['classname'] = $_POST['classname'];
        $data['orderby'] = $_POST['orderby'];
        $data['pid'] = 0;
        $id = $_POST['id'];
        //判断记录是否写入成功
        if (CategoryModel::getInstance()->update($data, $id)) {
            $this->jump("分类修改成功！", "?c=Category");
        } else {
            $this->jump("分类修改失败！", "?c=Category");
        }
    }
}