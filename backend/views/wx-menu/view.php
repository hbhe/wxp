<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WxMenu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->gh->title . '自定义菜单', 'url' => ['index', 'gh_id' => $model->gh_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定删除该菜单项？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'gh_id',
            'name',
            'menuPosition:text:菜单位置',
            'menuType:text:菜单类型',
            'key',
        ],
    ]) ?>

</div>
