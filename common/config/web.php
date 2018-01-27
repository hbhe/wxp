<?php
$config = [
    'components' => [
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => env('LINK_ASSETS'),
            'appendTimestamp' => YII_ENV_DEV,
            'assetMap' => [
                'jquery.js' => '//cdn.bootcss.com/jquery/2.2.4/jquery.min.js',
                'bootstrap.css' => '//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css',
                'bootstrap.js' => '//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js',
                'jquery-ui.css' => '//cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.css',
                'jquery-ui.js' => '//cdn.bootcss.com/jqueryui/1.11.4/jquery-ui.min.js',
                'AdminLTE.min.css' => '//cdn.bootcss.com/admin-lte/2.3.5/css/AdminLTE.min.css'
            ],
        ]
    ],
    
/*
    // Change application language according to user profile or browser header(if not login)
    'as locale' => [
        'class' => 'common\behaviors\LocaleBehavior',
        'enablePreferredLanguage' => true
    ]
*/ 

];

if (YII_DEBUG && YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.33.1', '58.19.5.*'],
    ];
}

if (YII_ENV_DEV) {
    $config['modules']['gii'] = [
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.33.1'],
    ];
}


return $config;
