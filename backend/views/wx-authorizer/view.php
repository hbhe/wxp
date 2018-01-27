<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WxAuthorizer */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Authorizers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-authorizer-view">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>

    <p style="display:none;">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'authorizer_appid',
            'authorizer_refresh_token',
            'user_name',
            'nick_name',
            [
                'attribute' => 'head_img',
                'format' => ['image', ['width' => '64', 'height' => '64']],
            ],

            'service_type_info',
            'verify_type_info',
            'alias',
            'qrcode_url:url',
            'func_info',
            'business_info',
            'principal_name',
            'signature:ntext',
            'miniprograminfo:ntext',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
