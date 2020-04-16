<?php
namespace tool;
use core\Redis;

class RedisLock{ 
    public $redis;
    public  $lock_id = [];
    
    public function __construct(){
        $this->redis =  Redis::getInstance();
    }
    /**
     * 加锁
     * @param	scene 加锁场景
     * @param	expire_time 锁的过期时间
     * @param	retyr_times 重试次数
     * @param	usleep 重试之间等待的时间
     */
    public function lock($scene, $expire_time= 5, $retry_times = 3, $usleep = 100000){
        while ( $retry_times > 0 ) {  //如果获取锁失败，会重新尝试获取锁
            $value = session_create_id();
            $lock = $this->redis->set($scene, $value, ['NX', 'EX'=>$expire_time] );
            if ($lock){
                break;
            }
            echo "尝试重新获取锁".PHP_EOL;
            $retry_times--;
            usleep($usleep);
        } 
        $this->lock_id[$scene] = $value;
        return $lock;
    } 
    /**
     * 解锁
     * @param	scene 场景
     */
    public function unLock($scene){
        /*
        $lock_value = $this->lock_id[$scene];
        $mylock = $redis->get($scene);
        if ($mylock == $lock_value){
            $redis->del($scene);
        }*/
        //改为lua脚本实现，保证原子性，减少请求
        $lock_value = $this->lock_id[$scene];
        if ($lock_value){
            
        $script = <<<LUA
            if (redis.call("get", KEYS[1]) == ARGV[1])
            then
                return redis.call("del", KEYS[1])
            else 
                return 0
            end
LUA;
        $this->redis->eval($script, [$scene, $lock_value], 1);
        
        }
    } 
}
  
?>  
