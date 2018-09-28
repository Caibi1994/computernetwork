/// <reference path="jquery-2.1.4.min.js" />
function clickfaces(obj, inputbox, type) {
    if (type == "pc")
    {
        var facepath = $(obj).attr("src");
        var facecode ="<span style=\"display:none\">"+ $(obj).attr("code")+"</span>";
        if ($(inputbox).length > 0) {
            $(inputbox).append($(obj).removeAttr("onclick").removeAttr("code").prop('outerHTML') + facecode + "&nbsp");
            $('#popup-dialog').toggle();
        }
    }
    //评论回复框
    else if(type=="pc-reply"||type=="pc-reply-fixEditor")
    {
        var editor;
        var emotionContainer;
        if (type == "pc-reply-fixEditor") {
            editor = $("#ueditor_reply_fix");
            emotionContainer = $('.edui-dialog-container_reply_fix');
        }
        else {
            editor = $('#ueditor_reply');
            emotionContainer = $('.edui-dialog-container_reply');
        }
        var facepath = $(obj).attr("src");
        var facecode = "<span style=\"display:none\">" + $(obj).attr("code") + "</span>";
        if (editor.length > 0) {
            editor.append($(obj).removeAttr("onclick").removeAttr("code").prop('outerHTML') + facecode + "&nbsp");
            emotionContainer.toggle();
        }
    }
    else {
        var facecode = $(obj).attr("code");
        if ($(inputbox).length > 0) {
            var currentVal = $(inputbox).val();
            $(inputbox).val(currentVal + facecode);
        }
    }
}
function insertHtml(val) {
    var doc = $('#ueditor_replace'),
        frag = doc.createDocumentFragment();
    doc.each(function () {
        frag.appendChild(this);
    });
    doc.deleteContents();
    doc.insertNode(frag);
    doc.collapse(false);
    
}
function showloading() {
    layer.msg("正在处理,请稍后...");
    //layer.open({
    //    type: 1,
    //    title: false,
    //    closeBtn: false,
    //    shadeClose: false,
    //    area: ["300px", "80px"],
    //    content: '<div id="loading" style="line-height:35px;color:#999;font-size:17px;margin:10px;text-align:center"><img style="display:inline" src="http://i.pengxun.cn/content/images/loading.gif"/> 正在处理，请稍后...</div>',
    //    cancel: function (index) { return false; }
    //})
}
function loadfacehtml(type) {
    var _faces = new Array({ "url": "100.gif", "name": "微笑", "code": "/::)" }, { "url": "101.gif", "name": "撇嘴", "code": "/::~" }, { "url": "102.gif", "name": "色", "code": "/::B" }, { "url": "103.gif", "name": "发呆", "code": "/::|" }, { "url": "104.gif", "name": "得意", "code": "/:8-)" }, { "url": "105.gif", "name": "流泪", "code": "/::<" }, { "url": "106.gif", "name": "害羞", "code": "/::$" }, { "url": "107.gif", "name": "闭嘴", "code": "/::X" }, { "url": "108.gif", "name": "睡", "code": "/::Z" }, { "url": "109.gif", "name": "大哭", "code": "/::'(" }, { "url": "110.gif", "name": "尴尬", "code": "/::-|" }, { "url": "111.gif", "name": "发怒", "code": "/::@" }, { "url": "112.gif", "name": "调皮", "code": "/::P" }, { "url": "113.gif", "name": "呲牙", "code": "/::D" }, { "url": "114.gif", "name": "惊讶", "code": "/::O" }, { "url": "115.gif", "name": "难过", "code": "/::(" }, { "url": "116.gif", "name": "酷", "code": "/::+" }, { "url": "117.gif", "name": "冷汗", "code": "/:--b" }, { "url": "118.gif", "name": "抓狂", "code": "/::Q" }, { "url": "119.gif", "name": "吐", "code": "/::T" }, { "url": "120.gif", "name": "偷笑", "code": "/:,@P" }, { "url": "121.gif", "name": "可爱", "code": "/:,@-D" }, { "url": "122.gif", "name": "白眼", "code": "/::d" }, { "url": "123.gif", "name": "傲慢", "code": "/:,@o" }, { "url": "124.gif", "name": "饥饿", "code": "/::g" }, { "url": "125.gif", "name": "困", "code": "/:|-)" }, { "url": "126.gif", "name": "惊恐", "code": "/::!" }, { "url": "127.gif", "name": "流汗", "code": "/::L" }, { "url": "128.gif", "name": "憨笑", "code": "/::>" }, { "url": "129.gif", "name": "大兵", "code": "/::,@" }, { "url": "130.gif", "name": "奋斗", "code": "/:,@f" }, { "url": "131.gif", "name": "咒骂", "code": "/::-S" }, { "url": "132.gif", "name": "疑问", "code": "/:?" }, { "url": "133.gif", "name": "嘘", "code": "/:,@x" }, { "url": "134.gif", "name": "晕", "code": "/:,@@" }, { "url": "135.gif", "name": "折磨", "code": "/::8" }, { "url": "136.gif", "name": "哀", "code": "/:,@!" }, { "url": "137.gif", "name": "骷髅", "code": "/:!!!" }, { "url": "138.gif", "name": "敲打", "code": "/:xx" }, { "url": "139.gif", "name": "再见", "code": "/:bye" }, { "url": "140.gif", "name": "擦汗", "code": "/:wipe" }, { "url": "141.gif", "name": "抠鼻", "code": "/:dig" }, { "url": "142.gif", "name": "鼓掌", "code": "/:handclap" }, { "url": "143.gif", "name": "糗大了", "code": "/:&-(" }, { "url": "144.gif", "name": "坏笑", "code": "/:B-)" }, { "url": "145.gif", "name": "左哼哼", "code": "/:<@" }, { "url": "146.gif", "name": "右哼哼", "code": "/:@>" }, { "url": "147.gif", "name": "哈欠", "code": "/::-O" }, { "url": "148.gif", "name": "鄙视", "code": "/:>-|" }, { "url": "149.gif", "name": "委屈", "code": "/:P-(" }, { "url": "150.gif", "name": "快哭了", "code": "/::'|" }, { "url": "151.gif", "name": "阴险", "code": "/:X-)" }, { "url": "152.gif", "name": "亲亲", "code": "/::*" }, { "url": "153.gif", "name": "吓", "code": "/:@x" }, { "url": "154.gif", "name": "可怜", "code": "/:8*" }, { "url": "155.gif", "name": "菜刀", "code": "/:pd" }, { "url": "156.gif", "name": "西瓜", "code": "/:<W>" }, { "url": "157.gif", "name": "啤酒", "code": "/:beer" }, { "url": "158.gif", "name": "篮球", "code": "/:basketb" }, { "url": "159.gif", "name": "乒乓", "code": "/:oo" }, { "url": "160.gif", "name": "咖啡", "code": "/:coffee" }, { "url": "161.gif", "name": "饭", "code": "/:eat" }, { "url": "162.gif", "name": "猪头", "code": "/:pig" }, { "url": "163.gif", "name": "玫瑰", "code": "/:rose" }, { "url": "164.gif", "name": "凋谢", "code": "/:fade" }, { "url": "165.gif", "name": "示爱", "code": "/:showlove" }, { "url": "166.gif", "name": "爱心", "code": "/:heart" }, { "url": "167.gif", "name": "心碎", "code": "/:break" }, { "url": "168.gif", "name": "蛋糕", "code": "/:cake" }, { "url": "169.gif", "name": "闪电", "code": "/:li" }, { "url": "170.gif", "name": "炸弹", "code": "/:bome" }, { "url": "171.gif", "name": "刀", "code": "/:kn" }, { "url": "172.gif", "name": "足球", "code": "/:footb" }, { "url": "173.gif", "name": "瓢虫", "code": "/:ladybug" }, { "url": "174.gif", "name": "便便", "code": "/:shit" }, { "url": "175.gif", "name": "月亮", "code": "/:moon" }, { "url": "176.gif", "name": "太阳", "code": "/:sun" }, { "url": "177.gif", "name": "礼物", "code": "/:gift" }, { "url": "178.gif", "name": "拥抱", "code": "/:hug" }, { "url": "179.gif", "name": "强", "code": "/:strong" }, { "url": "180.gif", "name": "弱", "code": "/:weak" }, { "url": "181.gif", "name": "握手", "code": "/:share" }, { "url": "182.gif", "name": "胜利", "code": "/:v" }, { "url": "183.gif", "name": "抱拳", "code": "/:@)" }, { "url": "184.gif", "name": "勾引", "code": "/:jj" }, { "url": "185.gif", "name": "拳头", "code": "/:@@" }, { "url": "186.gif", "name": "差劲", "code": "/:bad" }, { "url": "187.gif", "name": "爱你", "code": "/:lvu" }, { "url": "188.gif", "name": "no", "code": "/:no" }, { "url": "189.gif", "name": "ok", "code": "/:ok" }, { "url": "190.gif", "name": "爱情", "code": "/:love" }, { "url": "191.gif", "name": "飞吻", "code": "/:<L>" }, { "url": "192.gif", "name": "跳跳", "code": "/:jump" }, { "url": "193.gif", "name": "发抖", "code": "/:shake" }, { "url": "194.gif", "name": "怄火", "code": "/:<O>" }, { "url": "195.gif", "name": "转圈", "code": "/:circle" }, { "url": "196.gif", "name": "磕头", "code": "/:kotow" }, { "url": "197.gif", "name": "回头", "code": "/:turn" }, { "url": "198.gif", "name": "跳绳", "code": "/:skip" }, { "url": "199.gif", "name": "挥手", "code": "/:oY" });
    var facepath = __public__ + '/images/qqface/';
    var _html = "";
    if (type == "pc" || type == "pc-reply" || type == "pc-reply-fixEditor")
    {
        _html = "<table><tr>";
        for (var i = 0; i < _faces.length; i++) {
            if (i % 15==0)
                _html += "</tr><tr>";
            else
                _html += '<td><img style="display:inline"  src="' + facepath + _faces[i].url + '" title="' + _faces[i].name + '" code="[' + _faces[i].name + ']" onclick=clickfaces(this,"#ueditor_replace","'+type+'") border="0" /><td>';
        }
        _html+= "</tr></table>";
    }
        //手机端使用
    else
    {
        for (var i = 0; i < _faces.length; i++) {
            _html += '<span><img src="' + facepath + _faces[i].url + '" title="' + _faces[i].name + '" code="[' + _faces[i].name + ']" onclick=clickfaces(this,"#txtContentAdd") border="0"></span>';
        }
    }
    return _html;
}