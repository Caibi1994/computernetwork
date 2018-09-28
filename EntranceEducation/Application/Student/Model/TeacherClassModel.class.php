<?php
namespace Student\Model;
use Think\Model;

class TeacherClassModel extends Model {
	public function getTeacherClass($openId){
		$teacherClass = M('teacher_class')->where(array('openId'=>$openId))->select();
		return $teacherClass;
	} 
}