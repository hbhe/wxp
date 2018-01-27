<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;

$bundle = \common\modules\redpack\RedpackAsset::register($this);
//echo $bundle->baseUrl . '/images/anonymous.jpg';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>我的红包账户</title>
  <meta name="viewport" content="initial-scale=1, maximum-scale=1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <link rel="stylesheet" href="http://g.alicdn.com/msui/sm/0.6.2/css/sm.min.css">
  <style>
    .page{
      background: #fff;
    }
    .content-block{
      margin-top: .5rem;
      color: #222;
    }
    .buttons-tab{
      background: #ff4b44;
    }
    .buttons-tab .button{
      color: #fff;
    }
    .buttons-tab .button.active{
      color: #fff55d;
      border-color: #fff55d;
    }
    .remark{
      margin: 20px auto;
      width: 17rem;
      border: 2px dashed #ff4b44;
      border-radius: .5rem;
      padding: .5rem;
      color: #040000;
      font-family: "Source Han Sans CN";
      font-size: 12.5px;
      font-weight: 400;
    }
    .remark-title{
      /*max-width: 200px;*/
      height: 24px;
      font-family: "Helvetica Neue", Helvetica, sans-serif;
      font-weight: 700;
      font-size: 15px;
      display: flex;
      align-items: center;
      justify-content: left;
      margin-bottom: .2rem;
    }
    .remark-title label{
      border-radius: .3rem;
      background-color: #ff4b44;
      color: #ffffff;
      padding: .1rem .3rem;
    }
    .remark-title:not(:first-child){
      margin-top: .5rem;
    }
    .tab2 > header{
      width: 100%;
      height: 13.75rem;
      font-family: "Helvetica Neue", Helvetica, sans-serif;
    }
    .tab2 > header .header img{
      margin: 0 auto;
      margin-bottom: .5rem;
      margin-top: 1.25rem;
    }
    .tab2 > header .header .money{
      color: #000000;
      font-family: "Source Han Sans CN";
      text-align: center;
      font-weight: 400;
      font-size: 16.5px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }
    .tab2 > header .header .money mall{
      font-size: 29.5px;
    }
    .tab2 > header .footer{
      height: 1.9rem;
      margin-top: 1.25rem;
    }
    .footer ul{
      height: 100%;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .footer ul li{
      list-style: none;
      width: 100%;
      text-align: center;
    }
    .footer ul li:first-child{
      border-right: 1px solid #000;
    }
    .green{
      color: green !important;
    }
    .red{
      color: red !important;
    }
    .list-block{
      margin:  0 ;
    }

.list-block div:not(:last-child) .item-inner:after{
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      right: auto;
      top: auto;
      height: 1px;
      width: 100%;
      background-color: #e7e7e7;
      display: block;
      z-index: 15;
      -webkit-transform-origin: 50% 100%;
      transform-origin: 50% 100%;
    }

  </style>

    <?php $this->head() ?>
    <?php echo Html::csrfMetaTags() ?>
</head>
<body>
<?php $this->beginBody() ?>
  <div class="page current-page">
    <div class="content">
      <div class="buttons-tab">
        <a href="#tab1" class="tab-link active button">活动详情</a>
        <a href="#tab2" class="tab-link button">我的账户</a>
      </div>
      <div class="content-block">
        <div class="tabs">
          <div id="tab1" class="tab active">
            <div class="">
              <div class="remark">
                <div class="remark-title">
                  <label for="">参与方式</label>
                </div>
                <div>
                  用户关注“荆门电信”微信公众号，点击菜单栏“注册会员”，首次填写电话和验证码之后，生成自己专属的会员页面和二维码。会员通过自己的专属二维码来发展粉丝。
                </div>
                <div class="remark-title">
                  <label for="">活动奖励</label>
                </div>
                <div>每发展一个粉丝0.5元，粉丝达到10个或以上用户享有核酬资格。</div>
                <div class="remark-title">
                  <label for="">活动时间</label>
                </div>
                <div>2017年5月X日——2017年X月X日
                </div>
                <div class="remark-title">
                  <label for="">主办单位</label>
                </div>
                <div>本次活动主办单位为荆门电信，最终解释权归荆门电信所有</div>
                <div class="remark-title"><label for="">活动及兑奖说明</label></div>
                <div>
                  1、参与活动的用户需要关注“荆门电信”微信公众号，并且注册会员 <br>
                  2、会员发展的粉丝中湖北省内用户计入酬金体系，其他异省用户只统计到总粉丝数中<br>
                  3、每周三为酬金发放日，酬金通过“荆门电信”官微以红包形式发放，红包24小时未领取，系统将会自动退回，未领取酬金累计到下期发放<br>
                  4、红包每次发放限额5元至200元 <br>
                  5、本次活动真实有效，不正常套酬情况，系统一经发现，自动取消参赛资格，并有权不派发酬金
                </div>
              </div>
            </div>
          </div>
          <div id="tab2" class="tab ">
            <div class="content-block tab2">
              <header>
                <div class="header">
<!--
                  <img src="./img/money.png" style="width:5rem;height:5rem;display: block " alt="">
-->
                  <img src="<?php echo $bundle->baseUrl . '/img/money.png'; ?>" style="width:5rem;height:5rem;display: block " alt="">
                  

                  <div class="money">
                    总酬金 <br>
                    <mall>￥ <?php echo $model->getUserRevenueAmount() / 100; ?></mall>
                  </div>
                </div>
                <div class="footer">
                  <ul>
                    <li>已领酬金 <br>￥<?php echo $model->getUserConsumeAmount() / 100; ?></li>
                    <li>剩余酬金 <br>￥<?php echo $model->redpack_balance / 100; ?></li>
                  </ul>
                </div>
              </header>
              <ul>
                  <?php  echo yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                         'options' => [
                            'class' => 'list-block',
                        ],
                        'itemOptions' => [
                            //'tag' => 'span'
                        ], 
                        'itemView' => 'index_item',
                        'layout' => "{items}\n{pager}\n",
                        'emptyText' => ' ',
                        'pager' => [
                            'firstPageLabel' => false,
                            'lastPageLabel' => false,
                            'prevPageLabel' => '上页',
                            'nextPageLabel' => '下页',
                            'maxButtonCount' => 0,
                        ],

                    ]) ?>
            </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type='text/javascript' src='//g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
  <script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>