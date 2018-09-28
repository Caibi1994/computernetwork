/// <reference path="jquery-2.1.4.min.js" />
;var browser = {
    versions: function () {
        var u = navigator.userAgent, app = navigator.appVersion;
        return {//移动终端浏览器版本信息
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile/i) || !!u.match(/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
            iPhone: u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1, //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
        };
    }(),
    language: (navigator.browserLanguage || navigator.language).toLowerCase()
}
function ajaxOper(url, type, data, dataType, success, async) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType:dataType,
        success: success,
        async: async
    });
}

/*存cookie || 读cookie*/
function GetCookies(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
}

function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return "";
}
/*
*点赞，praiseType 1.帖子，2.评论
*/
function praise(itemId, obj, praiseType) {
    var fId = $("#hidfId").val();
    if ($('#hiduserId').val() == "" || $('#hiduserId').val() == "0") {
        if (!IsNullOrEmpty(fId)) {
            gowxOauthInfo(fId, praiseType + 'zan' + itemId);
        }
    }   
    $.ajax({
        type: "POST",
        url: "/ajax/InsertPraiseLog-" + fId,
        data: { ItemId: itemId, praisetype: praiseType, UId: $('#hiduserId').val(),fId:fId },
        dataType:"JSON",
        success: function (data) {
            if (data.code == 1) {
                vzanPostIM(data.uid, data.tuid, data.title, '', data.id, data.tid, fId, data.types, data.stypes);
                $('#' + obj).html(data.msg);
                $('#replybtn-' + praiseType + 'zan' + itemId).removeAttr("onclick");
                $('#replybtn-' + praiseType + 'zan' + itemId + ' i').attr("class", "zaned");
            }
        }
    });
}
function SignIn() {
    var fid=$("#hidfId").val();
    var uid = GetCookies("vzanuserinfo" + fid);
    if (!gowxOauthInfo(fid, "signin")) {
        $.ajax({
            type: "POST",
            url: "/ajax/SignIn-" + fid,
            data: { UserId: uid, MinisnsId: fid },
            dataType: "JSON",
            success: function (data) {
                if (data.code == 1) {
                    $('#replybtn-signin').empty().html('已签到').removeAttr("onclick");
                    Popup(1, data.msg);
                } else {
                    Popup(0, data.msg);
                }
            }
        });
    }
}
function shareSucceed() {
    if ($('#hiduserId').val() == "" || $('#hiduserId').val() == "0") {
        gowxOauthInfo($("#hidfId").val(), "");
        return false;
    }
    $.ajax({
        type: "POST",
        url: "/ajax/shareSucceed-1",
        data: { UId: $('#hiduserId').val(), minisnsId: $("#hidfId").val(), fromId: $("#hidArtId").val()},
        dataType:"text",
        success: function (data) {
           
        }
    });
}
function Popup(state, msg) {
    var img = "http://i.pengxun.cn/content/images/cg.jpg";
    if (state == "0") {
        img = "http://i.pengxun.cn/content/images/cw.gif";
    }
    layer.open({
        content: '<div style="height:30px;line-height:30px;color:#666">' +
                '<img src=\"' + img + '\" width="30" height="30" style="float:left" />' + msg + '</div>',
        style: ' border:none;',
        time: 1
    });
}
function GoHref(vUrl) {
    location.href = vUrl;
}
function showdiv(obj) {
    $("#" + obj).show();
}
function showOrHide(obj) {
    $('#' + obj).toggle();
}
function GetCookies(name, value, options) {
    if (typeof value != 'undefined') {
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else {
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
}
function layerClose() {
    layer.closeAll();
}
function isWeiXin() {
    var ua = window.navigator.userAgent.toLowerCase();
    if ((ua.match(/MicroMessenger/i) == 'micromessenger') || $("#checkIsWeiXin").val() == "1") {        
        return true;
    } else {
        return false;
    }
}
function showLoadingUI(vText) {
    layer.open({
        type: 2,
        content: vText
    })
}
function vzanShowTips(vtitle,vmsg) {
    layer.open({
        title: [
            vtitle,
            'background-color:red; color:#fff;'
        ],
        content: vmsg

    });
}
/*提交到消息中心
*uid 操作人Id
*tuid 接收人Id
*title 标题
*cts 内容
*id 帖子Id
*tid 互动Id
*siteid 论坛Id
*types 类型（发帖0，回帖1，点赞2，赞赏3）
*stypes(0帖子，1评论)
*/
function vzanPostIM(uid, tuid, title, cts, id, tid, siteid, types, stypes) {
    if (uid != tuid) {
        $.ajax({
            type: "POST",
            url: "/msg/rmsg",
            data: { uid: uid, tuid: tuid, cts: cts, id: id, tid: tid, siteid: siteid, types: types, stype: stypes, title: title },
            dataType: "json",
            success: function (data) {}
        });
    }        
}
function vzanPostIMDels(siteid, atid,tpid, types, uid) {
    $.ajax({
        type: "POST",
        url: "/msg/dels",
        data: { uid: uid, siteid: siteid, atid: atid, types: types, tpid: tpid },
        dataType: "json",
        success: function (data) { }
    });
}
function IsNullOrEmpty(str) {
    if (str == '' || str == null || str == undefined) {
        return true;
    } else {
        return false;
    }
}
function getimgsize(vurl, vsize) {
    if (vurl.indexOf('wx.qlogo') > -1) {
        return vurl.substring(0, vurl.lastIndexOf('/') + 1) + vsize;
    } else {
        return vurl;
    }
}