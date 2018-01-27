<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WxKeyword */

$this->title = '新建关键词';
$this->params['breadcrumbs'][] = ['label' => '关键词', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-keyword-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
