<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wx_qrlimit".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $action_name
 * @property string $scene_str
 * @property string $ticket
 * @property integer $kind
 * @property string $created_at
 * @property string $updated_at
 */
class WxQrlimit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_qrlimit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kind'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 32],
            [['action_name'], 'string', 'max' => 64],
            [['scene_str'], 'string', 'max' => 16],
            [['ticket'], 'string', 'max' => 128],
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gh_id' => 'Gh ID',
            'action_name' => 'Action Name',         // object id, for example, openid, office_id, ...
            'scene_str' => 'Scene Str',             // qr id
            'ticket' => 'Ticket',               // to get qr image url , no use
            'kind' => 'Kind', // 0: openid, 1:office, ...
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getGh() {
        return $this->hasOne(WxGh::className(), ['gh_id' => 'gh_id']);
    }
    
    public function getWxUser(){
        return $this->hasOne(\common\models\WxUser::className(), ['openid' => 'action_name']);
    }
    
    public function getWxMember() {
        return $this->hasOne(\common\models\WxMember::className(), ['openid' => 'action_name']);
    }

    public function getTicketFromCache($expireSeconds = null) {
        $key = __METHOD__ . $this->gh_id . $this->scene_str;
        $data = yii::$app->cache->get($key);
        if ($data !== false) {
            return $data;
        }

        $wxapp = $this->gh->getWxApp('snsapi_base', false)->getApplication();
        $qrcode = $wxapp->qrcode;        
        if (null === $expireSeconds) {
            $result = $qrcode->forever($this->scene_str);
            \yii::$app->cache->set($key, $result->ticket, 2592000);
        } else {
            $result = $qrcode->temporary($this->scene_str, $expireSeconds);     // scene_str should be integer str
            \yii::$app->cache->set($key, $result->ticket, empty($result->expire_seconds) ? 30 : $result->expire_seconds - 100);        
        }
        
        return $result->ticket;        
    }
    
}
