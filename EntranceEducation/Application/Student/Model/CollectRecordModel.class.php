<?php
namespace Student\Model;
use Think\Model;

/**
 * 收藏记录模型
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-9-8 16:34Authors
 *
 */

class CollectRecordModel extends Model {
	/**
	 * 收藏
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 16:36Authors
	 * @var string openid
	 * @return array(openid, quesid, time, )
	 */
	public function collect($openid = '',$quesid = '') {

		$result = $this->where(array('openid'=>$openid,'quesid'=>$quesid))->find();

		if (!$result) {//未收藏则add
			$record       = array(
				'openid'  => $openid,
				'quesid'  => $quesid, 
				'time'    => date('Y-m-d:H:i:s', time()),
			);
			return $this->add($record);
		}else{
			return false;//已收藏
		}		
		
	}
	/**
	 * 取消收藏
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 18:14Authors
	 * @var string openid
	 * @return 删除成功与否
	 */
	public function cancel($openid = '',$quesid = '') {

		$exsit = $this->where(array('openid'=>$openid,'quesid'=>$quesid))->find();
		if ($exsit) {//已收藏则delete
			$result = $this->where(array('openid'=>$openid,'quesid'=>$quesid))->delete();
			return $result;
		}else{
			return false;//未收藏
		}				
	}

	/**
	 * 获取用户的收藏题数
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 16:36Authors
	 * @var string openid
	 * @return count 收藏数
	 */
	public function getCollectNum($openid = '') {

		//收藏量
		$collectNum = count($this->where(array('openid'=>$openid))->select());
		
		return $collectNum;
	}

		/**
	 * getQuesList 获取用户收藏列表
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-114 13:18 Authors
	 * @var openid
	 * @return arrayList( 'quesid', 'result')
	 */
	public function getCollectList($openid) {

		$EXER = D('Exercise');
		$quesList = $this->where(array('openid'=>$openid))->order('quesid')->field('quesid')->select();
		//var_dump($quesList);
		foreach ($quesList as $key => $value) {
			//var_dump($value);
			$res = $EXER->where(array('quesid'=>$value['quesid'], 'openid'=>$openid))->getField('result');
			//var_dump($res);
			// 1 => 'placeholder-right' 
			// 0 => 'placeholder-wrong' 
			// else => 'placeholder' 
			if(!isset($res)) {
				$quesList[$key]['result'] = 'placeholder';	
				//$quesList[$key]['href'] = U('Collect/recordList', array('quesid'=>$value['quesid']));	
			} else if($res == 0) {
				$quesList[$key]['result'] = 'placeholder-wrong';
				$quesList[$key]['href'] = U('Collect/recordList', array('quesid'=>$value['quesid']));	
			} else if($res == 1) {
				$quesList[$key]['result'] = 'placeholder-right';
				$quesList[$key]['href'] = U('Collect/recordList', array('quesid'=>$value['quesid']));	
			} 
		}

		return $quesList;
	}

}