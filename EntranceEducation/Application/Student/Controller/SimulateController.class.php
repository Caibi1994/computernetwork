<?php

namespace Student\Controller;
use Think\Controller;
use Think\Model;

class SimulateController extends Controller {
	public function index(){

		$openId = session('?openId')? session('openId'): $this->error('由于某种原因，您的某些关键数据丢失，请在微信端发送0重新授权登录,然后再获取该链接');
		
		/*====获取测试数量=====*/
		$RECORD = M('simulate_answer_record');
		$testnum = $RECORD->where('openId="'.$openId.'"')->max('testId');
		$testId = $testnum+1;
		session('testId',$testId);
		//echo session('testId');
		//die();

		$QUESTION = M('question');
		$chapter = array(
			'1' => 'unit1',
			'2' => 'unit2',
			'3' => 'unit3',
			'4' => 'unit4',
			'5' => 'unit5',
			'6' => 'unit6',
			'7' => 'unit7',
		);
		$count_unit1 = $QUESTION->where('chapter="'.$chapter['1'].'"')->count();
		$min_unit1 = $QUESTION->where('chapter="'.$chapter['1'].'"')->min('id');
		$max_unit1 = $QUESTION->where('chapter="'.$chapter['1'].'"')->max('id');		
		//echo $count_unit1;
		//echo $min_unit1;
		//echo $max_unit1;
		//die();
		//echo $chapter['1'];
		$numbers_unit1 = range ($min_unit1,$max_unit1); //排列成数组
		shuffle ($numbers_unit1); //将数组随机打乱
		$result_unit1 = array_slice($numbers_unit1,0,8);//取其中的8个数
		//print_r($result);
		$min_rest = $QUESTION->where('chapter!="'.$chapter['1'].'"')->min('id');
		$max_rest = $QUESTION->where('chapter!="'.$chapter['1'].'"')->max('id');
		$numbers_rest = range ($min_rest,$max_rest); //排列成数组
		shuffle ($numbers_rest); //将数组随机打乱
		$result_rest = array_slice($numbers_rest,0,2);//取其中的2个数		
		// while (list (, $number) = each ($numbers)) { 
		// 	 echo $number.'<br>'; 
		// }
		$result = array_merge($result_unit1, $result_rest);//存有10个题目id的数组
		shuffle ($result);  
		//print_r($result);
		//die();
		session('result',$result);
		//$this->assign($result);
		//$this->simulate($result);
		$this->display();
	}

