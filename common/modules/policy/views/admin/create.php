<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SjPolicy */

$this->title = '添加保单';
$this->params['breadcrumbs'][] = ['label' => '屏安宝', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sj-policy-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
