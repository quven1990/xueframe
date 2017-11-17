<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/5/25
 * Time: 下午3:33
 */
namespace core\traits;
use core\Config;
use core\View; 

trait Jump{
	/**
     * success 成功提示页面
     * @access public
     * @return void
     */
    public static function success($mess="操作成功", $timeout=1, $location=""){
		Config::set("auto_cache",false);  //关闭模板缓存
		$location = self::makeLocation($location);
		
		$assign_data = [   //assign到模板的内容
			'mark' => true,
			'mess' => $mess,
			'timeout' => $timeout,
			'location' => $location
		];
		$view = new View($assign_data);    //调用视图类
		
		$display_file = static::getTpl();
		$view->display($display_file);    //视图类展示方法
       
		
    }
	/**
     * error 失败提示页面
     * @access public
     * @return void
     */
	public static function error($mess="操作失败", $timeout=3, $location=""){
		Config::set("auto_cache",false);  //关闭模板缓存
		$location = Jump::makeLocation($location);
		
		$assign_data = [
			'mark' => false,
			'mess'  => $mess,
			'timeout' => $timeout,
			'location' => $location
		];
		
		$view = new View($assign_data);    //调用视图类
		
		$display_file = static::getTpl();
		$view->display($display_file);    //视图类展示方法	
		
	}
	/**
     * getTpl  获取模板地址 
     * @access private
     * @return void
     */
	private static function getTpl(){
		$module = Config::get("default_module");	
		$tpl = $module.DS."view".DS."common".DS."success";  //提示模板文件目录
		return $tpl;
	}
	/**
     * makeLocation  对location进行单独处理 
     * @access private
     * @return void
     */
	private static function makeLocation($location){
		if($location==""){
			$location= "window.history.back()";
		}else{
			$path=trim($location, "/");
			if(strstr($path, "/")){
				$url=$path;
			}else{
				$url=$_GET["m"]."/".$path;
			}
			$location=DS.Config::get("default_module").DS.$url;
			$location="window.location='{$location}'";
		}
		return $location;
	}
}