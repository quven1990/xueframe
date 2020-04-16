<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/5/24
 * Time: 上午10:11
 */
namespace home\model;
use core\Model;
/*
 * 测试模型
 */
class TestModel extends Model{
    function __construct()
    {
        parent::__construct('test');
    }
}
