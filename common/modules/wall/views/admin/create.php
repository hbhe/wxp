<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\wall\models\WxWall */

$this->title = 'Create Wx Wall';
$this->params['breadcrumbs'][] = ['label' => 'Wx Walls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-wall-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
