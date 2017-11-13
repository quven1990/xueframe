<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/4/10
 * Time: 上午11:38
 */
namespace home\controller;

use core\Controller;
use core\Redis;
use core\Cookie;
use home\model\UserModel;
/**
 * user控制器
 */
class UserController extends Controller
{
    public function login()
    {
        $obj = new Cookie('dingdang', 10);
        
         $data = array(  
                         'name' => 'fdipzone',  
                                     'gender' => 'male'  
                                             );  
                $obj->set('me', $data, 5);  

    }
    public function register(){

        //        $model = new UserModel();
//        $data = [
//            'name' => 'xuehao',
//            'password' => '123',
//            'email' => '794872291@qq.com'
//        ];
//        $res = $model->getFields();
//        print_r($res);
//        die();
//        $this->display("user/login")
        //echo "login page";
        $this->display("user/register");
    }
}
