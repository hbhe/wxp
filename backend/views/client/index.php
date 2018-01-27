<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '客户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wxtpp-client-index">

    <h1 class="hide"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建新客户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn'],
            'id',
            'codename',
            'fullname',
            'shortname',
            'city',
            'province',
            'country',
            
        ],
    ]); ?>

</div>
