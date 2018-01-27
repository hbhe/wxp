<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\redpack\models\RedpackLog */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Redpack Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redpack-log-create">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
