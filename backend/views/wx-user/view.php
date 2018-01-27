<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WxUser */

$this->title = $model->gh_id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'gh_id' => $model->gh_id, 'openid' => $model->openid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'gh_id' => $model->gh_id, 'openid' => $model->openid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'gh_id',
            'openid',
            'unionid',
            'subscribe',
            'subscribe_time:datetime',
            'nickname',
            'sex',
            'city',
            'country',
            'province',
            //'language',
            'headimgurl:url',
            'groupid',
            'remark',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
