<?php
namespace Admin\Controller;

use Think\Controller;
class LoginController extends Controller
{
    public function index()
    {
        $this->display();
    }

    public function checklogin()
    {
        $username = I('post.username');
        $password = I('post.password');
        $member = D('adminer');
        $result = $member->where("username='%s' AND password='%s'", $username, $password)->find();
        if ($result) {
            $_SESSION['username'] = $result['username'];
            $_SESSION['nickname'] = $result['nickname'];
            $_SESSION['type'] = $result['type'];
            $this->success('登陆成功', U('Index/index'), 3);
        } else {
            $this->error('登陆失败');
        }
    }
    
    public function logout()
    {
        session(null);
        $this->success('欢迎再来', U('/'), 3);
    }
}