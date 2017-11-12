<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/5/25
 * Time: 下午3:46
 */
namespace home\controller;

use core\Controller;
use home\model\Md5Model;

class Md5Controller extends Controller{
    public function index(){
        $md5 = new Md5Model();

        $password = $_GET['password'];
        $res = $md5->getPassword($password);
        echo "<pre>";
        print_r(json_decode($res));
        echo "</pre>";
        die();
    }
}