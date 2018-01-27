<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxBrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '保单查询';
$this->params['breadcrumbs'][] = $this->title;
?>
<div  >
    <div >
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php echo yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
            'layout' => "{items}\n",
            'emptyText' => ' ',
            'pager' => [
                'firstPageLabel' => false,
                'lastPageLabel' => false,
                'prevPageLabel' => '上页',
                'nextPageLabel' => '下页',
                'maxButtonCount' => 0,
            ],

        ])

        ?>
    </div>
     
    <div style="height: 1.5rem;display: flex ;justify-content: flex-end;margin-right: 10px" >
        <a href="<?php echo Url::to(['sj-policy/fwtk']); ?>" style="display: flex ;font-size: .7rem;
            height: 100%;margin-left: 1rem;
             justify-content: flex-end;">
            <span style="color: #000; margin-right: .25rem;margin-top: .1rem">服务条款</span>
            <img src="sj-policy/img/wenhao2.png" alt="" width="20" height="22" />
        </a>
    </div>
</div>

