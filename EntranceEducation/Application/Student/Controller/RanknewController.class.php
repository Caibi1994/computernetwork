<?php


namespace Student\Controller;
use Think\Controller;
use Think\Model;

class RanknewController extends Controller {

    public function index(){

        $openId=session('openId');
        session('openId',$openId);

        $this->display('rankSchool');
    }


    public function rankSchool(){

        $openId   = session('openId');  
        $EXERCISE = D('exercise');
        $STUDENT = M('studentInfo');
        if (IS_AJAX) {
            if(session('?start')){
                $start = session('start') + 20;
                session('start',$start );
            } else {
                session('start',0);
                $start = 0;
            }
            $rankList    = $EXERCISE->getRankList($start);
            foreach($rankList as $key => $value){

                  $rankList[$key]['info'] = $STUDENT->where(array('openId' => $value['openid']))->find();
            }
            $this->ajaxReturn($rankList);

        } else {
            session('start',0);
            // dump($openId);
            $rankList = $EXERCISE->getRankList();
            // $me = array();
        //     获取"我的成绩与排名"
        //     foreach($rankList as $key=>$value){
        //      if ($value['openid']==$openId) {
        //        $me['rank'] = $key +1;
        //        $me['grade'] = $value['sum(result)'];
        //        break;
        //      }
        // }
            foreach($rankList as $key=>$value){

                  $rankList[$key]['info'] = $STUDENT->where(array('openId' => $value['openid']))->find();
            }
            // dump($rankList);

            // $this->assign('length',COUNT($rankList));
            // $this->assign('me',$me);
            $this->assign('rankList',$rankList);
            $this->display();

        }

    }
    public function rankSchoolPer(){

        $openId   = session('openId');  
        $EXERCISE = D('exercise');
        $STUDENT = M('studentInfo');

        if (IS_AJAX) {
            if(session('?start')){
                $start = session('start') + 20;
                session('start',$start );
            } else {
                return false;
            }
            $rankList    = $EXERCISE->getPerRankList($start);
            foreach($rankList as $key=>$value){

                  $rankList[$key]['info']= $STUDENT->where(array('openId' => $value['openid']))->find();
            }
            $this->ajaxReturn($rankList);

        } else {
            session('start',0);
            

            $rankList = $EXERCISE->getPerRankList();

            foreach($rankList as $key=>$value){

                  $rankList[$key]['info'] = $STUDENT->where(array('openId' => $value['openid']))->find();
            }
            // dump($rankList);

            // $this->assign('length',COUNT($rankList));
            // $this->assign('me',$me);
            $this->assign('rankList',$rankList);
            $this->display();

        }

    }


}