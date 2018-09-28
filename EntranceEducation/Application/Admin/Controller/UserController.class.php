<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {
    
    // 用户列表
    public function index(){

        $Student = M('StudentList');

        // 查询条件
        $college = D('Adminer')->getCollege();
        $map = array();

        if (!is_null($college)) {
            $map['academy'] = $college;
        }

        $map['type'] = 1;
        $list = $Student->where($map)->page($_GET['p'].',20')->select();
        $count = $Student->where($map)->count();
        
        $this->assign('userList',$list);

        $Page       = new \Think\Page($count,20);
        $show       = $Page->show();
        $this->assign('page', $show);

        // 注册数量和未注册数量和导出
        $num = D('StudentList')->getStudentNum();
        $this->assign('num', $num);
        $this->assign('export', 1);

       
        $this->display();
    }

    // 未注册名单，思路就是注册的时候去List表里的type设置为1，然后去List读取那些为0的用户
    public function unRegister() {

        // 查询条件
        $college = D('Adminer')->getCollege();
        $map['type'] = 0;

        if (!is_null($college)) {
            $map['academy'] = $college;
        }

        $list = M('StudentList')->where($map)->page($_GET['p'].',20')->select();
        $count = M('StudentList')->where($map)->count();

        $this->assign('userList',$list);

        $Page       = new \Think\Page($count,20);
        $show       = $Page->show();
        $this->assign('page', $show);

        // 注册数量和未注册数量
        $num = D('StudentList')->getStudentNum();
        $this->assign('num', $num);
        $this->assign('export', 0);

       
        $this->display('index');
        
    }

    
    public function export($type) {

        // 查询条件
        $college = D('Adminer')->getCollege();
        $map = array();

        if (!is_null($college)) {
            $map['academy'] = $college;
        }

        $title = array('序号','学院', '班级', '学号', '姓名');
        $filename  = is_null($college) ? '浙江工商大学' : $college;

        if($type == 1) {
            $map['type'] = 1;
            $list = M('StudentList')->where($map)->field('id,academy,class,number,name')->order('academy,class,number,id')->select();
            $filename .= '新生入学考试平台注册用户';
        } else {
            $map['type'] = 0;
            $list = M('StudentList')->where($map)->field('id,academy,class,number,name')->select();
            $filename .= '新生入学考试平台未注册用户';
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