	public function simulate(){
		/*=========定义变量=======*/
		$QUESTION = M('question');
		$RECORD = M('simulate_answer_record');
		//$COMMENT = D('Student/RandomComment');
		//$REPLY = D('Student/RandomReply');
		
		$openId = session('?openId')? session('openId'): $this->error('由于某种原因，您的某些关键数据丢失，请在微信端发送0重新授权登录,然后再获取该链接');
		//echo $openId;	
		$testId = session('testId');
		/*======获取答题数量======*/			
		$itemnum  =   $QUESTION->count(); //
        //echo $itemnum;


		/*======读取出一套模拟试卷的试题======*/
		// $chapter = I('chapter');
		// if($chapter){
		// 	$count = $QUESTION->where('type="'.$chapter.'"')->count();
		// 	$min = $QUESTION->where('type="'.$chapter.'"')->min('id');		
		// }else{
		// 	$count = $QUESTION->count();
		// 	$min = $QUESTION->min('id');		
		// }
		$result = session('result');//存有题目id的数组
		$next_id = I('next_id');
		// echo "题目的id在数组中的下标：".$next_id;
		//echo "<br/>";
		if ($next_id) {//如果接收到下一题的id
			//echo "fuck";
			if ($next_id<10) {
				$que_id = $result[$next_id];//在题库中的id
				//$proid = $next_id;
				$next_id++;
				$proid = $next_id;//显示在做题页面,当前是第几题
				//echo $que_id;
				//die();
			}else{
				//$this->simulateFinish($openId,$testId);
				if ($this->simulateFinish($openId,$testId)) {
					
					$this->assign('openId',$openId);
					$this->assign('testId',$testId);
					//$this->assign('next_id',$next_id);
					$this->display('simulateFinish');
					die();
				}
				//else{
					//$this->display('submitConfirm');
				// }
				
			}			
		}else{

			if(I('chooseId')){ 	//从答题卡页面传过来，如果接收到选择的某一题，则显示那一题
				$proid = I('chooseId');
				//echo $proid;
				//die();
				$que_id = $result[$proid-1];
				//echo $que_id;
				$next_id = $proid;
				//echo $next_id;
				//die();
			}else{	//如果没有接收到下一题的id，又没有选择某一题，那就从第一题开始
				$que_id = $result[0];
				$next_id = 1;
				$proid = $next_id;
			}
		}
		session('proid',$proid);//这套试卷的第几题
		//echo "题目的id:".$que_id;
		$item  = $QUESTION->where("id=".$que_id)->find();
        //var_dump($item);
		//die(); 

		// var_dump($item);	

		/*=======当前模拟试卷的答题情况==========*/

		$answerNum = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->count();
		$answerRightNum = $RECORD->where('openId="'.$openId.'" AND answerResult = "RIGHT"' )->count();
		
		$answerRecord = array(
			'answerNum' => $answerNum, //已经作答的题数
			'answerRightNum' => $answerRightNum,//答对题数
			'questionItem' => $proid,//当前题目是第几题
			'queNum' => 10, //每套卷子10道题目
		);
		//var_dump($answerRecord);

		/*=========评论数量=======*/
		// $commentNum = $COMMENT->where('questionId="'.$item['id'].'"')->count();
		// $replyNum = $REPLY->where('questionId="'.$item['id'].'"')->count();
		// $sumNum = $commentNum + $replyNum;

		/*======将题目分配到html页面中=====*/
		$this->assign('item',$item);
		$this->assign('enterTime',time());
		$this->assign('openid',$openId);
		$this->assign('itemid',$item['id']);
		$this->assign('answerRecord',$answerRecord);
		$this->assign('next_id',$next_id);
		//$this->assign('sumNum',$sumNum);
		$type = $QUESTION->where("id=".$que_id)->getField('type');//单选多选和判断？
		session('type',$type);
		if ($type=='2') {
			$this->display('simulateMultiple');
		}elseif ($type=='3') {
			$this->display('simulateJudge');
		}else{
			$this->display();
			//$this->display('randomMultiple');
			//$this->display('randomJudge');
		}
		
    }


    public function getRightAns(){
		/*=========判断是否通过ajax方式传输数据=======*/
		// var_dump(23333);
		// 		echo 2333;
		// 		die();调试神器
		if(IS_AJAX){
			
			/*=========定义变量=======*/
            $itemid   = I('post.itemid');
            $openId   = I('post.openid');
            //$answer   = I('post.answer');
            $enterTime = I('enterTime');
            $leaveTime = time();
			$QUESTION = M('question');
			$RECORD = M('simulate_answer_record');
			$type=session('type');
			//echo $type;
			if ($type == '2') {
				$answer1 = I('post.answer1');
				$answer2 = I('post.answer2');
				$answer3 = I('post.answer3');
				$answer4 = I('post.answer4');
				$answer = $answer1.$answer2.$answer3.$answer4;
				//echo $answer;
			}else{
				
				$answer  = I('post.answer');

			}
			/*=======获取正确答案=======*/	
			$ajaxreturn['rightAnswer']     = $QUESTION->where("id=".$itemid)->getField("rightAnswer");
			$ajaxreturn['analysisPicPath'] = $QUESTION->where("id=".$itemid)->getField("analysisPicPath");
			$ajaxreturn['analysisPicName'] = $QUESTION->where("id=".$itemid)->getField("analysisPicName");
			//var_dump($ajaxreturn);

			/*=======记录答题情况=====*/
			$this->recordOption($answer,$openId,$itemid,$enterTime,$leaveTime,$ajaxreturn['rightAnswer']);
				/*var_dump(23333);
				echo 2333;
				die();*/
			/*========获取各个选项的数量*/			
			// $ajaxreturn['opANum'] = $RECORD->where('questionId="'.$itemid.'" AND answer="A"')->count();
			// $ajaxreturn['opBNum'] = $RECORD->where('questionId="'.$itemid.'" AND answer="B"')->count();
			// $ajaxreturn['opCNum'] = $RECORD->where('questionId="'.$itemid.'" AND answer="C"')->count();
			// $ajaxreturn['opDNum'] = $RECORD->where('questionId="'.$itemid.'" AND answer="D"')->count();
			// $ajaxreturn['opAllNum'] = $RECORD->where('questionId="'.$itemid.'" ')->count();
			// $ajaxreturn['opANum'] = "A选项的数量";
			
			/*====返回答案解析到前台==*/
			$this->ajaxReturn($ajaxreturn);     
        }else 
			$this->ajaxReturn('非法的请求方式'); 	
	}

