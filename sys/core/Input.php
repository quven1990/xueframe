<?php
namespace core;
/**
 * =======================================
 * Created by xuehao.
 * User: xuehao
 * Date: 2017/11/17 0210
 * Time: 上午 9:13
 * =======================================
 */
class Input
{
	/**
     * get get方法
     * @access public
     * @return void
     */
	static public function get($key){
		return $_GET[$key];
	}
	/**
     * post post方法
     * @access public
     * @return void
     */
	static public function post($key){
		return $_POST[$key];
	}

}