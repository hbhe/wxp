<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');

?>
<?php $this->params['bodyStyle'] = "background: linear-gradient(225deg, rgba(140, 180, 200, 1) 0%, rgba(140, 180, 200, 1) 0%, rgba(64, 120, 155, 1) 100%, rgba(64, 120, 155, 1) 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);" ?>
<?php $this->title = '绑定手机' ?>
  <style>
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
  .talk_word_div{
    display: block;
    width: auto;
	background:#ffffff;
	padding:10px 15px;
	font-size: 14px;
  }
  .talk_word_div .inputbox{
    width: auto;
	border-bottom:0px;
  }
  .bind_btn{
    color: #666666;
    font-style: normal;
    font-weight: 400;
    height: 44px;
    text-align: center;
    text-decoration: none;
    box-sizing: border-box;
    padding: 1px 0;
	font-size: 18px;
	width:71vw;
  }
  .input_error{
	border-color:red;
	box-shadow:0 0 1px rgba(255, 0, 0, 1), 0 0 5px rgba(255, 0, 0, 1);;
	outline:0 none;
  }
.weui-agree {
  display: block;
  padding: .5em 15px;
  font-size: 13px;
}
.weui-agree a {
  color: #586C94;
}
.weui-agree__text {
  color: #999999;
}
.weui-agree__checkbox {
  -webkit-appearance: none;
          appearance: none;
  outline: 0;
  font-size: 0;
  border: 1px solid #D1D1D1;
  background-color: #FFFFFF;
  border-radius: 3px;
  width: 13px;
  height: 13px;
  position: relative;
  vertical-align: 0;
  top: 2px;
}
.weui-agree__checkbox:checked:before {
  font-family: "weui";
  font-style: normal;
  font-weight: normal;
  font-variant: normal;
  text-transform: none;
  text-align: center;
  speak: none;
  display: inline-block;
  vertical-align: middle;
  text-decoration: inherit;
  content: "\EA08";
  color: #09BB07;
  font-size: 13px;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -48%) scale(0.73);
  -webkit-transform: translate(-50%, -48%) scale(0.73);
}
.weui-agree__checkbox:disabled {
  background-color: #E1E1E1;
}
.weui-agree__checkbox:disabled:before {
  color: #ADADAD;
}
</style>

    <div style="margin-top:5.466667vw;margin-bottom:15.6vw;">
		<div class="avatar_bg">
			<img src="<?php echo $module->baseUrl . '/img/market/woman/u185.png'; ?>" />
		</div>
		<div class="avatar">
        <img src="<?php echo $model->getHeadimgurl(64); ?>">
		</div>
	</div>
	
	<div style="padding:10px 30px;text-align: center;" class="talk_word_div">
		<table style="margin:0 auto;width:100%;">
			<tr>
				<td><input id="tel" class="inputbox" style="height:38px;width:100%;" placeholder="请输入11位手机号" type="text" value=""></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid #e6e6e6;">
					<input id="code" class="inputbox" style="float:left;width:120px;height:38px;" placeholder="请输入4位验证码" type="text" value=""">
					<input id="codeBtn" type="button" style="float: right; top: 5px;" class="weui_btn weui_btn_mini weui_btn_plain_default" value="获取验证码">
				</td>
			</tr>
		</table>
	</div>
	
	<label class="weui-agree" for="weuiAgree" style="margin:8.666667vw 15px;text-align: left;font-size: 14px;color:#E4E4E4;">
		<input type="checkbox" class="weui-agree__checkbox" id="weuiAgree" name="weuiAgree" checked>
		<span class="weui-agree__text">
			已阅读并同意<a href="javascript:void(0);">《<?php echo $model->gh->title ?>会员注册协议》</a>
		</span>
	</label>
	
	<div style="margin:0px 30px;text-align: left;">
		<button id="okBtn" style="width:100%;"class="bind_btn">绑定手机</button>
	</div>
	
<script type="text/javascript">

$("#weuiAgree").change( function() {      
    var agree = document.getElementById("weuiAgree").checked;
    if (agree)
    {
        $('#okBtn').removeAttr("disabled");
        // $('#okBtn').css("background-color", "#004b37");
        //$('#okBtn').addClass( "weui_btn_primary" );
    } else {
        $('#okBtn').attr("disabled", "disabled");
        //$('#okBtn').removeClass( "weui_btn_primary" );
    }
}).change();


function vTele()
{
	var obj=$("#tel");
	var value = obj.val();
	if(!(/^1\d{10}$/.test(value)))
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

function vCode()
{
	var obj=$("#code");
	var value = obj.val();
	//if(value.length!=4)
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

        if ($(verifyBtn).hasClass('weui_btn_disabled')) {
            return false;
        }
        $(verifyBtn).addClass('weui_btn_disabled');

        $.ajax({
            url: '<?= Url::to(["/redpack/default/sms-ajax"], true) ?>',
            type: 'GET',
            data: 'mobile=' + mobile,
            dataType: 'json',
            cache: false,
            error: function (XHR, textStatus, errorThrown, err) {
                //frmwebm.prompt(XHR.responseText, '提示');
                weui.alert('发送出错' + XHR.responseText);

			//	frmwebm.prompt({
			//		title: "提示",
			//		content: '发送出错'
			//	});

                $(verifyBtn).removeClass('weui_btn_disabled');
            },
            success: function (data) {
                verifyEle.val('');
                if (data['code'] === 0) {
                    weui.toast("短信验证码已发送!");
                }
                else {
                    weui.toast(data['msg']);
                    $(verifyBtn).removeClass('weui_btn_disabled');
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
                    //frmwebm.prompt({
                      //  title: "温馨提示",
                        //content: ret['msg'],
                        //reload: true
                    //});                        
                }
            },
            error: function () {
                weui.alert('系统错误');
            }
        });


    });


});




</script>


<?php
/*
	<div style="padding:10px 30px;text-align: center;" class="talk_word_div">
		<table style="margin:0 auto;width:100%;">
			<tr>
				<td><input id="tel" class="inputbox" style="height:38px;width:100%;" placeholder="请输入11位手机号" type="text" value="" onblur="vTele();"></td>
			</tr>
			<tr>
				<td style="border-top: 1px solid #e6e6e6;">
					<input id="code" class="inputbox" style="float:left;width:120px;height:38px;" placeholder="请输入4位验证码" type="text" value="" onblur="vCode();">
					<input id="codeBtn" type="button" onclick="gotoCode()" style="float: right; top: 5px;" class="weui_btn weui_btn_mini weui_btn_plain_default" value="获取验证码">
				</td>
			</tr>
		</table>
	</div>

                        //weui.alert(title_1 + ", 预订成功！")
//                            frmwebm.prompt(title + "预订成功！", "温馨提示");
                        //frmwebm.prompt(title + "预订成功！点击确定后直接进入抽奖，100%中奖哦！" , "温馨提示");

                        frmwebm.prompt("您的预定申请已成功提交, 后台将在24小时处理您的订单！<br>点击确定后即可参与摇奖，100%中奖哦！", "温馨提示", function () {
                            location.href = "<?php echo Url::to(['/wap/bindsucc']) ?>"
                        });

                        frmwebm.prompt({
                            title: "手机绑定成功",
                            content: '恭喜手机绑定成功!',
                            afterClose: "<?php echo Url::to(['/wap/bindsucc']) ?>"
                        });


*/