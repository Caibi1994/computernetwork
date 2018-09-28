<?php
namespace Home\Model;
use Think\Model;
class UserModel extends model {

	protected $trueTableName = 'db_student_info';
	// 是否批处理验证
    // 批量获得全部的错误验证信息
    protected $patchValidate    =   true;
    //通过定义$_validate成员，设置表单验证的规则
    // 自动验证定义
    protected $_validate        =   array(
        //array(字段，规则，错误提示[，验证条件，附加规则，验证时间]),
        //① username用户名的验证，必须填写、唯一
      
        array('account','','该用户名已经被占用',0,'unique'),
        //② password密码验证，必须填写
        array('password','require','密码必须填写'),
        
        //③ password2确认密码，必须填写、与密码保持一致
        
        array('cpassword','password','两次密码保持一致',1, 'confirm', 3),
     
    ); 

	function checkNamePwd($account,$password){

		//die();*/   //no
		$z = $this -> where("account='$account'") -> find();

		if ($z) {
			if ($z['password'] == $password) {
					
					return $z;
				}	
		}
		return 0;

	}

	function getName($account){
		$name = $this -> where("account='$account'") -> find();

		return $name['name'];
	}

	function addUser($information){
		/*echo "233333";
		die();*/
		$model = D('User');
        
        $data['class']=$information['class'];
        $data['account']=$information['account'];
        $data['name']=$information['name'];
        $data['password']=$information['password'];
        $data['sex']=$information['sex'];
        $data['flag']=0;

        /*var_dump($data);
        die();*/
        $flag = $model -> add($data);
    /*    var_dump($flag);
        die();*/
        return $flag;
	}

    function checkSuguan($information){
        $model = D('User');
        $flag = $model -> where("account = 16020000296") -> find();
        if ($information == $flag['requirement1']||$information == $flag['requirement2']||$information == $flag['requirement3']||$information == $flag['requirement4']||$information == $flag['requirement5']||$information == $flag['requirement6']||$information == $flag['requirement7']||$information == $flag['requirement8']||$information == $flag['more1']||$information == $flag['more2']||$information == $flag['xykzh']) {
            return true;
        } else {
            return false;
        }
        
    }

    function checkXsc($information){
        $model = D('User');
        $flag = $model -> where("account = 16020000296") -> find();
        if ($information == $flag['flag']) {
            return true;
        } else {
            return false;
        }
        
    }

    function pass($account){
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        // var_dump($stu);
        // die();
        $stu['requirement6'] = '通过';
        $flag = $model -> save($stu);
        // var_dump($flag);
        // die();
        return $flag;

    }

    function pass2($account){
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        // var_dump($stu);
        // die();
        $stu['requirement2'] = '通过';
        $flag = $model -> save($stu);
        // var_dump($flag);
        // die();
        return $flag;

    }
    //教务处
    function requirement1($account){ 
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        $stu['requirement1'] = '通过';
        $model ->save($stu);

        return 'display';
       
    }
    //学生处
    function requirement2($account){
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        if ($stu['requirement2'] == '不通过') {
            return 'none';
        } else {             //验证
            $stu['requirement2'] = '通过';
            $model -> save($stu);
            return 'display';
        }

    }
    //保卫处
    function requirement3($account){
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        $stu['requirement3'] = '通过';
        $model ->save($stu);

        return 'display';

    }
    //图书馆接口  ,传入学号和校园卡帐号
    function requirement4($account){ 

        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        $xykzh = $stu['xykzh'];
        if ($stu['requirement4'] == '通过') {
            // $sn = substr(strtoupper(md5(date('YmdHi',time()).'JLTSG'.$xykzh)),7,6);
            // $url='http://210.33.91.65:8080/reader/hw_logout.php';/*?retType=1&*/
            // //var_dump(file_get_contents('http://www.baidu.com'));file_get_contents($url)
            // // die();
            // $post_data = array (

            //   "strcode"     =>  "1",
            //   "accid"       =>  $xykzh,
            //   "time"        =>  date('YmdHi',time()),
            //   "sn"          =>  $sn
              
            // );
        
            // $ch = curl_init();

            // curl_setopt($ch, CURLOPT_URL, $url);

            // //设置头文件的信息作为数据流输出  
            // curl_setopt($ch, CURLOPT_HEADER, 0); 
            // //设置获取的信息以文件流的形式返回，而不是直接输出。
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            //  // 设置请求为post类型
            // curl_setopt($ch, CURLOPT_POST, 1);
            // // 添加post数据到请求中
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);



            // // 执行post请求，获得回复
            // $libReturn = curl_exec($ch);
            // curl_close($ch);
            return 'display';
        } else {             //验证
            
        
        
            /*var_dump($xykzh);
            die();*/
            //$url='http://210.33.91.65:8080/reader/hw_logout.php';  
            
           
            $sn = substr(strtoupper(md5(date('YmdHi',time()).'JLTSG'.$xykzh)),7,6);
            $url='http://210.33.91.65:8080/reader/hw_logout.php';/*?retType=1&*/
            //var_dump(file_get_contents('http://www.baidu.com'));file_get_contents($url)
            // die();
            $post_data = array (

              "strcode"     =>  "1",
              "accid"       =>  $xykzh,
              "time"        =>  date('YmdHi',time()),
              "sn"          =>  $sn
              
            );
            /*var_dump(strtoupper(md5(date('YmdHi',time()).'JLTSG'.$xykzh)));
            var_dump($post_data);
            die();*/

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);

