<?php
//声明命名空间
namespace Frame;

//定义最终的框架初始类
use Admin\Controller\CategoryController;

final class Frame
{
    //公共的静态的框架初始化方法
    public static function run()
    {
        self::initConfig();        //初始化配置数据
        self::initRoute();        //初始化路由参数
        self::initConst();        //初始化常量定义
        self::initAutoLoad();    //初始化类的自动加载
        self::initDispatch();    //初始化请求分发
    }

    //私有的静态的初始化配置信息
    private static function initConfig()
    {
        //开启SESSION会话
        session_start();
        $GLOBALS['config'] = require_once(APP_PATH . "Conf" . DS . "Config.php");
    }

    //私有的静态的初始化路由参数
    private static function initRoute()
    {
        //获取路由参数
        $p = $GLOBALS['config']['default_platform']; //平台参数
        $c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller']; //控制器名
        $a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action']; //动作名
        define("PLAT", $p);
        define("CONTROLLER", $c);
        define("ACTION", $a);
    }

    //私有的静态的初始化目录常量
    private static function initConst()
    {
        define("VIEW_PATH", APP_PATH . "View" . DS . CONTROLLER . DS); //View目录
    }

    //私有的静态的初始化类的自动加载
    private static function initAutoLoad()
    {
        //类的自动加载
        spl_autoload_register(function ($className) {
            //传递过来类名参数：Home\Controller\StudentController
            //类文件的真实路径：./Home/Controller/StudentController.class.php
            //将传递的类名转成真实类文件路径
            $filename = ROOT_PATH . str_replace("\\", DS, $className) . ".class.php";
            //如果类文件存在，则包含
            if (file_exists($filename)) {
                require_once("{$filename}");
            }
        });
    }

    //私有的静态的初始化请求分发：创建哪个控制器类的对象？调用控制器对象的哪个方法？
    private static function initDispatch()
    {
        //构建控制器类名称：Home\Controller\IndexController
        $controllerClassName = PLAT . "\\" . "Controller" . "\\" . CONTROLLER . "Controller";
        //创建控制器类的对象
        $controllerObj = new $controllerClassName();

        //根据用户的不同动作，调用不同的方法
        $action_name = ACTION;
        $controllerObj->$action_name();
    }
}