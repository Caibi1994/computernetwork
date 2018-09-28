<?php
namespace Admin\Controller;

use Think\Controller;
class CommonController extends Controller
{
    function _initialize()
    {
        $member = D('Adminer');
        $arr = $member->where("username = '%s'", $_SESSION['username'])->find();
        if ($_SESSION['username'] == "" || $arr == null) {
            $this->error('请登录！', U('Login/index'), 3);
        }
    }
   
}