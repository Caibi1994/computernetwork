<?php 


/**
 * is_on 解析是否开启
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-9-16 11:13Authors
 * @var string $on 1 or 0
 * @return string 开启 关闭
 */
function is_on($on) {
	$state = array('关闭', '开启');
	return $state[$on];
}

/**
 * get_ques_type 获取题目类型
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-10-15 21:08Authors
 * @var string $on 1 or 0
 * @return string 开启 关闭
 */
function get_ques_type($type) {
	switch ($type) {
		case 1:
			return '单选题';
			break;
		case 2:
			return '判读题';
			break;
		case 3:
			return '多选题';
		default:
			return 'ss';
			break;
	}
}
/**
 * getAnswerNum 获取答题人数
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-10-29 14:36Authors
 * @var $id  题目id
 * @return int 答题人数
 */
function getAnswerNum($id) {
	$num = D('Exercise')->getAnswerNum($id);
	return $num;
}
/**
 * getAccuracy 获取正确百分比
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-10-29 14:52Authors
 * @var $id  题目id
 * @return int 正确百分比*100   (%符号在html中加上)
 */
function getAccuracy($id) {
	$accuracy = D('Exercise')->getAccuracy($id);
	return $accuracy*100;
}
/**
 * getSubmitNum 获取提交数
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-10-29 15:14Authors
 * @var $id  考试id
 * @return int 提交数
 */
function getSubmitNum($id) {
	$num = D('ExamSubmit')->getSubmitNum($id);
	return $num;
}
/**
 * getUnsubmitNum 获取未提交数
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-10-29 15:14Authors
 * @var $id  考试id
 * @return int 提交数
 */
function getUnsubmitNum($id) {
	$num = D('ExamSubmit')->getUnsubmitNum($id);
	return $num;
}
/**
 * getResult($openid) 获取$name的提交数及正确数
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-10-29 15:14Authors
 * @var $id  考试id
 * @return String 正确数|提交数
 */
function getResult($openid) {
	$result = D('Exercise')->getResult($openid);
	return $result;
}
/**
 * getNameByOpenid($openid) 获取$openid的名字
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-11-7 15:33Authors
 * @var $openid  
 * @return String name
 */
function getNameByOpenid($openid) {
	$info = D('StudentInfo')->getInfo($openid);
	return $info['0']["name"];
}

/**
 * getClassByOpenid($openid) 获取$openid的班级
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-11-7 15:37Authors
 * @var $openid  
 * @return String class
 */
function getClassByOpenid($openid) {
	$info = D('StudentInfo')->getInfo($openid);
	if ($info['0']['class'] == '') {
		return '非新生';
	}
	return $info['0']['class'];
}

/**
 * getNumberByOpenid($openid) 获取$openid的学号
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-11-7 15:37Authors
 * @var $openid  
 * @return String Number
 */
function getNumberByOpenid($openid) {
	$info = D('StudentInfo')->getInfo($openid);
	return $info['0']['number'];
}

 ?>