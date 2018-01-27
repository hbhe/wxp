<?php
$config = [
    'language' => 'zh-CN',
    'homeUrl' => Yii::getAlias('@backendUrl'),
    'controllerNamespace' => 'backend\controllers',

    //'bootstrap' => ['log', 'plugins'],

    //'defaultRoute'=>'timeline-event/index',
    'defaultRoute' => 'gh/index',

    'controllerMap' => [
        'file-manager-elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['manager'],
            'disabledCommands' => ['netmount'],
            'roots' => [
                [
                    'baseUrl' => '@storageUrl',
                    'basePath' => '@storage',
                    'path' => '/',
                    'access' => ['read' => 'manager', 'write' => 'manager']
                    //'baseUrl' => '@web',
                    //'basePath' => '@webroot',
                    //'path'   => 'config', // web/config子目录作用根目录
                    //'access' => ['read' => 'manager', 'write' => 'manager']
                ]
            ]
        ]
    ],

    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'request' => [
            'cookieValidationKey' => env('BACKEND_COOKIE_VALIDATION_KEY'),
        ],

        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl' => ['sign-in/login'],
            'enableAutoLogin' => true,
            'as afterLogin' => 'common\behaviors\LoginTimestampBehavior'
        ],

        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => false,
            'baseUrl' => '@web/assets',
            //'baseUrl' => YII_ENV_DEV ? '@web/assets' : 'http://oss-wxp-backend.mysite.com/assets',
        ],

        /*
                'plugins' => [
                    'class' => lo\plugins\components\PluginsManager::class,
                    'appId' => 2 // lo\plugins\BasePlugin::APP_BACKEND or our appId
                ],
                'view' => [
                    'class' => lo\plugins\components\View::class,
                ]
        */

    ],

    'modules' => [
        'i18n' => [
            'class' => 'backend\modules\i18n\Module',
            'defaultRoute' => 'i18n-message/index'
        ],

        'imagemanager' => [
            'class' => 'noam148\imagemanager\Module',
            //set accces rules ()
            'canUploadImage' => true,
            'canRemoveImage' => function () {
                return true;
            },
            // Set if blameable behavior is used, if it is, callable function can also be used
            'setBlameableBehavior' => true,
            //add css files (to use in media manage selector iframe)
            'cssFiles' => [
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css',
            ],
        ],
        /*
                'plugins' => [
                    'class' => 'lo\plugins\Module',
                    'pluginsDir'=>[
                        '@lo/plugins/core', // default dir with core plugins
                        '@common/plugins', // dir with our plugins
                    ]
                ],
        */

        'gridview' => [
            'class' => '\kartik\grid\Module'
        ],
    ],

    /*
        'as globalAccess'=>[
            'class'=>'\common\behaviors\GlobalAccessBehavior',
            'rules'=>[
                [
                    'controllers'=>['sign-in'],
                    'allow' => true,
                    'roles' => ['?'],
                    'actions'=>['login']
                ],
                [
                    'controllers'=>['sign-in'],
                    'allow' => true,
                    'roles' => ['@'],
                    'actions'=>['logout']
                ],
                [
                    'controllers'=>['site'],
                    'allow' => true,
                    'roles' => ['?', '@'],
                    'actions'=>['error']
                ],
                [
                    'controllers'=>['debug/default'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'controllers'=>['user'],
                    'allow' => true,
                    'roles' => ['administrator'],
                ],
                [
                    'controllers'=>['user'],
                    'allow' => false,
                ],

                [
                    'allow' => true,
                    'roles' => ['manager'],
                ]

            ]
        ]
    */
];

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'yii2-starter-kit' => Yii::getAlias('@backend/views/_gii/templates'),
                    'my_template' => Yii::getAlias('@common/wosotech/gii/crud/my_template')
                ],
                'template' => 'yii2-starter-kit',
                'messageCategory' => 'backend'
            ]
        ]
    ];
}

return $config;
