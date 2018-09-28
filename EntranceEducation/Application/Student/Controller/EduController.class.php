<?php 


namespace Student\Controller;
use Think\Controller;
use Curl\CurlAjax;

class EduController extends Controller{

    // 绑定信息
    public function register(){
        $openId = getOpenId();
        session('openId',$openId);
        if (M('edu_user')->where(array('openId'=>$openId))->find()) {
            $this->error('你已经绑定过了');
        }else{
            $this->display();   
        }
    }
    
    public function handRegister(){
       // if(!IS_AJAX) $this->error('你访问的界面不存在');
        $number = trim(I('number'));
        $password = trim(I('password'));
        $openId = session('openId');
        if(true === $this->check_login($number, $password)){
            $userInfo = $this->getUserInfo($number, $password);
            $userInfo = $userInfo['res'];
            $data = array(
                'openId' => $openId,
                'number' => $number,
                'password' => passport_encrypt($password) ,
            );


            $data = array_merge($data,$userInfo,array('time' => date('Y-m-d H:i:s')));
            if(D('EduUser')->add($data)){

                $stuInfo = array(
                    'openId' => $openId,
                    'name'   => $userInfo['name'],
                    'number' => $number,
                    'class'  => $userInfo['class'],
                    'time'   => date('Y-m-d H:i:s',time()),
                );
                if(false === D('StudentInfo')->isRegister($openId)){
                    if(D('StudentInfo')->add($stuInfo))
                        $this->success("绑定成功",'http://mp.weixin.qq.com/s/pvVmTLOqqSarRs0Ou8ngKA');
                    else
                        $this->error('添加到Stu表失败');    
                }else{
                    $this->success("绑定成功",'http://mp.weixin.qq.com/s/pvVmTLOqqSarRs0Ou8ngKA');    
                }
            }else{
                // $this->ajaxReturn(0);  //绑定失败
                $this->error("添加到User表失败");

            }
        }else{
            $this->error("你输入的账号密码不匹配");
            //$this->ajaxReturn(2);// 你输入的账号密码不匹配
        }
    }


    public function test(){
        $password = 'yanyi1993817102';
        $pass_password = passport_encrypt($password);
        p($password);
        p($pass_password);
    }
    

    // 获取学生信息
    // 变量：$number, $password,
    // 输出：$name, $class, $colloge
    public function getUserInfo($number='', $password=''){
        $url = 'http://139.196.4.153/eduapi/index.php/Home/Eduapi/getUserInfo/number/'.$number.'/password/'.$password;
        $get = file_get_contents($url);
        $arr = json_decode($get,true);
        return $arr;
    }


    // 获取学生课表 //周末里有课吗?
    // 变量：$number, $password, $year, $semester
    // 输出：code 0: 账号密码不正确; 3：没有注册
    public function getSchedule($openId){
        $USER = D('EduUser');
        if(false === $USER->isRegister($openId)){
            return '请你发送bd进行注册绑定';  //还没有注册绑定
        }
        $stuInfo = $USER->getUserInfo($openId);
        $number = $stuInfo['number'];  
        $password = $stuInfo['password'];
        $name = $stuInfo['name'];

        $url = 'http://139.196.4.153/eduapi/index.php/Home/Eduapi/getSchedule/number/'.$number.'/password/'.$password;
        $get = file_get_contents($url);
        $arr = json_decode($get,true);
        
        if (is_array($arr) && $arr['code'] === 1) {
            $schedule = $arr['res'];
            $string = $name."的课表："."\n";
            foreach ($schedule as $key => $value) {
                if($key !== '星期一')
                    $string .= "\n";
                $string .= "[".$key."]：\n";
                foreach ($value as $key => $v) {
                    if($key !== 0)
                        $string .= "\n";
                    $string .= "课程：".$v['kcmc']."\n"; //课程名称
                    $string .= "时间：".$v['jc']."(".$v['zcd'].")\n"; //上课地点
                    $string .= "地点：".$v['cdmc']."\n"; //上课地点
                    $string .= "教师：".$v['xm']."\n";   //上课教师 
                }

            }
            return $string;
        }
    }


    // 获取学生期末考试成绩
    // 变量 ：$year, $semeter, $number, $password
    // 输出：考试成绩
    public function getScore($openId){
        $USER = D('EduUser');
        if(false === $USER->isRegister($openId)){
            return '请你发送bd进行注册绑定';  //还没有注册绑定
        }
        $stuInfo = $USER->getUserInfo($openId);
        $number = $stuInfo['number'];  
        $password = $stuInfo['password'];
        $name = $stuInfo['name'];

        $url = 'http://139.196.4.153/eduapi/index.php/Home/Eduapi/getScore/number/'.$number.'/password/'.$password;
        $get = file_get_contents($url);
        $arr = json_decode($get,true);
        if (is_array($arr) && $arr['code'] === 1) {
            $items  = $arr['res'];
            $string = $name."的成绩："."\n";
            foreach ($items as $key => $v) {
                if($key !== 0)
                    $string .= "\n";
                $string .= "学科：".$v['kcmc']."\n"; 
                $string .= "学分：".$v['xf']."\n"; 
                $string .= "成绩：".$v['cj']."\n"; 
                $string .= "绩点：".$v['jd']."\n";   
            }
            return $string;
        }
    }


