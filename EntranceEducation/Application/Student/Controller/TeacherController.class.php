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
// | Time: 2016-07-17  18:03
// +----------------------------------------------------------------------
namespace Student\Controller;
use Think\Controller;
use Think\Model;

/**
 * 教师类
 */

class TeacherController extends Controller{
    
    public function index(){
        $openId = session("openId");
        if(!$openId){
            $openId = getOpenId();
            session('openId',$openId);
        } 

        $correcNumber = $this->getHomeCorrNum();
        $this->assign('correcNumber',$correcNumber);
        $this->assign('openId',$openId)->display();

    }
    //教师端个人信息
    public function user_index(){
        $this->display();
    }

    //教师端发布课后作业
    public function homework_index(){
        session('openId',null);
        $openId = getOpenId();
        session('openId',$openId);

        $correcNumber = $this->getHomeCorrNum();
        $this->assign('correcNumber',$correcNumber)->display();
    }

    //获取教师端作业待批改量
    private function getHomeCorrNum($homeworkId = 'null'){
        if($homeworkId === 'null'){ //如果$homeworkId 什么都没传递
            $CorrNum = M('student_homework')->where(array('correcter'=>'未批改'))->count();
            return $CorrNum;
        }else{
            $CorrNum = M('student_homework')->where(array('correcter'=>'未批改','homeworkId'=>$homeworkId))->count();
            return $CorrNum;
        }
    }
    //布置作业
    public function homework_assign(){
        $homeworkName = date('m月d日课后作业',time());
        session('homeworkName',$homeworkName);
        $this->assign('homeworkName',$homeworkName)->display();
    }
    

       
            //输出所有章节，里面包含此章节的所有题目
            // $chapternumber = M('homework_zg')->field('chapter')->group('chapter')->count();
           
            
           
            // for ($i=1; $i < $chapternumber+1; $i++) { 
            //     $chapterproblem = M('homework_zg')->where('chapter="$i"')->select();
            // $this->assign('chapterproblem',$chapterproblem);

    public function homework_assign_zg(){
            $this->display();        

    }

    public function homework_assign_zg2(){
   
            $unit   = intval(I('unit'));//章节
            session('unit',$unit);
            $number = intval(I('number'));//题数
            if($unit === 0)
                $this->error('你选择的章节出错了');
            $unitArray = str_split($unit);
             
            $result    = array();
            foreach ($unitArray as $value) {
               //$value  = 'unit'.$value ;
               $cond   = array('chapter' => $value);
               $result = array_merge($result,M('image_questionbank')->where($cond)->select());
            }
            // var_dump($result);die();
            if($number != ''){   //说明用户自定义选择了数量
                // var_dump(23333);die();
                $numberResult = array(); 
                $rand = array_rand($result,$number);
                foreach ($rand as $key => $value) {
                    $numberResult[] = $result[$value];
                }
                $this->assign('quesItem',$numberResult)->display();
            }else{
                $this->assign('quesItem',$result)->display();
            }
        
    
    }

    public function homework_class_list(){
        $openId =  session('openId');
        // echo $openId;die();
        $quesId = I('quesId');
        session('quesId',$quesId);
        // var_dump($quesId);die();
        $teacherClass = D('TeacherClass')->getTeacherClass($openId);//某位老师带的班级
        // var_dump($teacherClass);die();
        $this->assign('homeworkName',date("m月d日课后作业",time()));
        $this->assign('teacherClass',$teacherClass)->display();

    }
    
    public function homework_insert(){
        $data = I('post.');
        // var_dump(session('quesId'));die();
        $model = M('homework_zg');
        $map['class'] = $data['teacherClass'];
        $map['dead_time'] = $data['deadtime'];
        $map['hpdead_time'] = $data['hpdeadtime'];
        $map['problem_id'] = session('quesId');
        $map['homeworkname'] = $data['homeworkName'];
        $model->add($map);
        $this->ajaxReturn();
        // $problem = explode('_', $quesId);
        // var_dump($problem);die();
        // $map['problem_id'] = 
    }
          
    
            



