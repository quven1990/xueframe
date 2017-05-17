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
       $this->assign('name','xuehao');
       $this->display('index');
    }
}