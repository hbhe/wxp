<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxKeywordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '关键词回复列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-keyword-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('新建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'keyword',
            //'gh_id',
            [
            'attribute'=>'replyway',
            'value'=>function($model){
                return $model->replyway ? \common\models\WxKeyword::getReplywayTypeOptionName($model->replyway) : '';
                }
            ],
            [
            'attribute'=>'match',
            'value'=>function($model){
                return $model->match ? \common\models\WxKeyword::getMatchTypeOptionName($model->match) : '';
            }
            ],
            [
                'attribute'=>'type',
                'value'=>function($model){
                    return $model->type ? \common\models\WxKeyword::getActionTypeOptionName($model->type) : '';
                }
            ],
            //'type',
            //'action:ntext',
            [
                'attribute'=>'inputEventType',
                'value'=>function($model){
                    return \common\models\WxKeyword::getInputEventTypeOptionName($model->inputEventType);
                }
            ],
            'priority',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
