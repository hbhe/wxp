<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WxMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $selectedGh->title . '自定义菜单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wx-menu-index">

    <h3><?php //echo Html::encode($this->title) ?></h3>
    <p class="hide">
        <?php 
            $items = [];
            $ghs = \common\models\WxGh::find()->all();
            foreach ($ghs as $gh) {
                $items[$gh->gh_id] = $gh->title;
            }
            $url = Url::to(['wx-menu/index'], true);
            echo Html::dropDownList('gh_select', $selectedGh->gh_id, $items, ['onchange' => "javascript:var url='{$url}?gh_id=' + this.value; location.href=url"]);
        ?>
    </p>

    <p>
        <?= Html::a('微信服务器 -> 本地', ['from-wx', 'gh_id' => $selectedGh->gh_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('本地 -> 微信服务器', ['to-wx', 'gh_id' => $selectedGh->gh_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('创建新菜单项', ['create', 'gh_id' => $selectedGh->gh_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

//            'id',
//            'gh_id',
            'name',
            'menuPosition:text:菜单位置',
            'menuType:text:菜单类型',
            'keyString:text:菜单键值',

            ['class' => 'yii\grid\ActionColumn'],
            
        ],
    ]); ?>

</div>
