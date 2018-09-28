<?php 
namespace Student\Model;
use Think\Model;

/**
 * 学生选择题目模型
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-10-2 17:09Authors
 *
 */

class ExamSelectModel extends Model {
	
	/**
	 * 判断学生用户的这次题目是否初始化
	 * 学生选择题目的记录
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-2 17:09Authors
	 * @param $openid, $examid
	 * @return true, false
	 */

	public function isInit($openid, $examid) {
		$is_init = $this->where(array('openid'=>$openid, 'examid'=>$examid))->find();

		if($is_init)
			return true;
		else
			return false;
	}

	/**
	 * initExam 初始化考试题目
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-3 14:15Authors
	 * @param $openid, $examid
	 * @return true 初始化成功 false 初始化失败
	 */

	public function initExam($openid, $examid) {

		$quesUnits = D('ExamQuestionbank')->where(array('examid'=>$examid))->select();
		$result = false;

		foreach ($quesUnits as $key => $value) {
			
			$queUnit = $this->getRandQues($value['chapid'], $value['chap_num']);
			foreach ($queUnit as $k => $v) {
				
				$ques = array(
					'openid' => $openid,
					'examid' => $examid,
					'quesid' => $v,
					'time'   => date('Y-m-d H:i:s'),
 				);

				$result = $this->add($ques);
			}
		}
		
		return $result;
	}

	/**
	 * getRandQues 获取随机题目
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-3 16:08Authors
	 * @param $chapid, $rand_num
	 * @return array(quesid)
	 */

	public function getRandQues($chapid, $rand_num) {

		$quesArray     = D('Questionbank')->where(array('chapter'=>$chapid))->getField('id', true);

		$quesUnitArray = array_rand($quesArray, $rand_num);
		$return        = array();

		foreach ($quesUnitArray as $key => $value) {
			$return[] = $quesArray[$value];

		}
		return $return;
	}

	/**
	 * getExamItems 获取所有题目信息
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-3 15:52Authors
	 * @param $openid, $examid
	 * @return 
	 */

	public function getExamItems($openid, $examid) {

		$examQues = $this->where(array('openid'=>$openid, 'examid'=>$examid))->select();

		return $examQues;
	}

	/**
	 * getExamItem 获取某一道题目的quesid
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-6 10:24Authors
	 * @param $openid, $examid, $quesid
	 * @return 
	 * 1.首次进入页面获取最小的id
	   2.中途进入页面获取未做的题目中最小的id
	   3.还能获取用户指定的id
	 */
	public function getExamItem($openid, $examid, $selectid = 0) {

		$map = array(
			'openid' => $openid, 
			'examid' => $examid, 
		);
		// 用户指定了selectid
		if ($selectid != 0) {

			$map['id'] = $selectid;
			$quesItem = $this->where($map)->find();

			// 如果指定select不存在 
			if (empty($quesItem)) {
				unset($map['id']);
				$ques = $this->where($map)->order('id desc')->limit(1)->select();
				$quesItem = $ques[0];
			}

		} else {
			// 用户首次或者中途进入答题页面
			$map['result'] = -1;
			$ques = $this->where($map)->limit(1)->select();
			
			// 所有题目都做完了
			if (empty($ques)) {
				unset($map['result']);
				$ques = $this->where($map)->order('id desc')->limit(1)->select();
			}
			$quesItem = $ques[0];
			
		}

		$selectid = $quesItem['id'];
		$quesItem['seqid'] = $this->getExamSeqid($openid, $examid, $selectid);

		return $quesItem;		
	}


	/**
	 * getRightAnswer 获取某一道题目的正确答案
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-6 10:24Authors
	 * @param selectid
	 * @return right answer
	 */
	public function getRightAnswer($selectid) {

		$quesid = $this->where(array('id'=>$selectid))->getField('quesid');

		$right_answer = D('Questionbank')->getRightAnswer($quesid);

		return $right_answer;
	}

	/**
	 * getExamItemList 获取所有的题目用于构造索引模型
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-6 15:07Authors
	 * @param openid, examid
	 * @return 索引模型
	 */
	public function getExamItemList($openid, $examid) {

		$examQues = $this->where(array('openid'=>$openid, 'examid'=>$examid))
						 ->select();

		return $examQues;	
	}


	/**
	 * getExamSeqid 获取题目d
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-6 15:58Authors
	 * @param openid, examid, selectid 
	 * @return 索引模型
	 */
	private function getExamSeqid($openid, $examid, $selectid) {

		$map = array(
			'openid' => $openid,
			'examid' => $examid,
			'id'     => array('elt', $selectid),
		);

		$seqid = $this->where($map)->count();

		return $seqid;
	}

	/**
	 * getEndTime 获取考试截止时间
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-8 13:46Authors
	 * @param openid, examid, 
	 * @return 索引模型
	 */
	public function getEndTime($openid, $examid) {

	 	$set_time   = D('ExamSetup')->where(array('id'=>$examid))->getField('set_time');

        $start_time = D('ExamSelect')->where(array('openid'=>$openid,'examid'=>$examid))->min('time');
        if (empty($start_time)) {
        	$start_time = D('ExamSetup')->where(array('id'=>$examid))->getField('start_time');
        	$end_time = $start_time + intval($set_time) * 60;
        } else {
        	$end_time   = intval(strtotime($start_time)) + intval($set_time) * 60;
        }

        return $end_time;
	}




}

 ?>