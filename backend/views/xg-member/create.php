<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\WxMember */

$this->title = 'Create Wx Xgdx Member';
$this->params['breadcrumbs'][] = ['label' => 'Wx Xgdx Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-xgdx-member-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
