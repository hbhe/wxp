<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\WxAction;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\WxActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Wechat Actions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mwx-action-index">

    <p>
        <?= Html::a(Yii::t('backend', 'Create Wechat Action'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        
		'columns' => [
            
			'wx_action_id',
            
            [
                'attribute' => 'keyword',
                'value'=>function ($model, $key, $index, $column) { return $model->getKeywordAlias(); },
            ],
            
            [
                'attribute' => 'type',
                'value'=>function ($model, $key, $index, $column) { return WxAction::getActionTypeOptionName($model->type); },
                'filter'=> WxAction::getActionTypeOptionName(),
            ],

			'action:ntext',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],

        ],
    ]); ?>

</div>
