<?php
namespace Admin\Model;
use Think\Model;
class QuestionbankModel extends Model {

	/**
	 * getQuestionList 获取题目列表，包括正确率和提交人数
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-29 14:28 Authors
	 * @var  
	 * @return  array()
	 */
	public function getQuestionList() {

		$sql = "SELECT * FROM  cn_exam_college WHERE examid = '$id'";
		
		$Model = new \Think\Model();
		$res = $Model->query($sql);

		if (empty($res)) {
			return false;
		}
		return $res;
	}


}