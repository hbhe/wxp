<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\redpack\models\RedpackTest */

$this->title = 'Update Redpack Test: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Redpack Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="redpack-test-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
