<?php
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\LinkPager;

//use common\modules\outlet\assets\BroadAsset;

//$bundle = BroadAsset::register($this);

$this->beginPage()
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->head() ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="initial-scale=1, maximum-scale=1">
  <link rel="shortcut icon" href="/favicon.ico">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <link rel="stylesheet" href="//g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
  <style>
    .green{
      color: green !important;
    }
    .red{
      color: red !important;
    }
    .list-block{
      margin: .55rem 0 ;
    }
    .pagination{
    display: flex;
    justify-content: space-around;
    padding-left: 0;
    }
    .page.page-current {
    overflow: auto;
    }
  </style>
</head>
<body>
<?php $this->beginBody() ?>
<div class="page-group">
  <div class="page page-current">
    <div class="list-block">
      <ul>
        <li class="item-content">
          <div class="item-inner">
            <div class="item-title">酬金余额<?php echo isset($balance->redpack_balance) ? $balance->redpack_balance/100 : 0;?>元</div>
            <div class="item-after">
            <!--
              <a href="" class="button">提现</a>
                -->
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="list-block">
    <ul>
        <li class="item-content">
          <div class="item-inner">
            <div class="item-title"><a href="" class="button" >收入详情</a></div>
            <div class="item-after">
              <a href="<?php echo Url::to(['/redpack/default/spending']);?>" class="button" >支出详情</a>
            </div>
          </div>
        </li>
      </ul>
      <ul id="income">
      <?php  echo yii\widgets\ListView::widget([
            'dataProvider' => $incomeProvider,
            'itemView' => '_income',
            'layout' => "{items}\n{pager}\n",
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
      </ul>
      
    </div>
  </div>
</div>
<script type='text/javascript' src='//g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
<script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
