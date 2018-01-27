<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MArticleMult */

$this->title = 'Update Marticle Mult: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Marticle Mults', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->article_mult_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="marticle-mult-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
