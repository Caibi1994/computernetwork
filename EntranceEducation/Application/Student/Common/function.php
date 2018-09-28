<?php 
use Think\Model;


// 截止时间
function get_endtime($now, $set_time){
	return date('m月d日', $now + $set_time * 60);
}

function get_test_index_css($result) {
	
	if ($result == -1) {
		return 'placeholder';
	} else {
		return 'placeholder-right';
	}
}

// *******展示题目索引****************

function get_exam_index_css($result) {
	
	if ($result == -1) {
		return 'placeholder';
	} else {
		return 'placeholder-right';
	}
}

// *******展示题目索引 in  exercise *****
function get_exsercise_index_css($quesid) {
	
	$openid = session('openId');
	
	$map = array(
		'openid' => $openid,
		'quesid' => $quesid,
 	);
	
	$result =  M('exercise')->where($map)->getField('result');


	if(!isset($result)) {
		return 'placeholder';
	} else if ($result == 1) {
		return 'placeholder-right';
	} else {
		return 'placeholder-wrong';
	}
}

function get_exercise_url_css($quesid) {
	
	$openid = session('openId');
	
	$map = array(
		'openid' => $openid,
		'quesid' => $quesid,
 	);
	
	$result =  M('exercise')->where($map)->getField('result');


	if(!isset($result)) {
		return U('Exercise/exercise_chap', array('quesid'=>$quesid));
	} else {
		return 'javascript:;' ;		
	}

}

// *******展示题目索引 in  record *****
function get_record_index_css($quesid) {
	
	$openid = session('openId');
	
	$map = array(
		'openid' => $openid,
		'quesid' => $quesid,
 	);
	
	$result =  M('exercise')->where($map)->getField('result');


	if(!isset($result)) {
		return 'placeholder';
	} else if ($result == 1) {
		return 'placeholder-right';
	} else {
		return 'placeholder-wrong';
	}
}

function get_record_url_css($quesid) {
	

	return U('Record/record', array('quesid'=>$quesid));

}

// *******展示题目索引 in  collect *****
function get_collect_index_css($quesid) {
	
	$openid = session('openId');
	
	$map = array(
		'openid' => $openid,
		'quesid' => $quesid,
 	);
	
	$result =  M('exercise')->where($map)->getField('result');


	if(!isset($result)) {
		return 'placeholder';
	} else if ($result == 1) {
		return 'placeholder-right';
	} else {
		return 'placeholder-wrong';
	}
}

function get_collect_url_css($quesid) {
	

	return U('Collect/recordList', array('quesid'=>$quesid));

}

// *******展示题目索引 in  repeat *****
function get_repeat_index_css($quesid) {
	
	$openid = session('openId');
	
	$map = array(
		'openid' => $openid,
		'quesid' => $quesid,
 	);
	
	$result =  M('repeat')->where($map)->getField('result');


	if(!isset($result)) {
		return 'placeholder';
	} else if ($result == 1) {
		return 'placeholder-right';
	} else {
		return 'placeholder-wrong';
	}
}

function get_repeat_url_css($quesid) {
	
	//$openid = session('openId');
	
	//$map = array(
	//	'openid' => $openid,
	//	'quesid' => $quesid,
 	//);
	
	//$result =  M('repeat')->where($map)->getField('result');


	//if(!isset($result)) {
		return U('Repeat/repeat_chap', array('quesid'=>$quesid));
	//} else {
		return 'javascript:;' ;		
	//}

}

// *******处理包含图片的题目
function repleace_question_image($contents) {
	
	$pattern = '/!\[image\]\((\d{1,}.jpg)\)/';

	$r = preg_match_all($pattern, $contents, $m);
	

	if($r){
		foreach ($m[1] as $k => $v) {
			$contents = str_replace($m[0][$k], '<img class="c-pic" src="http://'. $_SERVER['HTTP_HOST'] .'/EntranceEducation/Public/images/questionbank/'.$v.'">', $contents);
		}
	}

	return $contents;
}


// 在模拟考试页面中是否显示这次openid同学的examid
function is_on_college($examid) {

	$openid = session('openId');

	if(true === D('ExamCollege')->is_college($openid, $examid)) {
		return 1;
	} else {
		return 0;
	}

}
/**
 * getChapterName 获取章节名
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-11-23 14:52Authors
 * @var 
 * @return String
 */
function getChapterName($chapter) {
	$chapter = M('QuestionChapter')->where(array('id'=>$chapter))->field('chapter')->find();
	return $chapter['chapter'];
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
			return '判断题';
			break;
		case 3:
			return '多选题';
		default:
			return 'ss';
			break;
	}
}

 ?>
