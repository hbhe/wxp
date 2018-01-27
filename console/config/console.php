<?php
return [

    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'command-bus' => [
            'class' => 'trntv\bus\console\BackgroundBusController',
        ],
        
        'message' => [
            'class' => 'console\controllers\ExtendedMessageController'
        ],
        
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => '@common/migrations/db',
            'migrationTable' => '{{%system_db_migration}}',
            'migrationNamespaces' => [
                'app\migrations',
                //'lo\plugins\migrations',                
                //'common\modules\redpack\migrations',
                //'common\modules\employee\migrations',
                //'common\modules\other\migrations',
                //'common\modules\outlet\migrations',
                //'common\modules\bargain\migrations',
                //'common\modules\present\migrations',
                //'common\modules\secondary\migrations',
            ],            
        ],
        
        'rbac-migrate' => [
            'class' => 'console\controllers\RbacMigrateController',
            'migrationPath' => '@common/migrations/rbac/',
            'migrationTable' => '{{%system_rbac_migration}}',
            'templateFile' => '@common/rbac/views/migration.php'
        ],

    ],
];
