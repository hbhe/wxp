<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WxKeyword */

$this->title = '修改: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '修改', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wx-keyword-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
