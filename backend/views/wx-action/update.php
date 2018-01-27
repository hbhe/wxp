<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MWxAction */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Wechat Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mwx-action-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
