<?php
namespace Student\Model;
use Think\Model;
class RandomCommentModel extends Model{
	public function getName($commentId){
		return  $this->where('id="'.$commentId.'"')->getField('name');
	}
	public function getQuestionId($commentId){
		return  $this->where('id="'.$commentId.'"')->getField('questionId');
	}
	public function say(){
		echo "这是Rondom函数";
	}
}
