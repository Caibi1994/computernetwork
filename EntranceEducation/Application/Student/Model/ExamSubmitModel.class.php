<?php 
namespace Student\Model;
use Think\Model;

/**
 * 学生考试提交 模型
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-10-2 17:11Authors
 *
 */

class ExamSubmitModel extends Model {

	/**
	 * is_submit() 判断考试是否提交
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-2 17:11Authors
	 * @param $openid, $examid
	 * @return true： 提交 false：未提交
	 */
	public function isSubmit($openid, $examid) {

		$is_submit = $this->where(array('openid'=>$openid, 'examid'=>$examid))->find();

		if($is_submit)
			return true;
		else
			return false;
	}

}

 ?>