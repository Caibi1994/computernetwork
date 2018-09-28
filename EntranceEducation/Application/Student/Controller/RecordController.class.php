<?php


namespace Student\Controller;
use Think\Controller;
use Think\Model;

class RecordController extends Controller {
	public function index(){

        $openId=session('openId');
        session('openId',$openId);
		//echo $openId;

		$this->display('explain');
	}

	/*
	  getNewestQuesid() 获取用户答题记录

	 */

	public function record(){

		$openId = session('openId');
		$RECORD = D('exercise');
		$QUESTION = D('Questionbank');
		$record = $RECORD->getExerciseRecord($openId);
		// var_dump($record);
		//die();
		$num = $record['count'];//总答题数
		//echo "<br/>答题数：".$num;
		//die();
		//$quesIdArr = array(); 

        // $quesIdArr = $RECORD->where(array('openid'=>$openId))->getfield('quesid',$num);
		$quesList = $RECORD->where(array('openid'=>$openId))->order('quesid asc')->field('quesid')->select();
		//var_dump($quesList);//所有做过的题目的id，二维数组
		$quesIdArr = array();  
		$quesIdArr = array_map('array_shift', $quesList);
		//$quesIdArr = array_column($quesList, 'quesid');  
		//var_dump($quesIdArr);//所有做过的题目的id，一维数组
		
		//die();
		
		if (I('nextid')) {
			//从下一题进入
			$nextid = I('nextid');	
			if ($nextid<$num) {
				$quesId = $quesList[$nextid]['quesid'];
				$nextid++;
			}else{
				$this->display('tip');
				die();
			}

		}else{
			if(I('quesid')){
				//从索引进入
				$quesId = I('quesid');
				//print_r(array_keys($quesIdArr,$quesId,true)); 
				//die();
				$nowid = array_keys($quesIdArr,$quesId,true); //array_keys返回的是数组
				$nextid = $nowid[0]+1;
			}else{
				//从首页入口进入显示第一题
				$quesId = $quesList[0]['quesid'];
				$nextid = 1;
			}
		}
		//echo "<br/>下一题的id在数组中的下标：".$nextid;
		//echo "<br/>题目id：".$quesId;
		//die();
		session('quesId', $quesId);
		session('nextid',$nextid);
		if($quesId==''){
			$this->display('none');
			die();
		}else{
			$quesItem  = $QUESTION->getQuestion($quesId);
		}
		//getQuestion方法当$quesId为空时，返回第一题
		
		$rightAns = $QUESTION->getRightAnswer($quesId);
		$recordArr = $RECORD->where(array('openid'=>$openId,'quesid'=>$quesId))->find();
		//var_dump($recordArr);
		//echo "用户答案：".$recordArr['answer']."<br/>";
		//echo "正确答案：".$rightAns."<br/>";
		//echo $recordArr['result'];
		//$quesList  = $QUESTION->getQuesList('8');
		//var_dump($quesList);
		
		// 判断是否已经做完了最后一道题目
		if ($quesItem) {
			$this->assign('record', $record);
			$this->assign('quesItem', $quesItem);
			$this->assign('rightAns',$rightAns);
			$this->assign('recordArr',$recordArr);
			//$this->assign('quesIdArr',$quesIdArr);
			$this->assign('quesList', $quesList);
			
			// 对题目类型判断 不同类型进入不同的页面
			if ($quesItem['type'] == '单选题') {
				$this->display('index');
			} else if ($quesItem['type'] == '判断题') {
				$this->display('judge');
			} else if ($quesItem['type'] == '多选题') {

				$this->display('mutil');
			}
				 
		} else {

			$this->display('tip');
		}
    }

	/**
	 * exercise_index 答题记录的索引
	 * @author 李俊君<hello_lijj@qq.com>
	 * @copyright  2017-9-8 14:58Authors
	 * @var  
	 * @return json. 正确，还是错误
	 */

	public function record_index() {

		$openId = session('openId');
		$RECORD = D('exercise');
		$quesList = $RECORD->where(array('openid'=>$openId))->field('quesid')->select();
		$this->assign('quesList', $quesList)->display();

	}


}