            // $homeworkName = session('homeworkName');
            // $homeworkzg = M('teacher_homework')->where(array('homeworkName'=>$homeworkName,'type'=>0))->find();
            // $problems = $homeworkzg['content'];
            // $proarr = explode(',', $problems);

   
    // public function homework_assign_kg(){
    //     var_dump(3444);
    //         $chapternumber = M('questionbank')->distinct('chapter')->group('chapter')->select();
    //         $num = count($chapternumber);
    //         $this->assign('num',$num);
    //         $this->display();
        
    // }

    //将上传的作业信息写入数据库
    public function homework_handAssign(){
        if(!IS_AJAX)
            $this->error('你访问的界面不存在');
        $openId   =  session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $HOMEWORK = M('teacher_homework');
        $user     = new UserController();
        $userInfo = $user->getTeacherInfo($openId);
        $name     = $userInfo['name']?$userInfo['name']:'null';

        $homework = array(
            'openId' => $openId, 
            'name' => $name,
            'homeworkName' => trim(I('homeworkName')),
            'content' => I('content'),
            'state' => '开启',
            'deadtime' => I('deadtime'),
            'time' => date('Y-m-d H:i:s',time()),
            );
        
        $HOMEWORK->add($homework);
    }

    //批改作业列表
    public function homework_list_correct(){
        $openId   =  session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $homework = M('teacher_homework')->order('time desc')->select();
        foreach ($homework as $key => $value) {
            $homework[$key]['correcNumber'] = $this->getHomeCorrNum($homework[$key]['id']);
        }
        $this->assign('homework',$homework)->display();
    }

    //批改作业
    public function homework_correct(){
        $STU_HOMEWORK = M('student_homework');
        $weixin       = new WeichatController();
        $signPackage  = $weixin->getJssdkPackage(); 
        $this->assign('signPackage',$signPackage);

        $homeworkId   = I('homeworkId') ? I('homeworkId') : $this->error('你访问的界面不存在');

        $cond         = array('homeworkId' => $homeworkId,'correcter' => '未批改');
        $homeworkList = $STU_HOMEWORK->where($cond)->order('time desc')->select();
        $this->assign('homeworkList',$homeworkList)->display();
    }

    //把教师打的分数添加到数据库
    public function homework_mark(){
        $STU_HOMEWORK = M('student_homework');
        $TEA = M('teacher_info');
        $openId       =  session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $name = $TEA->where(array('openId' => $openId))->getField('name');
        $personWorkId = I('personWorkId');
        $mark = I('mark');

        $correctInfo = array(
            'mark'        => $mark,
            'correcter'   => $name,
            'correctTime' => date('Y-m-d H:i:s',time()));
        $STU_HOMEWORK->where(array('id' => $personWorkId))->save($correctInfo);
    }

    //浏览已批改作业列表
    public function homework_list_view(){
        $openId   =  session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $homework = M('teacher_homework')->order('time desc')->select();

        foreach ($homework as $key => $value) {
            $homework[$key]['ViewNum'] = $this->getHomeViewNum($homework[$key]['id']);
        }
        $this->assign('homework',$homework)->display();
    }

    //获取教师端作业浏览量
    private function getHomeViewNum($homeworkId){
        $cond              = array('homeworkId' => $homeworkId);
        return M('student_homework')->where($cond)->count();
    }

    //批改作业
    public function homework_view(){
        $STU_HOMEWORK = M('student_homework');
        $weixin       = new WeichatController();
        // $signPackage  = $weixin->getJssdkPackage(); 
        // $this->assign('signPackage',$signPackage);

        $homeworkId   = I('homeworkId') ? I('homeworkId') : $this->error('你访问的界面不存在');
        $cond         = array('homeworkId' => $homeworkId);
        // $map['correcter']  = array('NEQ','未批改');  //已批改查询条件
        $homeworkList = $STU_HOMEWORK->where($cond)->select();
        $this->assign('homeworkList',$homeworkList)->display();
    }


    //教师端签到主页面
    public function signin_index(){
        session('openId',null);
        $openId = getOpenId();
        session('openId',$openId);

        $this->display();
    }

    //教师端发布签到页面
    public function signin_assign(){
        $weixin       = new WeichatController();
        $signPackage  = $weixin->getJssdkPackage(); 
        $this->assign('signPackage',$signPackage);
        $this->assign('time',date('m月d日签到',time()))->display();
    }

