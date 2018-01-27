<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@common/modules/wall/views/layouts/_clear.php')
?>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '微信现场活动管理',
        'brandUrl' => Yii::$app->homeUrl,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]); ?>
    <?php echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => '签到表', 'url' => ['admin/sign', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
            ['label' => '上墙消息', 'url' => ['admin/index', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
            ['label' => '摇一摇', 'url' => ['admin/shake', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
            ['label' => '活动设置', 
                    'items'=>[
                        ['label'=>'微信签到设置','url'=>['admin/settings', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
                        ['label'=>'微信上墙设置','url'=>['admin/settingswall', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
                        ['label'=>'摇一摇设置','url'=>['admin/settingsshake', 'gh_id' => \common\wosotech\Util::getSessionGhid()]]
                    ],
                    'url' => ['admin/settings', 'gh_id' => \common\wosotech\Util::getSessionGhid()]],
/*
            ['label' => Yii::t('mobile', 'Articles'), 'url' => ['/article/index']],
            ['label' => Yii::t('mobile', 'Contact'), 'url' => ['/site/contact']],
            ['label' => Yii::t('mobile', 'Signup'), 'url' => ['/user/sign-in/signup'], 'visible'=>Yii::$app->user->isGuest],
            ['label' => Yii::t('mobile', 'Login'), 'url' => ['/user/sign-in/login'], 'visible'=>Yii::$app->user->isGuest],
            [
                'label' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->getPublicIdentity(),
                'visible'=>!Yii::$app->user->isGuest,
                'items'=>[
                    [
                        'label' => Yii::t('mobile', 'Settings'),
                        'url' => ['/user/default/index']
                    ],
                    [
                        'label' => Yii::t('mobile', 'Backend'),
                        'url' => Yii::getAlias('@backendUrl'),
                        'visible'=>Yii::$app->user->can('manager')
                    ],
                    [
                        'label' => Yii::t('mobile', 'Logout'),
                        'url' => ['/user/sign-in/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ]
                ]
            ],
            [
                'label'=>Yii::t('mobile', 'Language'),
                'items'=>array_map(function ($code) {
                    return [
                        'label' => Yii::$app->params['availableLocales'][$code],
                        'url' => ['/site/set-locale', 'locale'=>$code],
                        'active' => Yii::$app->language === $code
                    ];
                }, array_keys(Yii::$app->params['availableLocales']))
            ]
*/
        ]
    ]); ?>
    <?php NavBar::end(); ?>

    <?php echo $content ?>

</div>

<?php $this->endContent() ?>