    // 获取学生期末考试安排
    // 变量 ：$year, $semeter, $number, $password
    // 输出：考试安排
    public function getExamArrange($openId){
        $USER = D('EduUser');
        if(false === $USER->isRegister($openId)){
            return '请你发送bd进行注册绑定';  //还没有注册绑定
        }
        $stuInfo = $USER->getUserInfo($openId);
        $number = $stuInfo['number'];  
        $password = $stuInfo['password'];
        $name = $stuInfo['name'];

        $url = 'http://139.196.4.153/eduapi/index.php/Home/Eduapi/getExamArrange/number/'.$number.'/password/'.$password;
        $get = file_get_contents($url);
        $arr = json_decode($get,true);
        if (is_array($arr) && $arr['code'] === 1) {
            $items  = $arr['res'];
            $string = $name."的本学期考试安排："."\n";
            foreach ($items as $key => $v) {
                if($key !== 0)
                    $string .= "\n";
                $string .= "考试名称：".$v['kcmc']."\n"; 
                $string .= "考试时间：".$v['kssj']."\n"; 
                $string .= "考试地点：".$v['cdmc']."\n"; 
            }
            return $string;
        }
    }

    
    // 获取学生期末考试安排
    // 变量 ：$year, $semeter, $number, $password
    // 检查账号密码是否匹配，保存sessionid 
    public function check_login($number, $password){
        $url = 'http://139.196.4.153/eduapi/index.php/Home/Eduapi/getUserInfo/number/'.$number.'/password/'.$password;
        $get = file_get_contents($url);
        $arr = json_decode($get,true);
        if($arr['code'] == 0){
            return false;
        }else{
            return true;
        }
    }


    public function curl_get($durl, $data=array(), $header=0){
        $logincookiejar = DATA_PATH.'\cookie.txt';
        $logincookiefile = DATA_PATH.'\cookie.txt';
        $t = parse_url($durl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL ,$durl);
        curl_setopt($ch, CURLOPT_TIMEOUT,5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_REFERER, "http://$t[host]/");
        curl_setopt($ch, CURLOPT_COOKIEFILE, $logincookiefile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $logincookiejar);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_ENCODING, 0); //gzip 解码
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER,$header);
        curl_setopt($ch,CURLOPT_AUTOREFERER,1);        
        if($data) {
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $r = curl_exec($ch);
        curl_close($ch);
        // 把header设置为1， 获取cookie
        if($header){
            preg_match('/Set-Cookie:(.*);/iU',$r,$str); //正则匹配
            $cookie = $str[1]; //获得COOKIE（SESSIONID）    
            return $cookie;
        }
        return $r;
    }


    /**
     *  功能：得到老师的联系方式
     *  @author 李俊君<hello_lijj@qq.com>
     *  时间 2016-11-26 20:11:34
     **/ 

    public function getTeaCon($name){

        if(!empty($name) ){
            $teaInfo = M('edu_tea_connection')->where(array('tea_name'=>$name))->find();
            if(!empty($teaInfo)) {
                $string  = '姓名：'.$teaInfo['tea_name']."\n";
                $string .= '学院：'.$teaInfo['college']."\n";
                $string .= '电话：'.$teaInfo['short_num'];
            } else {
                $string = '你输入的信息不存在';
            }
        }else{
            $string = '请规范输入信息';
        }
        return $string;
    }

    public function cet($openId){
        $EduCet  = D('EduCet');
        $StuInfo = D('StudentInfo');
        $openId  = getOpenId();
        session('openId',$openId);
        if(false === $EduCet->isBind($openId)){  //未绑定
            $StuInfo = $StuInfo->getStuInfo($openId);
            $this->assign('StuInfo', $StuInfo)->display();  
        }else{
            $cetInfo = $EduCet->getCetInfo($openId);
            $this->assign('cetInfo', $cetInfo)->display('cetShow');  
        }
    }

    public function saveCet(){
        $number = trim(I('number'));
        $name = trim(I('name'));
        $kao_number = trim(I('kao_number'));
        $openId = session('openId');
        $data = array(
            'openId'     => $openId,
            'name'       => $name,
            'number'     => $number,
            'kao_number' => $kao_number,
            'time'       => date('Y-m-d H:i:s')
        );
        if(D('EduCet')->add($data)){
            $this->success('操作成功',U('cetShow',array('openId'=>$openId)));
        }else{
            $this->error('操作失败');
        }
    }

    public function cetShow($openId=''){
        if(!$openId){
            $this->error('缺少参数');
        }
        $cetInfo = D('EduCet')->getCetInfo($openId);
        $this->assign('cetInfo', $cetInfo)->display();
    }


}