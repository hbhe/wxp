<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WxUser */

$this->title = 'Create Wx User';
$this->params['breadcrumbs'][] = ['label' => 'Wx Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
