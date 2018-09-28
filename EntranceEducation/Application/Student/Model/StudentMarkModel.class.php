<?php

namespace Student\Model;
use Think\Model;


class StudentMarkModel extends Model {
    // protected $tablePrefix = 'cn_';

    public function getLastMark($openid){
    	$map = array('openid'=>$openid);
    	$mark = $this->where($map)->getfield('lastMark');
    	// p($mark);die;
    	return $mark;
    }

    public function getRank($openid){
    	$map = array('openid',$openid);
    	$class = D('StudentInfo')->getClass($openid);
    	// p($class);
    	$rankList = $this->order('lastMark desc')->where(array('class'=>$class))->group('openid')->select();
    	// p($rankList);
    	foreach ($rankList as $key => $value) {
    		// p($rankList);
    		// p($value);
    		if(in_array($openid, $value)){
    			// p('匹配');
    			$rankMark=array_keys($rankList,$value);
    		}
    	}
    	return $rankMark[0]+1;
    }

    /**
	 * getRankList($start) 获取做对题数的排名
	 * @author 蔡佳琪
	 * @copyright  2017-11-22 15:12 Authors
	 * @var int $start
	 * @return array('openid', 'sum(result)') 从第$start名往后的20位同学的数组
	 */
	// public function getRankList( $start = 0 , $classArray = array() ) { 
	// 	var_dump($classArray);die();
		 
	// 	//$classArray = array('all','测试1601');
	// 	// p($classArray);
	// 	if($classArray[0] == 'all'){
	// 		//如果选择了所有班级
	// 		$sql = "SELECT * FROM cn_student_mark GROUP BY openid ORDER BY lastMark desc,openid desc LIMIT $start,20";					
	// 		// dump($sql);	
	// 		$Model = new \Think\Model();
	// 		$markList = $Model->query($sql);
	// 		if (empty($markList)) {
	// 			return false;
	// 		}

	// 	}else{
	// 		//如果没有选择所有班级，就查询某班级
	// 		$markList = array();
	// 		foreach ($classArray as $value) {
 //                $markList = array_merge($markList,$this->where(array('class' => $value))->order('lastMark desc')->group('openid')->select());
 //            }
            
	// 	}

	// 	return $markList;
	// }
}