    // public function test(){
    //     $signin        = array(
    //         'openId'    => 'qyhtesttest',
    //         'name'      => 'qyh',
    //         'signinName'=> '232sdf23sf',
    //         'latitude'  => 'ddd',
    //         'longitude' => 'ddd',
    //         'accuracy'  => 'ddd',
    //         'deadtime'  => date('Y-m-d H:i:s',time()),
    //         'state'     => '开启',
    //         'time'      => date('Y-m-d H:i:s',time())
    //             );
    //         M('teacher_signin')->add($signin);
    //         $this->ajaxReturn('success');
    // }

    //教师端将签到信息存入数据库
    public function signin_add(){
        if(!IS_AJAX)
            $this->error('你访问的界面不存在');
        $openId        =  session('openId') ? session('openId') : $this->error('请重新获取改页面');
        $name          = M('teacher_info')->where(array('openId' => $openId))->getField('name');
        $signin        = array(
            'openId'    => $openId,
            'name'      => $name,
            'signinName'=> trim(I('signinName')),
            'latitude'  => I('latitude'),
            'longitude' => I('longitude'),
            'accuracy'  => I('accuracy'),
            'deadtime'  => I('deadtime'),
            'state'     => '开启',
            'time'      => date('Y-m-d H:i:s',time())
                );
        if(M('teacher_signin')->add($signin))
            $this->ajaxReturn('success');
        else
            $this->ajaxReturn('failure');
    }

    //签到列表
    public function signin_list(){
        $cond['accuracy']  = array('NEQ','');  //精度accurcy，是v3.0版本特加的变量，依次区别是新的版本
        $signinList        = M('teacher_signin')->where($cond)->order('time desc')->select();
        foreach ($signinList as $key => $value) {
            $signinList[$key]['signinNum'] = $this->getSigninNum($signinList[$key]['id']);
        }
        $this->assign('signinList',$signinList)->display();
    }

    private function getSigninNum($signinId){
        return M('student_signin')->where(array('signinId' => $signinId))->count();
    }

    //设置状态关闭，代签
    public function signin_list_set(){
        $signinId = I('signinId') ? I('signinId') : $this->error('你访问的界面不存在');
        session('signinId',null);
        session('signinId',$signinId);
        $state = M('teacher_signin')->where(array('id' => $signinId))->getField('state');
        $signinNum = $this->getSigninNum($signinId);
        $this->assign('signinNum',$signinNum);
        $this->assign('state',$state)->display();
    }

    //已签到名单
    public function signin_view(){
        $signinId      =  session('?signinId') ? session('signinId') : $this->error('请重新获取该页面');
        $signinList    =  M('student_signin')->where(array('signinId' => $signinId))->select();
        $this->assign('signinList',$signinList)->display();
    }

    //未签到列表,未签到名单感觉没有必要做
    public function signin_allograph(){
        $this->display();
    }

    //关闭签到
    public function signin_close(){
        if(!IS_AJAX)
            $this->error('你访问的界面不存在');
        $signinId      =  session('?signinId') ? session('signinId') : $this->error('请重新获取该页面'); 
        if(M('teacher_signin')->where(array('id' => $signinId))->save(array('state' => '关闭')))
            $this->ajaxReturn('success');
        else
            $this->ajaxReturn('failure');
    }


//教师端随堂测试首页面,1.发布测试，2.测试管理
    public function test_index(){
        // session('openId',null);
        $openId = getOpenId();
        session('openId',$openId);
        //p($openId);
        $this->display();
    }

    //发布测试->章节列表
    public function test_unit_list(){
        //$chapArr = M('question_chapter')->select();
        //p($chapArr);
        $this->display();
    }

