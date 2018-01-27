<?php

namespace common\modules\wall\models;

use Yii;
use common\models\WxGh;
use common\models\WxUser;
use common\modules\walls\models\WxWalls;

/**
 * This is the model class for table "wx_wall_sign".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property integer $is_display
 * @property string $created_at
 * @property string $updated_at
 */
class WxWallSign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_wall_sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_display'], 'integer'],
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
            'is_display' => '是否签到',
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

    public static function getWallSignSceneId($gh_id) {
        return crc32(__METHOD__ . $gh_id);
    }
    
    public static function getWallSignQrUrl($gh_id)
    {
        $app = WxGh::findOne(['gh_id' => $gh_id])->getWxApp()->getApplication();                   
        $qrcode = $app->qrcode;            

        $result = $qrcode->temporary(self::getWallSignSceneId($gh_id));
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
        $gh_id = $params['gh_id'];
    
        $models = (new \yii\db\ActiveQuery(WxWallSign::className()))
        ->andFilterWhere(['gh_id' => $gh_id])
        ->andFilterWhere(['is_display' => 0])
      //->andFilterWhere(['is_checked' => 0])
        ->orderBy(['id' => SORT_ASC])
        ->all();
    
        foreach($models as $model) {
            $model->is_display = 1;
            $model->save();
        }
        
        $arr = [];
        foreach($models as $model) {
           
            $row = [];
            $row['openid'] = $model->openid;
            $row['nickname'] = $model->wxUser->nickname;
            $row['headimgurl'] = \common\wosotech\Util::getwxuserHeadimgurl($model->wxUser->headimgurl, 46);
            $arr[] = $row;
        }
    
        //yii::error(print_r($arr, true));
        return \yii\helpers\Json::encode(['code' => 0, 'data' => $arr]);
    }
    
    public static function getNewMessageAjaxx($params)
    {
        
        $content = $params['content'];
        $openid = $params['openid'];
        $gh_id = \common\wosotech\Util::getSessionGhid();
        //return \yii\helpers\Json::encode(['code' => 0, 'data' => $content]);
        $model = new WxWall();
        $model->gh_id = $gh_id;
        $model->openid = $openid;
        $model->content = trim($content);
        $model->save();
        $wall = WxWall::findOne(['openid' => $openid,'content'=>$content]);
        $id = $wall->id;
        
        $db = \Yii::$app->db->createCommand();
        $db->insert('wx_walls' , ['id'=>$id,'gh_id'=>$gh_id,'openid'=>$openid,'content'=>$content] )->execute();
        
        /* $models = new WxWalls();
        $models->id = $id;
        $models->gh_id = \common\wosotech\Util::getSessionGhid();
        $models->openid = $openid;
        //$model->openid = 'asdddasff';
        $model->content = trim($content);
        $models->save(); */
        //return $content;
        return \yii\helpers\Json::encode(['code' => 0, 'data' => '提交成功']);
    }
}
