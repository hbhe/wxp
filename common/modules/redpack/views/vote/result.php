<?php

use common\modules\redpack\models\Vote;

$module = yii::$app->assetManager->getBundle('common\modules\redpack\RedpackWeuiJSAsset');

?>
<?php $this->title = '问卷调查结果' ?>
<style>

    .weui-cells {
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

<br/>

<div class="page">
    <div class="page__hd">
        <h5 class="page__title">投票人数: <?= $data['totalNumber']; ?> </h5>
        <p class="page__desc"></p>
    </div>


    <div class="page__bd page__bd_spacing">
        <div class="weui-cells">

            <?php for ($i = 0; $i < count(Vote::getGenderOptionName()); $i++) { ?>
                <?= Vote::getGenderOptionName($i) ?>
                <?php $radio = sprintf("%.2f%%", $data['gender'][$i] / $data['gender']['sum'] * 100) ?>
                <div class="weui-progress">
                    <div class="weui-progress__bar">
                        <div class="weui-progress__inner-bar js_progress" style="width: <?= $radio ?>;"></div>
                    </div>
                    <?= $data['gender'][$i] ?> (<?= $radio ?>)
                </div>
                <br>

            <?php } ?>
        </div>

    </div>

</div>

<div class="page">
    <div class="page__hd">
        <h5 class="page__title"></h5>
        <p class="page__desc"></p>
    </div>


    <div class="page__bd page__bd_spacing">
        <div class="weui-cells">

            <?php for ($i = 0; $i < count(Vote::getAgeOptionName()); $i++) { ?>
                <?= Vote::getAgeOptionName($i) ?>
                <?php $radio = sprintf("%.2f%%", $data['age'][$i] / $data['age']['sum'] * 100) ?>
                <div class="weui-progress">
                    <div class="weui-progress__bar">
                        <div class="weui-progress__inner-bar js_progress" style="width: <?= $radio ?>;"></div>
                    </div>
                    <?= $data['age'][$i] ?> (<?= $radio ?>)
                </div>
                <br>

            <?php } ?>
        </div>

    </div>

</div>


<div class="page">
    <div class="page__hd">
        <h5 class="page__title"></h5>
        <p class="page__desc"></p>
    </div>


    <div class="page__bd page__bd_spacing">
        <div class="weui-cells">

            <?php for ($i = 0; $i < count(Vote::getExpenseOptionName()); $i++) { ?>
                <?= Vote::getExpenseOptionName($i) ?>
                <?php $radio = sprintf("%.2f%%", $data['expense'][$i] / $data['expense']['sum'] * 100) ?>
                <div class="weui-progress">
                    <div class="weui-progress__bar">
                        <div class="weui-progress__inner-bar js_progress" style="width: <?= $radio ?>;"></div>
                    </div>
                    <?= $data['expense'][$i] ?> (<?= $radio ?>)
                </div>
                <br>

            <?php } ?>
        </div>

    </div>

</div>


<div class="page">
    <div class="page__hd">
        <h5 class="page__title"></h5>
        <p class="page__desc"></p>
    </div>


    <div class="page__bd page__bd_spacing">
        <div class="weui-cells">
            <?php for ($i = 0; $i < count(Vote::getTypeOptionName()); $i++) { ?>
                <?= Vote::getTypeOptionName($i) ?>
                <?php $radio = sprintf("%.2f%%", $data['type'][$i] / $data['type']['sum'] * 100) ?>
                <div class="weui-progress">
                    <div class="weui-progress__bar">
                        <div class="weui-progress__inner-bar js_progress" style="width: <?= $radio ?>;"></div>
                    </div>
                    <?= $data['type'][$i] ?> (<?= $radio ?>)
                </div>
                <br>

            <?php } ?>
        </div>

    </div>

</div>


<div class="page">
    <div class="page__hd">
        <h5 class="page__title"></h5>
        <p class="page__desc"></p>
    </div>


    <div class="page__bd page__bd_spacing">
        <div class="weui-cells">
            <?php for ($i = 0; $i < count(Vote::getProblemOptionName()); $i++) { ?>
                <?= Vote::getProblemOptionName($i) ?>
                <?php $radio = sprintf("%.2f%%", $data['problem'][$i] / $data['problem']['sum'] * 100) ?>
                <div class="weui-progress">
                    <div class="weui-progress__bar">
                        <div class="weui-progress__inner-bar js_progress" style="width: <?= $radio ?>;"></div>
                    </div>
                    <?= $data['problem'][$i] ?> (<?= $radio ?>)
                </div>
                <br>

            <?php } ?>
        </div>

    </div>

</div>


