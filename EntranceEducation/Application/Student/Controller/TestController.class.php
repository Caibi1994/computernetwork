<?php

namespace Student\Controller;
use Think\Controller;
use Think\Model;

/**
 * 随堂测试类
 */
class TestController extends Controller{
  
    public function index(){
        //echo "默认页面";
        $openId=session('openId');
        session('openId',$openId);
        $this->display();
    }

    //首页->随堂测试列表
    public function testList(){
        //session('openId',null);
        //$openId = getOpenId();
        $openId=session('openId');
        session('openId',$openId);
        $STUINFO = D('StudentInfo');
        $TESTSUBMIT = D('TestSubmit');
        $class = $STUINFO->getClass($openId);//当前学生所在班级
        //echo $class;die;
        $map['class'] = $class;
        $map['state'] = array('neq','');
        $testList = M('test_set')->where($map)->order('time desc')->select();
        // p($testList);die;        
        //$testList = M('test_set_state')->where($map)->order('id desc')->select();
        
        //是否提交、提交人数、总人数、答对题数、总题数
        foreach ($testList as $key => $value) {
            $testList[$key]['isSubmit']  = $TESTSUBMIT->isSubmit($openId,$testList[$key]['id']);
            $testList[$key]['submitNum'] = $TESTSUBMIT->getSubmitNum($testList[$key]['id']);
            $testList[$key]['rightNum'] = $TESTSUBMIT->getRightNum($openId,$testList[$key]['id']);
            $testInfo = D('TestSet')->getTestInfo($testList[$key]['id']);//某次测试信息
            $testList[$key]['queNum'] = $testInfo['queNum'];
            $testList[$key]['stuNum'] = $STUINFO->getStuNum($openId);
        }
        // p($testList);
        $this->assign('testList',$testList)->display();
    }

    //随堂测试列表->随堂测试（在线测试，题目解析，测试统计，测试详情）
    public function testMenu(){
        $testid = I('testid')?I('testid'):$this->error('你访问的页面不存在');
        $openId   = session('?openId') ? session('openId') : $this->error('请重新获取该页面');
        session('testid',null);
        session('testid',$testid);
        $TESTSUBMIT = D('TestSubmit');
        $this->assign('state',$TESTSUBMIT->isSubmit($openId,$testid));//是否提交
        $this->assign('testid',$testid);
        $this->assign('number',$TESTSUBMIT->getSubmitNum($testid))->display();
    }

    // 在线测试
    public function test($selectid = 0){
        $testid   = session('?testid') ? session('testid') : $this->error('请重新获取该页面');
        $openId   = session('?openId') ? session('openId') : $this->error('请重新获取该页面');
        //p($testid);
        $TESTSUBMIT = D('TestSubmit');
        $TESTSET = D('TestSet');
        $TESTSELECT = D('TestSelect');
        if($TESTSUBMIT->isSubmit($openId,$testid))
            $this->error('你已提交！');
        if(!$TESTSET->isEnd($testid))
            $this->error('测试已经结束！');
        //$quesList = M('test_questionbank')->where(array('testid' => $testid))->order('time desc')->select();
        //p($quesList);
        //$this->assign('quesList',$quesList)->display();
     
        $testItem = $TESTSELECT->getTestItem($openId, $testid, $selectid);//select中某一条记录
        //p($testItem);
        //p($testItem['quesid']);
        $quesItem = D('Questionbank')->getQuestion($testItem['quesid']);//某一道题目的信息
        // p($quesItem);die;
        $this->assign('testItem', $testItem);        
        $this->assign('quesItem', $quesItem);

        $quesList = $TESTSELECT->getTestItems($openId, $testid);//某测试所有题目
        //p($quesList);
        foreach ($quesList as $key => &$value) {
            $selectidArr[$key+1] = $value['id'];
        }
        // p($selectidArr);
        // die;
        $this->assign('selectidArr', $selectidArr);
        $this->assign('quesList', $quesList);

        $end_time = $TESTSET->getEndTime($openId, $testid);//测试结束时间

        $this->assign('end_time', $end_time);    

        //根据题型显示
        if ($quesItem['type'] == '单选题') {
            $this->display('radio');
        } else if ($quesItem['type'] == '判断题') {
            $this->display('judge');
        } else if ($quesItem['type'] == '多选题') {
            $this->display('mutil');
        }        
    }

    public function testSubmit(){
        // if(!IS_AJAX)
        //     $this->error('你访问的页面不存在');
        
        $answer   = I('option');
        $selectid = intval(trim(I('selectid')));

        $right_answer = D('TestSelect')->getRightAnswer($selectid);
        $result = $answer == $right_answer ? 1 : 0;
        
        $data = array(
            'answer' => $answer,
            'result' => $result,
            'time'   => date('Y-m-d H:i:s', time()),
            // 'right_answer'=>$right_answer,
        );

        // 获取学生当前考试的环境信息
        $openid = session('openId');
        $testid = session('testid');
        $testInfo = D('TestSet')->beforeInitTest($openid, $testid);
        // p($testInfo);die;
        if ($testInfo['is_on'] == 1 && $testInfo['is_end'] == 0 && $testInfo['is_submit'] == 0) {
            D('TestSelect')->where(array('id'=>$selectid,'openid'=>$openid))->save($data);//修改数据
        } else {
            $this->ajaxReturn($testInfo);
        }
    }

