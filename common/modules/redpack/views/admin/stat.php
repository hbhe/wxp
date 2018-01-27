<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use \kartik\grid\GridView;
use common\modules\redpack\models\RedpackLog;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\redpack\models\RedpackLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '红包统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redpack-log-index">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>

        <?php echo \kartik\daterange\DateRangePicker::widget([
            'model' => $searchModel,
            'attribute' => 'created_at',
            'presetDropdown' => true,
            'options' => [
                'id' => 'created_at'
            ],
            'pluginOptions' => [
                'format' => 'YYYY-MM-DD',
                'separator' => ' TO ',
                'opens'=>'left',
            ] ,
            'pluginEvents' => [
                //"apply.daterangepicker" => "function() { $('.grid-view').yiiGridView('applyFilter'); }",
            ]
        ]); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => '#created_at',

        'responsive' => true,
        'resizableColumns' => true,
        'persistResize' => true,
        'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),

        //'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
/*
        'beforeHeader'=>[
            [
                'columns'=>[
                    ['content'=>'Header Before 1', 'options'=>['colspan'=>4, 'class'=>'text-center warning']], 
                    ['content'=>'Header Before 2', 'options'=>['colspan'=>4, 'class'=>'text-center warning']], 
                    ['content'=>'Header Before 3', 'options'=>['colspan'=>3, 'class'=>'text-center warning']], 
                ],
                'options'=>['class'=>'skip-export'] // remove this row from export
            ]
        ],
*/
        'toolbar' =>  [
            [
                //'content'=> Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class' => 'btn btn-success', 'title'=>'创建'])
            ],
            '{export}',
            '{toggleData}'
        ],
        //'pjax' => true,
        //'bordered' => false,
        //'striped' => false,
        //'condensed' => false,
        'responsive' => true,
        'showPageSummary' => true,
        'panel' => ['type' => GridView::TYPE_PRIMARY],

        'columns' => [

           [
                'attribute' => 'id',
                'headerOptions' => array('style'=>'width:40px;'), 
           ],

            //'gh_id',
            [
                'attribute' => 'created_at',
                'label' => '日期',
                'filter' => false,
            ],

           [
                'attribute' => 'recommend_revenue_amount',
                'format' => 'currency',
                'value'=>function ($model, $key, $index, $column) { return $model->recommend_revenue_amount / 100 ; },
           ],

           [
                'attribute' => 'recommend_fan_count',
                'value'=>function ($model, $key, $index, $column) { return $model->recommend_fan_count; },
           ],

           [
                'attribute' => 'recommend_fan_revenue_count',
                'value'=>function ($model, $key, $index, $column) { return $model->recommend_fan_revenue_count; },
           ],

            [
                'class' => 'kartik\grid\ActionColumn',
                //'dropdown' => true,
                'vAlign'=>'middle',
                //'urlCreator' => function($action, $model, $key, $index) { return '#'; },
                'viewOptions'=>['title'=>'view', 'data-toggle'=>'tooltip'],
                'updateOptions'=>['title'=>'update', 'data-toggle'=>'tooltip'],
                'deleteOptions'=>['title'=>'delete', 'data-toggle'=>'tooltip'], 
                 'headerOptions' => array('style'=>'width:160px;'), 
            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

