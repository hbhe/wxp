<?php
$config = [

    'homeUrl'=>Yii::getAlias('@mobileUrl'),
    'controllerNamespace' => 'mobile\controllers',
    'defaultRoute' => 'site/index',
    'bootstrap' => ['maintenance'],
    'modules' => [
        'user' => [
            'class' => 'mobile\modules\user\Module',
            //'shouldBeActivated' => true
        ],
    	'wall' => [
    		'class' => 'mobile\modules\wall\wall',
    				
    	],
        'api' => [
            'class' => 'mobile\modules\api\Module',
            'modules' => [
                'v1' => 'mobile\modules\api\v1\Module'
            ]
        ],

        'wxpay' => [
            'class' => 'wxpay\Module',
        ],  
        
        'wall' => [
            'class' => 'common\modules\wall\Module',
        ],  
        'vote' => [
            'class' => 'common\modules\vote\Module',
        ], 
        
/*
        'redpack' => [
            'class' => 'common\modules\redpack\Module',
        ],
        
        'campaign' => [
            'class' => 'common\modules\campaign\Module',
        ],
        
        'livingfair' => [
            'class' => 'common\modules\livingfair\Module',
        ],
        
        'games' => [
            'class' => 'common\modules\games\Module',
        ],     
*/        
    ],
    
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'clientId' => env('GITHUB_CLIENT_ID'),
                    'clientSecret' => env('GITHUB_CLIENT_SECRET')
                ],
                
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => env('FACEBOOK_CLIENT_ID'),
                    'clientSecret' => env('FACEBOOK_CLIENT_SECRET'),
                    'scope' => 'email,public_profile',
                    'attributeNames' => [
                        'name',
                        'email',
                        'first_name',
                        'last_name',
                    ]
                ]
            ]
        ],
        
        'errorHandler' => [
            'errorAction' => 'site/error'
        ],
        
        'maintenance' => [
            'class' => 'common\components\maintenance\Maintenance',
            'enabled' => false,
        ],
        
        'request' => [
            'cookieValidationKey' => env('FRONTEND_COOKIE_VALIDATION_KEY'),
            //'baseUrl' => YII_ENV_DEV ? null : 'http://wxp-mobile.oss-cn-shanghai.aliyuncs.com',
            //'baseUrl' => YII_ENV_DEV ? null : 'http://oss-wxp-mobile.mysite.com',
        ],

        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'baseUrl' => YII_ENV_DEV ? '@web/assets' : 'http://oss-wxp-mobile.mysite.com/assets',
        ],

        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl'=>['/user/sign-in/login'],
            'enableAutoLogin' => true,
            'as afterLogin' => 'common\behaviors\LoginTimestampBehavior'
        ]
    ]
];

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'class'=>'yii\gii\Module',
        'generators'=>[
            'crud'=>[
                'class'=>'yii\gii\generators\crud\Generator',
                'messageCategory'=>'mobile'
            ]
        ]
    ];
}

return $config;
