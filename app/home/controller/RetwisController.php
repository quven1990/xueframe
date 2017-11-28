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
use core\Cookie;
use home\model\Retwis\UserModel;
use home\model\Retwis\PostModel;
/**
 * index控制器
 */
class RetwisController extends Controller
{
    private $_user_id;  //用户id
    private $_username;  //用户名
    private $_login_status = false;//用户登陆状态

    public function __construct(){
	    $cookie = new Cookie('user');
        $this->_user_id = $cookie->get('user_id');
        $this->_username = $cookie->get('username');
        if($this->_user_id && $this->_username){
            $this->_login_status = true;
        }
       // var_dump($this->_login_status);

        $this->assign('user_id',$this->_user_id);
        $this->assign('username',$this->_username);
        $this->assign('login_status',$this->_login_status);
    }
    /**
     * index 主页
     * @access public
     * @return void
     */
	public function index(){
		if($this->_login_status){
			Jump::redirect("/retwis/home");
		}
		$this->display();
    }
	/**
     * home 发帖页面
     * @access public
     * @return void
     */
	public function home(){
        if(!$this->_login_status){
            Jump::error("请先登陆",3,"/retwis/index");
        }
		$user_model = new UserModel();
		$result = $user_model->followCount($this->_user_id);  //分析关注数
		//帖子展示
		$post_model = new PostModel();
		$content_list = $post_model->contentList($this->_user_id);
		
		$this->assign('follow_count',$result['follow_count']);
		$this->assign('follower_count',$result['follower_count']);
        $this->assign('content_list',$content_list);
		$this->display();
    }
	/**
     * timeline 时间线列表页
     * @access public
     * @return void
     */
	public function timeline(){
		if(!$this->_login_status){
            Jump::error("请先登陆",3,"/retwis/index");
        }
        $user_model = new UserModel();
        $user_list = $user_model->getNewers();

        $this->assign("user_list",$user_list);
		$this->display();
    }
	/**
     * profile 个人中心
     * @access public
     * @return void
     */
	public function profile($user_id){
		if(!$this->_login_status){
            Jump::error("请先登陆",3,"/retwis/index");
        }
        if(!$user_id){
            Jump::error('参数错误',2);
        }
        $user_model = new UserModel();
        $userinfo = $user_model->getUserInfo($user_id);
		if(!$userinfo){
			Jump::error('没有找到对应的用户信息',2);
		}
		
		$follow_status = $user_model->followStatus($user_id,$this->_user_id);
        $this->assign('user_id',$userinfo['user_id']);
        $this->assign("username",$userinfo['username']);
        $this->assign("userinfo",$userinfo);
        $this->assign("follow_status",$follow_status);
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
		Jump::success("登录成功",5,"/retwis/home");
		
	}
    /**
     * loginOut 退出登陆
     * @access public
     * @return void
     */
    public function loginOut(){
        $user_model = new UserModel();
		$user_model->loginOut();
		
        Jump::success("退出成功",5,"/retwis/index");
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

    /**
     * publish 发表帖子
     * @access public
     * @return bool
     */
    public function publish(){
        if(!$this->_login_status){
            Jump::error("请先登陆",3,"/retwis/index");
        }
        $content = Input::post('content');
        if(empty($content)){
            Jump::error("请输入帖子内容",3);
        }
		$post_model = new PostModel();
		$res = $post_model->publish($this->_user_id,$this->_username,$content);
		if($res){
			Jump::success("发布成功",3);
		}else{
			Jump::error("发布失败",3);
		}
	}
	/**
     * follow 关注
     * @access public
     * @return bool
     */
	public function follow($user_id){
		if(!$this->_login_status){
            Jump::error("请先登陆",3,"/retwis/index");
        }
		$user_model = new UserModel();
        $userinfo = $user_model->getUserInfo($user_id);
		if(!$userinfo){
			Jump::error('没有找到对应的用户信息',2);
		}
		if($user_id == $this->_user_id){
			Jump::error('不能关注自己',2);
		}
		
		$res = $user_model->follow($user_id,$this->_user_id);
		if($res){
			Jump::success("关注成功",2);
		}else{
			Jump::error('关注失败',2);
		}
	}
	/**
     * unFollow 关注
     * @access public
     * @return bool
     */
	public function unFollow($user_id){
		if(!$this->_login_status){
            Jump::error("请先登陆",3,"/retwis/index");
        }
		$user_model = new UserModel();
        $userinfo = $user_model->getUserInfo($user_id);
		if(!$userinfo){
			Jump::error('没有找到对应的用户信息',2);
		}
		if($user_id == $this->_user_id){
			Jump::error('不能取消关注自己',2);
		}
		
		$res = $user_model->unFollow($user_id,$this->_user_id);
		if($res){
			Jump::success("取消关注成功",2);
		}else{
			Jump::error('取消关注失败',2);
		}
	} 	
	

}
