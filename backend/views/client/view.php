<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WxClient */

$this->title = $model->shortname;
$this->params['breadcrumbs'][] = ['label' => '客户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wxtpp-client-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('更新', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定删除该客户？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'codename',
            'fullname',
            'shortname',
            'city',
            'province',
            'country',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'logo_id',
                'value' => $model->getLogoUrl(),
                'format' => ['image'],
                //'format' => ['image', ['width'=>'100','height'=>'100']],
            ],
        ],
    ]) ?>

</div>
