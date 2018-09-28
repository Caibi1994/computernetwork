<?php
namespace Admin\Model;
use Think\Model;
class ExerciseModel extends Model {

	/**
	 * getAnswerNum 获取提交人数
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-29 14:40 Authors
	 * @var  
	 * @return  String
	 */
	public function getAnswerNum($id) {
		if (empty($id)) {
			return 0;
		}
		$sql = " SELECT COUNT(*)  FROM ee_exercise WHERE quesid = $id";
		
		$Model = new \Think\Model();
		$res = $Model->query($sql);

		return $res['0']['COUNT(*)'];
	}
	/**
	 * getAccuracy 获取正确率
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-29 14:53 Authors
	 * @var  
	 * @return  String
	 */
	public function getAccuracy($id) {
		if (empty($id)) {
			return 0;
		}
		$sql = "SELECT ROUND( SUM( result ) / COUNT( * ) , 2 ) FROM  ee_exercise WHERE  quesid = $id ";

		$Model = new \Think\Model();
		$res = $Model->query($sql);
		return $res['0']["ROUND( SUM( result ) / COUNT( * ) , 2 )"];
	}
	/**
	 * getResult 获取自由练习答题情况
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-29 16:20 Authors
	 * @var  
	 * @return  String 正确数|提交数
	 */
	public function getResult($openid) {
		if (empty($openid)) {
			return 0;
		}
		$sql = "SELECT COUNT(result),SUM(result) FROM  ee_exercise WHERE  openid = '$openid' ";
		$Model = new \Think\Model();
		$res = $Model->query($sql);
		if ($res['0']["SUM(result)"] == NULL) {
			$res['0']["SUM(result)"] = '0';
		}
		return $res['0']["SUM(result)"].'|'.$res['0']['COUNT(result)'];
	}



}