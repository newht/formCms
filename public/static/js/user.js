var selSub = "", examid = "", u = "";

function init() {
    selSub = getUrlParam('ss');
    examid = getUrlParam('id');
    u = getUrlParam('u');

    switch (selSub) {
        case "3":
        case "5":
        case "6":
        case "7":
            SelSub(parseInt(selSub));
            break;
    }
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

function SelSub(aId) {
    switch (aId) {
        case 0:                 //  密码修改
            document.getElementById("yPwd").value = "";
            document.getElementById("Pwd").value = "";
            document.getElementById("qPwd").value = "";

            $('#bg_hide, #Div2').removeClass('hide');
            break;

        case 1:                 //  读取用户注册信息
            $.ajax({
                url: "../user/ActUserInfo.ashx", type: "POST",
                data: { ATypes: 3, t: new Date() },
                success: function (responseText) {
                    var Tmp = responseText.split('|');
                    if (Tmp.length > 1) {
                        switch (Tmp[0]) {
                            case "S":
                                console.log(Tmp);
                                document.getElementById("Id_Code").innerHTML = Tmp[1];
                                document.getElementById("Email").innerHTML = Tmp[2];
                                document.getElementById("Mobile").value = Tmp[3];

                                $('#bg_hide, #Div3').removeClass('hide');
                                break;
                            case "E":
                                alert(Tmp[1]);
                                break;
                        }
                    }
                    else {
                        alert(responseText);
                        return;
                    }
                }
            });
            break;

        case 2:             //  基本资料
            $.ajax({
                url: "../user/ActUserInfo.ashx", type: "POST",
                data: { ATypes: 5, t: new Date() },
                success: function (responseText) {
                    var Tmp = responseText.split('|');
                    if (Tmp.length > 1) {
                        switch (Tmp[0]) {
                            case "S":
                                document.getElementById("BId_Name").value = Tmp[1];
                                document.getElementById("BId_Code").value = Tmp[2];
                                document.getElementById("BMz").value = Tmp[3];
                                document.getElementById("BWork_Unit").value = Tmp[4];
                                document.getElementById("BOc").value = Tmp[5];
                                document.getElementById("BOe").value = Tmp[6];
                                document.getElementById("BAj").value = Tmp[7];
                                document.getElementById("BOg").value = Tmp[8];
                                document.getElementById("BOd").value = Tmp[9];
                                document.getElementById("BOo").value = Tmp[10];
                                document.getElementById("BMail").value = Tmp[11];
                                document.getElementById("BByyx").value = Tmp[12];
                                document.getElementById("BId_Graduat").value = Tmp[13];
                                document.getElementById("BWork_Years").value = Tmp[14];

                                $('#bg_hide, #Div4').removeClass('hide');
                                break;

                            case "E":
                                alert(Tmp[1]);
                                break;
                            default:
                                alert(Tmp[1]);
                                $(document).append(Tmp[0]);
                                break;
                        }
                    }
                    else {
                        alert(responseText);
                        return;
                    }
                }
            });
            break;
        case 3:
            $("#main").attr("src", "../bminfo");
            break;
        case 4:         //  查分申请状态
            $("#main").attr("src", "../appcheck/appcheckstatus.aspx?t=" + new Date());
            break;
        case 5:         //  身份验证
            $("#main").attr("src", "../user/usersf.aspx?t=" + new Date());
            break;
        case 6:         //  学历验证
            $("#main").attr("src", "../user/userxl.aspx?t=" + new Date());
            break;
        case 7:         //  学位验证
            $("#main").attr("src", "../user/userxw.aspx?t=" + new Date());
            break;
    }
}

function ResizDiv(vId) {
    var TmpWidth;
    TmpWidth = document.documentElement.clientWidth;
    if (TmpWidth == 0) {
        TmpWidth = document.body.clientWidth;
    }
    var Obj = "Div" + vId;
    var w = document.getElementById(Obj).style.width;
    w = w.replace("px", "");
    var InfoWinLeft = (TmpWidth - parseInt(w)) / 2;
    document.getElementById(Obj).style.pixelLeft = InfoWinLeft;

    for (var i = 1; i <= 4; i++) {
        Obj = "Div" + i;

        if (i == vId) {
            document.getElementById(Obj).style.display = "";
        }
        else {
            document.getElementById(Obj).style.display = "none";
        }
    }
}

function dyniframesize(down, tHeight) {
    var pTar = null;
    if (document.getElementById) {
        pTar = document.getElementById(down);
    }
    else {
        eval('pTar = ' + down + ';');
    }
    if (pTar && !window.opera) {
        pTar.style.display = "block";

        pTar.style.height = tHeight;
    }
}

function ToSavePwd(Obj) {
    if (!CheckAllFormInfo(Obj, 2, true, false, true)) {
        return false;
    }
    else {
        $.ajax({
            url: "../user/ActUserInfo.ashx", type: "POST",
            data: { ATypes: 2, YPwd: document.getElementById("yPwd").value, Pwd: document.getElementById("Pwd").value, t: new Date() },
            success: function (responseText) {
                var Tmp = responseText.split('|');
                if (Tmp.length == 2) {
                    alert(Tmp[1]);
                    switch (Tmp[0]) {
                        case "S":
                            $('#bg_hide, #Div2').addClass('hide');
                            break;
                        case "E":
                            break;
                    }
                }
                else {
                    alert(responseText);
                    return;
                }
            }
        });
    }
}

function ToSaveRegInfo(Obj) {
    if (!CheckAllFormInfo(Obj, 2, true, true, true)) {
        return false;
    }
    else {
        $.ajax({
            url: "../user/ActUserInfo.ashx", type: "POST",
            data: { ATypes: 4, Mobile: $.trim(document.getElementById("Mobile").value), t: new Date() },
            success: function (responseText) {
                var Tmp = responseText.split('|');
                if (Tmp.length == 2) {
                    switch (Tmp[0]) {
                        case "S":
                            alert("修改成功！");
                            $('#bg_hide, #Div3').addClass('hide');
                            break;
                        case "E":
                            alert(Tmp[1]);
                            break;
                    }
                }
                else {
                    alert(responseText);
                    return;
                }
            }
        });
    }
}

function ToSaveBaseInfo(Obj) {
    if (!CheckAllFormInfo(Obj, 1, false, true, true)) {
        return false;
    }
    else {
        $.ajax({
            url: "../user/ActUserInfo.ashx", type: "POST",
            data: {
                ATypes: 6,
                Id_Name: document.getElementById("BId_Name").value,
                Id_Code: document.getElementById("BId_Code").value,
                Mz: document.getElementById("BMz").value,
                Work_Unit: document.getElementById("BWork_Unit").value,
                Oc: document.getElementById("BOc").value,
                Oe: document.getElementById("BOe").value,
                Aj: document.getElementById("BAj").value,
                Og: document.getElementById("BOg").value,
                Od: document.getElementById("BOd").value,
                Oo: document.getElementById("BOo").value,
                Mail: document.getElementById("BMail").value,
                Byyx: document.getElementById("BByyx").value,
                Id_Graduat: document.getElementById("BId_Graduat").value,
                Work_Years: document.getElementById("BWork_Years").value,
                t: new Date()
            },
            success: function (responseText) {
                var Tmp = responseText.split('|');
                if (Tmp.length == 2) {
                    switch (Tmp[0]) {
                        case "S":
                            alert("修改成功！");
                            $('#bg_hide, #Div4').addClass('hide');
                            break;
                        case "E":
                            alert(Tmp[1]);
                            break;
                    }
                }
                else {
                    alert(responseText);
                    return;
                }
            }
        });
    }
}

function close($this) {
    $('#bg_hide').addClass('hide');
    $('.bg-box').addClass('hide');
}

init();