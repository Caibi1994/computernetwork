<?php
namespace Student\Model;
use Think\Model;
class EduUserModel extends Model {
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
		array('password', 'passport_decrypt', 3, 'function'),
	);
	
	/**
	 * 是否注册
	 * @author 李俊君<hello_lijj@qq.com>
	 */
	public function isRegister($openId){
		if ($this->where(array('openId'=>$openId))->find()) {
        	return true;
        }else{
        	return false;
        }
	}

	/**
	 * 获取用户信息
	 * @author 李俊君<hello_lijj@qq.com>
	 */
	public function getUserInfo($openId){
		$userInfo = $this->where(array('openId' => $openId))->find();
		$password = $userInfo['password'];
		$userInfo['password'] = passport_decrypt($password);  //对密码进行解密
		return $userInfo;
	}

	
	
}