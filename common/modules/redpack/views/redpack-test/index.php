<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\redpack\models\RedpackTestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Redpack Tests';
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .filters {
        display: none;
    }

    table.gridtable {
        font-family: verdana, arial, sans-serif;
        font-size: 11px;
        color: #333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }

    table.gridtable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }

    table.gridtable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
    }

</style>


<div class="redpack-test-index">

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'layout' => "{items}\n",
        'tableOptions' => ['class' => 'gridtable', 'align' => 'centerx'],
        'columns' => [
            //'id',
            'type1',
            'type2',
            //'factor',

            [
                'attribute' => 'factor',
                //'headerOptions' => array('style'=>'width:180px;align:center;'),
                'headerOptions' => ['width' => '200'],
            ],
            'real',
            'sum',

        ],
    ]); ?>

</div>
