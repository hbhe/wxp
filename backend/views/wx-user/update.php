<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxUser */

$this->title = 'Update Wx User: ' . ' ' . $model->gh_id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gh_id, 'url' => ['view', 'gh_id' => $model->gh_id, 'openid' => $model->openid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wx-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
