<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\modules\redpack\models\RedpackTest */

$this->title = 'Create Redpack Test';
$this->params['breadcrumbs'][] = ['label' => 'Redpack Tests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redpack-test-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
