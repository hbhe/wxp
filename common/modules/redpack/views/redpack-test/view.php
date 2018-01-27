<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\redpack\models\RedpackTest */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Redpack Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redpack-test-view">

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type1',
            'type2',
            'factor',
            'real',
            'sum',
        ],
    ]) ?>

</div>
