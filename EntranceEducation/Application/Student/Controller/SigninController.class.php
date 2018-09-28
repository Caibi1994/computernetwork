<?php
// +----------------------------------------------------------------------
// | 计算机网络教学互动平台
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://23.testet.sinaapp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: lijj <hello_lijj@qq.com>
// +----------------------------------------------------------------------
// | Time: 2016-07-20  14:14
// +----------------------------------------------------------------------
namespace Student\Controller;
use Think\Controller;
use Think\Model;

/**
 * 签到
 */

class SigninController extends Controller{
    
    //学生端入口主页面
    public function index(){
        // echo '2333';
        // die();
        session('openId',null);
        $openId = I('openId');
        session('openId',$openId);

        $cond['accuracy']  = array('NEQ','');  //精度accurcy，是v3.0版本特加的变量，依次区别是新的版本，不等于空
        $signinList        = M('teacher_signin')->where($cond)->order('time desc')->select();
        foreach ($signinList as $key => $value) {
            $signinList[$key]['isSignin']  = $this->isSignin($openId,$signinList[$key]['id']);
            $signinList[$key]['signinNum'] = $this->getSigninNum($signinList[$key]['id']);
        }

        $this->assign('signinList',$signinList)->display();
    }

    //判断是否签到
    private function isSignin($openId,$signinId){
        if(M('student_signin')->where(array('openId' => $openId,'signinId' => $signinId))->find())
            return true;
        else 
            return false;
    }

    //签到人数
    private function getSigninNum($signinId){
        return M('student_signin')->where(array('signinId'=>$signinId))->count();
    }

    //签到菜单
    public function signinMenu(){
        $openId       =  session('openId') ? session('openId') : $this->error('请你重新获取该页面');
        $signinId     = I('signinId') ? I('signinId') : $this->error('你访问的界面不存在');
        session('signinId',null);
        session('signinId',$signinId);

        $weixin       = new WeichatController();
        $signPackage  = $weixin->getJssdkPackage(); 

        $this->assign('state',$this->isSignin($openId,$signinId));
        $this->assign('signinNum',$this->getSigninNum($signinId));
        $this->assign('signPackage',$signPackage)->display();

    }

    //在线签到
    public function signinOnline(){
        if(!IS_AJAX)
            $this->error('你访问的界面不存在');

        $openId        =  session('?openId') ? session('openId') : $this->error('请重新获取该页面');
        $signinId      =  session('?signinId') ? session('signinId') : $this->error('请重新获取该页面');
        
        //老师已经关闭了签到
        if(M('teacher_signin')->where(array('id' => $signinId))->getField('state') != '开启')
            $this->ajaxReturn('close');

        //你已经签到过了
        if(M('student_signin')->where(array('openId' => $openId,'signinId' => $signinId))->find())
            $this->ajaxReturn('signined');

        $STU           = D('StudentInfo');
        $signin        = array(
            'openId'   => $openId,
            'name'     => $STU->getName($openId),
            'class'    => $STU->getClass($openId),
            'number'   => $STU->getNumber($openId),
            'signinId' => $signinId,
            'latitude' => I('latitude'),
            'longitude'=> I('longitude'),
            'accuracy' => I('accuracy'),
            'time'     => date('Y-m-d H:i:s',time()),
            );
        if(M('student_signin')->add($signin))
            $this->ajaxReturn('success');
        else
            $this->ajaxReturn('fail');
    }

    //查看签到
    public function signinView(){
        // 序号，姓名，签到时间，签到地点，
        $weixin       = new WeichatController();
        $signPackage  = $weixin->getJssdkPackage(); 
        $this->assign('signPackage',$signPackage);

        $signinId      =  session('?signinId') ? session('signinId') : $this->error('请重新获取该页面');
        $signinList = M('student_signin')->where(array('signinId' => $signinId))->select();

        $signinInfo = M('teacher_signin')->find($signinId);
        $this->assign('signinInfo',$signinInfo);
        $this->assign('signinList',$signinList)->display();
    }
}

