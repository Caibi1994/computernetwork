<?php
namespace Student\Model;
use Think\Model;
class EduCetModel extends Model {
	protected $tablePrefix = 'db_';

	/**
	 * 自动验证
	 * @author 李俊君<hello_lijj@qq.com>
	 */
	protected $_validate = array(
		
	);

	/**
	 * 自动完成
	 * @author 李俊君<hello_lijj@qq.com>
	 */
	protected $_auto = array(
		
	);
	
	/**
	 * 是否绑定
	 * @author 李俊君<hello_lijj@qq.com>
	 */
	public function isBind($openId){
		if ($this->where(array('openId'=>$openId))->find()) {
        	return true;
        }else{
        	return false;
        }
	}

	/**
	 * 获取cet信息
	 * @author 李俊君<hello_lijj@qq.com>
	 */
	public function getCetInfo($openId){
		$cetInfo = $this->where(array('openId' => $openId))->find();
		return $cetInfo;
	}

	
	
}