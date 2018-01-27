<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainItem */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Bargain Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bargain-item-create">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
