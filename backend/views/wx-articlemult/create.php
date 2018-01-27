<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MArticleMult */

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => '多图文', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marticle-mult-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
