<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\bargain\models\BargainTopicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我举办的营销活动';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bargain-topic-index">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?php echo Html::a('创建', ['select-activity', 'activity_id' => \Yii::$app->request->get('activity_id')], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'], 
            [
                'label' => '图片',
                'attribute' => 'logo_id',
                'format' => 'html',
                'filter' => false,
                'value' => function ($model, $key, $index, $column) {
                    return empty($model->activity) ? '' : Html::a(Html::img($model->activity->getLogoUrl(450, 250), ['width' => '150']), $model->activity->logoUrl);
                },
            ],
            [
                'attribute' => 'id',
                'filter' => false,
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'title',
                'filter' => false,
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

//            [
//                'attribute' => 'detail',
//                'filter' => false,
//                'contentOptions' =>['style'=>'vertical-align: middle;'],
//            ],

            [
                'attribute' => 'start_time',
                'filter' => false,
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'end_time',
                'filter' => false,
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'status',
                'format'=>'html',
                'value' => function ($model, $key, $index, $column) {
                    if ($model->status == 1) {
                        return \common\modules\bargain\models\BargainTopic::getStatusOptionName($model->status)." / ".Html::a("暂停", ['pause', 'id' => $model->id], ['class' => 'btn btn-xs btn-primary']);
                    }
                    if ($model->status == 2) {
                        return \common\modules\bargain\models\BargainTopic::getStatusOptionName($model->status)." / ".Html::a("继续", ['pause', 'id' => $model->id], ['class' => 'btn btn-xs btn-primary']);
                    }
                    return \common\modules\bargain\models\BargainTopic::getStatusOptionName($model->status);
                },
                'filter' => \common\modules\bargain\models\BargainTopic::getStatusOptionName(),
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'label' => '商品数',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) { 
                    return Html::a($model->bargainItemsCount, ['bargain-item/index', 'topic_id'=>$model->id]);
                },
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'label' => '参与数',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) { 
                    return Html::a($model->bargainPostsCount, ['bargain-post/index', 'topic_id'=>$model->id]);
                },
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'attribute' => 'created_at',
                'filter' => false,
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'label' => '',
                'format' => 'html',
                //'headerOptions' => array('style' => 'width:80px;'),
                'value' => function ($model, $key, $index, $column) {
                    $url = "http://wx-activity.mysite.com/#/activity/{$model->id}/bargainirg?appid={$model->wxGh->appId}";
                    return Html::a('前台活动链接', $url, ['class' => 'btn btn-xs btn-success', 'style' => 'margin: 2px;', 'title' => $url]);
                },
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'contentOptions' =>['style'=>'vertical-align: middle;'],
            ],
        ],
    ]); ?>
</div>


<?php
/*
<?=  GridView::widget([
	'layout' => "<div>{summary}\n{items}\n{pager}</div>",
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'options' => ['class' => 'table-responsive'],
	'tableOptions' => ['class' => 'table table-striped'],  
	'columns' => [     

		['class' => yii\grid\CheckboxColumn::className()],

		[
			'label' => 'Office',
			'value'=>function ($model, $key, $index, $column) {
				return empty($model->office->title) ? '' : $model->office->title;
				return Yii::$app->formatter->asCurrency($model->amount/100);
				return MItem::getItemCatName($model->cid);
				return "￥".sprintf("%0.2f", $model->feesum/100);
			},
			'filter'=> false,
			'filter'=> MItem::getItemCatName(),
			'headerOptions' => array('style'=>'width:80px;'),    
			'visible'=>Yii::$app->user->identity->openid == 'admin',          
		],

		[
			'label' => 'Post Image',
			'format'=>'html',
			'value'=>function ($model, $key, $index, $column) { 
				return Html::a($model->postResponseCount, ['post-response/index', 'post_id'=>$model->id]);
				return Html::a(Html::img(Url::to($model->getPicUrl()), ['width'=>'75']), $model->getPicUrl());
			},
		],

		[
			'class' => 'yii\grid\ActionColumn',
			'template' => '{update} {view} {delete}',
			'options' => ['style'=>'width: 100px;'],
			'buttons' => [
				'update' => function ($url, $model) {
					return Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, [
						'class' => 'btn btn-xs btn-primary',
						'title' => Yii::t('plugin', 'Update'),
					]);
				},
				'view' => function ($url, $model) {
					return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
						'class' => 'btn btn-xs btn-warning',
						'title' => Yii::t('plugin', 'View'),
					]);
				},
				'delete' => function ($url, $model) {
					return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [
						'class' => 'btn btn-xs btn-danger',
						'data-method' => 'post',
						'data-confirm' => Yii::t('plugin', 'Are you sure to delete this item?'),
						'title' => Yii::t('plugin', 'Delete'),
						'data-pjax' => '0',
					]);
				},
			]
		],
	]
]); 

*/