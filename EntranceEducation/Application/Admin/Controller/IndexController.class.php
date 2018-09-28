<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){

    	$member = D('Adminer');
        $arr = $member->where("username = '%s'", $_SESSION['username'])->find();
        if ($_SESSION['username'] == "" || $arr == null) {
            $this->redirect('Login/index');
        } else {
        	$this->display();
        }
    }
}