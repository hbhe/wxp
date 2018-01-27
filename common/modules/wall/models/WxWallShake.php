<?php

namespace common\modules\wall\models;

use Yii;
use common\models\WxGh;
use common\models\WxUser;

/**
 * This is the model class for table "wx_wall_shake".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property integer $number
 * @property integer $activitduration
 * @property integer $awardsnumber
 * @property string $activityname
 * @property string $created_at
 * @property string $updated_at
 */
class WxWallShake extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_wall_shake';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','activitduration','awardsnumber'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 64],
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
            'number' => '次数',
            'activitduration' => '活动时长',
            'awardsnumber' => '奖项个数',
            'activityname' => '活动名称',
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

    public static function getWallShakeQrUrl($gh_id)
    {
        $app = WxGh::findOne(['gh_id' => $gh_id])->getWxApp()->getApplication();                   
        $qrcode = $app->qrcode;            

        $result = $qrcode->temporary(self::getWallShakeSceneId($gh_id));
        $ticket = $result->ticket;
        $expireSeconds = $result->expire_seconds;
        $url1 = $result->url;    
        $url = $qrcode->url($ticket);
        //yii::error("$url1,$url"); 
        return $url;
    }
    public function getGh() {
        return $this->hasOne(WxGh::className(), ['id' => 'gh_id']);
    }
    public function getWxUser() {
        return $this->hasOne(WxUser::className(), ['openid' => 'openid']);
    }
    public static function getNewMessageAjaxs($params)
    {
   //     echo 1;exit;
        $gh_id = $params['gh_id'];
        $gh = \common\wosotech\Util::getSessionGh();
        $activityname=$gh->getKeyStorage('common.module.wallshake.title', '');
        $models = (new \yii\db\ActiveQuery(WxWallShake::className()))
        ->andFilterWhere(['gh_id' => $gh_id])
        ->andFilterWhere(['activityname' => $activityname])
      //->andFilterWhere(['is_checked' => 0])
        ->orderBy(['number' => SORT_DESC])
        ->limit(20)
        ->all();
        $key=Yii::$app->cache->get('data_key');
        $arr = [];
        foreach($models as $model) {

            $row = [];
            $row['number'] = $model->number;
            $row['nickname'] = $model->wxUser->nickname;
            $row['headimgurl'] = \common\wosotech\Util::getwxuserHeadimgurl($model->wxUser->headimgurl, 46);
            $arr[] = $row;
        }
        //yii::error(print_r($arr, true));
        return \yii\helpers\Json::encode([
                 'code' => 0,
                 'key'=>$key,
                 'activityname'=>$activityname,
                 'awardsnumber'=>$gh->getKeyStorage("common.module.wallshake.awards", ''),
                 'activitduration'=>$gh->getKeyStorage("common.module.wallshake.duration", ''),
                 'activitstart'=>$gh->getKeyStorage("common.module.wallshake.starttime", ''),
                 'data' => $arr,
        ]);
    } 
}
