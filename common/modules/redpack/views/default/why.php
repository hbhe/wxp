<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->title = '' ?>
<style>
.headimage-circle img {display:block; margin:0 auto;border-radius:100%;max-width:80px; max-height:80px}
.weui-cell {font-size: 15px;}
.weui-cell .weui-cell__ft {color:#999999;}
</style>
<div class="page">
	
	<div class="weui-cells">
		<article class="weui-article" style="color: #999999;font-size:14px;background: #ffffff none repeat scroll 0 0;">
			<h2 style="color:#333333">活动奖励</h2>
			<p>每发展一个粉丝0.5<?php echo $params['redpack.amount.recommend.consume.step'] / 100 ?>元，粉丝达到10个或以上用户享有核酬资格</p>
			<p style="margin-bottom:8px;"></p>
		</article>
	</div>

	<div class="weui-cells">
		<article class="weui-article" style="color: #999999;font-size:14px;background: #ffffff none repeat scroll 0 0;">
			<h2 style="color:#333333">活动时间</h2>
			<p>2017年5月X日——2017年X月X日 </p>
			<p style="margin-bottom:8px;"></p>
		</article>
	</div>
    
	<div class="weui-cells">
		<article class="weui-article" style="color: #999999;font-size:14px;background: #ffffff none repeat scroll 0 0;">
			<h2 style="color:#333333">举办单位</h2>
			<p>本次活动主办单位为<?php echo $gh->title ?>，最终解释权归<?php echo $gh->title ?>所有</p>
			<p style="margin-bottom:8px;"></p>
		</article>
	</div>
    
	<div class="weui-cells">
		<article class="weui-article" style="color: #999999;font-size:14px;background: #ffffff none repeat scroll 0 0;">
			<h2 style="color:#333333">活动及兑奖说明</h2>
			<p style="padding-bottom:10px;">1. 参与活动的用户需要关注“<?php echo $gh->title ?>”微信公众号，并且注册会员</p>
			<p>2. 会员发展的粉丝中湖北省内用户计入酬金体系，其他异省用户只统计到总粉丝数中。</p>
			<p>3. 粉丝多次重复关注的，系统默认只统计一次。</p>
			<p>4. 每周三为酬金发放日，酬金通过“<?php echo $gh->title ?>”官微以红包形式发放，红包24小时未领取，系统将会自动退回，未领取酬金累计到下期发放。</p>
			<p>5. 红包每次发放限额5元至200元 。</p>
			<p style="margin-bottom:8px;">6. 本次活动真实有效，不正常套酬情况，系统一经发现，自动取消参赛资格，并有权不派发酬金。</p>
		</article>
	</div>


</div>
