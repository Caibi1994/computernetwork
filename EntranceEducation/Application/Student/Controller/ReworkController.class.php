<?php 
namespace Student\Controller;
use Think\Controller;

class ReworkController extends Controller{

	public function chose(){

		$openId = session('openId');
		$QUESTION= M('questionbank');

		$quesid = D('MistakeHistory')->getMistakeData($openId);
		// dump($quesid);die;
		$num = D('MistakeHistory')->getNumberOfMistake($openId);
		// p($num);
		session('quesid',$quesid);
		$ques = D('MistakeHistory')->getQuestionByid($quesid);
		// dump($ques);
		$name = M('StudentInfo')->where('openId="'.$openId.'"')->getField('name');

		$this->assign('num',$num);
		$this->assign('name',$name);
		$this->assign('ques',$ques);
		$this->assign('openId',$openId);
		// echo $quesidArray[$WrongQuesid]['quesid'];
		// echo $num;
		if ($num == 0) {
			$this->display('tip-none');
			return false;
		}
		if ($ques) {
			if ($ques['type'] == '单选题') {
				$this->display('chose');
			} else if ($ques['type'] == '判断题') {
				$this->display('judge');
			} else if ($ques['type'] == '多选题') {
				$this->display('mutil');
			}
		} else {
			$this->display('tip');
		}

	}

	public function submit() {
		if (!IS_AJAX) {
			$this->error('您访问的页面不存在');
		}
		$openid       = session('openId');
		$quesid       = session('quesid');
		$option       = trim(I('option'));
		$time     = I('time');
		$right_answer = trim(D('Questionbank')->getRightAnswer($quesid));
		$data = array(	
			'openid' => $openid,
			'quesid' => $quesid,
			'answer' => $option,
			'result' => $option == $right_answer ? 1 : 0,
			'spend'  => $time,
			'time'   => date('Y-m-d:H:i:s', time())
		);

		M('MistakeHistory')->add($data);
		
		//若错题回顾中回答正确，则更新exercise表中的is_rework
		if($option == $right_answer){
			$map = array(
				'openid' => $openid,
				'quesid' => $quesid
			);
			$data2['is_rework'] = 1;
			M('exercise')->where($map)->save($data2);
		}
		
		$this->ajaxReturn($right_answer, 'json');
	}



}

 ?>