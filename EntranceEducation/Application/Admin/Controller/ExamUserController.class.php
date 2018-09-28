<?php 
namespace Admin\Controller;
use Think\Controller;

/**
 * EXAMUSER 控制器 新生考试 模拟考试
 * @author 陈伟昌<1339849378@qq.com>
 * @copyright  2017-10-29 14:128Authors
 * @var  
 * @return 
 */
class ExamUserController extends CommonController{
	
	/**
	 * index 模拟考试主页面 显示考试列表，提交人数等
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-10-29 14:12Authors
	 * @var  
	 * @return 
	 */

	public function index() {

		$EXAM = M('ExamSetup');
        $EXAMCOLLEGE = D('ExamCollege');

        $college = D('Adminer')->getCollege();
        $list = $EXAMCOLLEGE->getExamList($college);
        foreach ($list as $key => $value) {
        	$list[$key]['info'] = $EXAM->where(array('id' => $value['examid']))->find();
        }
        $this->assign('examList',$list);

        $this->display();
	}

	/**
	 * detail 模拟考试详细信息 提交人员详情
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-11-7 15:12Authors
	 * @var  
	 * @return 
	 */

	public function detail($id = 0) {

		$SUBMIT = M('ExamSubmit');
        $college = D('Adminer')->getCollege();

		$submitList = $SUBMIT->where(array('examid'=>$id))->select();

        $this->assign('export', 1);
		$this->assign('submitList',$submitList);
		$this->assign('id',$id);
		$this->display();
	}


	/**
	 * unSubmit 模拟考试详细信息 未提交人员详情
	 * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-11-7 15:50Authors
	 * @var  
	 * @return 
	 */

	public function unSubmit($id = 0) {

		$STUDENT = D('ExamSubmit');

		$unSubmitList = $STUDENT->getUnsubmitList($id);

		dump($unSubmitList);die;
        $this->assign('export', 0);
		$this->assign('submitList',$unSubmitList);
		$this->assign('id',$id);
		$this->display();
	}


    /**
     * 导出到excel
     * @author 陈伟昌<1339849378@qq.com>
	 * @copyright  2017-11-12 15:00Authors
	 * @var  
	 * @return 
     */
    public function export($type,$id) {

		$SUBMIT = D('ExamSubmit');

        // 查询条件
        $college = D('Adminer')->getCollege();
        $map = array();

        if (!is_null($college)) {
            $map['academy'] = $college;
        }

        $title = array( '姓名', '班级', '学号','正确数');
        $filename  = is_null($college) ? '浙江工商大学' : $college;

        if($type == 1) {
            $openid = $SUBMIT->where(array('examid'=>$id))->field('openid')->select();
            foreach ($openid as $key => $value) {
            	$list[$key]['name'] = getNameByOpenid($value['openid']);
            	$list[$key]['class'] = getClassByOpenid($value['openid']);
            	$list[$key]['number'] = getNumberByOpenid($value['openid']);
            	$list[$key]['result'] = getResult($value['openid']);
            }
            $filename .= '提交用户';
        } else {
            $openid = $SUBMIT->getUnsubmitList($id);
            foreach ($openid as $key => $value) {
            	$list[$key]['name'] = getNameByOpenid($value['openId']);
            	$list[$key]['class'] = getClassByOpenid($value['openId']);
            	$list[$key]['number'] = getNumberByOpenid($value['openId']);
            	$list[$key]['result'] = getResult($value['openId']);
            }
            $filename .= '未提交用户';
        }

        $this->excel($list, $title, $filename);
    }

    //导出成绩报表
    public function excel($arr=array(),$title=array(),$filename='export'){
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-type:application/vnd.ms-excel");  
        header("Content-Disposition:attachment;filename=".$filename.".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        //导出xls 开始
      
        if (!empty($title)){
            foreach ($title as $k => $v) {
                $title[$k]=iconv("UTF-8", "GB2312",$v);
            }
            $title= implode("\t", $title);
            echo "$title\n";
        }
        //查询数据库  $arr 是二维数组

        if(!empty($arr)){
            foreach($arr as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $arr[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
                }
                $arr[$key]=implode("\t", $arr[$key]);
            }
            echo implode("\n",$arr);
        }

        die;
        // 使用die是为了避免输出多余的模板html代码
    }



}