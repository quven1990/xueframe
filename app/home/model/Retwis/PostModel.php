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
use home\model\Retwis\UserModel;
/*
 * 帖子模型
 */
class PostModel {
	private $_global_post_incr_key;  //帖子表自增id
	private $_post_userid_key;   //user_id
	private $_post_username_key;  //username
	private $_post_time_key;   //发帖时间
	private $_post_content_key;  //  content
	
	private $_post_new_key;  //最新帖子集合
	private $_post_key;  //保存帖子的hash 
	private $_post_scan_list_key; //可见的帖子id集合
    private $_follower; //粉丝集合

    private $_post_owner_key;
    private $_redis;
    const VISIBLE_LEN = 3;
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
		$this->_post_key = "post:post_id:%d";
		$this->_post_scan_list_key = "scan:user_id:%d";  //可见的帖子id集合
		$this->_follower = "follower:%d";  //粉丝集合
        $this->_post_new_key = "post_new";
        $this->_post_owner_key = "post:owner:%d";
		$this->_redis = Redis::getInstance();
	}
	/**
     * publish 发帖
     * @access public
     * @return void
     */
	public function publish($user_id,$username,$content){
		$post_owner_key = sprintf($this->_post_owner_key,$user_id);
		
		$primary_key = $this->getPostPrimaryKey();
		
		$post_key = sprintf($this->_post_key,$primary_key);
		
		$data = [
			'user_id'  =>$user_id,
			'username' =>$username,
			'content'  =>$content,
			'time'     => time()
		];
		$this->_redis->hMset($post_key,$data);
        
        //将每个人发的帖子插入有序集合，方便粉丝进行拉取
        $post_owner_key = sprintf($this->_post_owner_key,$user_id);
        $this->_redis->zAdd($post_owner_key,$data['time'],$primary_key);
        $count = $this->_redis->zCard($post_owner_key);
		
        if($count > self::VISIBLE_LEN){  //控制每个人帖子数长度
            $this->_redis->zRemRangeByRank($post_owner_key,0,($count-self::VISIBLE_LEN-1));
        }
        /*
		$this->_redis->lpush($this->_post_new_key,$primary_key);
		$this->_redis->lTrim($this->_post_new_key,0,2);
         */
		/*
		$user_id_key = sprintf($this->_post_userid_key,$primary_key);
		$user_name_key = sprintf($this->_post_username_key,$primary_key);
		$time_key = sprintf($this->_post_time_key,$primary_key);
		$content_key = sprintf($this->_post_content_key,$primary_key);
		
		$this->_redis->set($user_id_key,$user_id);
		$this->_redis->set($user_name_key,$username);
		$this->_redis->set($time_key,time());
		$this->_redis->set($content_key,$content);
		
		//插入可见的帖子队列
		$scan_list_key = sprintf($this->_post_scan_list_key,$user_id);
		$this->_redis->lPush($scan_list_key,$primary_key);
		$follower_list_key = sprintf($this->_follower,$user_id);
		$follower_list = $this->_redis->sMembers($follower_list_key);
		foreach($follower_list as $follower_id){
			$scan_list_key = sprintf($this->_post_scan_list_key,$follower_id);
			$this->_redis->lPush($scan_list_key,$primary_key);
		}
		*/
		return true;
	}
	/**
     * getNewers 获取最新的文章列表
     * @access public
     * @return void
     */
	public function getNewers(){
		
		$post_key = str_replace("%d","*",$this->_post_key);
		$sort = [
			'by' => $post_key."->time",
			'get' => [$post_key."->content",$post_key."->username",$post_key."->user_id",$post_key."->time"],
			'sort' => 'desc'
		];
		
		$list = $this->_redis->sort($this->_post_new_key,$sort);
		$result = [];
		for($i = 0; $i<count($list);$i = $i+4){
			$result[$i]['user_id'] = $list[$i+2];
			$result[$i]['username'] = $list[$i+1];
			$result[$i]['time'] = $this->Sec2Time(time() - $list[$i+3]);
			$result[$i]['content'] = $list[$i];
		}
		return $result;
	}
	/**
     * list 获取文章列表
     * @access public
     * @return void
     */
	public function contentList($user_id){
        $user_model = new UserModel();
        $follow_list = $user_model->getFollowList($user_id);  
		$follow_list[] = $user_id;  //自己的帖子和自己关注的人发的帖子同时拉取
        foreach($follow_list as $follow_id){
			$post_owner_key = sprintf($this->_post_owner_key,$follow_id);
				
			//$this->_redis->zAdd($post_owner_key,$data['time'],$primary_key);
			//$count = $this->_redis->zCard($post_owner_key);

        }
		/*
		$scan_list_key = sprintf($this->_post_scan_list_key,$user_id);
		$sort = [
			'by' =>[str_replace("%d","*",$this->_post_time_key)],
			'get' => [
						str_replace("%d","*",$this->_post_content_key),
						str_replace("%d","*",$this->_post_username_key),
						str_replace("%d","*",$this->_post_userid_key),
						str_replace("%d","*",$this->_post_time_key)],
			'sort' => 'desc'			
		];
		$list = $this->_redis->sort($scan_list_key,$sort);
		$result = [];
		for($i = 0; $i<count($list);$i = $i+4){
			$result[$i]['user_id'] = $list[$i+2];
			$result[$i]['username'] = $list[$i+1];
			$result[$i]['time'] = $this->Sec2Time(time() - $list[$i+3]);
			$result[$i]['content'] = $list[$i];
		}
		*/
		return $result;
	} 
	/**
     * Sec2Time 秒数转换
     * @access private
     * @return void
     */
	private function Sec2Time($time){
		if(is_numeric($time)){
		$value = array(
		  "years" => 0, "days" => 0, "hours" => 0,
		  "minutes" => 0, "seconds" => 0,
		);
		if($time >= 31556926){
		  $value["years"] = floor($time/31556926);
		  $time = ($time%31556926);
		}
		if($time >= 86400){
		  $value["days"] = floor($time/86400);
		  $time = ($time%86400);
		}
		if($time >= 3600){
		  $value["hours"] = floor($time/3600);
		  $time = ($time%3600);
		}
		if($time >= 60){
		  $value["minutes"] = floor($time/60);
		  $time = ($time%60);
		}
		$value["seconds"] = floor($time);
		//return (array) $value;
		$t = '';
		
		if($value["years"]){
			$t.=$value["years"] ."年";
		}
		if($value["days"]){
			$t.=$value["days"] ."天";
		}
		if($value["hours"]){
			$t.=$value["hours"] ."小时";
		}
		if($value["minutes"]){
			$t.=$value["minutes"] ."分";
		}
		if($value["seconds"]){
			$t.=$value["seconds"] ."秒";
		}
		
		Return $t;
		
		 }else{
		return (bool) FALSE;
		}
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
