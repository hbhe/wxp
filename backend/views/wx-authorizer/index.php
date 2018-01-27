<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxAuthorizerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wx Authorizers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-authorizer-index">

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
            'authorizer_appid',
            'user_name',
            'nick_name',
            'principal_name',
            //'head_img:image',
            [
                'attribute' => 'head_img',
                'format' => ['image', ['width' => '64', 'height' => '64']],
            ],
            'service_type_info',
            'verify_type_info',
            'alias',
            'authorizer_refresh_token',

            [
            'attribute' => 'status',
            'value'=>function ($model, $key, $index, $column) {
                    return \common\models\WxAuthorizer::getStatusOptionName($model->status);
                },
            ],
            //'func_info',
            // 'qrcode_url:url',
            // 'business_info',
            // 'signature:ntext',
            // 'miniprograminfo:ntext',
            // 'status',
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