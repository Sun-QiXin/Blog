<?php
//声明命名空间
namespace Home\Controller;

use \Frame\Libs\BaseController;
use \Home\Model\LinksModel;
use \Home\Model\CommentModel;
use \Home\Model\UserModel;
use \Home\Model\CategoryModel;
use \Home\Model\ArticleModel;

//定义首页控制器类，并继承基础控制器类
class IndexController extends BaseController
{
    //首页显示方法
    public function index()
    {
        //(1)获取友情链接、评论数据
        $links = LinksModel::getInstance()->fetchAll();
        $comments = CommentModel::getInstance()->fetchAll("addate DESC");
        //根据用户id查询出用户名，重新封装成数组对象
        $showComments[][] = [];
        $count = 0;
        foreach ($comments as $comment) {
            $userId = $comment["user_id"];
            $user = UserModel::getInstance()->fetchOne("id={$userId}");
            //发表评论的姓名
            $showComments[$count]["name"] = $user["name"];
            //发表评论的内容
            $showComments[$count]["content"] = $comment["content"];
            //发表评论的时间
            $showComments[$count]["addTime"] = $comment["addate"];
            $count++;
        }

        //(2)获取无限级分类数据
        $categorys = CategoryModel::getInstance()->categoryList(
            CategoryModel::getInstance()->fetchAllWithJoin()
        );

        //(3)获取文章按月份归档数据
        $months = ArticleModel::getInstance()->fetchAllWithCount();

        //(4)构建搜索的条件
        $where = "2>1";
        if (!empty($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id'];
        if (!empty($_REQUEST['keyword'])) $where .= " AND title LIKE '%" . $_REQUEST['keyword'] . "%'";

        //(5)构建分页参数
        $pagesize = 5;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $startrow = ($page - 1) * $pagesize;
        $records = ArticleModel::getInstance()->rowCount($where);
        $params = array('c' => CONTROLLER, 'a' => ACTION);
        if (!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
        if (!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];

        //(6)获取分页字符串数据
        $pageObj = new \Frame\Vendor\Pager($records, $pagesize, $page, $params);
        $pageStr = $pageObj->showPage();

        //(7)获取文章连表查询的分页数据
        $articles = ArticleModel::getInstance()->fetchAllWithJoin($where, $startrow, $pagesize);

        //(8)向视图赋值，并显示视图
        $this->smarty->assign(array(
            'links' => $links,
            'showComments' => $showComments,
            'categorys' => $categorys,
            'months' => $months,
            'articles' => $articles,
            'pageStr' => $pageStr,
        ));
        $this->smarty->display("index.html");
    }

    //显示列表页
    public function showList()
    {
        //(1)获取友情链接、评论数据
        $links = LinksModel::getInstance()->fetchAll();
        $comments = CommentModel::getInstance()->fetchAll("addate DESC");
        //根据用户id查询出用户名，重新封装成数组对象
        $showComments[][] = [];
        $count = 0;
        foreach ($comments as $comment) {
            $userId = $comment["user_id"];
            $user = UserModel::getInstance()->fetchOne("id={$userId}");
            //发表评论的姓名
            $showComments[$count]["name"] = $user["name"];
            //发表评论的内容
            $showComments[$count]["content"] = $comment["content"];
            //发表评论的时间
            $showComments[$count]["addTime"] = $comment["addate"];
            $count++;
        }

        //(2)获取无限级分类数据
        $categorys = CategoryModel::getInstance()->categoryList(
            CategoryModel::getInstance()->fetchAllWithJoin()
        );

        //(3)获取文章按月份归档数据
        $months = ArticleModel::getInstance()->fetchAllWithCount();

        //(4)构建搜索的条件
        $where = "2>1";
        if (!empty($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id'];
        if (!empty($_REQUEST['keyword'])) $where .= " AND title LIKE '%" . $_REQUEST['keyword'] . "%'";

        //(5)构建分页参数
        $pagesize = 30;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $startrow = ($page - 1) * $pagesize;
        $records = ArticleModel::getInstance()->rowCount($where);
        $params = array('c' => CONTROLLER, 'a' => ACTION);
        if (!empty($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
        if (!empty($_REQUEST['keyword'])) $params['keyword'] = $_REQUEST['keyword'];

        //(6)获取分页字符串数据
        $pageObj = new \Frame\Vendor\Pager($records, $pagesize, $page, $params);
        $pageStr = $pageObj->showPage();

        //(7)获取文章连表查询的分页数据
        $articles = ArticleModel::getInstance()->fetchAllWithJoin($where, $startrow, $pagesize);

        //(8)向视图赋值，并显示视图
        $this->smarty->assign(array(
            'links' => $links,
            'showComments' => $showComments,
            'categorys' => $categorys,
            'months' => $months,
            'articles' => $articles,
            'pageStr' => $pageStr,
        ));
        $this->smarty->display("list.html");
    }

    //显示详细内容
    public function content()
    {
        //(1)获取友情链接、评论数据
        $links = LinksModel::getInstance()->fetchAll();
        $comments = CommentModel::getInstance()->fetchAll("addate DESC");
        //根据用户id查询出用户名，重新封装成数组对象
        $showComments[][] = [];
        $count = 0;
        foreach ($comments as $comment) {
            $userId = $comment["user_id"];
            $user = UserModel::getInstance()->fetchOne("id={$userId}");
            //发表评论的姓名
            $showComments[$count]["name"] = $user["name"];
            //发表评论的内容
            $showComments[$count]["content"] = $comment["content"];
            //发表评论的时间
            $showComments[$count]["addTime"] = $comment["addate"];
            $count++;
        }

        //(2)获取无限级分类数据
        $categorys = CategoryModel::getInstance()->categoryList(
            CategoryModel::getInstance()->fetchAllWithJoin()
        );

        //(3)获取文章按月份归档数据
        $months = ArticleModel::getInstance()->fetchAllWithCount();

        //(4)构建搜索的条件
        $where = "2>1";
        if (!empty($_GET['category_id'])) $where .= " AND category_id=" . $_GET['category_id'];
        if (!empty($_REQUEST['keyword'])) $where .= " AND title LIKE '%" . $_REQUEST['keyword'] . "%'";

        $id = $_GET['id'];
        //(5)更新文章阅读数
        ArticleModel::getInstance()->updateRead($id);

        //(6)根据ID获取连表查询的文章数据
        $article = ArticleModel::getInstance()->fetchOneWithJoin($id);

        //(7)根据ID连表查询当前文章的评论
        $currentComments = ArticleModel::getInstance()->fetchAllComment($id);

        //(8)获取当前ID的前一篇和后一篇
        $prevNext[] = ArticleModel::getInstance()->fetchOne("id<$id", "id DESC");
        $prevNext[] = ArticleModel::getInstance()->fetchOne("id>$id", "id ASC");

        //向视图赋值，并显示视图
        $this->smarty->assign(array(
            'links' => $links,
            'showComments' => $showComments,
            'categorys' => $categorys,
            'months' => $months,
            'article' => $article,
            'prevNext' => $prevNext,
            'currentComments' => $currentComments

        ));
        $this->smarty->display("content.html");
    }

    //文章点赞
    public function praise()
    {
        //获取文章ID
        $id = $_GET['id'];

        //判断用户是否登录
        if (!empty($_SESSION['username'])) {
            //每篇文章只能点赞一次
            if (empty($_SESSION['praise'][$id])) {
                //更新点赞数
                ArticleModel::getInstance()->updatePraise($id);
                //保存当前ID的状态
                $_SESSION['praise'][$id] = true;
                $this->jump("id={$id}的文章点赞成功！", "?c=Index&a=content&id=$id");
            } else {
                //同一篇文章只能点赞一次
                $this->jump("同一篇文章只能点赞一次！", "?c=Index&a=content&id=$id");
            }
        } else {
            //如果没有登录，跳转到登录页面
            $this->jump("请先登录，才能点赞！", "admin.php?c=User&a=login");
        }
    }

    //评论文章
    public function saveComment()
    {
        //获取文章ID
        $id = $_POST['article_id'];
        //判断用户是否登录
        if (!empty($_SESSION['username'])) {
            $userID = $_SESSION['uid'];
            //保存评论至数据库
            //获取表单数据
            $data['article_id'] = $id;
            $data['user_id'] = $userID;
            $data['pid'] = $_POST['pid'];
            $data['content'] = $_POST['content'];
            $data['addate'] = time();

            //判断记录是否插入成功
            if (CommentModel::getInstance()->insert($data)) {
                $this->jump("发布评论成功！", "?c=Index&a=content&id={$id}");
            } else {
                $this->jump("发布评论失败！", "?c=Index&a=content&id=={$id}");
            }
        } else {
            //如果没有登录，跳转到登录页面
            $this->jump("请先登录，才能点赞！", "admin.php?c=User&a=login");
        }
    }
}
