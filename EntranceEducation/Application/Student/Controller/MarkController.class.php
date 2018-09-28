<?php

namespace Student\Controller;
use Think\Controller; 


/**
 * 积分类
 */

class MarkController extends Controller{

    public function index(){

        $openId=session('openId');
        session('openId',$openId);

        $this->display('markClass');
    }

    //教师端->积分管理
    public function markMenu(){
        $openId = session("openId");
        if(!$openId){
            $openId = getOpenId();
            session('openId',$openId);
        }
        $this->display(); 
    }

    //教师端->积分管理->查看积分->选择班级
    public function classList(){
        $openId=session('openId');
        session('openId',$openId);
        $classList = D('TeacherClass')->getTeacherClass($openId);
        // p($classList);
        $this->assign('classList',$classList)->display();
    }

    //教师端->积分管理->查看积分->选择班级->积分排名
    public function markClass(){

        $MARK = M('student_mark');

        if($classStr = I('class')){ //教师端进入，传入班级
            $classArray = explode('_', substr($classStr, 0,strlen($classStr)-1));
            //p($classArray);
            
            if($classArray[0] == 'all'){
                //如果选择了所有班级，人不多，就不用分页加载了
                $rankList = $MARK->order('lastMark desc')->group('openid')->select();
            }else{
                //如果没有选择所有班级，就查询某班级
                $rankList = array();
                foreach ($classArray as $value) {
                    $markList = $MARK->where(array('class' => $value))->order('lastMark desc')->group('openid')->select();
                    $rankList = array_merge($rankList,$markList);
                }
                //对二维数组根据lastmark高低重新排序
                $marks = array();
                foreach ($rankList as $value) {
                    $marks[] = $value['lastMark'];
                }
                array_multisort($marks, SORT_DESC, $rankList);
            }
        }else{  //学生端进入，只显示自己班级的排名
            $openId=session('openId');
            $class = D('StudentInfo')->getClass($openId);
            $rankList = $MARK->order('lastMark desc')->where(array('class'=>$class))->group('openid')->select();
        }
        // P($rankList);die;
        $this->assign('rankList',$rankList);
        $this->display();
    } 

    //教师端->积分管理->积分权重    
    public function markWeight(){
        $openId=session('openId');
        $weight = M('student_mark_weight')->where(array('openId'=>$openId))->find();
        $this->assign('weight',$weight)->display();
    }

    //教师端->积分管理->积分权重->设置权重
    public function setMarkWeight(){
        $weight = I();
        //p($weight);die;
        $openId=session('openId');
        $WEIGHT = M('student_mark_weight');
        $weight['openId'] = session('openId');
        $weight['name'] = M('teacher_info')->where(array('openId'=>$openId))->getField('name');
        // $weight['comComment'] = $weight['ranReply'] = $weight['ranComment'] = $weight['comReply'];
        // $weight['doran'] = I('doran');
        // p($weight);die;
        if($WEIGHT->where(array('openId'=>$openId))->find()){
            $WEIGHT->where(array('openId'=>$openId))->save($weight);
            $this->success('修改成绩权重成功',U('Teacher/index'));
        }else{
            $WEIGHT->add($weight);
            $this->success('设置成绩权重成功',U('Teacher/index'));
            //$this->error('修改成绩权重失败');
        }
    }

