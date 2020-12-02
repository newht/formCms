var RInfo = "";
var PwdStr = "";
var XmStr = "";
var SfzhStr = "";
var Sfzh_Xb = "";
var Sfzh_Csrq = "";
var SubmitButton = "";

var IsMob = false;
var IeTypes = "";
var browser = {
    versions: function () {
        var u = navigator.userAgent, app = navigator.appVersion;
        return {
            mobile: u.indexOf('Mobile') > -1 //是否为移动终端
        };
    }()
}

IsMob = browser.versions.mobile;

//  说明：
//  FormName：表单名称
//  ShowTypes：显示类型 1：alert提示 2：提示框中提示
//  ChkDb：是否检查数据库
function WrAlertInfo(FormElementName, AlertInfo) {
    if (document.getElementById("r_" + FormElementName)) {
        document.getElementById(FormElementName).style.border = "1px solid #4db1d5";

        if (AlertInfo != "") {
            var ImgStr = "<img src='../images/due.png' style='width:15px; height:15px; vertical-align:middle' /> ";
            document.getElementById("r_" + FormElementName).innerHTML = ImgStr + AlertInfo;
        }
        else {
            document.getElementById("r_" + FormElementName).innerHTML = "";
        }
    }
}

function CheckAllFormInfo(FormName, ShowTypes, ChkDb, LblGetFocus, LblWrBorder) {
    var lblYes = true;
    var obj = FormName || event.srcElement;
    var count = obj.elements.length;
    var type = "", name = "";

    if (document.getElementById("btn")) {
        SubmitButton = document.getElementById("btn");
    }

    for (var i = 0; i < count; i++) {
        if (obj.elements[i].title != "") {
            RInfo = "";
            type = obj.elements[i].type.toLowerCase();
            name = obj.elements[i].name.toLowerCase();

            if (!CheckFormInfo(obj.elements[i].id, name, ShowTypes, ChkDb, LblGetFocus, LblWrBorder, type)) {
                return false;
            }
        }
    }
    delete obj;
    return true;
}

