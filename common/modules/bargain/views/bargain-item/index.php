<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\bargain\models\BargainItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '活动商品';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="bargain-item-index">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建', ['create', 'topic_id' => Yii::$app->request->get('topic_id', '')], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            //'gh_id',

            'bargainTopic.title',

            //'cat',
            [
                'attribute' => 'cat',
                'value' => function ($model, $key, $index, $column) {
                    return \common\modules\bargain\models\BargainItem::getCatOptionName($model->cat);
                },
                'filter' => \common\modules\bargain\models\BargainItem::getCatOptionName(),
            ],

            'title',
//            [
//                'attribute' => 'imgUrl',
//                'format' => ['image', ['width'=>'32', 'height'=>'32']],
//            ],

            [
                'attribute' => 'image_id',
                'format' => ['image', ['width'=>'32', 'height'=>'32']],
                'value'=>function ($model, $key, $index, $column) { return $model->getImageUrl(); },
            ],
            [
                'attribute' => 'price',
                'format' => 'currency',
                'value'=>function ($model, $key, $index, $column) { return $model->price / 100 ; },
            ],

            [
                'attribute' => 'end_price',
                'format' => 'currency',
                'value'=>function ($model, $key, $index, $column) { return $model->end_price / 100 ; },
            ],

            'end_number',
            'total_count',
            'rest_count',
            // 'send_item_cat',
            // 'send_how',
            // 'send_where',
            // 'send_date_range_type',
            // 'send_date_start',
            // 'send_date_end',
            // 'send_date_active_in_days',
            // 'send_date_active_len',
            // 'send_week',
            // 'sub_title',
            // 'send_customer_service_tel',
            // 'send_faq',
            // 'send_btn_style',
            // 'send_btn_label',
            // 'send_btn_url:url',
            // 'sort',
            // 'status',
            // 'remark:ntext',
            // 'created_at',
            // 'updated_at',

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