    //教师端->积分管理->导出成绩
    public function exportExcel($arr=array(),$title=array(),$filename='计算机网络成绩统计表'){
        $MARK = M('student_mark');
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");  
        header("Content-Disposition:attachment;filename=".$filename.".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
        //数据库对应xls标题的定义
        $title=array('id','openId','name','number','class','weixinMessageNum','comCommentNum','comReplyNum','ranCommentNum','ranReplyNum','doRanNum','doRanRightNum','registerNum','classTestNum','classTestRightNum','signinNum','lastMark');
        if (!empty($title)){
            foreach ($title as $k => $v) {
                $title[$k]=iconv("UTF-8", "GB2312",$v);
            }
            $title= implode("\t", $title);
            echo "$title\n";
        }
        //查询数据库  $arr 是二维数组

        $arr = $MARK->select();
        if(!empty($arr)){
            foreach($arr as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $arr[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
                }
                $arr[$key]=implode("\t", $arr[$key]);
            }
            echo implode("\n",$arr);
        }
    }

    //积分详情
    public function markDetails(){
        $openId   = session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        //$mark     = new MarkController();
        $markInfo = $this->getDetails($openId);
        $markInfo['commu']= $markInfo['comCommentNum']+$markInfo['comReplyNum']+$markInfo['ranCommentNum']+$markInfo['ranReplyNum'];
        $this->assign('markInfo',$markInfo)->display();
    }      
    
    //教师端->积分管理->更新积分
    public function update(){
        $openId=session('openId');//教师id
        // $classList = D('TeacherClass')->getTeacherClass($openId);
        $classList = M('teacher_class')->where(array('openId'=>$openId))->getField('class',true);
        // p($classList);
        $STU = array();
        foreach ($classList as $key => $value) {
            $stuidArr  = M('student_info')->where(array('class'=>$value))->getField('openId', true); // 获取openId数组
            $STU = array_merge($STU,$stuidArr);
        }
        // p($STU);die;
        // $STU  = M('student_info')->where(array('class'=>$class))->getField('openId', true); // 获取openId数组

        $MARK = M('student_mark');
        foreach ($STU as $value) {
            // p($value);
            $markInfo = $this->getDetails($value);
            // p($markInfo);
            $markInfo['openid'] = $value;
            $markInfo['lastMark'] = $this->getMark($value);
            // p($markInfo);
            if($MARK->where(array('openid' => $value))->find()){
                $MARK->where(array('openid' => $value))->save($markInfo);
                echo $value."积分更新成功<br/>";
            }
            else{
                $MARK->add($markInfo);
                echo $value."积分插入成功<br/>";
            }
        }
    }

    //积分详情，每个项目的值
    public function getDetails($openId){

        $MESSREC     = M('weixin_message_record');   
        $EXERCISE    = M('exercise');
        $RAN_RECORD  = M('random_exercise');
        $TESTSUBMIT  = M('test_submit');
        $TESTSELECT  = M('test_select');
        $SIGNIN      = M('student_signin');
        $HOMEWORK    = M('student_homework');
        $user        = new UserController();

        $mark = array();
        $mark = array_merge($mark,$user->getUserInfo($openId));
        $mark['weixinMessageNum']  = $MESSREC->where(array('openId' => $openId))->count();   //微信消息数量
        $exerciseData = D('Questionbank')->getProgress($openId);//用户自由练习答题统计
        $mark['exerciseNum'] = $exerciseData['count'];//自由练习总答题量
        $mark['exerciseRightNum'] = $exerciseData['sumNum'];//自由练习答对题量
        $mark['doRanNum']          = $RAN_RECORD->where(array('openid' => $openId))->count();//随机练习答题数
        $mark['doRanRightNum']     = $RAN_RECORD->where(array('openid' => $openId,'result' => '1'))->count();   //随机练习答对题数
        $mark['registerNum']       = $user->isRegister($openId) ? 1 : 0; //是否注册
        $mark['classTestNum']      = $TESTSUBMIT->where(array('openid' => $openId))->count();//参与测试次数
        $mark['classTestRightNum'] = $TESTSELECT->where(array('openid' => $openId,'result' => '1'))->count();//随堂测试答对题数
        $mark['signinNum']         = $SIGNIN->where(array('openId' => $openId))->count();//签到次数
        $homeworkMark = $HOMEWORK->where(array('openId' => $openId))->sum('mark');
        if(empty($homeworkMark))
            $mark['homeworkMark'] = 0;
        else 
            $mark['homeworkMark'] = $HOMEWORK->where(array('openId' => $openId))->sum('mark');

        return $mark;
        // p($mark);
    }

    //获取某位学生的积分 = 项目的值 * 项目的权重
    public function getMark($openId){
        $markInfo = $this->getDetails($openId);//传入的是学生id
        // p($markInfo);
        //获取学生班级->教师openid->教师设置的积分权重
        $class = D('StudentInfo')->getClass($openId);
        $teacherid = M('teacher_class')->where(array('class'=>$class))->getField('openId');
        $markWeight = M('student_mark_weight')->where(array('openId'=>$teacherid))->find();
        // $markWeight['homework'] = (int) $markWeight['homework'];
        // p($markWeight);
        $mark = $markInfo['weixinMessageNum'] * $markWeight['weixinMessage']
        + $markInfo['exerciseNum'] * $markWeight['exerciseNum']
        + $markInfo['exerciseRightNum'] * $markWeight['exerciseRightNum']
        + $markInfo['doRanNum'] * $markWeight['doRan'] 
        + $markInfo['doRanRightNum'] * $markWeight['doRanRight']
        + $markInfo['registerNum'] * $markWeight['register']
        + $markInfo['classTestNum'] * $markWeight['classTest']
        + $markInfo['classTestRightNum'] * $markWeight['classTestRight'] 
        + $markInfo['signinNum'] * $markWeight['signin']
        + $markInfo['homeworkMark'] * $markWeight['homework'];
        return ($mark);
        // p($mark);
    }


}
/*
    public function markClass(){

        $openId   = session('openId');  
        $MARK = D('StudentMark');
        $STUDENT = M('studentInfo');
        if (IS_AJAX) {
            if(session('?start')){
                $start = session('start') + 20;
                session('start',$start );
            } else {
                session('start',0);
                $start = 0;
            }
            $rankList = $MARK->getRankList($start);
            // P($rankList);
            // foreach($rankList as $key => $value){
            //     $rankList[$key]['info'] = $STUDENT->where(array('openId' => $value['openid']))->find();
            // }
            $this->ajaxReturn($rankList);

        } else {
            session('start',0);
            // dump($openId);
            $rankList = $MARK->getRankList();
            //P($rankList);
            // foreach($rankList as $key=>$value){
            //     $rankList[$key]['info'] = $STUDENT->where(array('openId' => $value['openid']))->find();
            // }
            //P($rankList);
            $this->assign('rankList',$rankList);
            $this->display();
        }

    } 
*/
