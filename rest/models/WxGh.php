<?php

namespace rest\models;

use Yii;
use yii\db\ActiveRecord;
use yii\filters\RateLimitInterface;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;


/**
 * Class WxGh
 * @package rest\models
 */
class WxGh extends \common\models\WxGh implements IdentityInterface, RateLimitInterface
{

    /**
     * @param int|string $id
     */
    public static function findIdentity($id)
    {

    }

    /**
     * @param mixed $token
     * @param null $type
     * @return ActiveRecord
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //return static::findOne(['accessToken' => $token]);
        return static::find()->where(['or', ['appId' => $token], ['accessToken' => $token]])->one();
    }


    /**
     *
     */
    public function getId()
    {

    }

    /**
     *
     */
    public function getAuthKey()
    {

    }

    /**
     * @param string $authKey
     */
    public function validateAuthKey($authKey)
    {

    }

    public function getRateLimit($request, $action)
    {
        return [
            ArrayHelper::getValue(Yii::$app->params, 'maxRateLimit', 20),
            ArrayHelper::getValue(Yii::$app->params, 'perRateLimit', 1),        // $rateLimit : max 200 requests per 10 seconds
        ];
    }

    public function loadAllowance($request, $action)
    {
        if (false === ($value = yii::$app->cache->get([__CLASS__]))) {
            return $this->getRateLimit($request, $action);
        }
        return $value;
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        yii::$app->cache->set([__CLASS__], [$allowance, $timestamp]);
    }

}

