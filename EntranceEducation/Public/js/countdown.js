// 输入开始时间，考试时间
function countdown(endtime) {

	var EndTime= new Date(endtime*1000);
    // console.log(EndTime);
    var NowTime = new Date();

    var t = endtime*1000 - NowTime.getTime();

    var h=0;
    var m=0;
    var s=0;
    if(t>=0){
        h = Math.floor(t/1000/60/60%24);
        m = Math.floor(t/1000/60%60);
        s = Math.floor(t/1000%60);
    }

    if (h>0)
        $('title').html('模拟考试-'+h+'时'+m+'分'+s+'秒');
    else
        $('title').html('模拟考试-'+m+'分'+s+'秒');

}

// 学生做出选择后反馈给用户信息
function returnInfo(res) {
    console.log(res);
    if(res.is_on == 0) {
        $.alert("本次考试还未开启！", "提示");
        return;
    }
    
    // 已开启，已提交，已结束
    if(res.is_submit == 1 && res.is_end == 1) {
        $.alert("本次考试已经结束，你成功提交！", "提示");
        return;
    }
    // 考试倒计时已经结束
    if(res.time_end == 1 && res.is_submit == 0) {
        $.alert("本次考试时间已到，系统已自动为你保存答题记录，请退出！！", "提示");
        // var url = "{:U('User/index')}";
        // window.location="{:U('User/index',array('openId'=>session('openId')))}";
        return ;
    }
    // 已开启，未提交，已结束
    if(res.is_submit == 0 && res.is_end == 1) {
        $.alert("本次考试已经结束，但你没有参加，请联系管理员！！", "提示");
        // window.location.href="{:U('User/index',array('openId'=>session('openId')))}";
        return;
    }
  

}