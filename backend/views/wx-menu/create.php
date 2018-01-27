<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WxMenu */

$this->title = '创建新菜单';
$this->params['breadcrumbs'][] = ['label' => $gh->title, 'url' => ['index', 'gh_id' => $gh->gh_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-menu-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
