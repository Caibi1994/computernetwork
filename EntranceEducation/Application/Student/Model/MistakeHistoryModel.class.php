<?php
namespace Student\Model;
use Think\Model;
class MistakeHistoryModel extends Model {

	//获取错题信息
	public function getMistakeData($openid = '') { 

		$map = array(
			'openid'    => $openid,
			'result'    => 0,
			'is_rework' => 0,
		);

		$data = M('exercise')->where($map)->find();

		return $data['quesid'];
	}

	//获取错题数量
	public function getNumberOfMistake($openid = ''){
		$map = array(
			'openid'    => $openid,
			'result'    => 0,
			'is_rework' => 0,
		);

		$data = M('exercise')->where($map)->count();

		return $data;

	}

	//获取答对的错题数量
	public function getNumberOfRight($openid = ''){
		$map = array(
			'openid'    => $openid,
			'result'    => 0,
			'is_rework' => 1,
		);

		$data = M('exercise')->where($map)->count();

		return $data;
	}	

	//获取题目信息
	public function getQuestionByid($quesid = 0) {
		
		$param = array();
		if(!$quesid)
			$param['id']      = $quesid;

		$quesArr = M('Questionbank')->where(array('id' => $quesid))->find();

		$quesArr['chapter'] = $this->getQuesChapter($quesArr['chapter']);
		$quesArr['type']    = $this->getQuesType($quesArr['type']);
		
		return $quesArr;
	}
	
	//获取题目种类
	protected function getQuesType($ty = 0) {

		$typeArr = array('单选题', '判断题', '多选题');
		return $typeArr[$ty - 1];

	}

	//获取题目章节
	protected function getQuesChapter($cp_id = 1) {

		$Chapter = M('question_chapter', 'cn_', $this->database_con);
		$chapter = $Chapter->where(array('id' => $cp_id))->getField('chapter');

		return $chapter;
	}

}