	public function recordOption($answer,$openId,$itemid,$enterTime,$leaveTime,$rightans){
		
		/*==========定义变量=============*/
		$ANSWER = M('simulate_answer_record');
		$QUESTION = M('question');
		$DOER = M('student_info');


		$name = $DOER->where('openId="'.$openId.'"')->getField('name');
		$class = $DOER->where('openId="'.$openId.'"')->getField('class');
		$number = $DOER->where('openId="'.$openId.'"')->getField('number');
		$testId = session('testId');
		//$proId = $ANSWER->where(array('openId' => $openId , 'testId' => $testId))->count();
		$proId = session('proid');
		$questionType = $QUESTION->where('id="'.$itemid.'"')->getField('chapter');
		$answerResult = $answer == $rightans? "RIGHT" : "WRONG" ;//多选题如何比较？？
		$answerTimeSecond = $leaveTime - $enterTime;    //回答时间的秒数int型
		$answerTime = (ceil($answerTimeSecond / 60)-1).'分'.($answerTimeSecond % 60).'秒';


		/*=======构造插入数据库答题信息数组======*/
		$record = array(
			'openId' => $openId, 
			'name' => $name,
			'class' => $class,
			'number' => $number,
			'testId' => $testId,//测试id
			'proId' => $proId, //题目在该套卷子中是第几题
			'questionId' => $itemid,//题目在题库中的id
			'questionType' => $questionType,
			'answer' => $answer,
			'rightAnswer' => $rightans,
			'answerResult' => $answerResult,
			'enterPageTime' => date("Y-m-d H:i:s",$enterTime),
			'leavePageTime' => date("Y-m-d H:i:s",$leaveTime),
			'answerTime' => $answerTime,
		);

		//如果该题目已回答过，用save来更新，如果没有回答过，用add插入数据
		if( !$ANSWER->where(array('openId' => $openId , 'testId' => $testId , 'questionId' => $itemid))->find()){
			$ANSWER->data($record)->add();
		}
		else{
			//更新的话，只更新答案和正确与否
			//$proId = $ANSWER->where(array('openId' => $openId , 'testId' => $testId , 'questionId' => $itemid))->getField('proId');
			//$record['proId'] = $proId;
			$record = array(
				'answer' => $answer,
				'answerResult' => $answerResult,
			);
			$ANSWER->where(array('openId' => $openId , 'testId' => $testId , 'questionId' => $itemid))->save($record);
		
		}
		//$ANSWER -> add($record);
		//$this->simulateFinish($openId,$testId);

		//如果回答错误，把答题信息记录到错题回顾表
		if ($answerResult == "WRONG") {
			$WRONG = M('wrong_review_record');
			$WRONG->data($record)->add();
		}

	}

	//判断是否完成所有题目
    private function simulateFinish($openId,$testId){
    	$RECORD = M('simulate_answer_record');
    	$finish = $RECORD->where(array('openId' => $openId,'testId' => $testId))->count();
        if($finish==10){
            return true;
            //$this->display();
        }
        else{
            return false;
        }
    }

