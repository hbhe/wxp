<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\bargain\models\BargainTopicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '选择您想创建的活动';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bargain-topic-index">

    <?php foreach ($model as $res) { ?>
        <div style="float: left; margin: 7px; border: dashed">
            <a href="<?php echo Url::to(['create', "activity_id" => $res["id"]]) ?>">
                <div style="text-align: center">
                    <img src="<?php echo \Yii::$app->imagemanager->getImagePath($res['logo_id']); ?>" width="40%">
                    <h4><?php echo $res['title'] ?></h4>
                </div>
            </a>
        </div>
    <?php } ?>

</div>
