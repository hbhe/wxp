<?php

use common\modules\policy\models\SjPolicy;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SjPolicySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '屏安宝';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sj-policy-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('添加保单', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'generate_policy_sid',
            'imei',
            'openid',
            'mobile',
            // 'imgPath',
            // 'clerk_id',
            // 'brand_id',
            // 'model_id',
			[
                'attribute' =>'state',
                'value'=> function ($model){
                    if (!isset($model->state)) return '';
                    return SjPolicy::getStatusOption($model->state);
                }
			],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>


