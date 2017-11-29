<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/11/17
 * Time: 下午15:28
 */
namespace home\model\Retwis;
use core\Redis;
use core\Cookie;
/*
 * 用户模型
 */
class UserModel {
	private $_username_key;   //username
	private $_userpassword_key;  //password
	private $_user_id_from_username_key;  //通过username查找user_id
	private $_follow;  //我关注的人
	private $_follower;  //关注我的人
	private $_global_user_incr_key;  //user表自增id
	private $_user_login_status;  //用户登录状态
	private $_redis;  //redis对象
	
	/**
     * __construct 初始化
     * @access public
     * @return void
     */
	public function __construct(){
		$this->_username_key = "user:user_id:%d:username";  //通过userid查询username
		$this->_userpassword_key = "user:user_id:%d:password"; //通过userid查询password
		$this->_global_user_incr_key = "user:incr:key"; //user 表自增id
		$this->_user_id_from_username_key = "user:username:%s:user_id"; //通过username查询userid
        $this->_user_register_len = "user_register_len"; //记录最新注册的50个用户的user_id
		$this->_user_login_status = "user:login:%d"; //用户登录状态
		
		$this->_follow = "follow:%d";
		$this->_follower = "follower:%d";
		$this->_redis = Redis::getInstance();
	}
	/**
     * register 注册
     * @access public
     * @return void
     */
	public function register($username, $password){
		$result = ['status'=>'success','msg'=>''];
		
		$exists = $this->check_user_exists($username);
		
		if($exists){
			$result['status'] = 'error';
			$result['msg'] = '该用户已存在';
			return $result;
		}
		
		$primary_key = $this->getUserPrimaryKey();
		$user_name_redis_key = sprintf($this->_username_key,$primary_key);
		$user_password_redis_key = sprintf($this->_userpassword_key,$primary_key);
		$this->_redis->set($user_name_redis_key,$username);
		$this->_redis->set($user_password_redis_key,$password);
		//加入冗余数据,用于用username查找user_id
		$user_id_from_user_name_redis_key = sprintf($this->_user_id_from_username_key,$username);  //用于判断username是否存在
		$this->_redis->set($user_id_from_user_name_redis_key,$primary_key);
        //记录最新注册的50个用户
        $this->_redis->lPush($this->_user_register_len,$primary_key);
        $this->_redis->lTrim($this->_user_register_len,0,49);

		return $result;
	}
	/**
     * login 登录
     * @access public
     * @return void
     */
	public function login($username, $password){
		$result = ['status'=>'success','msg'=>'','data'=>''];
		$user_id = $this->check_user_exists($username);
		if(!$user_id){
			$result['status'] = 'error';
			$result['msg'] = '该用户不存在';
			return $result;
		}
		//检测用户是否登录
		/*
		$login_status_key = sprintf($this->_user_login_status,$user_id);
		$login_status = $this->_redis->get($login_status_key);
		if($login_status){
			$result['status'] = 'error';
			$result['msg'] = '该用户已在别处登录,有问题请联系管理员';
			return $result;
		}*/
		
		$user_password_redis_key = sprintf($this->_userpassword_key,$user_id);
		$password_from_redis = $this->_redis->get($user_password_redis_key);
		if($password != $password_from_redis){
			$result['status'] = 'error';
			$result['msg'] = '用户密码错误';
			return $result;
		}
		//写入cookie操作
        $cookie = new Cookie("user");
        $cookie->set("user_id",$user_id);
        $cookie->set("username",$username);
		//记录用户登录状态
		//$this->_redis->set($login_status_key,$user_id);
        $result['data'] = [
            'user_id'=>$user_id,
            'username' => $username
        ];
        return $result;
	}
	/**
     * loginOut 退出
     * @access public
     * @return void
     */
	public function loginOut(){
		$cookie = new Cookie('user');
		$user_id = $cookie->get('user_id');
        $cookie->clear('username');
        $cookie->clear('user_id');
		//$login_status_key = sprintf($this->_user_login_status,$user_id);
		//$this->_redis->del($login_status_key);
	}
	/**
     * check_user_exists 检测用户是否存在
     * @access public
     * @return void
     */
	public function check_user_exists($username){
		$user_id_from_user_name_redis_key = sprintf($this->_user_id_from_username_key,$username); //用于判断username是否存在
		$user_id = $this->_redis->get($user_id_from_user_name_redis_key);
		return $user_id;
	}
    /**
     * getNewers 获取最新的注册用户列表
     * @access public
     * @return void
     */
    public function getNewers(){
		$user_list = [];
		$sort = [
			'get'=>['#',str_replace("%d","*",$this->_username_key)]
		];
        $list = $this->_redis->sort($this->_user_register_len,$sort);
		foreach($list as $key=>$val){
			if($key % 2 == 0){
				$k = $val;
			}else{
				$user_list[$k] = $val;
			}	
		}
		return $user_list;
    }
	/**
     * getUserInfo 获取用户详情
     * @access public
     * @return void
     */
	public function getUserInfo($user_id){
		if(!$user_id){
			return false;
		}
		$redis_key = sprintf($this->_username_key,$user_id);
		$username = $this->_redis->get($redis_key);
		$user_info = [
			'user_id' =>$user_id,
			'username' => $username
		];
		return $user_info;
	}
	/**
     * follow 关注
     * @access public
     * @return void
     */
	public function follow($follow_user_id,$follower_user_id){
		if(!$follow_user_id || !$follower_user_id){
			return false;
		}
		$follow_key = sprintf($this->_follow,$follower_user_id);  
		$follower_key = sprintf($this->_follower,$follow_user_id);
		
		$this->_redis->sAdd($follow_key,$follow_user_id);
		$this->_redis->sAdd($follower_key,$follower_user_id);
		
		return true;
	}
	/**
     * unFollow 取消关注
     * @access public
     * @return void
     */
	public function unFollow($user_id,$follower_user_id){
		$follow_key = sprintf($this->_follow,$follower_user_id);
		$follow = $this->_redis->srem($follow_key,$user_id);
		$follower_key = sprintf($this->_follower,$user_id);
		$follower = $this->_redis->srem($follower_key,$follower_user_id);
		if($follow && $follower){
			return true;
		}else{
			return false;
		}
	}
	/**
     * followStatus 关注状态
     * @access public
     * @return void
     */	
	public function followStatus($user_id,$follower_user_id){
		$follow_key = sprintf($this->_follow,$follower_user_id);
		$follow_list = $this->_redis->sMembers($follow_key);
		
		$follower_key = sprintf($this->_follower,$user_id);
		$follower_list =  $this->_redis->sMembers($follower_key);
		
		if(in_array($user_id,$follow_list) && in_array($follower_user_id,$follower_list)){ //关注表和粉丝表双重判断
			return true;
		}else{
			return false;
		}
	}
	/**
     * followCount 粉丝数和关注数
     * @access public
     * @return void
     */
	public function followCount($user_id){
		$follow_key = sprintf($this->_follow,$user_id);
		$follower_key = sprintf($this->_follower,$user_id);
		
		$follow_count = $this->_redis->scard($follow_key);
		$follower_count = $this->_redis->scard($follower_key);
		return [
			'follow_count' => $follow_count,
			'follower_count' => $follower_count,
		];
	}
	
	/**
     * getUserPrimaryKey 获取user表的主键
     * @access private
     * @return void
     */
	private function getUserPrimaryKey(){
		return $this->_redis->incr($this->_global_user_incr_key);
	}
		
    
}