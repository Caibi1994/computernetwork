<?php 
namespace Student\Controller;
use Think\Controller;

class ExerciseController extends Controller{
	
	/**
	 * index 自由练习主页面 能显示当前进度，答题了多少道
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-10 9:39Authors
	 * @var  
	 * @return 
	 */
	public function index() {

		$openid         = session('openId');
		$QUES           = D('Questionbank');
        $RANDOM         = D('RandomExercise');
		$quesTypeArr    = $QUES->getQuesAllType($openid);
		$quesChapterArr = $QUES->getQuesAllChapter($openid);
		$randomArr = $RANDOM->getExerciseRecord($openid);
		$icon = array('bodygood', 'notebook', 'shenghuo', 'sate_edu', 'notebook', 'heartword', 'consciousness');


		$this->assign('quesTypeArr', $quesTypeArr);
		$this->assign('quesChapterArr', $quesChapterArr);
		$this->assign('quesNum', $QUES->getQuesNum($openid));
		$this->assign('icon', $icon);
		$this->assign('randomArr',$randomArr);

	
		$this->display('list');
	}

	/**
	 * exercise 用户做题页面
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 14:51Authors
	 * @var  
	 * @return 
	 */

	public function exercise_chap() {
		$openid = session('openId');
		$record = D('exercise')->getExerciseRecord($openid); 

		$chapid = I('chapid'); if(empty($chapid)) {$chapid = 0; }
		$typeid = I('typeid'); if(empty($typeid)) {$typeid = 0; }
		$quesid = I('quesid'); 

		session('chapid', $chapid);
		session('typeid', $typeid);
		// 首次进入，否则点击下一题进入
		
		if (empty($quesid)) {
			$quesid = D('exercise')->getNewestQuesid($openid, $chapid, $typeid);
		}		
		

		if (false == $quesid) {
			$this->display('tip'); die;
		}

		//如果已经做过，直接显示选择答案
		if($recordArr = D('Exercise')->where(array('openid'=>$openid,'quesid'=>$quesid))->find()){
			$done=1;
			$this->assign('done',$done);
			$this->assign('recordArr',$recordArr);
		}
		session('quesid', $quesid);
		$quesItem  = D('Questionbank')->getQuestion($quesid, $chapid,$typeid);
		$quesList  = D('Questionbank')->getQuesList($quesid);
		// p($quesList);//二维数组

		// 判断是否已经做完了最后一道题目
		if ($quesItem) {
			$this->assign('record', $record);
			$this->assign('quesItem', $quesItem);
			$this->assign('quesList', $quesList);

			// 对题目类型判断 不同类型进入不同的页面
			if ($quesItem['type'] == '单选题') {
				$this->display('index');
			} else if ($quesItem['type'] == '判断题') {
				$this->display('judge');
			} else if ($quesItem['type'] == '多选题') {
				$this->display('mutil');
			}
				 
		} else {

			$this->display('tip');
		}

	}
	/**
	 * submit 处理用户提交结果
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 14:58Authors
	 * @var  
	 * @return json. 正确，还是错误
	 */
	public function submit() {
		if (!IS_AJAX) {
			$this->error('你访问的页面不存在');
		}
		$openid       = session('openId');
		$quesid       = I('quesid');
		$option       = I('option');
		$time         = intval(trim(I('time'))); //将毫秒转为秒并取整
		$right_answer = D('Questionbank')->getRightAnswer($quesid);
		$done = D('Exercise')->where(array('openid'=>$openid,'quesid'=>$quesid))->find();
        if(!$done){  //如果不存在
        	$data = array(
                'openid' => $openid,
                'quesid' => $quesid,
                'answer' => $option,
                'result' => $option == $right_answer ? 1 : 0,
                'spend'  => $time,
                'time'   => date('Y-m-d:H:i:s', time())
            );

            D('Exercise')->add($data);//自由练习

            if($option != $right_answer){
            	D('MistakeHistory')->add($data);//错题表
            }

            $this->ajaxReturn($right_answer, 'json');
        }else{ //如果已存在
        	$this->ajaxReturn('done');
        }
	}

	public function test() {
		D('Questionbank')->getQuesList('ohd41t0Bx0TshKrf18RvG9PuH8DI');
	}


	/**
	 * exercise_index 自由练习的索引
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 14:58Authors
	 * @var  
	 * @return json. 正确，还是错误
	 */

	public function exercise_index() {

		// $quesList = D('Questionbank')->field('id')->limit(140)->select();
		// $this->assign('quesList', $quesList)->display();

		$openId = session('openId');  

        if (IS_AJAX) {
            if(session('?start')){
                $start = session('start') + 140;
                session('start',$start );
            } else {
                session('start',0);
                $start = 0;
            }
            $quesList = D('Questionbank')->field('id')->limit($start,140)->select();
            foreach ($quesList as $key => &$value) {
            	$quesList[$key]['css'] = get_exsercise_index_css($value['id']);
            	$value['url'] = get_exercise_url_css($value['id']);
            }
            //var_dump($quesList);
            $this->ajaxReturn($quesList);
			//$this->assign('quesList', $quesList)->display();
        } 
        else 
        {
            session('start',0);
            //$start = 0;
            // dump($openId);
            // dump($quesList);
			$quesList = D('Questionbank')->field('id')->limit($start,140)->select();

            $this->assign('length',COUNT($quesList));
            // $this->assign('me',$me);
            $this->assign('quesList',$quesList);
            $this->display();

        }
	}

	public function test11() {
		echo $_SERVER['HTTP_HOST'];
	}

	
}

 ?>