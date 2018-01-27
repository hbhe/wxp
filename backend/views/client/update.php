<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxClient */

$this->title = '更新客户资料: ' . ' ' . $model->shortname;
$this->params['breadcrumbs'][] = ['label' => '客户', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->shortname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="wxtpp-client-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
