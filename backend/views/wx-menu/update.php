<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxMenu */

$this->title = '更新菜单项: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->gh->title . '自定义菜单', 'url' => ['index', 'gh_id'=>$model->gh_id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="wx-menu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
