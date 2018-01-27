<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\bargain\models\BargainComment */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => 'Bargain Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bargain-comment-create">

    <h1 style="display:none;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
