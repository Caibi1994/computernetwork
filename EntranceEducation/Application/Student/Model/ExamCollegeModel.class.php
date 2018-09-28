<?php 
namespace Student\Model;
use Think\Model;

/**
 * 创建考试参与学院 模型
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-10-2 17:11Authors
 *
 */

class ExamCollegeModel extends Model {
	
	/**
	 * 获取参与本次考试的学院数组
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-11-06 19:48Authors
	 * @param $examid
	 * @return Array(), 参与本次考试的学院
	 */

	public function getCollege($examid) {

		$map = array(
			'examid' => $examid,
			'state'  => 1,
		);
		$college = $this->where($map)->select();

		return $college;
	}


	/**
	 * 判断同学是否可以参与本次考试
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-11-06 19:54Authors
	 * @param $openid, $examid
	 * @return ture or false  是或者否
	 */

	public function is_college($openid, $examid) {
		$is_college = false;  // 初始化

		$college_on   = $this->getCollege($examid);   //获取examid参与学院

		$student_info = D('StudentInfo')->getStuInfo($openid);
		$college_stu  = $student_info['academy'];

		foreach ($college_on as $key => $value) {
			
			if($college_stu == $value['academy']) {
				$is_college = true;
				break;
			}
		}
		
		return $is_college;
	}

}

 ?>