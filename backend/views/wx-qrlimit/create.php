<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WxQrlimit */

$this->title = 'Create Wx Qrlimit';
$this->params['breadcrumbs'][] = ['label' => 'Wx Qrlimits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-qrlimit-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
