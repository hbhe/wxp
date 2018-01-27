<?php
/**
 * @var $this yii\web\View
 */
use backend\assets\BackendAsset;
use backend\widgets\Menu;
use common\models\TimelineEvent;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$bundle = BackendAsset::register($this);
?>
<?php $this->beginContent('@backend/views/layouts/base.php'); ?>
    <div class="wrapper">
        <header class="main-header">
            <a href="<?php echo Yii::getAlias('@frontendUrl') ?>" class="logo">
                <?php echo Yii::$app->name ?>
            </a>

            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"><?php echo Yii::t('backend', 'Toggle navigation') ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <li id="timeline-notifications" class="notifications-menu">
                            <a href="<?php echo Url::to(['/timeline-event/index']) ?>">
                                <i class="fa fa-bell"></i>
                                <span class="label label-success">
                                    <?php echo TimelineEvent::find()->today()->count() ?>
                                </span>
                            </a>
                        </li>

                        <li id="log-dropdown" class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                                <span class="label label-danger">
                                <?php echo \backend\models\SystemLog::find()->count() ?>
                            </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"><?php echo Yii::t('backend', 'You have {num} log items', ['num' => \backend\models\SystemLog::find()->count()]) ?></li>
                                <li>
                                    <ul class="menu">
                                        <?php foreach (\backend\models\SystemLog::find()->orderBy(['log_time' => SORT_DESC])->limit(5)->all() as $logEntry): ?>
                                            <li>
                                                <a href="<?php echo Yii::$app->urlManager->createUrl(['/log/view', 'id' => $logEntry->id]) ?>">
                                                    <i class="fa fa-warning <?php echo $logEntry->level == \yii\log\Logger::LEVEL_ERROR ? 'text-red' : 'text-yellow' ?>"></i>
                                                    <?php echo $logEntry->category ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <?php echo Html::a(Yii::t('backend', 'View all'), ['/log/index']) ?>
                                </li>
                            </ul>
                        </li>

                        <?php echo $this->render('_platform_nav') ?>

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo empty(Yii::$app->user->identity->userProfile) ? '' : Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.jpg')) ?>"
                                     class="user-image">
                                <span><?php echo Yii::$app->user->identity->username ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header light-blue">
                                    <img src="<?php echo Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.jpg')) ?>"
                                         class="img-circle" alt="User Image"/>
                                    <p>
                                        <?php echo Yii::$app->user->identity->username ?>
                                        <small>
                                            <?php echo Yii::t('backend', 'Member since {0, date, short}', Yii::$app->user->identity->created_at) ?>
                                        </small>
                                </li>

                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?php echo Html::a(Yii::t('backend', 'Profile'), ['/sign-in/profile'], ['class' => 'btn btn-default btn-flat']) ?>
                                    </div>
                                    <div class="pull-left">
                                        <?php echo Html::a(Yii::t('backend', 'Account'), ['/sign-in/account'], ['class' => 'btn btn-default btn-flat']) ?>
                                    </div>
                                    <div class="pull-right">
                                        <?php echo Html::a(Yii::t('backend', 'Logout'), ['/sign-in/logout'], ['class' => 'btn btn-default btn-flat', 'data-method' => 'post']) ?>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <?php echo Html::a('<i class="fa fa-cogs"></i>', ['/site/settings']) ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <?php
                /*
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?php echo Yii::$app->user->identity->userProfile->getAvatar($this->assetManager->getAssetUrl($bundle, 'img/anonymous.jpg')) ?>" class="img-circle" />
                    </div>
                    <div class="pull-left info">
                        <p><?php echo Yii::t('backend', 'Hello, {username}', ['username'=>Yii::$app->user->identity->getPublicIdentity()]) ?></p>
                        <a href="<?php echo Url::to(['/sign-in/profile']) ?>">
                            <i class="fa fa-circle text-success"></i>
                            <?php echo Yii::$app->formatter->asDatetime(time()) ?>
                        </a>
                    </div>
                </div>
                */ ?>

                <?php echo Menu::widget([
                    'options' => ['class' => 'sidebar-menu'],
                    'linkTemplate' => '<a href="{url}">{icon}<span>{label}</span>{right-icon}{badge}</a>',
                    'submenuTemplate' => "\n<ul class=\"treeview-menu\">\n{items}\n</ul>\n",
                    'activateParents' => true,
                    'items' => [
                        [
                            'label' => '粉丝管理',
                            'icon' => '<i class="fa fa-wechat"></i>',
                            'url' => ['/wx-user/index'],
                            'visible' => Yii::$app->user->can(User::ROLE_USER)
                        ],

                        [
                            'label' => '微信菜单管理',
                            'icon' => '<i class="fa fa-users"></i>',
                            'url' => ['/wx-menu/index'],
                            'visible' => Yii::$app->user->can(User::ROLE_USER)
                        ],

                        [
                            'label' => '关键词管理',
                            'icon' => '<i class="fa fa-edit"></i>',
                            'url' => ['/wx-keyword/index'],
                            'visible' => true,
                        ],
                        /*
                                                [
                                                    'label'=>'图文',
                                                    'icon'=>'<i class="fa fa-wechat"></i>',
                                                    'url'=>'#',
                                                    'options'=>['class'=>'treeview'],
                                                    'items'=>[
                                                        ['label'=>Yii::t('backend', '单图文'), 'url'=>['/wx-article/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                                        ['label'=>Yii::t('backend', '多图文'), 'url'=>['/wx-article-mult/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                                    ],
                                                 ],
                        */
                        [
                            'label' => '部门组织结构',
                            'icon' => '<i class="fa fa-shopping-bag"></i>',
                            'url' => ['/department/index'],
                            'visible' => true,
                        ],

                        [
                            'label' => '部门员工管理',
                            'icon' => '<i class="fa fa-shopping-bag"></i>',
                            'url' => ['/employee/index'],
                            'visible' => false
                        ],

                        [
                            'label' => '品牌',
                            'icon' => '<i class="fa fa-users"></i>',
                            'url' => ['/brand/index'],
                            'visible' => true,
                        ],

                        [
                            'label' => '会员管理',
                            'icon' => '<i class="fa fa-users"></i>',
                            'url' => '#',
                            'options' => ['class' => 'treeview'],
                            'items' => [
                                ['label' => '会员列表', 'url' => ['/xg-member/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => '红包发放明细', 'url' => ['/redpack/admin/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => '红包活动统计', 'url' => ['/redpack/admin/stat'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ],
                            'visible' => true,
                        ],

                        [
                            'label' => '内容管理',
                            'url' => '#',
                            'icon' => '<i class="fa fa-edit"></i>',
                            'options' => ['class' => 'treeview'],
                            'items' => [
                                ['label' => Yii::t('backend', 'Static pages'), 'url' => ['/page/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'Articles'), 'url' => ['/article/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'Article Categories'), 'url' => ['/article-category/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'Text Widgets'), 'url' => ['/widget-text/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'Menu Widgets'), 'url' => ['/widget-menu/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'Carousel Widgets'), 'url' => ['/widget-carousel/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ],
                            'visible' => false,
                        ],

                        [
                            'label' => '营销活动管理',
                            'icon' => '<i class="fa fa-users"></i>',
                            'url' => '#',
                            'options' => ['class' => 'treeview'],
                            'items' => [
                                ['label' => '我的营销活动', 'url' => ['/bargain/bargain-topic/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                //['label' => '砍价活动', 'url' => ['/bargain/bargain-post/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                //['label'=>'砍价者列表', 'url'=>['/bargain/bargain-comment/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],
                                //['label'=>'商品列表', 'url'=>['/bargain/bargain-item/index'], 'icon'=>'<i class="fa fa-angle-double-right"></i>'],

                            ],
                            'visible' => true,
                        ],

                        [
                            'label' => '微信现场活动',
                            'icon' => '<i class="fa fa-users"></i>',
                            'url' => '#',
                            'options' => ['class' => 'treeview'],
                            'items' => [
                                ['label' => Yii::t('backend', '签到表'), 'url' => ['/wall/admin/sign', 'gh_id' => \common\wosotech\Util::getSessionGhid()], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', '上墙消息'), 'url' => ['/wall/admin/index', 'gh_id' => \common\wosotech\Util::getSessionGhid()], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', '摇一摇'), 'url' => ['/wall/admin/shake', 'gh_id' => \common\wosotech\Util::getSessionGhid()], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', '活动设置'),
                                    'items' => [
                                        ['label' => '微信签到设置', 'url' => ['/wall/admin/settings', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
                                        ['label' => '微信上墙设置', 'url' => ['/wall/admin/settingswall', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
                                        ['label' => '摇一摇设置', 'url' => ['/wall/admin/settingsshake', 'gh_id' => \common\wosotech\Util::getSessionGhid()]]
                                    ],
                                    'url' => ['/wall/admin/settings', 'gh_id' => \common\wosotech\Util::getSessionGhid()], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                            ],
                            'visible' => true,
                        ],

                        [
                            'label' => '参数设置',
                            'url' => '#',
                            'icon' => '<i class="fa fa-cogs"></i>',
                            'options' => ['class' => 'treeview'],
                            'items' => [
                                [
                                    'label' => Yii::t('backend', 'i18n'),
                                    'url' => '#',
                                    'icon' => '<i class="fa fa-flag"></i>',
                                    'options' => ['class' => 'treeview'],
                                    'items' => [
                                        ['label' => Yii::t('backend', 'i18n Source Message'), 'url' => ['/i18n/i18n-source-message/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                        ['label' => Yii::t('backend', 'i18n Message'), 'url' => ['/i18n/i18n-message/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                    ],
                                    'visible' => false,
                                ],

                                [
                                    'label' => Yii::t('backend', 'System Information'),
                                    'url' => ['/system-information/index'],
                                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                                    'visible' => false,
                                ],
                                ['label' => Yii::t('backend', 'Key-Value Storage'), 'url' => ['/ks/key-storage/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'File Storage'), 'url' => ['/file-storage/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'Cache'), 'url' => ['/cache/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                ['label' => Yii::t('backend', 'File Manager'), 'url' => ['/file-manager/index'], 'icon' => '<i class="fa fa-angle-double-right"></i>'],
                                [
                                    'label' => Yii::t('backend', 'Logs'),
                                    'url' => ['/log/index'],
                                    'icon' => '<i class="fa fa-angle-double-right"></i>',
                                    'badge' => \backend\models\SystemLog::find()->count(),
                                    'badgeBgClass' => 'label-danger',
                                ],
                            ],
                            'visible' => Yii::$app->user->can(User::ROLE_ADMINISTRATOR)
                        ],
                    ]
                ]) ?>
            </section>
        </aside>

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?php echo $this->title ?>
                    <?php if (isset($this->params['subtitle'])): ?>
                        <small><?php echo $this->params['subtitle'] ?></small>
                    <?php endif; ?>
                </h1>

                <?php echo Breadcrumbs::widget([
                    'tag' => 'ol',
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?php if (Yii::$app->session->hasFlash('alert')): ?>
                    <?php echo \yii\bootstrap\Alert::widget([
                        'body' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body'),
                        'options' => ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options'),
                    ]) ?>
                <?php endif; ?>
                <?php echo $content ?>
            </section><!-- /.content -->
        </aside><!-- /.right-side -->
    </div><!-- ./wrapper -->

<?php $this->endContent(); ?>

<?php
/*
                        [
                            'label'=>Yii::t('backend', 'Main'),
                            'options' => ['class' => 'header']
                        ],
                        
                        [
                            'label'=>Yii::t('backend', 'Timeline'),
                            'icon'=>'<i class="fa fa-bar-chart-o"></i>',
                            'url'=>['/timeline-event/index'],
                            'badge'=> TimelineEvent::find()->today()->count(),
                            'badgeBgClass'=>'label-success',
                        ],

                        [
                            'label'=>Yii::t('backend', 'System'),
                            'options' => ['class' => 'header']
                        ],

                        [
                            'label'=>Yii::t('backend', 'Users'),
                            'icon'=>'<i class="fa fa-users"></i>',
                            'url'=>['/user/index'],
                            'visible'=>Yii::$app->user->can(User::ROLE_ADMINISTRATOR)
                        ],

                        [
                            'label'=>'客户配置',
                            'icon'=>'<i class="fa fa-user"></i>',
                            'url'=>['/client/index'],
                            'visible'=>Yii::$app->user->can(User::ROLE_ADMINISTRATOR)
                        ],

                        [
                            'label'=>'公众号配置',
                            'icon'=>'<i class="fa fa-users"></i>',
                            'url'=>['/gh/index'],
                            'visible'=>Yii::$app->user->can(User::ROLE_ADMINISTRATOR)
                        ],
*/
