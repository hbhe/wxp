<?php
use common\modules\redpack\models\Vote;
use yii\helpers\Url;

$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');

?>
<?php $this->title = '课程要求 游戏问卷调查 烦请支持' ?>
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

    .input_error {
        border-color: red;
        box-shadow: 0 0 1px rgba(255, 0, 0, 1), 0 0 5px rgba(255, 0, 0, 1);;
        outline: 0 none;
    }

</style>


    <div class="page">
        <div class="page__bd">
            <div class="weui-cells__title">
                <h4>请有一定游戏爱好者填选（如非您本人 则烦请代填选 谢谢！）</h4>
            </div>


            <div class="weui-cells weui-cells_form">
                <div class="weui-cells__title">您的性别是</div>
                <div class="weui-cells weui-cells_radio">
                    <?php foreach (Vote::getGenderOptionName() as $value => $label): ?>
                        <label class="weui-cell weui-check__label" for="<?= 'id-gender-' . $value ?>">
                            <div class="weui-cell__bd">
                                <p><?= $label ?></p>
                            </div>
                            <div class="weui-cell__ft">
                                <input type="radio" class="weui-check" name="gender" value="<?= $value ?>" id="<?= 'id-gender-' . $value ?>" <?php if ($value == 0): ?>checked="checked" <?php endif; ?> />
                                <span class="weui-icon-checked"></span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="weui-cells weui-cells_form">
                <div class="weui-cells__title">您处于以下哪个年龄段</div>
                <div class="weui-cells weui-cells_radio">
                    <?php foreach (Vote::getAgeOptionName() as $value => $label): ?>
                        <label class="weui-cell weui-check__label" for="<?= 'id-age-' . $value ?>">
                            <div class="weui-cell__bd">
                                <p><?= $label ?></p>
                            </div>
                            <div class="weui-cell__ft">
                                <input type="radio" class="weui-check" name="age" value="<?= $value ?>" id="<?= 'id-age-' . $value ?>" <?php if ($value == 0): ?>checked="checked" <?php endif; ?> />
                                <span class="weui-icon-checked"></span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="weui-cells weui-cells_form">
                <div class="weui-cells__title">您在游戏上的月均投入</div>
                <div class="weui-cells weui-cells_radio">
                    <?php foreach (Vote::getExpenseOptionName() as $value => $label): ?>
                        <label class="weui-cell weui-check__label" for="<?= 'id-expense-' . $value ?>">
                            <div class="weui-cell__bd">
                                <p><?= $label ?></p>
                            </div>
                            <div class="weui-cell__ft">
                                <input type="radio" class="weui-check" name="expense" value="<?= $value ?>" id="<?= 'id-expense-' . $value ?>" <?php if ($value == 0): ?>checked="checked" <?php endif; ?> />
                                <span class="weui-icon-checked"></span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="weui-cells weui-cells_form">
                <div class="weui-cells__title">您玩的主要游戏类型是</div>
                <div class="weui-cells weui-cells_checkbox">
                    <?php foreach (Vote::getTypeOptionName() as $value => $label): ?>
                        <label class="weui-cell weui-check__label" for="<?= 'id-type-' . $value ?>">
                            <div class="weui-cell__hd">
                                <input type="checkbox" class="weui-check" name="type" value="<?= $value ?>" id="<?= 'id-type-' . $value ?>" <?php if ($value == 0): ?>checked="checked" <?php endif; ?>/>
                                <i class="weui-icon-checked"></i>
                            </div>
                            <div class="weui-cell__bd">
                                <p><?= $label ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>

                </div>
            </div>


            <div class="weui-cells weui-cells_form">
                <div class="weui-cells__title">您觉得国内游戏最需要提升改进的问题是</div>
                <div class="weui-cells weui-cells_checkbox">
                    <?php foreach (Vote::getProblemOptionName() as $value => $label): ?>
                        <label class="weui-cell weui-check__label" for="<?= 'id-problem-' . $value ?>">
                            <div class="weui-cell__hd">
                                <input type="checkbox" class="weui-check" name="problem" value="<?= $value ?>" id="<?= 'id-problem-' . $value ?>" <?php if ($value == 0): ?>checked="checked" <?php endif; ?>/>
                                <i class="weui-icon-checked"></i>
                            </div>
                            <div class="weui-cell__bd">
                                <p><?= $label ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>

                </div>
            </div>


            <div class="weui-btn-area">
                <button class="weui-btn weui-btn_primary" href="javascript:" id="okBtn">提交问卷</button>
            </div>
        </div>


    </div>

<script type="text/javascript">

    var ajaxUrl = "<?= Url::to(['/redpack/default/ajax-broker'], true); ?>";

    $(document).ready(function () {
        $("#okBtn").click(function () {
            var gender = $('input:radio[name="gender"]:checked').val();
            var age = $('input:radio[name="age"]:checked').val();
            var expense = $('input:radio[name="expense"]:checked').val();
            var type = $('input[name=type]:checked').map(function(_, el) {
                return $(el).val();
            }).get();

            var problem = $('input[name=problem]:checked').map(function(_, el) {
                return $(el).val();
            }).get();

            var args = {
                'classname': '\\common\\modules\\redpack\\models\\Vote',
                'funcname': 'ajaxCreate',
                'params': {
                    'gender': gender,
                    'age': age,
                    'expense': expense,
                    'type': type,
                    'problem': problem,
                    'openid': "<?= $openid ?>"
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
                        location.href = "<?php echo Url::to(['/redpack/vote/over']) ?>";
                    }
                    else {
                        weui.alert(ret['msg']);
                    }
                },
                error: function () {
                    weui.alert('系统错误');
                }
            });

            return false;

        });


    });


</script>
