<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/4/10
 * Time: 上午11:38
 */
namespace home\controller;

use core\Controller;
/**
 * user控制器
 */
class UserController extends Controller
{
    public function login()
    {
        $this->display("user/login");
        //echo "login page";
    }
    public function register(){
        $this->display("user/register");
    }
}