    //发布测试->章节列表->题目列表
    public function test_ques_list(){
        $unit   = intval(I('unit'));//章节
        $number = intval(I('number'));//题数
        if($unit === 0)
            $this->error('你选择的章节出错了');
        $unitArray = str_split($unit);
         
        $result    = array();
        foreach ($unitArray as $value) {
           //$value  = 'unit'.$value ;
           $cond   = array('chapter' => $value);
           $result = array_merge($result,M('questionbank')->where($cond)->select());
        }
        // var_dump($result);
        // die;
        if($number != 0){   //用户自定义题目数量
            $numberResult = array(); 
            $rand = array_rand($result,$number);
            //var_dump($rand);die;
            foreach ($rand as $key => $value) {
                $numberResult[] = $result[$value];
            }
            //var_dump($numberResult);die;
            $this->assign('quesItem',$numberResult)->display();
        }else{
            $this->assign('quesItem',$result)->display();
        }
    }



    //发布测试->章节列表->题目列表->班级列表
    public function test_class_list(){
        $openId =  session('openId');
        // echo $openId;
        $quesId = I('quesId');
        session('quesId',$quesId);
        $teacherClass = D('TeacherClass')->getTeacherClass($quesId);//某位老师带的班级
        //var_dump($teacherClass);
        $this->assign('teacherClass',$teacherClass)->display();

    }
 
    //发布测试
    public function test_assign(){
        $openId =  session('openId');
        $quesId = session('quesId');//_2_3
        $banji = I('banji');//_班级1_班级2
        //echo $quesId;
        //echo $banji;
        //die;
        if(empty($quesId))
            $this->error('你访问的界面不存在');

        $assignArray['quesId']     = substr($quesId, 1);//从第2个开始截取
        $assignArray['banji']      = substr($banji, 1);
        //echo $assignArray['quesId'];
        $assignArray['testName']   = date('Y年m月d日H:i:s',time());
        $assignArray['quesNumber'] = count(explode('_', substr($quesId, 1)));
        //var_dump($assignArray);
        
        $this->assign('assignArray',$assignArray)->display();
    }

    //添加测试，套了三层循环，紧张
    public function test_add(){
        if(!IS_AJAX)
            $this->error('你访问的界面不存在');
        $openId      = session('?openId') ? session('openId') : $this->error('请重新获取改页面');
        $classArray  = explode('_', I('testBanji'));//以_为分割,打成数组
        $quesIdArray = explode('_', I('testQuesId'));//题目id数组
        // var_dump($quesIdArray);die;
        $user        = new UserController();
        $teaInfo     = $user->getTeacherInfo($openId);
        $QUESBANK    = M('questionbank');
        $TESTSET     = M('test_set');
        $TESTBANK    = M('test_questionbank');
        $TESTSELECT  = M('test_select');

        /*===============添加测试，设置=================*/
        foreach ($classArray as $key => $value) {
            $quiz = array(
                'openid'   => $openId,
                'name'     => $teaInfo['name']?$teaInfo['name']:'佚名',
                'testName' => $value.'的'.trim(I('testName')),
                // 'quizName' => trim(I('testName')),
                'class'    => $value,
                'state'    => '开启',
                'deadtime' => I('deadtime'),
                'time'     => date('Y-m-d H:i:s',time()),
            );
            //var_dump($quiz);die;
            $testid = $TESTSET->add($quiz);
            // var_dump($testid);die;//居然是返回自增id握草，666
            //$quiz['testid'] = $testid;
            //M('test_set')->save($quiz);

            if(!$testid)
                $this->ajaxReturn('failure');

            /*===============插入测试信息========================*/
            foreach ($quesIdArray as $v) {
                $quesInfo      = $QUESBANK->where(array('id' => $v))->find();
                //var_dump($quesInfo);die;
                $quesInfoArray = array(
                    'openid'           => $openId,                
                    'name'             => $teaInfo['name']?$teaInfo['name']:'default',
                    'class'            => $value,
                    'testid'           => $testid,
                    'quesid'           => $v,
                    'rightAnswer'      => $quesInfo['right_answer'],
                    'time' => date('Y-m-d H:i:s') 
                );
                if(!$TESTBANK->add($quesInfoArray))
                    $this->ajaxReturn('failure');

                /*===============在学生答题记录表插入题目========================*/
                $openidArr = M('student_info')->where(array('class'=>$value))->field('openId')->select();//某班级的所有学生openid
                //p($openidArr);
                foreach ($openidArr as $kk => $vv) {
                    //p($vv);
                    $openid = $vv['openId'];
                    $stuInfo     = $user->getUserInfo($openid);
                    $que = array(
                        'openid' => $openid,
                        'name'   => $stuInfo['name']?$stuInfo['name']:'default',
                        'testid' => $testid,
                        'quesid' => $v,
                        'time'   => date('Y-m-d H:i:s'),
                    );
                    //p($que);
                    // die;
                    $init = $TESTSELECT->add($que);

                }
            }

        }
   
        $this->ajaxReturn('success');
    }



