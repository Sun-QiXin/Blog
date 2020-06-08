<?php
//声明命名空间
namespace Admin\Controller;

use Admin\Model\ArticleModel;
use \Frame\Libs\BaseController;
use \Admin\Model\LinksModel;

//定义友情链接控制器类，并继承基础控制器类
class LinksController extends BaseController
{
    //显示列表
    public function index()
    {
        //权限验证
        $this->denyAccess();

        //获取多行数据
        $links = LinksModel::getInstance()->fetchAll();
        //向视图赋值，并显示视图
        $this->smarty->assign("links", $links);
        $this->smarty->display("index.html");
    }

    //显示添加的表单
    public function add()
    {
        $this->smarty->display("add.html");
    }

    //插入记录
    public function insert()
    {
        //获取表单数据
        $data['domain'] = $_POST['domain'];
        $data['url'] = $_POST['url'];
        $data['orderby'] = $_POST['orderby'];
        //判断是否写入到数据表
        if (LinksModel::getInstance()->insert($data)) {
            $this->jump("记录添加成功！", "?c=Links");
        } else {
            $this->jump("记录添加失败！", "?c=Links");
        }
    }

    //删除链接记录
    public function delete()
    {
        if (isset($_GET["id"])) {
            //获取链接ID
            $id = $_GET["id"];
            //判断记录是否删除成功
            if (LinksModel::getInstance()->delete($id)) {
                $this->jump("链接删除成功！", "?c=Links");
            } else {
                $this->jump("链接删除失败！", "?c=Links");
            }
        } else {
            $this->jump("id传输错误", "?c=Links");
        }
    }

    //显示修改页面
    public function edit()
    {
        if (isset($_GET["id"])) {
            //获取链接ID
            $id = $_GET["id"];
            //查询当前id的链接信息
            $linkMsg = LinksModel::getInstance()->fetchOne("id={$id}");
            //向视图赋值，并显示视图
            $this->smarty->assign("linkMsg", $linkMsg);
            $this->smarty->display("edit.html");
        } else {
            $this->jump("id传输错误", "?c=Links");
        }
    }

    //修改页面
    public function update(){
        //获取表单数据
        $data['domain'] = $_POST['domain'];
        $data['url'] = $_POST['url'];
        $data['orderby'] = $_POST['orderby'];
        $id = $_POST['id'];
        //判断是否写入到数据表
        if (LinksModel::getInstance()->update($data,$id)) {
            $this->jump("记录修改成功！", "?c=Links");
        } else {
            $this->jump("记录修改失败！", "?c=Links");
        }
    }
}