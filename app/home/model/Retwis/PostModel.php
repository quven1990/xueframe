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
 * 帖子模型
 */
class PostModel {
	private $_global_post_incr_key;  //帖子表自增id
	private $_post_userid_key;   //user_id
	private $_post_username_key;  //username
	private $_post_time_key;   //发帖时间
	private $_post_content_key;  //  content
	private $_redis;
	/**
     * __construct 初始化
     * @access public
     * @return void
     */
	public function __construct(){
		$this->_global_post_incr_key = "post:incr:key"; //post 表自增id
		$this->_post_userid_key = "post:post_id:%d:user_id";   //发帖人user_id
		$this->_post_username_key = "post:post_id:%d:username"; //发帖人username
		$this->_post_time_key = "post:post_id:%d:time";   //发帖时间
		$this->_post_content_key = "post:post_id:%d:content";   //帖子内容
		$this->_redis = Redis::getInstance();
	}
	/**
     * publish 发帖
     * @access public
     * @return void
     */
	public function publish($user_id,$username,$content){
		$primary_key = $this->getPostPrimaryKey();
		$user_id_key = sprintf($this->_post_userid_key,$primary_key);
		$user_name_key = sprintf($this->_post_username_key,$primary_key);
		$time_key = sprintf($this->_post_time_key,$primary_key);
		$content_key = sprintf($this->_post_content_key,$primary_key);
		
		$this->_redis->set($user_id_key,$user_id);
		$this->_redis->set($user_name_key,$username);
		$this->_redis->set($time_key,time());
		$this->_redis->set($content_key,$content);
		return true;
	}
	/**
     * getUserPrimaryKey 获取user表的主键
     * @access private
     * @return void
     */
	private function getPostPrimaryKey(){
		return $this->_redis->incr($this->_global_post_incr_key);
	}
		
	
}