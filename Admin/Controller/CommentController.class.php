<?php
//声明命名空间
namespace Admin\Controller;

use Admin\Model\ArticleModel;
use Admin\Model\CategoryModel;
use \Frame\Libs\BaseController;
use \Admin\Model\CommentModel;

//定义分类模型类，并继承基础模型类
class CommentController extends BaseController
{
    //显示评论列表
    public function index()
    {
        //获取分类数据
        $categorys = CategoryModel::getInstance()->fetchAll("id ASC");

        //构建搜索条件：$where = "WHERE cat.id = 1 AND c.content LIKE '%今天%'"
        $where = "2>1";
        if (!empty($_REQUEST['category_id'])) $where .= " AND cat.id=" . $_REQUEST['category_id'];
        if (!empty($_REQUEST['keyword'])) $where .= " AND c.content LIKE '%" . $_REQUEST['keyword'] . "%'";

        //分页参数
        $pagesize = 6; //每页显示条数
        $page = isset($_GET['page']) ? $_GET['page'] : 1; //当前页码
        $startrow = ($page - 1) * $pagesize; //开始行号
        $records = CommentModel::getInstance()->myRowCount($where);
        $params = array('c' => CONTROLLER, 'a' => ACTION); //附加参数
        if (!empty($_REQUEST['category_id'])) $params['category_id'] = $_REQUEST['category_id'];
        if (!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];

        //创建分页类对象
        $pageObj = new \Frame\Vendor\Pager($records, $pagesize, $page, $params);
        $pageStr = $pageObj->showPage();

        //获取连表查询的分页文章数据
        $comments = CommentModel::getInstance()->fetchALLComment($where, $startrow, $pagesize);
        //向视图赋值，并显示视图
        $this->smarty->assign(array(
            'categorys' => $categorys,
            'comments' => $comments,
            'pageStr' => $pageStr,
        ));
        $this->smarty->display("index.html");
    }

    //删除评论
    public function delete(){
        if (isset($_GET["id"])) {
            //获取文章ID
            $id = $_GET["id"];

            //判断记录是否删除成功
            if (CommentModel::getInstance()->delete($id)) {
                $this->jump("评论删除成功！", "?c=Comment");
            } else {
                $this->jump("评论删除失败！", "?c=Comment");
            }
        } else {
            $this->jump("id传输错误", "?c=Article&a=index");
        }
    }
}