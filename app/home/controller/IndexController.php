<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/4/1
 * Time: 下午4:26
 */
namespace home\controller;

use core\Controller;
/**
 * index控制器
 */
class IndexController extends Controller
{
    public function index()
    {
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
       $this->display('index');
    }
}