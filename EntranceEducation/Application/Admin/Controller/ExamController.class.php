<?php 
namespace Admin\Controller;
use Think\Controller;

/**
 * EXAM 控制器 新生考试 模拟考试
 * @author 李俊君<hello_lijj@qq.com>
 * @copyright  2017-9-12 15:08Authors
 * @var  
 * @return 
 */
class ExamController extends CommonController{
    
    /**
     * index 自由练习主页面 能显示当前进度，答题了多少道
     * @author 李俊君<hello_lijj@qq.com>
     * @copyright  2017-9-10 9:39Authors
     * @var  
     * @return 
     */

    public function index() {

        $examList = D('ExamSetup')->select();
        // p($examList);
        $this->assign('examList', $examList);
        $this->display();
    }


    /**
     * add 创建新生考试
     * @author 李俊君<hello_lijj@qq.com>
     * @copyright  2017-9-15 21:18Authors
     * @var  
     * @return 
     */
    public function add() {
        if (IS_POST) {
            
            $st = I('start_time');
            $st = str_replace('T', ' ', $st).':00';

            $data = array(
                'title' => I('title'),
                'start_time' => strtotime($st),
                'set_time' => I('spend_time'),
                'is_on' => intval(I('is_on')),
                'time' => date('Y-m-d H:i:s'),
            );

            $res = D('ExamSetup')->add($data);

            if($res) {
                if (D('ExamCollege')->init($res)) {
                    $this->success('考试创建成功', U('Exam/index'));
                }else{
                    $this->error('初始化失败');
                }
            } else {
                $this->error('考试创建失败');
            }

        } else {
            
            $this->display();
        }
    }

    /**
     * delete 创建新生考试
     * @author 李俊君<hello_lijj@qq.com>
     * @copyright  2017-9-15 21:18Authors
     * @var  
     * @return 
     */
    public function delete($id = 0) {

        $res = M('exam_setup')->where(array('id' => $id))->delete();

        if($res) {
            $this->success('删除成功', U('Exam/index'));
        } else {
            $this->success('删除失败', U('Exam/index'));
        }
    }

    /**
     * edit 设置考试信息
     * @author 陈伟昌<1339849378@qq.com>
     * @copyright  2017-9-16 11:12Authors
     * @var  $id
     * @return 
     */
    public function edit($id = 0) {
        if (IS_POST) {
        	$SET = M('ExamSetup');

            $st = I('start_time');
            $st = str_replace('T', ' ', $st).':00';
            $time = $SET->where(array('id'=>$id))->field('time')->find();
            $data = array(
                'title' => I('name'),
                'start_time' => strtotime($st),
                'set_time' => I('set_time'),
                'is_on' => intval(I('is_on')),
                'time' => $time['time'],
            );
            if($SET->data($data)->where(array('id' => $id))->save($date))
            	$this->success('修改成功', U('Exam/index'));
            else
            	$this->error('修改失败', U('Exam/index'));
        }else{

            $info = M('ExamSetup')->find($id);
            $this->assign('info',$info)->display();
        }
    }

    /**
     * addQues 添加考试题目
     * @author 李俊君<hello_lijj@qq.com>
     * @copyright  2017-9-16 11:12Authors
     * @var  $id
     * @return 
     */
    public function addQues($id) {
        if (IS_POST) {
            $data = I();
            foreach ($data as $key => $value) {
                $quesData = array();
                $quesData['examid'] = $id;
                $quesData['chapid'] = intval(substr($key, 8));
                $quesData['chap_num'] = intval($value);
                D('ExamQuestionbank')->add($quesData);
            }
            $this->success('题目添加成功', U('Exam/index'));
        } else {
            
            $examList = D('ExamSetup')->where(array('id'=>$id))->find();
            // dump($examList['title']);
            $chapterList = M('QuestionChapter')->select();
            foreach($chapterList as $key=>$value){
                $chapterList[$key]['maxNum']=D('Student/Questionbank')->getQuesChapterNum($key+1);
                $map = array(
                    'examid'=>$id,
                    'chapid'=>$value['id'],
                );
                $selectNum = M('ExamQuestionbank')->where($map)->getField('chap_num');
                $chapterList[$key]['selectNum']=is_null($selectNum) ? 0 : $selectNum;
            }
            // dump($chapterList);
            $this->assign('chapterList',$chapterList);

            $this->assign('examList',$examList['title']);

            $this->display();
        }

    }


    /**
     * college 设置考试参与学院信息
     * @author 李俊君<hello_lijj@qq.com>
     * @copyright  2017-10-26 13:05Authors
     * @var  $id
     * @return 
     */
    public function college($id) {
        //创建考试的时候就已经向exam_college写入数据

        if (IS_POST) {
            # code...
        } else {
            $list = D('ExamCollege')->getInfo($id);
            if ($list == false) {
                $this->error('读取失败');
            }
            $this->assign('list',$list);
            
            $this->display();
        }
    }

    public function editCollege($id, $state) {
        $COLLEGE = M('ExamCollege');
        if ($state == 0) {
            $state = 1;
        } else {
            $state = 0;
        }
        $data = $COLLEGE ->where(array('id'=>$id))->find();
        $data['state'] = $state;
        if ($COLLEGE->save($data)) {
            $this->success('修改成功', U('college',array('id'=>$data['examid'])));
        }else{
            $this->error('修改失败！', U('college',array('id'=>$data['examid'])));
        }

    }


}