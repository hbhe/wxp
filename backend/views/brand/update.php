<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SjPolicy */

$this->title = 'Update Sj Policy: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sj Policies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sj-policy-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
