<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use \kartik\grid\GridView;
use common\modules\redpack\models\RedpackLog;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\redpack\models\RedpackLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '红包收支明细';
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
                "apply.daterangepicker" => "function() { $('.grid-view').yiiGridView('applyFilter'); }",
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
            ['content'=>
                //Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type'=>'button', 'title'=>Yii::t('kvgrid', 'Add Book'), 'class'=>'btn btn-success', 'onclick'=>'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['data-pjax'=>0, 'class' => 'btn btn-success', 'title'=>'创建'])
            ],
            '{export}',
            '{toggleData}'
        ],
        //'pjax' => true,
        //'bordered' => false,
        //'striped' => false,
        //'condensed' => false,
        'responsive' => true,
        //'showPageSummary' => true, //是否显示汇总栏
        //'panel' => ['type' => GridView::TYPE_PRIMARY],
        'layout' => "{toolbar}\n{summary}\n{items}\n{pager}",

        'columns' => [
           [
                'attribute' => 'id',
                'headerOptions' => array('style'=>'width:40px;'),              
           ],
			[
				'label' => '头像',
				'format'=>'html',
				'value'=>function ($model, $key, $index, $column) { 
                    if (empty($model->wxUser->headimgurl))
                        return '';
                    $headimgurl = Html::img(\common\wosotech\Util::getWxUserHeadimgurl($model->wxUser->headimgurl, 46), ['class' => "img-responsive img-circle"]);
                    return Html::a($headimgurl, ['/xg-member/index', 'openid' => $model->openid]);
				},
			],
            [
                'label'=>'微信昵称',
                'format'=>'html',
                'value'=>function ($model) {
                    if (empty($model->wxUser->nickname))
                        return '';
                    return empty($model->wxUser->nickname) ? '---' : Html::a($model->wxUser->nickname, ['index', 'openid' => $model->openid]);
                },

                'headerOptions' => array('style'=>'width:80px;')
            ],

           [
                'label' => '手机',
                'value'=>function ($model, $key, $index, $column) { return empty($model->WxMember) ? '' : $model->WxMember->tel; },
           ],

            [
                'attribute' => 'openid',
                'format' => 'html',
                'value'=>function ($model) {
                    return Html::a($model->openid, ['index', 'openid' => $model->openid]);
                },
                'visible' => false,
			],

           [
                'attribute' => 'category',
                'value'=>function ($model, $key, $index, $column) { return $model->getCategoryLabel(); },
           ],

           [
                'attribute' => 'is_revenue',
                'label' => '方向',
                'value'=>function ($model, $key, $index, $column) { return RedpackLog::getIsRevenueOption($model->is_revenue); },
                'filter'=> RedpackLog::getIsRevenueOption(),
                'headerOptions' => array('style'=>'width:60px;'),              
           ],

           [
                'attribute' => 'amount',
                'format' => 'currency',
                'pageSummary' => true, // 显示
                'value'=>function ($model, $key, $index, $column) { return $model->amount / 100 ; },
           ],

            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'comment',        
                'vAlign'=>'middle',
                'headerOptions'=>['class'=>'kv-sticky-column'],
                'contentOptions'=>['class'=>'kv-sticky-column'],
                'editableOptions'=>[
                    'header'=>'备注', 
                    'size'=>'md',
                    //'asPopover' => false,
                ]
            ],

        //    'comment',

            [
                'attribute' => 'openid_another',
                'label'=>'推荐粉丝',
                'format'=>'html',
                'value'=>function ($model) {
                    if (empty($model->openid_another))
                        return '---';
                    return $model->openid_another;
                },
                'headerOptions' => array('style'=>'width:80px;')
            ],
            
            [
                 'attribute' => 'status',
                 'value'=>function ($model, $key, $index, $column) { 
                     return $model->is_revenue ? '---' : RedpackLog::getStatusOption($model->status); 
                 },
                 'filter'=> RedpackLog::getStatusOption(),
                 'headerOptions' => array('style'=>'width:60px;'),              
            ],

            [
                'attribute' => 'created_at',
                'filter' => false,
            ],

            //'mch_billno',

            'sendtime',

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

