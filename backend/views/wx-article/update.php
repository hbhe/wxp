<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MArticle */

$this->title = Yii::t('app', 'Update') . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '单图文列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="marticle-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
