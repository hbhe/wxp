<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

use common\modules\redpack\models\RedpackLog;

/**
 * This is the model class for table "point_log".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property integer $amount
 * @property string $category
 * @property string $comment
 * @property integer $created_at
 * @property integer $updated_at
 */
class PointLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'point_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'openid', 'created_at', 'updated_at'], 'required'],
            [['amount', 'created_at', 'updated_at'], 'integer'],
            [['gh_id', 'openid', 'category'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gh_id' => 'Gh ID',
            'openid' => 'Openid',
            'amount' => 'Amount',
            'category' => 'Category',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }
    
    public function getWxUser() {
        return $this->hasOne(WxUser::className(), [
            'gh_id' => 'gh_id',
            'openid' => 'openid',
        ]);
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (self::CATEGORY_RESET === $this->category) {
            $this->wxUser->updateAttributes([
                'points' => 0,
            ]);
        } else if (key_exists('amount', $changedAttributes)) {
            if (($this->wxUser->points - $changedAttributes['amount'] + $this->amount) >= 0) {
                $this->wxUser->updateAttributes([
                    'points' => $this->wxUser->points - $changedAttributes['amount'] + $this->amount,
                ]);
            } else {
                $this->wxUser->updateAttributes([
                    'points' => 0,
                ]);                
            }
        }
    }
    
    const CATEGORY_INIT = 'initialize';
    const CATEGORY_DAILY = 'daily';
    const CATEGORY_REDPACK = 'redpack';
    const CATEGORY_SHARE_TO_FRIEND = 'share to friend';
    const CATEGORY_SHARE_TO_TIMELINE = 'share to timeline';
    const CATEGORY_YAOYIYAO = 'yaoyiyao';
    const CATEGORY_RESET = 'reset';
    const CATEGORY_DONATE = 'donate';
    const CATEGORY_USER_GET_CARD = 'user get card';
    const CATEGORY_USER_CONSUME_CARD = 'user consume card';
    
    const AMOUNT_DAILY = 5;
    const AMOUNT_SHARE = 5;
    const AMOUNT_USER_GET_CARD = 100;
    const AMOUNT_USER_CONSUME_CARD = 1000;
    
    public static function initializeWxUser($gh_id, $openid) {
        $wxUser = WxUser::findOne(['gh_id' => $gh_id, 'openid' => $openid]);
        $amount = RedpackLog::getUserTotalAmount($wxUser);
        if (!empty($amount) && $amount > 0) {
            $log = new self;
            $log->gh_id = $gh_id;
            $log->openid = $openid;
            $log->category = self::CATEGORY_INIT;
            $log->amount = $amount;
            $log->save(false);        
        }
    }
    
    public static function daily($gh_id, $openid) {
        $start_time = strtotime(date('Y-m-d').' 00:00:00');
        $end_time = strtotime(date('Y-m-d').' 23:59:59');
        $log = self::find()->where([
            'gh_id' => $gh_id,
            'openid' => $openid,
            'category' => self::CATEGORY_DAILY,
        ])->andWhere([
            '>', 'created_at', $start_time
        ])->andWhere([
            '<', 'created_at', $end_time
        ])->one();
        if (empty($log)) {
            $log = new self;
            $log->gh_id = $gh_id;
            $log->openid = $openid;
            $log->category = self::CATEGORY_DAILY;
            $log->amount = self::AMOUNT_DAILY;
            $log->save(false);
        }
    }
    
    public static function redpack($gh_id, $openid, $amount) {
        $log = new self;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->category = self::CATEGORY_REDPACK;
        $log->amount = $amount;
        $log->save(false);
    }
    
    const SHARE_TIME_SPAN = 60 * 30;
    public static function share($gh_id, $openid, $share_to, $category) { 
        $log = NULL;
        if ('timeline' === $share_to) {
            $start_time = time() - self::SHARE_TIME_SPAN;
            $log = self::find()->where([
                'gh_id' => $gh_id,
                'openid' => $openid,
                'category' => self::CATEGORY_SHARE_TO_TIMELINE,
                'comment' => $category,
            ])->andWhere([
                '>', 'created_at', $start_time
            ])->one();
        }
        if (empty($log)) {
            $log = new self;
            $log->gh_id = $gh_id;
            $log->openid = $openid;
            $log->category = 'timeline' === $share_to ? self::CATEGORY_SHARE_TO_TIMELINE : self::CATEGORY_SHARE_TO_FRIEND;
            $log->amount = self::AMOUNT_SHARE;
            $log->comment = $category;
            $log->save(false);
        }
    }
    
    public function getDesc() {
        switch($this->category) {
            case self::CATEGORY_DAILY:
                return '签到';
            case self::CATEGORY_INIT:
                return '初始';
            case self::CATEGORY_REDPACK:
                return '红包';
            case self::CATEGORY_SHARE_TO_FRIEND:
            case self::CATEGORY_SHARE_TO_TIMELINE:
                return '分享';
            case self::CATEGORY_YAOYIYAO:
                return '摇一摇';
            case self::CATEGORY_RESET:
                return '积分清零';
            case self::CATEGORY_DONATE:
                return '赠予';
            case self::CATEGORY_USER_GET_CARD:
                return '领取卡券';
            case self::CATEGORY_USER_CONSUME_CARD:
                return '消费卡券';
        }
    }
    
    public static function consumeAjax($gh_id, $openid, $amount) {
        $log = new self;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->category = self::CATEGORY_YAOYIYAO;
        $log->amount = $amount;
        $log->save(false);
        return Json::encode(['code' => 0]);
    }
    
    public static function reset($gh_id, $openid) {
        $log = new self;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->category = self::CATEGORY_RESET;
        $log->amount = -$log->wxUser->points;
        $log->save(false);
    }
    
    public static function donate($gh_id, $openid, $amount) {
        $log = new self;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->category = self::CATEGORY_DONATE;
        $log->amount = $amount;
        $log->save(false);
    }
    
    public static function user_get_card($gh_id, $openid, $amount) {
        $log = new self;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->category = self::CATEGORY_USER_GET_CARD;
        $log->amount = $amount;
        $log->save(false);
    }
    
    public static function user_consume_card($gh_id, $openid, $amount) {
        $log = new self;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->category = self::CATEGORY_USER_CONSUME_CARD;
        $log->amount = $amount;
        $log->save(false);
    }
}
