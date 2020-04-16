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
use home\model\TestModel;
/**
 * test控制器
 */
class TestController extends Controller
{
    public function index()
    {
        ini_set("display_errors", "On");
        error_reporting(E_ALL | E_STRICT);
        $user_id = rand(1,100000);
        $content_id = rand(1,100000);
        $status = rand(1,9);
        $create_ts = time();
        $update_ts = time();
        
        $test_model = new TestModel();
        $num = rand(10000, 20000);
        for ($i = 0 ; $i<$num; $i++) {
            $data = [
                'user_id'  => $user_id,
                'content_id' => $content_id,
                'status'     => $status,
                'create_ts'  => $create_ts,
                'update_ts'  => $update_ts
            ];
            $res = $test_model->save($data);
        }
        exit;
    }

}
