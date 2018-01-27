<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainPost */

$this->title = '更新：' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bargain Posts', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="bargain-post-update">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
