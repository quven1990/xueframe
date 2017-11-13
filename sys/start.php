<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2017/4/1
 * Time: 上午11:31
 */


header("Content-type: text/html; charset=utf-8");  //设置页面编码
date_default_timezone_set("PRC");            //设置时区（中国）


//框架启动文件
define('APP_PATH', ROOT_PATH . 'app' . DS);    //定义应用程序目录路径
define('RUNTIME_PATH', ROOT_PATH . 'runtime' . DS);    //定义框架运行时目录路径
define('CONF_PATH', ROOT_PATH . 'config' . DS);        //定义全局配置目录路径
define('CORE_PATH', ROOT_PATH . 'sys' .DS . 'core' . DS);    //定义框架核心目录路径

//引入自动加载文件
require CORE_PATH.'Loader.php';

//实例化自动加载类
$loader = new core\Loader();
$loader->addNamespace('core',ROOT_PATH . 'sys' .DS . 'core');        //添加命名空间对应base目录
$loader->addNamespace('home',APP_PATH . 'home');
$loader->register();    //注册命名空间

//加载全局配置
\core\Config::set(include CONF_PATH . 'config.php');

$debug = \core\Config::get('debug');  //判断是否开启调试模式
if($debug){
    ini_set('display_errors', 'On'); 
    error_reporting(E_ALL ^ E_NOTICE);   //输出除了注意的所有错误报告
}else{
    ini_set('display_errors', 'Off');       //屏蔽错误输出
    ini_set('log_errors', 'On');                //开启错误日志，将错误报告写入到日志中
    ini_set('error_log', RUNTIME_PATH.'/error_log'); //指定错误日志文件
}

