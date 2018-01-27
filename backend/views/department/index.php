<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DepartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '部门';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);
    ?>

    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'], 

            //'id',
            'pid',
            'sid',
            //'gh_id',
            'title',
            // 'detail:ntext',
            'address',
            //'longitude',
            //'latitude',
            //'offset_type',
            //'telephone',
            // 'open_time',
            //'is_public',
            //'is_visible',
            //'is_self_operated',
            // 'status',
            // 'created_at',
            // 'updated_at',
            'sort_order',
            [
                'label' => '员工数',
                'format' => 'html',
                'value' => function ($model, $key, $index, $column) {
                    return Html::a($model->employeesCount, ['employee/index', 'department_ids' => $model->id]);
                },
            ],

            [
                'label' => '子结点数',
                'format' => 'html',
                'value' => function ($model, $key, $index, $column) {
                    return Html::a(count($model->children), ['index', 'pid' => $model->id]);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
