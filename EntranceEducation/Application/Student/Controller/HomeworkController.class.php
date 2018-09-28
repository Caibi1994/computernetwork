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
// | Time: 2016-07-16  19:34
// +----------------------------------------------------------------------
namespace Student\Controller;
use Think\Controller;
use Think\Model;
use Think\Upload;   
use Com\WechatAuth;   



/**
 * 课后作业类
 */

class HomeworkController extends Controller{


    public function index(){
        //+++++++++++++++++++处理访问界面的openId
        $openId = session("openId");
        if(!$openId){
            $openId = I('openId');
            session('openId',$openId);
        } 
        

        $HOMEWORK = M('teacher_homework');
        $count    = $HOMEWORK->count();
        $Page       = new \Think\Page($count,$count);
        $show       = $Page->show();
        $homework = $HOMEWORK->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        

        //+++++++++++++++++++把是否提交和访问人数也加到数组里
        foreach ($homework as $key => $value) {
            $homework[$key]['isSubmit']  = $this->isSubmit($openId,$homework[$key]['id']);
            $homework[$key]['submitNum'] = $this->getSubmitNum($homework[$key]['id']);
        }
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('homework',$homework)->display();
    }

    private function isSubmit($openId,$homeworkId){
        $submitInfo = M('student_homework')->where(array('openId' => $openId,'homeworkId' => $homeworkId))->find();
        if(empty($submitInfo))
            return '未提交';
        if($submitInfo['correcter'] == '未批改')
            return '未批改';
        else
            return $submitInfo['mark'];
    }

    //提交人数,最好写在model里
    private function getSubmitNum($homeworkId){
        return M('student_homework')->where(array('homeworkId' => $homeworkId))->count();
    }


    //点击作业后的菜单界面
    public function homeworkMenu(){
        $openId       = session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $homeworkId = I('homeworkId')?I('homeworkId'):$this->error('你访问的界面不存在');
        session('homeworkId',null);
        session('homeworkId',$homeworkId);

        $state  = $this->isSubmit($openId,$homeworkId);
        $number = $this->getSubmitNum($homeworkId);

        $this->assign('state',$state);
        $this->assign('homeworkId',$homeworkId);
        $this->assign('number',$number)->display();
    }

    //上传图片页面
    public function homework(){
        
        $weixin       = new WeichatController();
        $signPackage  = $weixin->getJssdkPackage(); 
        $this->assign('signPackage',$signPackage);

        $openId       = session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $homeworkId   = session('?homeworkId') ? session('homeworkId') : $this->error('请重新获取改页面');
        /*======================判断不可重复提交===================================*/
        $cond = array('homeworkId' => $homeworkId,'openId' => $openId);
        if(M('student_homework')->where($cond)->find())
            $this->error('你已经提交过了，不可重复提交');
        $HOMEWORK     = M('teacher_homework');
        $homework     = $HOMEWORK->find($homeworkId);
        $this->assign('homework',$homework)->display(); 
    }

    public function upload(){

        /*=====================实例化类============================*/
        $weixin       =  new WeichatController();   //示例化Weichat类，获取token
        $saes         =  new \SaeStorage();        //创建SaeStorage对象
        $STU          = D('StudentInfo');
        $HOMEWORK     = M('student_homework');

        /*=====================定义初始变量====================*/
        $openId       = session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $homeworkId   = session('?homeworkId') ? session('homeworkId') : $this->error('请重新获取改页面');
        // $homeworkId   = I('homeworkId');
        $cond         = array('openId' => $openId);
        $stuInfo      = $STU->where($cond)->find();
        $picIdArray   = I('id');
        $ACCESS_TOKEN = $weixin->getAccessToken();
        $domain       = 'public';
        // $dir          = './homework/homework'.session('homeworkId').'/'; 
        $dir          = './homework/homework'.'1'.'/';


        $filenameFix  = mt_rand(10000000,99999999).$homeworkId.'_';//  图片命名前缀：网络1401班李俊君1400150108.jpg;


        /*======================构造数据上传数组===================================*/
        $homeworkInfo = array(
            'openId'  => $openId,
            'name'    => $stuInfo['name'],
            'number'  => $stuInfo['number'],
            'class'   => $stuInfo['class'],
            'homeworkId' => $homeworkId,
            'correcter' => '未批改',
            'time'    => date('Y-m-d H:i:s',time()),
            );

        $picUrl   = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$ACCESS_TOKEN.'&media_id='.$picIdArray[0];
        // p($picUrl);
        $this->ajaxReturn($picUrl);

        /*======================循环写入Storage===================================*/
        foreach ($picIdArray as $key => $value) {
            $picUrl   = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$ACCESS_TOKEN.'&media_id='.$picIdArray[$key];
            
            $filename = $filenameFix.($key+1).'.jpg';//设置保存在domain中的文件名
            $ch       = curl_init($picUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; //curl_exec执行成功则返回执行结果
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; //在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出。
            $output   = curl_exec($ch) ;
            curl_close($ch);
            $url = $saes->write( $domain , $dir.$filename , $output );//将数据写入到Storage domain并返回存储在domain中此文件的url
            $stuInfoArrayKey = 'pic'.($key+1).'Url';
            $homeworkInfo[$stuInfoArrayKey] = $dir.$filename;
        }

        /*======================存入数据库==========================================*/
        $HOMEWORK->add($homeworkInfo);
    }

    public function homeworkmark()
    {
        var_dump(session());
        return $this->display();
    }
}

