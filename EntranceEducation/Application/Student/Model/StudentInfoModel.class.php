<?php
namespace Student\Model;
use Think\Model;
class StudentInfoModel extends Model {
	// protected $tablePrefix = 'db_';

	public function getName($openid){
		return  $this->where('openId="'.$openid.'"')->getField('name');
	}
	public function getClass($openid){
		return  $this->where('openId="'.$openid.'"')->getField('class');
	}
	public function getNumber($openid){
		return  $this->where('openId="'.$openid.'"')->getField('number');
	}

	public function getStuInfo($openid){
		return  $this->where('openId="'.$openid.'"')->find();
	}

	public function getStuNum($openid){
		$class = $this->getClass($openid);
		$stuNum = $this->where(array('class'=>$class))->count();
		return $stuNum;
	}

	//判断是否注册
	public function isRegister($openId){
		$condition['openId'] = $openId;                //查询条件
		if($this->where($condition)->find())                    
			return true;
		else
			return false;
	}

	//返回新手列表里的信息
	public function newerInfo($number) {
		$info = D('student_list')->where(array('number'=>$number))->find();
		
		if (empty($info)) {
			return false;
		}

		return $info;
	}

	/**
	 * 判断用户是否为新生
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-10-3 14:25Authors
	 * @param $openid
	 * @return true or false
	 */
	public function isNewer ($openid){

		$newer = $this->where(array('openid'=>$openid))->getField('is_newer');
		if ($newer)
			return true;
		else
			return false;
		
	}
	public function classList() {
		$sql = "SELECT DISTINCT(class) FROM (SELECT DISTINCT(class),time FROM db_student_info ORDER BY time DESC) P LIMIT 15";
		$Model = new \Think\Model();
		$class = $Model->query($sql);

		if (empty($class)) {
			return false;
		}
		// dump($class);
		return $class;


    }
}