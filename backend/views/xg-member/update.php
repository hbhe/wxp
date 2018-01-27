<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxMember */

$this->title = 'Update Wx Xgdx Member: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Wx Xgdx Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wx-xgdx-member-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
