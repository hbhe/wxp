<?php

use common\modules\policy\models\SjPolicy;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SjPolicy */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sj Policies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sj-policy-view">

    <p>
        <?php echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php echo Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定删除么?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'generate_policy_sid',
            'imei',
            'openid',
            'mobile',
            'clerk_id',
            'brand_id',
            'model_id',
			['attribute' =>'state','value'=> SjPolicy::getStatusOption($model->state)],
            'created_at',
            'updated_at',
			'imgPath',
        ],
    ]) ?>
</div>
<?php 
$imgsrc=explode(';', $model->imgPath);
foreach ($imgsrc as $v){
?>
<img style="height:145px;width:145px;" src="<?php echo $v;?>">
<?php }?>


   
			





