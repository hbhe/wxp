<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MArticle */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => '单图文列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
