<?php
$config = [
    'controllerNamespace' => 'rest\controllers',
    'defaultRoute' => 'site/index',

    'modules' => [
        'v1' => [
            'class' => 'rest\modules\v1\Module',
        ],
    ],

    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'request' => [
            'cookieValidationKey' => getenv('FRONTEND_COOKIE_VALIDATION_KEY'),
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

        'assetManager' => [
            'linkAssets' => false
        ],

        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,

            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/article', 'only' => ['index', 'view', 'options']],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/v1/user', 'only' => ['index', 'view', 'options']],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/wx-user'],
                    'tokens' => [
                        '{id}' => '<id:>',
                    ],
                    'extraPatterns' => [
                        'GET check-subscribe' => 'check-subscribe',
                    ],
                    'only' => ['view', 'check-subscribe'],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/bargain-topic'],
                    'except' => ['delete'],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/bargain-post'],
                    'except' => ['delete'],
                    //'except' => ['delete', 'update'],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/bargain-item'],
                    //'except' => ['delete', 'update' ,'create'],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/bargain-comment'],
                    //'only' => ['index', 'view', 'create'],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/wx-xgdx-member'],
                    'only' => ['view'],
                    'tokens' => [
                        '{id}' => '<id:>',
                    ],
                ],

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/wx-gh'],
                    'extraPatterns' => [
                        'GET get-jssdk-config' => 'get-jssdk-config',
                    ],
                    'only' => ['get-jssdk-config'],
                ],
            ]

        ],

        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'rest\models\WxGh',
            'loginUrl' => null,
            //'enableAutoLogin' => true,
            //'as afterLogin' => 'common\behaviors\LoginTimestampBehavior'
        ],

        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                if (Yii::$app->request->isOptions) {
                    return;
                }
                $response = $event->sender;
                $response->data = [
                    'success' => $response->isSuccessful,
                    'data' => $response->data,
                ];
                $response->statusCode = 200;
            },
        ],
    ]
];

return $config;