//  FormElementName：表单名称
//  ShowTypes：显示类型 1：alert提示 2：提示框中提示
//  ChkDb：  是否检查数据库
//  LblGetFocus：是否移到焦点
//  LblWrBorder：是否绘制边框
//  RInfo:  提示信息
//  type: 表单类型
function CheckFormInfo(FormElementName, name, ShowTypes, ChkDb, LblGetFocus, LblWrBorder, type) {
    var ChkTypes = "";
    var LblChk = false;
    var FFormElementName = "";
    var FormTitle = "";

    if (document.getElementById(FormElementName)) {
        FormTitle = document.getElementById(FormElementName).title;
    }

    var FormValue = "", newText = "";

    if (type == undefined) {

    }

    if ($.trim($("#" + FormElementName).attr("disabled"))) {
        return true;
    }

    RInfo = "";
    FormValue = $.trim($("#" + FormElementName).val());

    switch (FormTitle) {
        case "用户名":
            if (CheckSfzh(FormValue) == "") {
                LblChk = true;
            }
            else {
                ChkTypes = "用户名";
                if (!CI(ChkTypes, FormValue)) {
                    RInfo = "用户名填写错误！";
                    LblChk = false;
                }
                else {
                    LblChk = true;
                }
            }
            break;

        case "校验码":
        case "验证码":
            var c_start = "";
            var c_end = "";
            var c_name = "ValidateImage=";
            var CookieValue = "";

            if (FormValue.length != 6) {
                RInfo = "校验码填写错误！";
                LblChk = false;
            }
            else {
                LblChk = true;
            }
            break;

        case "姓名":
            LblChk = DefChkFun(FormTitle, FormElementName, FormValue);
            if (LblChk) {
                if (document.getElementById(FormElementName + "_1")) {
                    var Obj = document.getElementById(FormElementName + "_1");
                    if (Obj.value != "") {
                        if (Obj.value == FormValue) {
                            ActRInfo(true, FormElementName + "_1", ShowTypes, LblGetFocus, LblWrBorder);
                        }
                        else {
                            RInfo = "姓名确认错误";
                            ActRInfo(false, FormElementName + "_1", ShowTypes, LblGetFocus, LblWrBorder);
                        }
                    }
                }
                XmStr = FormValue;
            }
            break;

        case "身份证号":
        case "证件号":
            RInfo = CheckSfzh(FormValue);
            if (RInfo == "") {
                if (ChkDb) {
                    var LblTmpChk = CheckDb("Sfzh", FormValue);
                    if (LblTmpChk) {
                        RInfo = "身份证号已被注册！";
                        LblChk = false;
                    }
                    else {
                        LblChk = true;
                    }
                }
                else {
                    LblChk = true;
                }
            }
            else {
                LblChk = false;
            }

            if (LblChk) {
                if (LblChk) {
                    if (document.getElementById(FormElementName + "_1")) {
                        var Obj = document.getElementById(FormElementName + "_1");
                        if (Obj.value != "") {
                            if (Obj.value.toLowerCase() == FormValue.toLowerCase()) {
                                ActRInfo(true, FormElementName + "_1", ShowTypes, LblGetFocus, LblWrBorder);
                            }
                            else {
                                RInfo = "身份证号确认错误";
                                ActRInfo(false, FormElementName + "_1", ShowTypes, LblGetFocus, LblWrBorder);
                            }
                        }
                    }
                    SfzhStr = FormValue;
                }
            }
            break;

        case "电子邮件":
            LblChk = DefChkFun(FormTitle, FormElementName, FormValue);
            if (LblChk) {
                if (ChkDb) {
                    var LblTmpChk = CheckDb("Email", FormValue);
                    if (LblTmpChk) {
                        RInfo = "电子邮件已被注册！";
                        LblChk = false;
                    }
                    else {
                        LblChk = true;
                    }
                }
                else {
                    LblChk = true;
                }
            }
            break;

        case "原密码":
            LblChk = DefChkFun(FormTitle, FormElementName, FormValue);
            if (LblChk) {
                if (ChkDb) {
                    var LblTmpChk = CheckDb("Password", FormValue);
                    if (!LblTmpChk) {
                        RInfo = "原密码填写错误！";
                        LblChk = false;
                    }
                    else {
                        LblChk = true;
                    }
                }
            }
            break;

        case "密码":
            LblChk = DefChkFun(FormTitle, FormElementName, FormValue);
            if (LblChk) {
                PwdStr = FormValue;
            }

        case "密码确认":
            LblChk = DefChkFun(FormTitle, FormElementName, FormValue);
            if (LblChk) {
                if (FormValue != PwdStr) {
                    RInfo = "两次输入的密码不同！";
                    LblChk = false;
                }
            }
            break;

        case "姓名确认":
            LblChk = DefChkFun(FormTitle, FormElementName, FormValue);
            if (LblChk) {
                if (XmStr == "") {
                    FFormElementName = FormElementName.replace(/_1/g, "");
                    if (document.getElementById(FFormElementName)) {
                        XmStr = document.getElementById(FFormElementName).value;
                    }
                }

                if (FormValue != XmStr) {
                    RInfo = "姓名确认错误！";
                    LblChk = false;
                }
            }
            break;

        case "身份证号确认":
            LblChk = DefChkFun(FormTitle, FormElementName, FormValue);
            if (LblChk) {
                if (SfzhStr == "") {
                    FFormElementName = FormElementName.replace(/_1/g, "");
                    if (document.getElementById(FFormElementName)) {
                        SfzhStr = document.getElementById(FFormElementName).value;
                    }
                }

                if (FormValue.toLowerCase() != SfzhStr.toLowerCase()) {
                    RInfo = "身份证号确认错误！";
                    LblChk = false;
                }
                else {
                    RInfo = CheckSfzh(FormValue);
                }
            }
            break;

        default:
            //var newText = FormValue.replace(/\s+/g, "");
            newText = FormValue.replace(//g, "");

            switch (type) {
                case "file":
                //if (!IsSafeForm(newText)) {
                //    alert("照片路径含有非法字符，请修改后重新提交。");
                //    return false;
                //}

                case "textarea":
                    newText = SafeForm(newText);
                    FormValue = newText;

                    try {
                        $("#" + FormElementName).val(newText);
                    }
                    catch (e) {
                        $("[name='" + FormElementName + "']").html(newText);
                    }
                    break;

                default:
                    newText = SafeForm(newText);

                    if (document.getElementById(FormElementName)) {
                        document.getElementById(FormElementName).value = newText;
                    }
                    //$("#" + FormElementName).val(newText);
                    break;
            }

            LblChk = DefChkFun(FormTitle, FormElementName, name, FormValue);
            break;
    }

    ActRInfo(LblChk, FormElementName, ShowTypes, LblGetFocus, LblWrBorder);

    return LblChk;
}

function ActRInfo(LblChk, FormElementName, ShowTypes, LblGetFocus, LblWrBorder) {
    if (LblChk) {
        if (LblWrBorder) {
            if (browser.versions.mobile) {
                document.getElementById(FormElementName).style.border = "none";
            }
            else {
                document.getElementById(FormElementName).style.border = "1px solid #999999";
            }
        }
        else {
            document.getElementById(FormElementName).style.border = "none";
        }

        switch (ShowTypes) {
            case 1:
                break;
            case 2:
                if (document.getElementById("r_" + FormElementName)) {
                    var ImgStr = "<img src='../images/success.png' style='width:15px; height:16px; vertical-align:middle' /> ";
                    document.getElementById("r_" + FormElementName).innerHTML = ImgStr;
                }
                if (document.getElementById("q" + FormElementName)) {
                    document.getElementById("q" + FormElementName).disabled = false;
                }
                break;
        }
    }
    else {
        if (document.getElementById(FormElementName)) {
            document.getElementById(FormElementName).style.border = "#FF0000 1px solid";
        }

        switch (ShowTypes) {
            case 1:
                alert(RInfo);
                break;

            case 2:
                if (document.getElementById("r_" + FormElementName)) {
                    var ImgStr = "<img src='../images/fail.png' style='width:15px; height:15px; vertical-align:middle' /> ";

                    $("#r_" + FormElementName).html(ImgStr + RInfo);
                }
                if (document.getElementById("q" + FormElementName)) {
                    document.getElementById("q" + FormElementName).disabled = true;
                    document.getElementById("r_q" + FormElementName).innerHTML = "";
                }
                break;
        }

        if (LblGetFocus) {
            $("#" + FormElementName).focus();
        }
    }
}

function DefChkFun(FormTitle, FormElementName, name, FormValue) {
    var LblChk = true, LblChk_ = false, cols = 0, id = 0;

    if (FormValue != "") {
        FormValue = $.trim($("#" + FormElementName).val());
    }

    switch (FormTitle) {
        case "密码":
        case "密码确认":
        case "原密码":
            if (FormValue.length < 6 || FormValue.length > 16) {
                RInfo = "密码6-16个字符！";
                LblChk = false;
            }
            break;

        default:
            if (FormValue.length == 0) {
                //if (FormTitle.substring(0, 7) == "MulRows") {
                //    cols = FormTitle.substring(8, 9);
                //    id = FormElementName.substring(0, FormElementName.length - 2);

                //    for (var j = 0; j < cols; j++) {
                //        if ($("#" + id + "_" + j).length > 0) {
                //            if($.trim($("#" + id + "_" + j).val()) != "") {
                //                LblChk_ = false;
                //                break;
                //            }
                //        }
                //    }

                //    $("input[name='" + name + "']").each(function () {
                //        if ($(this).val() != "") {
                //            LblChk_ = true;

                //            return false;
                //        }
                //    });

                //    if (LblChk_) {
                //        return true;
                //    }
                //}

                RInfo = FormTitle + "不能为空！";
                LblChk = false;
            }
            break;
    }

    if (LblChk) {
        if (!CI(FormTitle, FormValue)) {
            RInfo = FormTitle + "填写错误！";
            LblChk = false;
        }
        else {
            LblChk = true;
        }
    }

    return LblChk;
}

function CI(FormTitle, ChkValue) {
    var ChkStatus = false;
    var LitStr = GetValidator(FormTitle);

    if (LitStr != "") {
        var re = new RegExp(LitStr);
        if (!re.test(ChkValue)) {
            ChkStatus = false;
        }
        else {
            ChkStatus = true;
        }
        delete re;

        return ChkStatus;
    }
    else {
        return true;
    }
}

function GetValidator(FormTitle) {
    var TStr = "";

    //if (FormTitle.indexOf("电话") >= 0 || FormTitle.indexOf("手机") >= 0) {
    //    if (FormTitle != "固定电话") { TStr = "电话"; }
    //}
    //else {
    //    TStr = FormTitle;
    //}

    TStr = FormTitle;

    switch (TStr) {
        case "密码":
        case "密码确认":
        case "原密码":
            return /^[0-9a-zA-Z`~!@#$%\^&*()_+-={}|\[\]<>?,.]{6,20}$/;

        case "电子邮件":
            return /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

        case "电话":
        case "手机号码":
            return /^[1][3456789]\d{9}$/;

        case "问题答案":
            return /^[\u0391-\uFFE50-9a-zA-Z]+$/;

        case "文件路径":
            return /^[\u0391-\uFFE50-9a-zA-Z:_@!-.\\]+$/;

        case "中文":
            return /^[\u0391-\uFFE5]+$/;

        case "姓名":
        case "xm":
        case "id_name":
        case "idname":
            return /^[\u0391-\uFFE5a-zA-Z.·]+$/;

        case "用户名":
            return /^[a-zA-Z_][a-zA-Z0-9_]{2,16}$/;

        default:
            return "";
    }
}

function CheckSfzh(CheckSubValue) {
    var date, Ai, xb;
    var verify = "10x98765432";
    var Wi = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
    var area = ['', '', '', '', '', '', '', '', '', '', '', '北京', '天津', '河北', '山西', '内蒙古', '', '', '', '', '', '辽宁', '吉林', '黑龙江', '', '', '', '', '', '', '', '上海', '江苏', '浙江', '安微', '福建', '江西', '山东', '', '', '', '河南', '湖北', '湖南', '广东', '广西', '海南', '', '', '', '重庆', '四川', '贵州', '云南', '西藏', '', '', '', '', '', '', '陕西', '甘肃', '青海', '宁夏', '新疆', '', '', '', '', '', '台湾', '', '', '', '', '', '', '', '', '', '香港', '澳门', '', '', '', '', '', '', '', '', '国外'];
    var re = CheckSubValue.match(/^(\d{2})\d{4}(((\d{2})(\d{2})(\d{2})(\d{3}))|((\d{4})(\d{2})(\d{2})(\d{3}[x\d])))$/i);

    if (re == null) return "身份证号错误";
    if (re[1] >= area.length || area[re[1]] == "") return "身份证号填写错误";
    if (re[2].length == 12) {
        Ai = CheckSubValue.substr(0, 17);
        date = [re[9], re[10], re[11]].join("-");

        xb = CheckSubValue.substr(16, 1);
        if (xb % 2 == 0) {
            Sfzh_Xb = "女";
        }
        else {
            Sfzh_Xb = "男";
        }
    }
    else {
        Ai = CheckSubValue.substr(0, 6) + "19" + CheckSubValue.substr(6);
        date = ["19" + re[4], re[5], re[6]].join("-");
    }
    if (!this.IsDate(date, "ymd")) {
        return "身份证号填写错误";
    }
    else {
        Sfzh_Csrq = date;
    }
    var sum = 0;
    for (var i = 0; i <= 16; i++) {
        sum += Ai.charAt(i) * Wi[i];
    }
    Ai += verify.charAt(sum % 11);
    if (CheckSubValue.toLowerCase() != Ai.toLowerCase()) {
        return "身份证号填写错误";
    }
    else {
        return "";
    }
}
function IsDate(op, formatString) {
    formatString = formatString || "ymd";
    var m, year, month, day;
    switch (formatString) {
        case "ymd":
            m = op.match(new RegExp("^((\\d{4})|(\\d{2}))([-./])(\\d{1,2})\\4(\\d{1,2})$"));
            if (m == null) return false;
            day = m[6];
            month = m[5] * 1;
            year = (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
            break;
        case "dmy":
            m = op.match(new RegExp("^(\\d{1,2})([-./])(\\d{1,2})\\2((\\d{4})|(\\d{2}))$"));
            if (m == null) return false;
            day = m[1];
            month = m[3] * 1;
            year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10));
            break;
        default:
            break;
    }

    if (!parseInt(month)) return false;
    month = month == 0 ? 12 : month;
    var date = new Date(year, month - 1, day);
    return (typeof (date) == "object" && year == date.getFullYear() && month == (date.getMonth() + 1) && day == date.getDate());
}

function CheckDb(SubName, SubValue) {
    var LblChk;
    var options = {
        url: "../user/ActUserInfo.ashx",
        type: "POST",
        data: { ATypes: 1, SubName: SubName, SubValue: SubValue },
        async: false,
        success: function (responseText) {
            switch (responseText) {
                case "1":
                    LblChk = true;
                    break;
                case "0":
                    LblChk = false;
                    break;
            }
        }
    };
    $.ajax(options);

    return LblChk;
}

function SafeForm(Str) {
    var newText = Str.replace(/&#61623;/g, " ");
    newText = newText.replace(/&#61694;/g, " ");
    newText = newText.replace(/;/g, "；");
    newText = newText.replace(/</g, "《");
    newText = newText.replace(/>/g, "》");
    newText = newText.replace(/&/g, "");
    newText = newText.replace(/#/g, "");

    return newText;
}

function IsSafeForm(Str) {
    if (Str.indexOf('#') >= 0) { return false; }
    if (Str.indexOf('&') >= 0) { return false; }
    if (Str.indexOf('<') >= 0) { return false; }
    if (Str.indexOf('>') >= 0) { return false; }
    if (Str.indexOf(';') >= 0) { return false; }

    return true;
}