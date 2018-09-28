<?php 
namespace Student\Controller;
use Think\Controller;

class RandomController extends Controller{
	
	/**
	 * random 随机做题页面
	 * @author 菜比
	 * @copyright  2017-9-23 14:51Authors
	 * @var  
	 * @return 
	 */

	public function random() {
		$openid = session('openId');
		$record = D('RandomExercise')->getExerciseRecord($openid);
	
		$quesNum = D('Questionbank')->getQuesNum($openid);
		$quesid = rand(1,$quesNum['num']);
		
		//var_dump($quesNum);
		//echo $quesid;
		//die();
		session('quesid', $quesid);
		$quesItem  = D('Questionbank')->getQuestion($quesid);
		$quesList  = D('Questionbank')->getQuesList($openid);
		// p($quesList);

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
    	$data = array(
            'openid' => $openid,
            'quesid' => $quesid,
            'answer' => $option,
            'result' => $option == $right_answer ? 1 : 0,
            'spend'  => $time,
            'time'   => date('Y-m-d:H:i:s', time())
        );

        D('RandomExercise')->add($data);

        $this->ajaxReturn($right_answer, 'json');

	}


}

 ?>