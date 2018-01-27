<?php
/**
 * @var $this yii\web\View
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php if (yii::$app->user->can(\common\models\User::ROLE_ADMINISTRATOR)): ?>                        
<li id="log-dropdown" class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <span>平台管理<i class="caret"></i></span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">客户及公众号</li>
        <li>
            <ul class="menu">
                <li>
                    <?php echo Html::a('<i class="fa fa-users text-blue"></i>客户管理', ['/client/index'], ['class' => '']) ?>
                    <?php echo Html::a('<i class="fa fa-wechat text-blue"></i>公众号管理', ['/gh/index'], ['class' => '']) ?>
                    <?php echo Html::a('<i class="fa fa-user text-blue"></i>操作人员管理', ['/user/index'], ['class' => '']) ?>
                    <?php //echo Html::a('<i class="fa fa-user text-blue"></i>角色权限管理', ['/admin/assignment'], ['class' => '', 'target' => '_blank']) ?>
                    <?php echo Html::a('<i class="fa fa-wechat text-blue"></i>授权公众号管理', ['/wx-authorizer/index'], ['class' => '']) ?>
                    <?php echo Html::a('<i class="fa fa-wechat text-blue"></i>活动管理', ['/activity/index'], ['class' => '']) ?>

                </li>
            </ul>
        </li>

        <li class="footer hide">
            <?php echo Html::a('平台设置', ['/log/index']) ?>
        </li>
    </ul>
</li>
<?php endif; ?>

<?php if (!empty($gh = \common\wosotech\Util::getSessionGh())): ?>
<li id="timeline-notifications" class="notifications-menu">
    <a href="<?php echo Url::to(['/wx-user/index']) ?>">
        <i class="fa fa-wechat"></i> <?php echo $gh->title ?>
    </a>
</li>
<?php endif; ?>

