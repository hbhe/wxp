<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxBrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '品牌';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-brand-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('Create Wx Brand', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'client_id',
            'name',
            'sort_order',

			[
				'label' => "型号",
                'format'=>'html',
				'value'=>function ($model, $key, $index, $column) {
				    return Html::a('型号:' . $model->modelCount, ['sj-phone-model/index', 'brand_id'=>$model->id], ['title' => '', 'class' => 'btn btn-xs btn-primary']);
				},
                'headerOptions' => array('style'=>'width:70px;'),           
			],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>

<div class="sj-policy-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
