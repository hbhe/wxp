<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('上架活动', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered'],
        'columns' => [

            [
                'label' => '图片',
                'attribute' => 'logo_id',
                'format' => 'html',
                'filter' => false,
                'value' => function ($model, $key, $index, $column) {
                    return Html::a(Html::img($model->getLogoUrl(450, 250), ['width' => '150']), $model->logoUrl);
                },
            ],

            [
                'attribute' => 'id',
                'filter' => false,
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'sid',
                'filter' => \common\models\Activity::getHolidayOptionName(),
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'holiday',
                'value' => function ($model, $key, $index, $column) {
                    return \common\models\Activity::getHolidayOptionName($model->holiday);
                },
                'filter' => \common\models\Activity::getHolidayOptionName(),
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'category',
                'value' => function ($model, $key, $index, $column) {
                    return \common\models\Activity::getCategoryOptionName($model->category);
                },
                'filter' => \common\models\Activity::getCategoryOptionName(),
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'title',
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'status',
                'value' => function ($model, $key, $index, $column) {
                    return \common\models\Activity::getStatusOptionName($model->status);
                },
                'filter' => \common\models\Activity::getStatusOptionName(),
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'label' => '操作',
                'format' => 'html',
                'value' => function ($model, $key, $index, $column) {
                    return Html::a('已创建的活动', Url::to(['/bargain/bargain-topic/index', 'activity_id' => $model->id]));
                },
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'contentOptions' => ['style'=>'vertical-align: middle;'],
            ],
        ],
    ]); ?>

</div>
