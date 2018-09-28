<?php
namespace Student\Model;
use Think\Model;

/**
 *  随机练习记录模型
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-9-8 16:34Authors
 *
 */

class RandomExerciseModel extends Model {
	/**
	 * 获取用户的答题记录
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 16:36Authors
	 * @var string openid
	 * @return array(name, count, rig_cot, wrg_cot, sum)姓名,答题总数,正确数，错误数，题库总数
	 */
	public function getExerciseRecord($openid = '') {

		//答题量
		$count        = count($this->where(array('openid'=>$openid))->group('quesid')->select());

		$rig_cot      = count($this->where(array('openid'=>$openid, 'result' => 1))->group('quesid')->select());

		$record       = array(
			'name'    => D('student_info')->getName($openid),
			'count'   => $count, //答题量
			'rig_cot' => $rig_cot,
	        'wrg_cot' => $count - $rig_cot,
			'sum'     => D('questionbank')->count(),
			'next_quesid' => $next_quesid + 1,
		);
		
		return $record;
	}
	
	
	//获取做对题数的排名
	public function getRankList($openid = '') { 

		// $sql = "SELECT openid,COUNT(result) FROM (SELECT DISTINCT openid,quesid,result FROM cn_exercise) P GROUP BY openid having COUNT(result) ORDER BY COUNT(result) desc";
		$sql = "SELECT openid, sum(result) FROM (SELECT DISTINCT openid,quesid,result FROM cn_exercise) P GROUP BY openid ORDER BY SUM(result) desc LIMIT 10";

		// dump($sql);die;	
		$Model = new \Think\Model();
		$res = $Model->query($sql);
		// dump($res);
		if (empty($res)) {
			return false;
		}

		return $res;
	}


}