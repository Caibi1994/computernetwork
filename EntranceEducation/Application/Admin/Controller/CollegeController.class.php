<?php
namespace Admin\Controller;

use Think\Controller;
class CollegeController extends CommonController
{
    function _initialize()
    {
        if ($_SESSION['type'] != 1) {
        	$this->error('你没有访问权限');
        }
    }
   
}