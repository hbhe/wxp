<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

use common\models\WxUser;

/**
 * This is the model class for table "wx_msg_log".
 *
 * @property integer $id
 * @property string $ToUserName
 * @property string $FromUserName
 * @property integer $CreateTime
 * @property string $MsgType
 * @property string $WholeMsg
 * @property integer $created_at
 * @property integer $updated_at
 */
class WxMsgLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wx_msg_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ToUserName', 'FromUserName', 'created_at', 'updated_at'], 'required'],
            [['CreateTime', 'created_at', 'updated_at'], 'integer'],
            [['ToUserName', 'FromUserName'], 'string', 'max' => 64],
            [['MsgType'], 'string', 'max' => 255],
            [['WholeMsg'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'ToUserName' => 'To User Name',
            'FromUserName' => 'From User Name',
            'CreateTime' => 'Create Time',
            'MsgType' => 'Msg Type',
            'WholeMsg' => 'Whole Msg',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function getWxUser() {
        return $this->hasOne(WxUser::className(), ['openid' => 'FromUserName']);
    }

    public function getMsg() {
        $msg = Json::decode($this->WholeMsg, true);
        switch ($msg['MsgType']) {
            case 'text':
                return $msg['Content'];
            case 'event':
                switch ($msg['Event']) {
                    case 'VIEW':
                        return WxMenu::getViewMenuPathInfo($this->ToUserName, $msg['EventKey']);
                    case 'subscribe':
                        return '关注';
                    case 'unsubscribe':
                        return '取消关注';
                    case 'SCAN':
                        return '扫码';
                    default:
                        return $msg['Event'];
                }                
            default:
                return $msg['MsgType'];
        }
    }

    public static function getMsgCount($gh_id, $start_date = NULL, $end_date = NULL) {
        $query = self::find()->where([
            'ToUserName' => $gh_id,
        ]);
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'created_at', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'created_at', strtotime($end_date)
            ]);

        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, WxUser::CACHE_DURATION);
        return $result;
    }

    public static function getMsgUserCount($gh_id, $start_date = NULL, $end_date = NULL) {
        $query = self::find()->where([
            'ToUserName' => $gh_id,
        ]);
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'created_at', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'created_at', strtotime($end_date)
            ]);

        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->groupBy('FromUserName')->count();
        }, WxUser::CACHE_DURATION);
        return $result;
    }

    public static function badgeDataAjax($gh_id, $start_date, $end_date) {
        $key = __METHOD__ . "$gh_id, $start_date, $end_date";
        $data = yii::$app->cache->get($key);
        if ($data !== false) {
            return $data;
        }
        $data = Json::encode([
            'data1' => self::getMsgUserCount($gh_id, $start_date, $end_date),
            'data2' => self::getMsgCount($gh_id, $start_date, $end_date),
        ]);
        yii::$app->cache->set($key, $data, WxUser::CACHE_DURATION);
        return $data;
    }

    public static function getActivityTimelineAjax($gh_id, $start_date, $end_date) {
        $key = __METHOD__ . "$gh_id, $start_date, $end_date";
        $data = yii::$app->cache->get($key);
        if ($data !== false) {
            return $data;
        }

        $results = [];
        $startTimestamp = strtotime($start_date);
        $endTimestamp = strtotime($end_date);
        $length = 0;

        while ($startTimestamp < $endTimestamp) {
            $day_users = self::getMsgUserCount($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            $day_msgs = self::getMsgCount($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));

            $results['data1'][] = [$startTimestamp * 1000, $day_users];
            $results['data2'][] = [$startTimestamp * 1000, $day_msgs];

            $length += 1;
            $startTimestamp += 24 * 3600;
        }
        $results['length'] = $length;
        $data = Json::encode($results);
        yii::$app->cache->set($key, $data, WxUser::CACHE_DURATION);
        return $data;
    }
    
    public static function getLastVisitTime($gh_id, $openid) {
        $log = self::find()->where([
            'ToUserName' => $gh_id,
            'FromUserName' => $openid,
        ])->orderBy('created_at DESC')->one();
        return $log->created_at;
    }

}
