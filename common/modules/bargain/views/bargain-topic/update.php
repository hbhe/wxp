<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainTopic */

$this->title = '更新：' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Bargain Topics', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="bargain-topic-update">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
