<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\wall\models\WxWall */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Walls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-wall-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
            'gh_id',
            'openid',
            'content',
            'is_checked',
            'is_wall',
            'is_from_openid',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
