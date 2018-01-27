<?php
use yii\helpers\Url;

$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');

?>
<?php $this->title = '小孩作业，问卷调查，烦请支持' ?>
<style>
    .headimage-circle img {
        display: block;
        margin: 0 auto;
        border-radius: 100%;
        max-width: 80px;
        max-height: 80px
    }

    .weui-cell {
        font-size: 15px;
    }

    .weui-cell .weui-cell__ft {
        color: #999999;
    }

    .avatar_bg {
    }

    .avatar {
        margin-top: -20.8vw;
    }

    .avatar_bg img {
        display: block;
        margin: 0 auto;
        height: 21.6vw;
        width: 21.6%;
    }

    .avatar img {
        display: block;
        margin: 0 auto;
        height: 20vw;
        width: 20%;
        border-radius: 100%;
    }

    .input_error {
        border-color: red;
        box-shadow: 0 0 1px rgba(255, 0, 0, 1), 0 0 5px rgba(255, 0, 0, 1);;
        outline: 0 none;
    }

</style>

<form id="my_form">
<div class="page">
    <div class="page__bd">
        <div class="weui-cells__title">
            <h4>请有一定游戏爱好者填选（如非您本人 则烦请代填选 谢谢！）</h4>
        </div>


        <div class="weui-cells weui-cells_form">

            <div class="weui-cells__title">您的性别是</div>
            <div class="weui-cells weui-cells_radio">
                <label class="weui-cell weui-check__label" for="x11">
                    <div class="weui-cell__bd">
                        <p>男</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" class="weui-check" name="gender" value="0" id="x11" checked="checked" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>

                <label class="weui-cell weui-check__label" for="x12">
                    <div class="weui-cell__bd">
                        <p>女</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="gender" class="weui-check" value="1" id="x12" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
            </div>
        </div>

        <div class="weui-cells weui-cells_form">
            <div class="weui-cells__title">您处于以下哪个年龄段</div>
            <div class="weui-cells weui-cells_radio">
                <label class="weui-cell weui-check__label" for="x13">
                    <div class="weui-cell__bd">
                        <p>18岁及以下</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" class="weui-check" name="age" value="0" id="x13" checked="checked"/>
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="x14">
                    <div class="weui-cell__bd">
                        <p>19至22</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="age" class="weui-check" value="1" id="x14" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="x15">
                    <div class="weui-cell__bd">
                        <p>23至28</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="age" class="weui-check" value="2" id="x15" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="x16">
                    <div class="weui-cell__bd">
                        <p>29至36</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="age" class="weui-check" id="x16" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="x17">
                    <div class="weui-cell__bd">
                        <p>36以上</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="age" class="weui-check" id="x17" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
            </div>
        </div>

        <div class="weui-cells weui-cells_form">
            <div class="weui-cells__title">您在游戏上的月均投入</div>
            <div class="weui-cells weui-cells_radio">
                <label class="weui-cell weui-check__label" for="x18">
                    <div class="weui-cell__bd">
                        <p>几乎没有</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" class="weui-check" name="expense" id="x18" checked="checked" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="x19">
                    <div class="weui-cell__bd">
                        <p>25元及以下</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="expense" class="weui-check" id="x19" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="x20">
                    <div class="weui-cell__bd">
                        <p>25至200（含200）</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="expense" class="weui-check" id="x20" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="x21">
                    <div class="weui-cell__bd">
                        <p>200以上</p>
                    </div>
                    <div class="weui-cell__ft">
                        <input type="radio" name="expense" class="weui-check" id="x21" />
                        <span class="weui-icon-checked"></span>
                    </div>
                </label>
            </div>
        </div>

        <div class="weui-cells weui-cells_form">
            <div class="weui-cells__title">您玩的主要游戏类型是</div>
            <div class="weui-cells weui-cells_checkbox">
                <label class="weui-cell weui-check__label" for="s22">
                    <div class="weui-cell__hd">
                        <input type="checkbox" class="weui-check" name="type" id="s22" checked="checked"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>网页游戏（能够用电脑浏览器直接玩的游戏）</p>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="s23">
                    <div class="weui-cell__hd">
                        <input type="checkbox" name="type" class="weui-check" id="s23"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>客户端游戏（需要下载安装程序至电脑上才能使用的游戏）</p>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="s24">
                    <div class="weui-cell__hd">
                        <input type="checkbox" name="type" class="weui-check" id="s24"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>手机上玩的游戏</p>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="s25">
                    <div class="weui-cell__hd">
                        <input type="checkbox" name="type" class="weui-check" id="s25"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>游戏主机上玩的游戏（PS4,XBOX,PSP,SWITCH,VR等）</p>
                    </div>
                </label>

            </div>
        </div>

        <div class="weui-cells weui-cells_form">
            <div class="weui-cells__title">您觉得国内游戏最需要提升改进的问题是</div>
            <div class="weui-cells weui-cells_checkbox">
                <label class="weui-cell weui-check__label" for="s26">
                    <div class="weui-cell__hd">
                        <input type="checkbox" class="weui-check" name="problem" id="s26" checked="checked"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>娱乐性不足</p>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="s27">
                    <div class="weui-cell__hd">
                        <input type="checkbox" name="problem" class="weui-check" id="s27"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>品质（视觉，音效，流畅度等）不佳</p>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="s28">
                    <div class="weui-cell__hd">
                        <input type="checkbox" name="problem" class="weui-check" id="s28"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>游戏内容上层次、格调待提升</p>
                    </div>
                </label>
                <label class="weui-cell weui-check__label" for="s29">
                    <div class="weui-cell__hd">
                        <input type="checkbox" name="problem" class="weui-check" id="s29"/>
                        <i class="weui-icon-checked"></i>
                    </div>
                    <div class="weui-cell__bd">
                        <p>游戏的美感、艺术性不足</p>
                    </div>
                </label>

            </div>
        </div>


        <div class="weui-btn-area">
            <button class="weui-btn weui-btn_primary" href="javascript:" id="okBtn">提交问卷</button>
        </div>
    </div>


</div>
</form>
<script type="text/javascript">

    /*
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
     */

    var ajaxUrl = "<?= Url::to(['/redpack/default/ajax-broker']); ?>";

    $(document).ready(function () {
        /*
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
         */

        $("#okBtn").click(function () {
            /*
             //var mobile = $('#tel').val();
             //var verifycode = $('#code').val();
             if (!mobile || !/1[3|4|5|7|8]\d{9}/.test(mobile))
             {
             weui.toast("无效的手机号", 1000);
             return false;
             }

             if (!vCode()) {
             weui.toast("无效的验证码", 1000);
             return false;
             }
             */
            var gender = $('input:radio[name="gender"]:checked').val();
            var age = $('input:radio[name="age"]:checked').val();
            var expense = $('input:radio[name="expense"]:checked').val();
            alert(expense);

            weui.toast("无效的验证码", 1000);
            return false;

            var args = {
                'classname': '\\common\\models\\WxMember',
                'funcname': 'bindMobileAjax',
                'params': {
                    'gender': gender,

                    'openid': '<?php echo $model->openid ?>',
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