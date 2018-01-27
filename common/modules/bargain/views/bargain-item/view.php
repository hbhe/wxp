<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainItem */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Bargain Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bargain-item-view">

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
            'gh_id',
            'topic_id',
            'cat',
            'title',
            'imageUrl',
            'price',
            'sort',
            'status',
            'remark:ntext',
            'created_at',
            'updated_at',
            'end_price',
            'end_number',
            'total_count',
            'rest_count',
            'send_item_cat',
            'send_how_offline',
            'send_where_offline',
            'send_how_web',
            'send_where_web',
            'send_date_range_type',
            'send_date_start',
            'send_date_end',
            'send_date_active_in_days',
            'send_date_active_len',
            'send_week',
            'sub_title',
            'send_customer_service_tel',
            'send_faq',
            'send_btn_style',
            'send_btn_link_label',
            'send_btn_link_url:url',
            'send_btn_qr_label',
            'send_btn_qr_id',
        ],
    ]) ?>

</div>
