<?php 
namespace Student\Model;
use Think\Model;

/**
 * 创建考试章节 模型
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-10-2 17:11Authors
 *
 */

class ExamQuestionbankModel extends Model {

	/**
	 * 创建考试章节 模型
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-2 17:11Authors
	 * @param $examid 考试id
	 * @return count($examid)
	 */
	public function count($examid) {

		$count = $this->where(array('examid'=>$examid))->sum('chap_num');
		return $count;
	}
}

 ?>