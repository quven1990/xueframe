<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/4/1
 * Time: 下午4:26
 */
namespace home\controller;

use core\Controller;
use core\traits;
use core\Redis;
use core\tool\RedisLock;
/**
 * index控制器
 */
class IndexController extends Controller
{
    public $redis;
    public $lock_key;
    public function __construct(){
        $this->redis = Redis::getInstance();
        $this->lock_key = "test";
    }
    //redislock测试方法
    public function redis_lock_test1(){
        $lock_value = create_session_id();
        $expire_time =  10;
        //$res = $this->redis-set($this->lock_key, $lock_value, ['NX', 'EX'=>$expire] );
            var_dump($res);
        exit;
         
        if ($this->redis->get("store") > 0){
            //todo业务逻辑
            sleep(5);
            $this->redis->decr('store');
        }
        echo $this->redis->get("store");
    
    }
    //redislock测试方法
    public function redis_lock_test2(){
        
        if ($this->redis->get("store") > 0){
            //todo业务逻辑
            sleep(5);
            $this->redis->decr("store");
        }
        echo $this->redis->get("store");
    
    }

    public function index()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        //RedisLock::test();
       $data = [
           'a' => 1,
           'b' => 2,
           'c' => 3,
           'd' => 4,
           'e' => 5,
       ];
       $this->assign('name','xuehao');
       $this->assign('check',true);
       $this->assign('data',$data);

       $this->display();
    }
}