    public function simulateResult(){
    	$openId = I('openId');
    	$testId = I('testId');
        $RECORD = M('simulate_answer_record');
        $RESULT = M('simulate_result_record');
        $GRADE = M('simulate_grade_record');
		$name = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->getField('name');
		$class = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->getField('class');
		$number = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->getField('number');
		//echo $number;die();
		$answerNum = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->count();
		$answerRightNum = $RECORD->where(array('openId' => $openId , 'testId' => $testId, 'answerResult' => "RIGHT"))->count();
		$startTime = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->min('enterPageTime');
		$submitTime = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->max('leavePageTime');
		$answerTimeSecond = strtotime($submitTime) - strtotime($startTime);    //回答时间的秒数int型
		//$answerTime = ceil(abs($submitTime - $startTime)/86400); 	
		$answerTime = (ceil($answerTimeSecond / 3600)-1).'小时'.(ceil($answerTimeSecond / 60)-1).'分'.($answerTimeSecond % 60).'秒';
		// echo $startTime."<br/>";
		// echo $submitTime."<br/>";
		// echo $answerTimeSecond."<br/>";
		// echo $answerTime;
		// die();
		if ($answerRightNum>=9) {
			$simulateResult = 'pass';//判断是否通过
		}else{
			$simulateResult = 'fail';
		}
		$answerRecord = array(
			'openId' => $openId,
			'name' => $name,
			'class' => $class,
			'number' => $number,
			'testId' => $testId,//测试id			
			//'answerNum' => $answerNum, //已经作答的题数
			'answerRightNum' => $answerRightNum,//答对的题数
			'answerWrongNum' => $answerNum - $answerRightNum,
			'simulateResult' => $simulateResult,
			'startTime' => $startTime,
			'submitTime' => $submitTime,
			'answerTime' => $answerTime,
		); 

		//将答题结果同步记录到答题结果表中       
		if( !$RESULT->where(array('openId' => $openId , 'testId' => $testId ))->find()){
			$RESULT->data($answerRecord)->add();//如果答题结果表中没有记录则插入
		}

		//将答题成绩记录到答题成绩表中，只存最好成绩（分数高，用时短）       
		if (!$GRADE->where(array('openId' => $openId))->find()) {
			$GRADE->data($answerRecord)->add();//如果答题成绩表中没有记录则插入
		}else{
			$gradeBest = $GRADE->where(array('openId' => $openId))->getField('answerRightNum');
			$timeBest = $GRADE->where(array('openId' => $openId))->getField('answerTime');
			//echo $gradeBest;
			//die();
			//如果新分数更高，则更新；如果分数一样，但用时更短，则更新
			if ($answerRightNum > $gradeBest || ($answerRightNum > $gradeBest && $answerTime < $timeBest)) {
				$GRADE->where(array('openId' => $openId))->save($answerRecord);
				//echo "恭喜你获得更好成绩，已更新";
			}			
		}
        $this->assign('answerRecord',$answerRecord)->display();
    }

    //题目解析
    public function simulateAnalyze(){
        $testId   = session('?testId') ? session('testId') : $this->error('请重新获取该页面');
        $openId   = session('?openId') ? session('openId') : $this->error('请重新获取该页面');

        $RECORD = M('simulate_answer_record');
        $QUESTION = M('question');
        for ($i=1; $i <= 10; $i++) { 
        	$answerRecord = $RECORD->where(array('openId' => $openId , 'testId' => $testId , 'proId' => $i))->find();
			//$proId = $RECORD->where(array('openId' => $openId , 'testId' => $testId))->getField('proId');
			//var_dump($answerRecord);//答题记录数组
			//echo $proId;
			//die(); 
			$questionId = $answerRecord['questionId'];
			//echo $questionId;     
	        $quesList = $QUESTION->where(array('id' => $questionId))->select();
	 		//var_dump($quesList);//题目信息数组
			//die();
			$proId = $i;
			$this->assign('proId',$proId);
			$this->assign('answerRecord',$answerRecord);   
	        $this->assign('quesList',$quesList)->display();
        }

    }


}