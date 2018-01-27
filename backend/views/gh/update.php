<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxGh */

$this->title = '更新公众号配置: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '公众号', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->gh_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="wx-gh-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
