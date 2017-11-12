<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/5/25
 * Time: 下午3:43
 */
namespace home\model;
use core\Model;
/*
 * md5处理
 */
class Md5Model{
    private $_uid;
    private $_token;
    private $_url;
    function __construct()
    {
        $this->_uid = 'hhp6CmpRShGZQ';
        $this->_token = md5('hijknay409');
        $this->_url = 'http://www.ttmd5.com/do.php';
    }
    function getPassword($password){
        $res = $this->_url.="?c=Api&m=crack&uid=".$this->_uid."&token=".$this->_token."&cipher=".$password;
        $result = file_get_contents($res);
        return $result;
    }

}