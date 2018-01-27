<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WxGh */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '公众号', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-gh-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <!-- Hello, world! -->
    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定删除该公众号？',
                'method' => 'post',
            ],
        ]) ?>
        <?php //echo Html::a('自定义菜单', ['/wx-menu/index', 'gh_id' => $model->gh_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'clientName',
            'gh_id',
            'appId',
            'appSecret',
            'token',
            'accessToken',
            'accessToken_expiresIn:datetime',
            'encodingAESKey',
            'encodingMode',
            'wxPayMchId',
            'wxPayApiKey',
            'sms_template',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'qr_image_id',
                'value' => $model->getQrImageUrl(),
                'format' => ['image', ['width'=>'100','height'=>'100']],
            ],

        ],
    ]) ?>

</div>
