<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxQrlimitsearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wx Qrlimits';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-qrlimit-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Create Wx Qrlimit', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'gh_id',
            'action_name',
            'scene_str',
            'ticket',
            // 'kind',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
