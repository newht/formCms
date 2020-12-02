var ReadPhoPath = "http://photo.e21cn.com/";
var PhoSize = null, PhoWidth = "", PhoHeight = "";

function ReImgInputInfo() {
    if (document.getElementById("ShowImgUpload")) {
        document.getElementById("ShowImgUpload").innerHTML = "<input name='ImgUpload' type='file' id='ImgUpload' title='" + document.getElementById("ImgUpload").title + "' style='width:92%' onchange='onUploadImgChange(this,0)' />";
    }
}

function onUploadImgChange(sender, PhoTypes, subName, minLen, maxLen) {
    var imgSrc = null;

    if (sender.files != undefined) {
        PhoSize = parseInt(sender.files['0'].size / 1024);
    }

    //  图片尺寸
    switch (PhoTypes) {
        case 0:         //  照片
            if (!sender.value.match(/.jpg/i)) {
                $("#" + sender.id).val("");

                alert('图片格式无效！');
                return false;
            }
            if (!CI("文件路径", sender.value)) {
                $("#" + sender.id).val("");

                alert("照片文件名或所在文件夹路径错误！文件夹或文件名称只支持中文、数字、字母！");
                return false;
            }
            if (PhoSize != null) {
                if (PhoSize <= minLen || PhoSize >= maxLen) {
                    $("#" + sender.id).val("");

                    alert("照片尺寸在 " + minLen + " - " + maxLen + " kb 之间！\r\n当前照片为：" + PhoSize + " kb");
                    return false;
                }
            }

            PhoWidth = "120";
            PhoHeight = "150";
            break;

        case 1:         //  文件
            var filename = sender.value;
            var imgExtention = sender.value.substring(sender.value.length - 4).toLowerCase();

            if (!sender.value.match(/.jpg/i)) {
                $("#" + sender.id).val("");

                alert('文件格式无效！只支持 jpg 文件');
                return false;
            }
            if (!CI("文件路径", sender.value)) {
                $("#" + sender.id).val("");

                alert("照片文件名或所在文件夹路径错误！文件夹或文件名称只支持中文、数字、字母！");
                return false;
            }
            if (PhoSize != null) {
                if (PhoSize <= minLen || PhoSize >= maxLen) {
                    $("#" + sender.id).val("");

                    alert("文件尺寸在 " + minLen + " - " + maxLen + " kb 之间！\r\n当前文件为：" + PhoSize + " kb");
                    return false;
                }
            }

            switch (imgExtention) {
                case ".jpg":
                case ".jpeg":
                    PhoWidth = document.documentElement.clientWidth;
                    PhoWidth = PhoWidth * 0.3;
                    PhoHeight = "";
                    break;

                case ".pdf":
                    return;
            }
            break;

        default:
            //@@@   图片格式判断
            if (!sender.value.match(/.jpg|.gif|.png|.bmp/i)) {
                ReImgInputInfo();

                alert('图片格式无效！');
                return false;
            }
            if (!CI("文件路径", sender.value)) {
                ReImgInputInfo();

                alert("照片文件名错误！只支持中文、数字、字母！");
                return false;
            }
            if (PhoSize <= minLen || PhoSize >= maxLen) {
                ReImgInputInfo();

                alert("照片尺寸在 " + minLen + " - " + maxLen + " kb 之间！\r\n当前照片为：" + PhoSize + " kb");
                return false;
            }

            PhoWidth = 'auto';
            PhoHeight = 'auto';
            break;
    }

    var objPreview = document.getElementById('preview_' + subName);
    var objPreviewFake = document.getElementById('preview_fake_' + subName);

    if (sender.files && sender.files[0]) {
        objPreview.style.display = 'block';
        objPreview.style.width = PhoWidth + "px";

        if (PhoHeight != "") {
            objPreview.style.height = PhoHeight + "px";
        }

        try {
            objPreview.src = sender.files[0].getAsDataURL();
        }
        catch (e) {
            objPreview.src = window.URL.createObjectURL(sender.files[0]);
        }
    }
    else if (objPreviewFake.filters) {
        sender.select();
        sender.blur();

        imgSrc = document.selection.createRange().text;
        objPreviewFake.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = imgSrc;

        delete sender;
    }
}

function onPreviewLoad(sender) {
    autoSizePreview(sender, sender.offsetWidth, sender.offsetHeight);
}

function autoSizePreview(objPre, originalWidth, originalHeight, PhoTypes) {
    var zoomParam = "";

    switch (PhoTypes) {
        case 0:
            zoomParam = clacImgZoomParam(PhoWidth, PhoHeight, originalWidth, originalHeight);
            break;
        case 1:
            zoomParam = clacImgZoomParam(300, 300, originalWidth, originalHeight);
            break;
    }

    if (zoomParam != "") {
        objPre.style.width = zoomParam.width + 'px';
        objPre.style.height = zoomParam.height + 'px';
        objPre.style.marginTop = zoomParam.top + 'px';
        objPre.style.marginLeft = zoomParam.left + 'px';
    }
}

function CloseUpPho() {
    document.getElementById("ShowUpPho").style.display = "none";
    document.body.style.overflow = "auto";
}

$(function () {
    $('form').ajaxForm({
        success: function (responseText) {
            var Status = responseText.split('|')[0];
            var StatusInfo = responseText.split('|')[1];

            switch (Status) {
                case "E":
                    ReImgInputInfo();
                    alert(StatusInfo);
                    break;

                case "S":
                    document.getElementById("ShowUpPho").style.display = "none";
                    $("#main").attr("src", "bminfo");
                    break;
            }
        }
    });
});