    // 教师端 测试管理->随堂测试列表
    public function test_list(){
        $openId = session('openId');
        session('openId',$openId);
        $map['state'] = array('neq','');
        $map['openid'] = $openId;
        // p($map);
        $testList = M('test_set')->where($map)->order('time desc')->select();
        //$TEST = new TestController();
        $TEST = D('TestSubmit');
        $STUINFO = D('StudentInfo');
        foreach ($testList as $key => $value) {
            $testList[$key]['submitNum'] = $TEST->getSubmitNum($testList[$key]['id']);
            $testList[$key]['stuNum'] = $STUINFO->getStuNum($openId);
        }
        // p($testList);
        $this->assign('testList',$testList)->display();
    }


    //教师端 测试管理->随堂测试列表->测试管理（停止测试，提交列表，题目解析，测试统计）
    public function test_list_set(){
        $testid = I('testid') ? I('testid') : $this->error('你访问的界面不存在');
        session('testid',null);
        session('testid',$testid);
        $state     = M('test_set')->where(array('id' => $testid))->getField('state');
        //$TEST      = new TestController();
        $TEST = D('TestSubmit');
        $submitNum = $TEST->getSubmitNum($testid);
        $this->assign('submitNum',$submitNum);
        $this->assign('state',$state)->display();
    }   

    //关闭测试
    public function test_close(){
        if(!IS_AJAX)
            $this->error('你访问的界面不存在');
        $testid      =  session('?testid') ? session('testid') : $this->error('请重新获取该页面'); 
        if(M('test_set')->where(array('id' => $testid))->save(array('state' => '关闭')))
            $this->ajaxReturn('success');
        else
            $this->ajaxReturn('failure');
    }

    // 测试管理->随堂测试列表->测试管理->提交列表
    public function test_view(){
        $testid = I('testid');
        if(empty($testid))
            $testid  =  session('?testid') ? session('testid') : $this->error('请重新获取该页面');
        $SUBMIT =  M('test_submit');
        $testList    =  $SUBMIT->where(array('testid' => $testid))->select();
        // var_dump($testList);
        // $stuTestList =  $this->array_unset($testList,'openid');
        // var_dump($stuTestList);
        // foreach ($stuTestList as $key => $value) {
        //     $stuTestList[$key]['rightNum'] = $SUBMIT->where(array('testid' => $testid,'openid' => $stuTestList[$key]['openid'],'result'=>'1'))->count();
        // }
        // var_dump($stuTestList);
        //$this->assign('stuTestList',$stuTestList)->display();
        $this->assign('stuTestList',$testList)->display();
    }
    
    // 去除二维数组里的重复的值 
    public function array_unset($arr,$key){   //$arr->传入数组   $key->判断的key值
        $res = array();      //建立一个目标数组
        foreach ($arr as $value){         
           if(isset($res[$value[$key]])){ //查看有没有重复项
                 unset($value[$key]);//有：销毁
           }else{
                $res[$value[$key]] = $value;
           }
        }
        return $res;
    }

    // 测试管理->随堂测试列表->测试管理->题目解析
    public function test_analyze(){
        $testid   = session('?testid') ? session('testid') : $this->error('请重新获取改页面');
        $openId   = session('?openId') ? session('openId') : $this->error('请重新获取改页面');

        $quesList = M('test_questionbank')->where(array('testid' => $testid,'openId'=>$openId))->select();
        // var_dump($quesList);
        foreach ($quesList as $key => &$value) {
            $quesItem[$key] = D('Questionbank')->getQuestion($value['quesid']);
        }    
        // var_dump($quesItem);
        $this->assign('quesItem',$quesItem)->display('Test/testAnalyze');
    }
}