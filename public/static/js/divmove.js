var _move = false; //移动标记
var _move1 = false;
var _move2 = false;
var _move3 = false;

var _x, _y, _y1; //鼠标离控件左上角的相对位置

$(document).ready(function () {
    $("#UpPhoTools").click(function () {
    }).mousedown(function (e) {
        _move = true;
        _x = e.pageX - parseInt(GetNum(document.getElementById("Div1").style.pixelLeft));
        _y = e.pageY - parseInt(GetNum(document.getElementById("Div1").style.pixelTop));
    });

    $(document).mousemove(function (e) {
        if (_move) {
            var x = e.pageX - _x;                       //移动时根据鼠标位置计算控件左上角的绝对位置
            var y = e.pageY - _y;

            var h = parseInt(GetNum(document.getElementById("Div1").style.height)) + 3;
            var w = parseInt(GetNum(document.getElementById("Div1").style.width)) + 3;

            if (x > 0 && y > 0 && x < $(window).width() - w - $(document).scrollLeft() && y < $(window).height() - h - $(document).scrollTop()) {
                $("#Div1").css({ top: y, left: x }); //控件新位置
            }
        }
    }).mouseup(function () {
        if (document.getElementById("Div1").style.display != "none") {
            _move = false;
        }
    });

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $("#EditPwdTools").click(function () {
    }).mousedown(function (e) {
        _move = true;
        _x = e.pageX - parseInt(GetNum(document.getElementById("Div2").style.pixelLeft));
        _y = e.pageY - parseInt(GetNum(document.getElementById("Div2").style.pixelTop));
    });

    $(document).mousemove(function (e) {
        if (_move) {
            var x = e.pageX - _x;                       //移动时根据鼠标位置计算控件左上角的绝对位置
            var y = e.pageY - _y;

            var h = parseInt(GetNum(document.getElementById("Div2").style.height)) + 3;
            var w = parseInt(GetNum(document.getElementById("Div2").style.width)) + 3;

            if (x > 0 && y > 0 && x < $(window).width() - w - $(document).scrollLeft() && y < $(window).height() - h - $(document).scrollTop()) {
                $("#Div2").css({ top: y, left: x }); //控件新位置
            }
        }
    }).mouseup(function () {
        if (document.getElementById("Div2").style.display != "none") {
            _move = false;
        }
    });

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $("#RegInfoTools").click(function () {
    }).mousedown(function (e) {
        _move = true;
        _x = e.pageX - parseInt(GetNum(document.getElementById("Div3").style.pixelLeft));
        _y = e.pageY - parseInt(GetNum(document.getElementById("Div3").style.pixelTop));
    });

    $(document).mousemove(function (e) {
        if (_move) {
            var x = e.pageX - _x;                       //移动时根据鼠标位置计算控件左上角的绝对位置
            var y = e.pageY - _y;

            var h = parseInt(GetNum(document.getElementById("Div3").style.height)) + 3;
            var w = parseInt(GetNum(document.getElementById("Div3").style.width)) + 3;

            if (x > 0 && y > 0 && x < $(window).width() - w - $(document).scrollLeft() && y < $(window).height() - h - $(document).scrollTop()) {
                $("#Div3").css({ top: y, left: x }); //控件新位置
            }
        }
    }).mouseup(function () {
        if (document.getElementById("Div3").style.display != "none") {
            _move = false;
        }
    });

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    $("#BaseInfoTools").click(function () {
    }).mousedown(function (e) {
        _move = true;
        _x = e.pageX - parseInt(GetNum(document.getElementById("Div4").style.pixelLeft));
        _y = e.pageY - parseInt(GetNum(document.getElementById("Div4").style.pixelTop));
    });

    $(document).mousemove(function (e) {
        if (_move) {
            var x = e.pageX - _x;                       //移动时根据鼠标位置计算控件左上角的绝对位置
            var y = e.pageY - _y;

            var h = parseInt(GetNum(document.getElementById("Div4").style.height)) + 3;
            var w = parseInt(GetNum(document.getElementById("Div4").style.width)) + 3;

            if (x > 0 && y > 0 && x < $(window).width() - w - $(document).scrollLeft() && y < $(window).height() - h - $(document).scrollTop()) {
                $("#Div4").css({ top: y, left: x }); //控件新位置
            }
        }
    }).mouseup(function () {
        if (document.getElementById("Div4").style.display != "none") {
            _move = false;
        }
    });
});

function GetNum(Str) {
    if (isNaN(Str)) {
        Str = Str.replace("px", "");
    }
    return Str;
}