    /**
     * 提交成功
     * @author 蔡佳琪
     * @copyright  2017-12-20 15:32
     **/
    public function tip() {
        $openId = session('openId');
        $testid = session('testid');
        $info = D('StudentInfo')->getStuInfo($openId);
        $score = M('TestSelect')->where(array('openid'=>$openId,'testid'=>$testid,'result'=>1))->count();
        $data = array(
            'openid' => $openId,
            'testid' => $testid,
            'name'   => $info['name'],
            'class'=> $info['class'],
            'score'  => $score,
        );
        $TESTSUBMIT = D('TestSubmit');
        if (!$TESTSUBMIT->isSubmit($openId,$testid)) {
            M('TestSubmit')->add($data);//未提交，则add
        }
        $map1 = array('openid' => $openId,'testid' => $testid);
        $map1['result'] = array('neq',-1);
        //p($map1);die;       
        $ansNum = M('TestSelect')->where($map1)->count();//本次测试答题数
        $map2 = array('openid' => $openId,'testid' => $testid,'result'=>'1');
        $rightNum = M('TestSelect')->where($map2)->count();//本次测试答对题数
        $this->assign('ansNum',$ansNum);
        $this->assign('rightNum',$rightNum);

        $this->display();
    }

    //提交结果
    public function testResult(){
        $testResult = I('result');
        $testid  = session('?testid') ? session('testid') : $this->error('请重新获取该页面');
        $this->assign('testid',$testid);
        $this->assign('testResult',$testResult)->display();
    }

    //题目解析
    public function testAnalyze(){
        $testid   = session('?testid') ? session('testid') : $this->error('请重新获取该页面');
        $openId   = session('?openId') ? session('openId') : $this->error('请重新获取该页面');
        $TESTSUBMIT = D('TestSubmit');
        $TESTSELECT = D('TestSelect');
        if(!$TESTSUBMIT->isSubmit($openId,$testid))
            $this->error('请完成测试再查看解析');
        $quesList = M('test_questionbank')->where(array('testid' => $testid))->select();
        // var_dump($quesList);//某次测试的所有题目和正确答案
        foreach ($quesList as $key => &$value) {
            $quesItem[$key] = D('Questionbank')->getQuestion($value['quesid']);
            $quesItem[$key]['answer'] = $TESTSELECT->getUserAnswer($openId,$testid,$value['quesid']);
        }    
        // var_dump($quesItem);die;
        $this->assign('quesItem',$quesItem)->display();
    }

    //测试统计(教师端和学生端共用)
    public function testDetails(){
        $testid   = session('?testid') ? session('testid') : $this->error('请重新获取该页面');
        $openId   = session('?openId') ? session('openId') : $this->error('请重新获取该页面');

        $TESTBANK    = M('test_questionbank');
        $TESTSELECT  = M('test_select');

        //$TESTRECORD = M('student_classtest_record');
        $QUESTIONBANK = M('questionbank');
        $quesList = $TESTBANK->where(array('testid' => $testid))->select();
        //var_dump($quesList);
        foreach ($quesList as $key => $value) {
            $quesId = $quesList[$key]['quesid'];
            // p($quesId);
            $quesItem = D('Questionbank')->getQuestion($quesId);
            // p($quesItem);
            //$quesList = array_merge($quesList[$key],$quesItem);
            //var_dump($quesList);die;
            $quesList[$key]['type'] = $quesItem['type'];
            $quesList[$key]['contents'] = $quesItem['contents'];
            $quesList[$key]['option_a'] = $quesItem['option_a'];
            $quesList[$key]['option_b'] = $quesItem['option_b'];
            $quesList[$key]['option_c'] = $quesItem['option_c'];
            $quesList[$key]['option_d'] = $quesItem['option_d'];
            $quesList[$key]['optionA'] = $TESTSELECT->where(array('testid' => $testid,'quesid' => $quesId,'answer' => 'A'))->count();
            $quesList[$key]['optionB'] = $TESTSELECT->where(array('testid' => $testid,'quesid' => $quesId,'answer' => 'B'))->count();
            $quesList[$key]['optionC'] = $TESTSELECT->where(array('testid' => $testid,'quesid' => $quesId,'answer' => 'C'))->count();
            $quesList[$key]['optionD'] = $TESTSELECT->where(array('testid' => $testid,'quesid' => $quesId,'answer' => 'D'))->count();
            $quesList[$key]['optionR'] = $TESTSELECT->where(array('testid' => $testid,'quesid' => $quesId,'answer' => '对'))->count();
            $quesList[$key]['optionW'] = $TESTSELECT->where(array('testid' => $testid,'quesid' => $quesId,'answer' => '错'))->count();

        }
        // p($quesList);
        // die;
        $this->assign('quesList',$quesList)->display();
    }

    public function compare(){

        $now = time();
        $testInfo = D('TestSet')->getTestInfo($testid);
        $testTime = strtotime($testInfo['deadtime']);
        echo "当前时间：".$now;
        echo "<br/>考试截止时间：".$testTime;
        if($now > $testTime){
            p('考试已结束');
        }else{
            p('考试未结束');
        }
    }
}