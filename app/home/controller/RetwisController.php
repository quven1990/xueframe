<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/4/1
 * Time: 下午4:26
 */
namespace home\controller;

use core\Controller;
use core\traits\Jump;
/**
 * index控制器
 */
class RetwisController extends Controller
{
	/**
     * index 主页
     * @access public
     * @return void
     */
	public function index(){
		$this->display();
    }
	/**
     * home 发帖页面
     * @access public
     * @return void
     */
	public function home(){	
		$this->display();
    }
	/**
     * timeline 时间线列表页
     * @access public
     * @return void
     */
	public function timeline(){	
		$this->display();
    }
	/**
     * profile 个人中心
     * @access public
     * @return void
     */
	public function profile(){	
		$this->display();
    }
	
	/**
     * login 登录
     * @access public
     * @return void
     */
	public function login(){
		Jump::success("123","/home/retwis/index");
		
		exit;
	}
	/**
     * register 注册
     * @access public
     * @return void
     */
	public function register(){
		echo "注册成功";
		exit;
		
	}

}
