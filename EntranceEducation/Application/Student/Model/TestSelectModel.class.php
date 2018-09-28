<?php 
namespace Student\Model;
use Think\Model;

/**
 * 学生选择题目模型
 * @author 蔡佳琪
 * @copyright  2017-12-19 21:23
 *
 */

class TestSelectModel extends Model {

	/**
	 * getTestItems 获取用户答案
	 * @author 蔡佳琪
	 * @copyright  2018-01-22 15:27Authors
	 * @param $openid, $testid，$quesid
	 * @return 
	 */
	public function getUserAnswer($openid, $testid,$quesid) {

		$select = $this->where(array('openid'=>$openid, 'testid'=>$testid,'quesid'=>$quesid))->find();

		return $select['answer'];
	}

	/**
	 * getTestItems 获取某次测试的所有题目信息
	 * @author 蔡佳琪
	 * @copyright  2017-12-19 21:29Authors
	 * @param $openid, $testid
	 * @return 
	 */
	public function getTestItems($openid, $testid) {

		$testQues = $this->where(array('openid'=>$openid, 'testid'=>$testid))->select();

		return $testQues;
	}

	/**
	 * getTestItem 获取测试中某一道题目的quesid
	 * @author 蔡佳琪
	 * @copyright  2017-12-20 12:24Authors
	 * @param $openid, $testid, $quesid
	 * @return 
	 * 1.首次进入页面：获取最小的id
	 * 2.中途进入页面：获取未做题目中最小的id
	 * 3.$selectid != 0：用户指定id
	 */
	public function getTestItem($openid, $testid, $selectid = 0) {

		$map = array(
			'openid' => $openid, 
			'testid' => $testid, 
		);
		//用户指定selectid
		if ($selectid != 0) {

			$map['id'] = $selectid;
			$quesItem = $this->where($map)->find();

			// 如果找不到指定题目 
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
		$quesItem['seqid'] = $this->getTestSeqid($openid, $testid, $selectid);

		return $quesItem;		
	}


	/**
	 * getRightAnswer 获取某一道题目的正确答案
	 * @author 蔡佳琪
	 * @copyright  2017-12-20 12:24Authors
	 * @param selectid
	 * @return right answer
	 */
	public function getRightAnswer($selectid) {

		$quesid = $this->where(array('id'=>$selectid))->getField('quesid');

		$right_answer = D('Questionbank')->getRightAnswer($quesid);

		return $right_answer;
	}

	/**
	 * getTestItemList 获取所有的题目用于构造索引模型
	 * @author 蔡佳琪
	 * @copyright  2017-12-20 15:07Authors
	 * @param openid, testid
	 * @return 索引模型
	 */
	public function getTestItemList($openid, $testid) {

		$testQues = $this->where(array('openid'=>$openid, 'testid'=>$testid))->select();

		return $testQues;	
	}


	/**
	 * getTestSeqid 获取当前题目是该测试的第几题
	 * @author 蔡佳琪
	 * @copyright  2017-12-20 15:58Authors
	 * @param openid, testid, selectid 
	 * @return 索引模型
	 */
	private function getTestSeqid($openid, $testid, $selectid) {

		$map = array(
			'openid' => $openid,
			'testid' => $testid,
			'id'     => array('elt', $selectid),  //小于等于
		);

		$seqid = $this->where($map)->count();

		return $seqid;
	}


	
	/**
	 * isPass 获取是否通过
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-12-09 14:30Authors
	 * @param $openid
	 * @return true, false
	 */

	public function isPass($openid) {
		if (empty($openid)) {
			return 'isPass($openid)传参错误';
		}
		$info = D('StudentInfo')->getInfo($openid);
		$testidList = D('Admin/TestSubmit')->formal_testid;
		$testid = $testidList[$info['academy']];
		$score = M('TestSelect')->where(array('testid'=>$testid,'openid'=>$openid,'result'=>1))->count();

		if($score >= 80)
			return true;
		else
			return false;
	}




}

 ?>