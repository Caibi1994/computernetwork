<?php
namespace Admin\Controller;
use Think\Controller;
class ExerciseController extends CommonController {
    
    public function index(){

        $Question = M('Questionbank');
        $list = $Question->page($_GET['p'].',20')->select();
        $this->assign('questionList',$list);

        $count      = $Question->count();
        $this->assign('count', $count);
        $Page       = new \Think\Page($count,20);
        $show       = $Page->show();
        $this->assign('page',$show);
       
        $this->display();
    }


}