<?php
namespace Admin\Model;
use Think\Model;
class ExamSubmitModel extends Model {

    /**
     * getSubmitNum 获取提交人数
     * @author 陈伟昌<1339849378@qq.com>
     * @copyright  2017-10-29 14:28 Authors
     * @var  
     * @return  int
     */
    public function getSubmitNum($id) {

        $sql = "SELECT COUNT(*) FROM ee_exam_submit WHERE examid = '$id' ";

        $Model = new \Think\Model();
        $res = $Model->query($sql);

        if (empty($res)) {
            return 0;
        }
        return $res['0']['COUNT(*)'];
    }
    /**
     * getUnsubmitNum 获取未提交人数
     * @author 陈伟昌<1339849378@qq.com>
     * @copyright  2017-10-29 16:59 Authors
     * @var  
     * @return  int
     */
    public function getUnsubmitNum($id) {

        $Student = M('StudentList');

        $college = D('Adminer')->getCollege();
        $map = array();

        if (!is_null($college)) {
            $map['academy'] = $college;
        }

        $count = $Student->where($map)->count();


        $sql = "SELECT COUNT(*) FROM ee_exam_submit WHERE examid = '$id' ";

        $Model = new \Think\Model();
        $res = $Model->query($sql);

        if (empty($res)) {
            return 'error';
        }
        return $count-$res['0']['COUNT(*)'];
    }
    /**
     * getUnsubmitList 获取未提交名单
     * @author 陈伟昌<1339849378@qq.com>
     * @copyright  2017-11-7 16:00 Authors
     * @var  
     * @return  array('openid')
     */
    public function getUnsubmitList($id) {

        $Student = M('StudentList');
        $college = D('Adminer')->getCollege();
        $map = array();

        if (!is_null($college)) {
            $map['academy'] = $college;
            $sql = "SELECT openId FROM ee_student_info WHERE academy = $college AND NOT EXISTS (SELECT openid,examid FROM ee_exam_submit where examid = '$id' AND  ee_student_info.openId =ee_exam_submit.openid)";
        } else {
            $sql = "SELECT openId FROM ee_student_info WHERE NOT EXISTS (SELECT openid,examid FROM ee_exam_submit where examid = '$id' AND  ee_student_info.openId =ee_exam_submit.openid)";
        }

        $Model = new \Think\Model('student_info');
        $res = $Model->query($sql);
        
        return $res;
    }


}

// NOT EXISTS (SELECT openid,examid FROM ee_exam_submit where examid = '$id' AND  ee_student_info.openId =ee_exam_submit.openid)

// select openId from ee_student_info, ee_student_list where ee_student_list.number = ee_student_info.number AND openId not in (select openid from ee_exam_submit where examid = 1)


// $Model = new \Think\Model('student_info');
// $res = $Model->page($_GET['p'].',25')->query($sql);


