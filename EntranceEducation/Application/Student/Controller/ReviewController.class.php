<?php


namespace Student\Controller;
use Think\Controller;
use Think\Model;

class ReviewController extends Controller {
	public function index(){

        $openId=session('openId');
        session('openId',$openId);
		//echo $openId;
		$this->display();
	}
	public function review(){
		/*=========定义变量=======*/
		$QUESTION = M('question');
		$RECORD = M('wrong_review_record');
		
		$openId = session('?openId')? session('openId'): $this->error('由于某种原因，您的某些关键数据丢失，请在微信端发送0重新授权登录,然后再获取该链接');
		//echo $openid;	

		/*======获取错题数量======*/
			
		$wrongNum  =  $RECORD->where('openId="'.$openId.'"')->count(); //
		if ($wrongNum==0) {
			$this->display('noWrong');
			die();
		}
		
		/*======读取错题======*/

		$wrongArray = $RECORD->where('openId="'.$openId.'" AND answerResult = "WRONG"' )->select();
		//var_dump($wrongArray);//错题的二维数组
		for ($i=0; $i < $wrongNum; $i++) { 
			$wrong_array = $wrongArray[$i];//某一题的一维数组
			$wrongId = $wrong_array['questionId'];
			//echo $wrongId;
			$wrongId_array[] = $wrongId;//错误题号数组
		}
		//var_dump($wrongId_array);
		$id = rand(0,$wrongNum-1);//生成随机数
		//echo $id."<br/>";
		$wrongId = $wrongId_array[$id];//随机取一个错题，否则每次都是同一题
		//echo $wrongId;
		//die();


		$item  = $QUESTION->where("id=".$wrongId)->find();
		//echo $item['id'];
		//die(); 
		/*======将题目分配到html页面中=====*/
		$this->assign('item',$item);
		$this->assign('enterTime',time());
		$this->assign('openId',$openId);
		$this->assign('itemId',$item['id']);
		$this->assign('wrongNum',$wrongNum);
		$this->assign('wrongId',$wrongId);
		//$this->assign('answerRecord',$answerRecord);
		//$this->assign('sumNum',$sumNum);
		$type = $QUESTION->where("id=".$wrongId)->getField('type');//单选多选和判断？
		
		session('type',$type);
		if ($type=='2') {
			$this->display('reviewMultiple');
		}elseif ($type=='3') {
			$this->display('reviewJudge');
		}else{
			$this->display();
			//$this->display('randomMultiple');
			//$this->display('randomJudge');
		}
		
		
    }


    public function getRightAns(){
		/*=========判断是否通过ajax方式传输数据=======*/
		//var_dump(23333);
		// echo 2333;
		// die();
		if(IS_AJAX){
			//echo "3333";
			/*=========定义变量=======*/
            $itemId   = I('post.itemId');
            $openId   = I('post.openId');
            //echo $itemId;
            //die();
            //$answer   = I('post.answer');
            $enterTime = I('enterTime');
            $leaveTime = time();
			$QUESTION = M('question');
			$RECORD = M('wrong_review_record');
			$type=session('type');
			//echo $chapter;
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
			$ajaxreturn['rightAnswer']     = $QUESTION->where("id=".$itemId)->getField("rightAnswer");
			$ajaxreturn['analysisPicPath'] = $QUESTION->where("id=".$itemId)->getField("analysisPicPath");
			$ajaxreturn['analysisPicName'] = $QUESTION->where("id=".$itemId)->getField("analysisPicName");
			//var_dump($ajaxreturn);
		//echo "sss";
		//die();
			/*=======记录答题情况=====*/
			$this->recordOption($answer,$openId,$itemId,$enterTime,$leaveTime,$ajaxreturn['rightAnswer']);
		
			/*========获取各个选项的数量*/			
			// $ajaxreturn['opANum'] = $RECORD->where('questionId="'.$itemId.'" AND answer="A"')->count();
			// $ajaxreturn['opBNum'] = $RECORD->where('questionId="'.$itemId.'" AND answer="B"')->count();
			// $ajaxreturn['opCNum'] = $RECORD->where('questionId="'.$itemId.'" AND answer="C"')->count();
			// $ajaxreturn['opDNum'] = $RECORD->where('questionId="'.$itemId.'" AND answer="D"')->count();
			// $ajaxreturn['opAllNum'] = $RECORD->where('questionId="'.$itemId.'" ')->count();
			// $ajaxreturn['opANum'] = "A选项的数量";
			
			/*====返回答案解析到前台==*/
			$this->ajaxReturn($ajaxreturn);     
        }else 
			$this->ajaxReturn('非法的请求方式'); 	
	}

	public function recordOption($answer,$openId,$itemId,$enterTime,$leaveTime,$rightans){
		/*==========定义变量=============*/
		$ANSWER = M('wrong_review_record');
		$QUESTION = M('question');

		$answerResult = $answer == $rightans? "RIGHT" : "WRONG" ;
		$answerTimeSecond = $leaveTime - $enterTime;    //回答时间的秒数int型
		$answerTime = (ceil($answerTimeSecond / 60)-1).'分'.($answerTimeSecond % 60).'秒';
		/*=======构造插入数据库答题信息数组======*/


		//如果回答错误，只需更新答题时间
		if( $answerResult == "WRONG"){

			$record = array(
				'enterPageTime' => date("Y-m-d H:i:s",$enterTime),
				'leavePageTime' => date("Y-m-d H:i:s",$leaveTime),
				'answerTime' => $answerTime,
			);
			$ANSWER->where(array('openId' => $openId , 'questionId' => $itemId))->save($record);
		}
		else{

			//如果回答正确，用save来更新结果和时间，
			// $record = array(
			// 	'answer' => $answer,
			// 	'answerResult' => $answerResult,
			// 	'enterPageTime' => date("Y-m-d H:i:s",$enterTime),
			// 	'leavePageTime' => date("Y-m-d H:i:s",$leaveTime),
			// 	'answerTime' => $answerTime,
			// );
			// $ANSWER->where(array('openId' => $openId , 'questionId' => $itemId))->save($record);
			//如果回答正确，直接删除该条错题记录
			$ANSWER->where(array('openId' => $openId , 'questionId' => $itemId))->delete();
		}
		

	}


}