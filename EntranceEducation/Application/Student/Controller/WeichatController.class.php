<?php

namespace Student\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Com\Jssdk; 


class WeichatController extends Controller{

    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = ''){
        //调试
        try{
            $appid     = 'wx913b2486f97088cb';
            $appsecret = '635f5e327e4c8c2f70744690d9a1e02a';
            $crypt = 'IFw7tLUcSrbyksiIJUUGEkyL2snSaiXL2arnGzGhXQj'; //消息加密KEY（EncodingAESKey）
            $token = 'weixin';
            
            /* 加载微信SDK */
            $wechat = new Wechat($token, $appid, $crypt);
            $user   = new UserController();
            /* 获取请求信息 */
            $data = $wechat->request();
            $userInfo = $user->getUserInfo($data['FromUserName']);
            $record = array(
                'openId' => $data['FromUserName'],
                'messageType' => 'formUser',
                'time'  => date('Y-m-d H:i:s',time()),
                );
            if(!empty($data['Content']))  $record['messageContent'] = $data['Content'];
            if(!empty($userInfo['name'])) $record['name'] = $userInfo['name'];
            if(!empty($userInfo['class'])) $record['class'] = $userInfo['class'];
            if(!empty($userInfo['number'])) $record['number'] = $userInfo['number'];
            M('weixin_message_record')->add($record);

            if($data && is_array($data)){
                file_put_contents('./data.json', json_encode($data));
                
                //判断是否注册
                
                if($user->isRegister($data['FromUserName']))
                    $this->user($wechat, $data);
                else 
                    $this->passer($wechat,$data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
        
    }

    /**
     * DEMO
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息，用户
     */
    private function user($wechat, $data){
        define('URL_ROOT', 'http://1111.classtest.sinaapp.com/index.php');
        define('PICURL_ROOT', 'http://classtest.sinaapp.com/Public/images/qqface/');
        $user             = new UserController();
        
        
        $userInfo         = $user->getUserInfo($data['FromUserName']);
        $welcome          = array("欢迎".$userInfo['name']."关注计算机网络平台",'',URL_ROOT."/Index/help",PICURL_ROOT."100.gif");
        //$connect          = array("联系我", "点击查看很多关于我的信息","http://www.sohu.com","");
        //$more             = array("教学信息","如果你想查看更多关于教学信息，请点击这里","http://mp.weixin.qq.com/s/pvVmTLOqqSarRs0Ou8ngKA","");
        //$myInfo           = array('发送1：我的信息','', URL_ROOT."/User/index/openId/".$data['FromUserName'],PICURL_ROOT.'myInfo.jpg');
        //$fileDownload     = array('发送2：资料下载','', "http://pan.baidu.com/s/1i4TeukX",PICURL_ROOT.'fileDownload.jpg');
        //$homework         = array('发送3：课后作业','', URL_ROOT."/Homework/index/openId/".$data['FromUserName'],PICURL_ROOT.'homework.jpg');
        //$signin           = array('发送4：在线签到','',URL_ROOT."/Signin/index/openId/".$data['FromUserName'],PICURL_ROOT.'signin.jpg');
        //$test             = array('发送5：随堂测试','',URL_ROOT."/Test/testList/openId/".$data['FromUserName'],PICURL_ROOT.'classtest.jpg');
        //$random           = array('发送6：自由练习','',"http://classtest.sinaapp.com/index.php/Random/index/openId/".$data['FromUserName'],PICURL_ROOT.'random.jpg');
        //$interation       = array('发送7：平台互动','', "http://classtest.sinaapp.com/index.php/Community/index/openId/".$data['FromUserName'],PICURL_ROOT.'interation.jpg');
        $teacher          = array('教师端入口', '点击进入教师端', URL_ROOT.'/Teacher/index/openId/'.$data['FromUserName'],'');
        
       
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyNews($welcome,$myInfo);
                        break;
                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    default:
                        $wechat->replyText("欢迎访问计算机网络教学在线平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;

            case Wechat::MSG_TYPE_TEXT:
                $schedule  = new EduController();
                $isTeacher = $user->isTeacher($data['FromUserName']);
                $mark      = M('student_mark')->where(array('openId' => $data['FromUserName']))->getField('lastMark');
                switch ($data['Content']) {
                    case '好吧':
                        $wechat->replyText(json_encode($data));
                        break;
                    case '我':
                       $wechat->replyNewsOnce('我的信息：','姓名：'.$userInfo['name']."\n班级：".$userInfo['class']."\n学号：".$userInfo['number']."\n积分：".$mark, URL_ROOT."/User/index/openId/".$data['FromUserName']); 
                        break;
                    case '1':
                       $wechat->replyNewsOnce('发送1：计算机网络',"计算机网络在线学习平台，让同学们更加方便愉快得学习。计算机爱网络，我们爱计算机网络。", "http://pan.baidu.com/s/1i4TeukX","http://classtest.sinaapp.com/Public/images/weixin/fileDownload.jpg"); 
                        break;
                    case '?':
                    case '？':
                        if($isTeacher)
                            // $wechat->replyNews($teacher,$more,$myInfo,$fileDownload,$homework,$signin,$test,$random,$interation);
                            $wechat->replyNews($myInfo, $teacher);
                        else
                            $wechat->replyNews($myInfo);
                        break;
                    default:
                        
                }
                break;
            
            default:
                # code...
                break;
        }
        $data_contents = $data['Content'];
        if (substr($data_contents, 0, 4) === 'lsdh') {
            $wechat->replyText($schedule->getTeaCon(substr($data_contents, 4)));
        }
    }

    /**
     * DEMO
     * 未注册处理函数,路人
     */
    private function passer($wechat, $data){
        define('URL_ROOT', 'http://1111.classtest.sinaapp.com/index.php');
        define('PICURL_ROOT', 'http://classtest.sinaapp.com/Public/images/weixin/');
        
        $welcome      = array("欢迎关注计算机网络教学互动平台","计算机网络教学互动平台是一款便利于师生教学计算机网络科目的产品。提供：课后作业、课堂签到、自由练习等功能。");

        $schedule     = new EduController();
        $user         = new UserController();
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyNews($welcome);
                        break;
                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;

                    default:
                        $wechat->replyText("欢迎访问计算机网络教学互动平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;

            case Wechat::MSG_TYPE_TEXT:
                switch ($data['Content']) {
                    case '0' :
                        $wechat->replyText("ss".$data['FromUserName']);
                        break;
                    case 't' :
                    case 'T' :
                        $wechat->replyText($user->teacherAddFromPasser($data['FromUserName']));
                        break;
                    case '1':
                    case '1':
                       $wechat->replyNewsOnce('发送1：计算机网络',"计算机网络在线学习平台，让同学们更加方便愉快得学习。计算机爱网络，我们爱计算机网络。", "http://pan.baidu.com/s/1i4TeukX","http://classtest.sinaapp.com/Public/images/weixin/fileDownload.jpg"); 
                        break;
                    case '2':
                       $wechat->replyNewsOnce("发送2：资料下载","这里有历年浙江工商大学计算机网络期末考试真题、历年数学考研真题资源下载、任课教师上课课件、课后习题答案等海量资源等你预览下载。", "http://pan.baidu.com/s/1i4TeukX","http://classtest.sinaapp.com/Public/images/weixin/fileDownload.jpg");
                        break;
                    case '?':
                    default:       
                        $wechat->replyNews();
                        break;
                }
                break;
            
            default:
                # code...
                break;
        }
    }




    /**
     * 资源文件上传方法
     * @param  string $type 上传的资源类型
     * @return string       媒体资源ID
     */
    private function upload($type){
        $appid     = 'wxb0c98aa1d0019242';

        $appsecret = '906e2e3803f51e05adbb382afb4e8176';

        $token = session("token");

        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }

        switch ($type) {
            case 'image':
                $filename = './Public/image.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'voice':
                $filename = './Public/voice.mp3';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'video':
                $filename    = './Public/video.mp4';
                $discription = array('title' => '视频标题', 'introduction' => '视频描述');
                $media       = $auth->materialAddMaterial($filename, $type, $discription);
                break;

            case 'thumb':
                $filename = './Public/music.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;
            
            default:
                return '';
        }

        if($media["errcode"] == 42001){ //access_token expired
            session("token", null);
            $this->upload($type);
        }

        return $media['media_id'];
    }


    public function getAccessToken(){
        $appid     = 'wxb0c98aa1d0019242';
        $appsecret = '906e2e3803f51e05adbb382afb4e8176';

        // $token = session("token");

        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $tokenArray = $auth->getAccessToken();
            $token = $tokenArray['access_token'];
        }

        return $token;
    }

    public function getJssdkPackage(){
        $appid     = 'wx913b2486f97088cb';
        $appsecret = '635f5e327e4c8c2f70744690d9a1e02a';

        $jssdk = new Jssdk($appid, $appsecret);
        return $jssdk->GetSignPackage();
    }

    /**
     * 获取信电官微的用户头像
     * 
     * @param string $openid 用户的openid
     * @return string $imgheadurl 用户头像url
     */
    public function getHeadimgurl ($openid = '') {

        $accessTokenArr = M('access_token')->find(1);
        $access_token   = $accessTokenArr['access_token'];
        $now_time = time();

        // 判断 access_token 是否过期

       if ($now_time - $accessTokenArr['time'] > 7000) {
            $appid        = 'wx1530ad1155dda9ad';
            $appsecret    = '3fea03b8dd35b465c31b1c37e659cb66';
            $wechatOauth  = new WechatAuth($appid, $appsecret);
            $tokenArray   = $wechatOauth->getAccessToken();
            $access_token = $tokenArray['access_token'];
            $data = array('access_token' =>$access_token, 'time' => time(), 'id' => 1);
            M('access_token')->where('id=1')->save($data);
        }


        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $info = json_decode(file_get_contents($url));

        $imgurl = $info->headimgurl;

        return $imgurl;
    }

    /**
     * 用于批量获取并保存用户头像
     * 
     * @param string $openid 用户的openid
     * @return string $imgheadurl 用户头像url
     */
    public function pel() {
        $data = D('student_info')->select();

        foreach ($data as $key => $value) {
            $imgurl = self::getHeadimgurl($value['openId']);
            p($imgurl);
            if (!empty($imgurl)) {
                $data['id'] = $value['id'];
                $data['headimgurl'] = $imgurl;
                D('student_info')->save($data);
            } else {
                $data['id'] = $value['id'];
                $data['headimgurl'] = '';
                D('student_info')->save($data);
            }
        }

       
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }
}
