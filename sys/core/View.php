<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/4/1
 * Time: 下午4:20
 */
namespace core;

use core\Config;    //使用配置类
use core\Parser;    //使用模板解析类
/**
 * 视图类
 */
class View
{
    //模板变量
    public $_vars = [];

    function __construct($vars =[])
    {
        //if (!is_dir(Config::get('cache_path')) || !is_dir(Config::get('compile_path')) || !is_dir(Config::get('view_path'))) {
        if (!is_dir(Config::get('cache_path')) || !is_dir(Config::get('compile_path'))) {
            exit('The directory does not exist');
        }
        $this->_vars = $vars;
        $this->_config = Config::get();
    }
    //展示模板
    public function display($file)
    {
        //模板文件
        //$tpl_file = Config::get('view_path').$file.Config::get('view_suffix');
        $tpl_file = APP_PATH.$file.Config::get('view_suffix');
        if (!file_exists($tpl_file)) {
            exit('Template file does not exist');
        }
        /*
        //编译文件(文件名用 MD5 加密加上原始文件名)
        //$parser_file = Config::get('complie_path').md5("$file").$file.'.php';
        */
        $file_path = explode(DS,$file);
        $file_name = end($file_path);
        array_pop($file_path);
        $file_path = implode(DS,$file_path);
        //编译文件（对应的路由目录 + md5加密的文件名+文件名）  分离parser的path和name是为了方便创建文件
        $parser_file_path = Config::get('compile_path').$file_path;
        $parser_file_name = md5("$file_name").$file_name.'.php';
        $parser_file = $parser_file_path.DS.$parser_file_name;
        /*
        //缓存文件(缓存前缀加原始文件名)
        //$cache_file = Config::get("cache_path").Config::get("cache_prefix").$file.'.html';
        */
        //缓存文件(对应的路由目录+缓存前缀+原始文件名)
        $cache_file_path = Config::get("cache_path").$file_path;
        $cache_file_name =Config::get("cache_prefix").$file_name.'.html';
        $cache_file = $cache_file_path.DS.$cache_file_name;
        //是否开启了自动缓存
        if (Config::get('auto_cache')) {
            if (file_exists($cache_file) && file_exists($parser_file)) {
                if (filemtime($cache_file) >= filemtime($parser_file) && filemtime($parser_file) >= filemtime($tpl_file)) {
                    return include $cache_file;
                }
            }
        }
        //是否需要重新编译模板
        if (!file_exists($parser_file) || filemtime($parser_file) < filemtime($tpl_file)) {
            $parser = new Parser($tpl_file);
            $parser->compile($parser_file_path,$parser_file_name);
        }
        include $parser_file;    //引入编译文件
        //若开启了自动缓存则缓存模板
        if (Config::get('auto_cache')) {
            if (!file_exists($cache_file_path)) {
                @mkdir($cache_file_path,0777,TRUE);
            }
            $content = ob_get_contents();
            file_put_contents($cache_file,$content);
            ob_end_clean();
            echo $content;
        }
    }
}