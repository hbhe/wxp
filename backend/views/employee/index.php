<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = '员工';
//$this->params['breadcrumbs'][] = $department->title;

$this->params['breadcrumbs'][] = $this->title;

if (!empty($department)) {
    $this->params['breadcrumbs'][] = ['label' => $department->title];
}

?>
<div class="employee-index">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a('新建员工', !empty($department) ? ['create', 'department_id' => $department->id] : ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'], 

            //'id',
            //'gh_id',
            //'employee.name',
            //'employee.mobile',
            'name',
            'mobile',
            [
                'label' => '所属部门',
                'value'=>function ($model, $key, $index, $column) {
                    return implode('', ArrayHelper::getColumn($model->departments, 'title'));
                },

            ],
            //'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

    <div class="sj-policy-create">

        <?php  echo $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>