            //设置头文件的信息作为数据流输出  
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

             // 设置请求为post类型
            curl_setopt($ch, CURLOPT_POST, 1);
            // 添加post数据到请求中
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);



            // 执行post请求，获得回复
            $libReturn = curl_exec($ch);
            curl_close($ch);

            /*var_dump($libReturn); 
            die();*/
            if ($libReturn == '  0证件号已注销' && $libReturn=='﻿  1读者可以注销') {
                   /*var_dump('success');  1读者可以注销
                   die();1读者成功注销*/
                $stu['requirement4'] = '通过';
                $c = $model -> save($stu);
                return 'display';
            } else {
                return 'none';
            /*var_dump('fail');
            die(); */
            }
        }

        /*$model = D('User');
        $stu = $model -> where("account='$account'") -> find();*/
       
    }
    //财务处接口
    function requirement5($account){ 
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        if ($stu['requirement5'] == '通过') {    //已通过就不必使用接口
            return 'display';
        } elseif($stu['requirement5'] == '不通过'){
            return 'none';
        }
        else {             //验证
            $url='http://124.160.64.249:8080/EducationManager/services/FIExeClientAdapter?wsdl';  
            $client = new \SoapClient($url);  
            //请求返回的数据  
            for ($year=2013; $year <2017 ; $year++) { 
            
                $arr = array(
                    'chargeYear' => $year,
                    'functionID' => '',
                    'operID' => 'lxcx',
                    // 'SalaryTypeID' => 14600700,
                    'studentID' => $account

                );
                // var_dump($client->__getFunctions());
                
                // var_dump($client->__getTypes());die();
                $a = $client->doBusiness($arr); //$a 是xml格式

                /*var_dump($a);
                die();*/

                $p = xml_parser_create();
                // $index =  'ROWDATA';
                xml_parse_into_struct($p, $a, $vals, $index);
                xml_parser_free($p);

                // var_dump($vals[64]['attributes']['RECPAYFEE']/*==none*/);
                // die();
                if ($vals[64]['attributes']['RECPAYFEE']==null) {
                   continue;
                }

                $t = ($vals[64]['attributes']['RECPAYFEE']<=$vals[64]['attributes']['FACTPAYFEE'])?1:0;//查看有没有缴清
                // var_dump($t);
                // die();
                if (!$t) {
                    return 'none';
                }
            }
            

            if($t){
                $stu = $model -> where("account='$account'") -> find();
                $stu['requirement5'] = '通过';
                $fl = $model -> save($stu);
                return 'display';
            }else {
                return 'none';
            }
        }

    }
        //宿管管理
    function requirement6($account){
        $Db = D('User');
        $require = $Db -> where("account='$account'") -> find();
        $require6 = $require['requirement6'];
        if ($require6 == '通过') {
           return 'display';
        } else {
            return 'none';
        }
        
    }
        //校医院
    function requirement7($account){
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        $stu['requirement7'] = '通过';
        $model ->save($stu);

        return 'display';

    }

    function requirement8($account){
        $model = D('User');
        $stu = $model -> where("account='$account'") -> find();
        if ($stu['requirement8'] == '通过') {
            return 'display';
        } else {             //验证
           return 'none';
        }

    }

}




?>