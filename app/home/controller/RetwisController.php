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
use core\Input;
use home\model\Retwis\UserModel;
/**
 * index控制器
 */
class RetwisController extends Controller
{
	//public $controller_name;
	public function __construct(){
		// parent::__construct();  //调用父类构造方法 
		var_dump($this->controller_name);
		var_dump($this->action_name);
		
	}
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
		$username = Input::post("username");
		$password = Input::post("password");
		
		$user_model = new UserModel();
		$res = $user_model->login($username, $password);
		
		if($res['status'] == 'error'){
			Jump::error($res['msg'],5);
		}
		
		Jump::success("登录成功",5);
		
	}
	/**
     * register 注册
     * @access public
     * @return void
     */
	public function register(){
		$user_model = new UserModel();

		$username = Input::post("username");
		$password = Input::post("password");
		$password2 = Input::post("password2");

		if($password != $password2){
			Jump::error("请保持两次密码一致",5);
		}
		//将新纪录写入redis
		$res = $user_model->register($username,$password);
		
		if($res['status'] == 'error'){
			Jump::error($res['msg'],5);
		}
		
		Jump::success("注册成功",5);
		exit;
		
	}
	

}
