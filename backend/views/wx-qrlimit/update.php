<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxQrlimit */

$this->title = 'Update Wx Qrlimit: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Qrlimits', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wx-qrlimit-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
