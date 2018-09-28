<?php
namespace Admin\Model;
use Think\Model;
class ExamCollegeModel extends Model {

	/**
	 * init($id) 初始化exam_college表(创建考试)
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-26 14:59 Authors
	 * @var  $id 
	 * @return  boolen
	 */
	public function init($id) {
		if (empty($id)) {
			return false;
		}
		$collegeList = D('StudentList')->getCollegeList();

		foreach ($collegeList as $key => $value) {
			$value['examid'] = $id;
			$this->add($value);
		}

		$notStu = array('examid'=>$id,'academy' => '非新生');
		// $this->add($value);
		$this->add($notStu);

		return true;
	}

	/**
	 * getInfo($id) 获取$id号考试的学院信息(是否允许)
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-26 15:09 Authors
	 * @var  $id 
	 * @return  array('academy','state')
	 */
	public function getInfo($id) {
		if (empty($id)) {
			return false;
		}
		$sql = "SELECT * FROM  ee_exam_college WHERE examid = '$id' order by state desc";
		
		$Model = new \Think\Model();
		$res = $Model->query($sql);

		if (empty($res)) {
			return false;
		}
		return $res;
	}

	/**
	 * getExamList($college) 获取$college的考试列表
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-29 15:55 Authors
	 * @var  $id 
	 * @return  array()
	 */
	public function getExamList($college) {
		if (empty($college)) {
			$examList = D('ExamSetup')->select();

			return $examList;
		}else {
			$idList = M('ExamCollege')->where(array('academy' => $college ,'state' => 1))->field('examid')->select();
			$res = array();
			foreach ($idList as $key => $value) {
				$res[$key] = M('ExamSetup')->where(array('id'=>$value['examid']))->find();
			}

			if (empty($res)) {
				return false;
			}
			return $res;
		}		


	}

}