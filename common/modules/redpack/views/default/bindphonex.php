<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');

?>
<?php $this->title = '绑定' ?>
<style>
.headimage-circle img {display:block; margin:0 auto;border-radius:100%;max-width:80px; max-height:80px}
.weui-cell {font-size: 15px;}
.weui-cell .weui-cell__ft {color:#999999;}

  .avatar_bg{
  }
  .avatar{
	margin-top:-20.8vw;
  }
  .avatar_bg img{
    display:block; margin:0 auto;height:21.6vw;width:21.6%;
  }
  .avatar img{
        display:block; margin:0 auto;height:20vw;width:20%;border-radius: 100%;
  }

  .input_error{
	border-color:red;
	box-shadow:0 0 1px rgba(255, 0, 0, 1), 0 0 5px rgba(255, 0, 0, 1);;
	outline:0 none;
  }

</style>

<div class="page">

    <div class="page__bd">

        <div class="weui-cells__title">
            <div style="margin-top:5.466667vw;margin-bottom:15.6vw;">
                <div class="avatar_bg">
                    <img src="<?php echo $module->baseUrl . '/img/market/woman/u185.png'; ?>" />
                </div>
                <div class="avatar">
                <img src="<?php echo $model->getHeadimgurl(64); ?>">
                </div>
            </div>
        </div>

        <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__hd"><label for="" class="weui-label">昵称</label></div>
                <div class="weui-cell__bd"><?php echo $model->nickname; ?>
                </div>
            </div>

            <div id="id-div-tel" class="weui-cell weui-cell_vcode">
                <div class="weui-cell__hd">
                    <label class="weui-label">手机号</label>
                </div>
                <div class="weui-cell__bd">
                    <input id="tel" class="weui-input" type="tel" placeholder="请输入手机号"/>
                </div>
                <div class="weui-cell__ft" id="id-div-tel-err"><i class="weui-icon-warn"></i></div>
                <div class="weui-cell__ft">
                    <button class="weui-vcode-btn" id="codeBtn">获取验证码</button>
                </div>
            </div>

            <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
                <div class="weui-cell__bd">
                    <input id="code" class="weui-input" type="number" placeholder="请输入验证码"/>
                </div>
            </div>
        </div>

        <label for="weuiAgree" class="weui-agree">
            <input id="weuiAgree" type="checkbox" class="weui-agree__checkbox"/>
            <span class="weui-agree__text">
			    已阅读并同意<a href="javascript:void(0);">《<?php echo $model->gh->title ?>会员条款》</a>
            </span>
        </label>

        <div class="weui-btn-area">
            <button class="weui-btn weui-btn_primary" href="javascript:" id="okBtn">确定</button>
        </div>
    </div>


</div>
<script type="text/javascript">

$("#weuiAgree").change( function() { 
    var agree = document.getElementById("weuiAgree").checked;
    if (agree) {
        $('#okBtn').removeAttr("disabled");
        $('#okBtn').addClass( "weui-btn_primary" );
    } else {
        $('#okBtn').attr("disabled", "disabled");
        $('#okBtn').removeClass( "weui-btn_primary" );
    }
}).change();

$("#id-div-tel-err").hide();

function vTele() {
	var obj = $("#tel");
	var value = obj.val();
	if(!(/^1\d{10}$/.test(value)))
	{
		obj.addClass("input_error");
        $("#id-div-tel").addClass( "weui-cell_warn");
        $("#id-div-tel-err").show();
		obj.focus();
		return false;
	}
	else
	{
		obj.removeClass("input_error");
        $("#id-div-tel").removeClass( "weui-cell_warn");
        $("#id-div-tel-err").hide();
		return true;
	}
}

function vCode() {
	var obj=$("#code");
	var value = obj.val();
    if (!value || !/\d{4}/.test(value))
	{
		obj.addClass("input_error");
		obj.focus();
		return false;
	}
	else
	{
		obj.removeClass("input_error");
		return true;
	}
}

var ajaxUrl = "<?= Url::to(['/redpack/default/ajax-broker']); ?>";

$(document).ready(function () {
    var smsVerify = function (mobileEle, verifyEle, verifyBtn) {
        var mobile = mobileEle.val();

        if (!vTele()) {
            weui.alert('无效的手机号码!');
            return false;
        }

        if ($(verifyBtn).hasClass('weui-btn_disabled')) {
            return false;
        }
        $(verifyBtn).addClass('weui-btn_disabled');

        $.ajax({
            url: '<?= Url::to(["/redpack/default/sms-ajax"], true) ?>',
            type: 'GET',
            data: 'mobile=' + mobile,
            dataType: 'json',
            cache: false,
            error: function (XHR, textStatus, errorThrown, err) {
                weui.alert('发送出错' + XHR.responseText);
                $(verifyBtn).removeClass('weui-btn_disabled');
            },
            success: function (data) {
                verifyEle.val('');
                if (data['code'] === 0) {
                    weui.toast("短信验证码已发送!");
                }
                else {
                    weui.toast(data['msg']);
                    $(verifyBtn).removeClass('weui-btn_disabled');
                }
            }
        });
    };

    $('#codeBtn').on('click', function (e) {
        smsVerify($('#tel'), $('#code'), this);
    });

    $("#okBtn").click(function () {
        var mobile = $('#tel').val();
        var verifycode = $('#code').val();
        if (!mobile || !/1[3|4|5|7|8]\d{9}/.test(mobile))
        {
            weui.toast("无效的手机号", 1000);
            return false;
        }

        if (!vCode()) {
            weui.toast("无效的验证码", 1000);
            return false;
        }

        var args = {
            'classname': '\\common\\models\\WxMember',
            'funcname': 'bindMobileAjax',
            'params': {
                'openid': '<?= $model->openid ?>',
                'mobile': mobile,
                'verifycode': verifycode
            }
        };

        $.ajax({
            url: ajaxUrl,
            type: "GET",
            cache: false,
            dataType: "json",
            data: "args=" + JSON.stringify(args),
            success: function (ret) {
                if (0 === ret['code']) {
                    location.href = "<?php echo Url::to(['/redpack/default/profile']) ?>";
                }
                else {
                    weui.alert(ret['msg']);                    
                }
            },
            error: function () {
                weui.alert('系统错误');
            }
        });


    });


});


</script>