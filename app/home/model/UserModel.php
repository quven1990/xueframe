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
 * 用户模型
 */
class UserModel extends Model{
    function __construct()
    {
        parent::__construct('user');
    }
}