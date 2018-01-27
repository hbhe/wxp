<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Employee */

$this->title = '创建';

if (!empty($department)) {
    $this->params['breadcrumbs'][] = ['label' => $department->title];
}

$this->params['breadcrumbs'][] = ['label' => '员工', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'department' => $department,
    ]) ?>

</div>
