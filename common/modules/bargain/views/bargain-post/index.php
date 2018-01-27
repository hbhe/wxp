<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\bargain\models\BargainPostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '参与人创建的砍价';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bargain-post-index">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'], 

            //'id',
            //'gh_id',
            //'topic_id',
            [
                'label' => '创建者',
                'attribute' => 'wxUser.headimgurl',
                'format' => ['image', ['width'=>'32', 'height'=>'32', 'class' => "img-responsive img-circle"]],
            ],

            'openid',
            'name',
            // 'phone',
            // 'item_id',
            // 'rest_price',
            // 'status',
            // 'created_at',
            // 'updated_at',

            [
                'label' => '图片',
                'attribute' => 'bargainItem.imageUrl',
                'format' => ['image', ['width'=>'32', 'height'=>'32']],
            ],

            [
                'attribute' => 'bargainItem.price',
                'format' => 'currency',
                'value'=>function ($model, $key, $index, $column) { return $model->bargainItem->price / 100 ; },
            ],

            [
                'attribute' => 'status',
				'format'=>'html',
                'value' => function ($model, $key, $index, $column) {
					if ($model->status == 3) {
						return \common\modules\bargain\models\BargainPost::getStatusOptionName($model->status)." / ".Html::a("领取", ['pick', 'id'=>$model->id], ['class' => 'btn btn-xs btn-primary']);
					}
					return \common\modules\bargain\models\BargainPost::getStatusOptionName($model->status);
                },
                'filter' => \common\modules\bargain\models\BargainPost::getStatusOptionName(),
            ],

            [
                'attribute' => 'rest_price',
                'format' => 'currency',
                'value'=>function ($model, $key, $index, $column) { return $model->rest_price / 100 ; },
            ],

            [
                'label' => '已砍次数',
                'format'=>'html',
                'value'=>function ($model, $key, $index, $column) { 
                    return Html::a($model->bargainCommentsCount, ['bargain-comment/index', 'post_id'=>$model->id]);
                },
            ],
			'created_at',
            ['class' => 'yii\grid\ActionColumn'],
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