<?php
namespace Student\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){

        $model      =   D('student_info');
        $name       =   session('name');
        $openId     =   session('openId');

        if(empty($openId)){
        	$this->redirect('User/index');
        }
       
		$this->display();
    }

	public function login (){
        if (cookie('name')!= null) {
            $this -> assign('name',$name);
            session('name',cookie('name'));
            session('openId',cookie('openId'));
            $this -> redirect('Index/index');
        } else {
            $this->display();
        }       
            
    }


    public function check(){

        $model = D('User');
        /*var_dump($model);
        die();*/
        $information = I('post.');

        // var_dump($information['u']);
        // die();
        $info = $model -> checkNamePwd($information['u'],$information['p']);
        /*var_dump($info);
        die();*/
        if (!$info) {
            /*echo "333";
            die();*/   //ok
            /*$this->redirect('Index/login');*/
            $this->error('帐号或密码错误','login',1);  
        }else{
            /*$this->redirect('Index/index');*/

            $name = $model -> getName($information['u']);

            cookie('name',$name,86400);
            cookie('openId',$information['u'],86400);

            session('name',$name);
            session('openId',$information['u']);

            $this -> assign('name',$name);
            $flag = $info['flag'];
            /*var_dump($flag);
            die();*/
            // if ($flag) {
                // $this -> display('Index/admin');
            // } else {
                $this -> redirect('Index/index');
            // }
        }


      
    }


    /**
     * main 项目主页面
     * @author 李俊君<hello_lijj@qq.com>
     * @copyright  2017-9-10 9:39Authors
     * @var  
     * @return 
     */
    public function main() {
        $weixin       = new WeichatController();
        $signPackage  = $weixin->getJssdkPackage();
        // var_dump($signPackage);
        // die();
        $this->assign('signPackage',$signPackage);
        // $this->assign('AAA','23333');
        $this->display();
        
    }
    
}