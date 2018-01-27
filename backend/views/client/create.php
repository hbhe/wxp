<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WxClient */

$this->title = '创建新客户';
$this->params['breadcrumbs'][] = ['label' => '客户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wxtpp-client-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
