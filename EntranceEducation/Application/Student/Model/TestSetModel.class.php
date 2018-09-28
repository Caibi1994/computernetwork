<?php 
namespace Student\Model;
use Think\Model;


class TestSetModel extends Model {

	public function isEnd($testid){
		$state = $this->where(array('id' => $testid))->getField('state');
		if($state=='开启'){
			return true;
		}else{
			return false;
		}
		// return $state;
	}

	public function getTestInfo($testid){
		$testInfo = $this->find($testid);
		$testInfo['queNum'] = M('test_questionbank')->where(array('testid'=>$testid))->count('quesid');
		//$testInfo['count'] = M('test_questionbank')->group('quesid')->having('count(quesid)')->select(); 

		return $testInfo;
	}


	/**
	 * 获取用户参与考试的基本条件
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-3 14:15Authors
	 * @param $openid, $testid
	 * @return 
	 */
	public function beforeInitTest ($openid, $testid){

        $testInfo = $this->getTestInfo($testid);
        $now      = time();
        
        $info           = array(
        	//'is_newer'  => 0,	
	        'is_on'     => 1,
	        'is_end'    => 0,
	        'is_submit' => 0,
	        // 'time_end'  => 0,
	        // 'is_college'=> 0,
        );

        //测试是否开启 
        if ($this->isEnd($testid)) {
        	$info['is_on'] = 1;
        } 

        //测试是否截止
        if ($now > strtotime($testInfo['deadtime'])) {
        	$info['is_end'] = 1;
        }

        //是否提交
        if (D('TestSubmit')->isSubmit($openid, $testid)) {
        	$info['is_submit'] = 1;
        }
		// return $testInfo;
        return $info;

	} 

	/**
	 * getEndTime 获取考试截止时间
	 * @author 蔡佳琪
	 * @copyright  2017-12-20 12:15Authors
	 * @param openid, testid, 
	 * @return 索引模型
	 */
	public function getEndTime($openid, $testid) {

	 	$deadtime = $this->where(array('id'=>$testid))->getField('deadtime');

        // $start_time = D('TestSelect')->where(array('openid'=>$openid,'testid'=>$testid))->min('time');
        // if (empty($start_time)) {
        // 	$start_time = D('TestSet')->where(array('id'=>$testid))->getField('start_time');
        // 	$end_time = $start_time + intval($set_time) * 200;
        // } else {
        // 	$end_time   = intval(strtotime($start_time)) + intval($set_time) * 200;
        // }
        //$start_time = D('TestSet')->where(array('id'=>$testid))->getField('start_time');
        //$end_time = $start_time + intval($set_time) * 200;

        return $deadtime;
	}
	
	
}

 ?>