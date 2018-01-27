<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WxMember */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Xgdx Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-xgdx-member-view">

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
            'gh_id',
            'openid',
            'tel',
            //'to_userid',
            'vermicelli',
            //'ticket',
            'is_binding',
            'member_type',
            'member_grade',
            'redpack_status',
            'redpack_balance',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
