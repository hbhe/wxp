<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WxKeyword */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '修改', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-keyword-view">

    <p>
        <?php echo Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确认删除么？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'gh_id',
            'keyword',
            'match',
            'type',
            'action:ntext',
            'inputEventType',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
