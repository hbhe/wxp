<?php
/**
 * Require core files
 */
require_once(__DIR__ . '/../helpers.php');

/**
 * Setting path aliases
 */
Yii::setAlias('@base', realpath(__DIR__.'/../../'));
Yii::setAlias('@common', realpath(__DIR__.'/../../common'));
Yii::setAlias('@frontend', realpath(__DIR__.'/../../frontend'));
Yii::setAlias('@backend', realpath(__DIR__.'/../../backend'));
Yii::setAlias('@console', realpath(__DIR__.'/../../console'));
Yii::setAlias('@storage', realpath(__DIR__.'/../../storage'));
Yii::setAlias('@tests', realpath(__DIR__.'/../../tests'));
Yii::setAlias('@mobile', realpath(__DIR__.'/../../mobile'));
Yii::setAlias('@wechat', realpath(__DIR__.'/../../wechat'));
Yii::setAlias('@rest', realpath(__DIR__.'/../../rest'));
Yii::setAlias('@weixin', realpath(__DIR__.'/../../weixin'));

Yii::setAlias('wxpay', '@common/wosotech/wxpay');
/**
 * Setting url aliases
 */
Yii::setAlias('@frontendUrl', env('FRONTEND_URL'));
Yii::setAlias('@backendUrl', env('BACKEND_URL'));
Yii::setAlias('@storageUrl', env('STORAGE_URL'));
Yii::setAlias('@mobileUrl', env('MOBILE_URL'));

Yii::$classMap['yii\base\ArrayableTrait'] = '@common/wosotech/base/ArrayableTrait.php';
Yii::$classMap['yii\helpers\ArrayHelper'] = '@common/wosotech/base/ArrayHelper.php';


