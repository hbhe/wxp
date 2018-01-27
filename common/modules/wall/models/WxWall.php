<?php
namespace common\modules\wall\models;

use Yii;

use \common\models\WxUser;
require_once Yii::getAlias('@common/3rdlibs/php-emoji/emoji.php');


/**
 * This is the model class for table "wx_wall".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property string $content
 * @property integer $is_checked
 * @property integer $is_wall
 * @property string $created_at
 * @property string $updated_at
 */
class WxWall extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_wall';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_checked', 'is_wall'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 64],
            [['content'], 'string', 'max' => 1024],
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
            'content' => '消息内容',
            'is_checked' => '审核通过',
            'is_wall' => '已上墙',
            'is_from_openid' => '消息来源',            // 1: 表示由个人向公众号发消息, 0:公众号向个人发
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

    public function getGh() {
        return $this->hasOne(WxGh::className(), ['id' => 'gh_id']);
    }

    public function getWxuser() {
        return $this->hasOne(WxUser::className(), ['openid' => 'openid']);
    }

    public static function getNewMessageAjax($params)    
    {
        $gh_id = $params['gh_id'];

        $models = (new \yii\db\ActiveQuery(WxWall::className()))
            ->andFilterWhere(['gh_id' => $gh_id])
            ->andFilterWhere(['is_checked' => 1])            
//            ->andFilterWhere(['is_checked' => 0])            
            ->andFilterWhere(['is_wall' => 0])                        
            ->orderBy(['id' => SORT_ASC])
            ->all();

        foreach($models as $model) {
            $model->is_wall = 1;
            $model->save();
        }

        $arr = [];
        foreach($models as $model) {
            $row = [];
            $row['openid'] = $model->openid;
            $row['nickname'] = $model->wxuser->nickname;
            $row['headimgurl'] = \common\wosotech\Util::getwxuserHeadimgurl($model->wxuser->headimgurl, 46);            
            $row['content'] = \common\wosotech\Util::qqface_convert_html(emoji_unified_to_html(emoji_softbank_to_unified($model->content)));            
            $arr[] = $row;
        }
        
        //yii::error(print_r($arr, true));
        return \yii\helpers\Json::encode(['code' => 0, 'data' => $arr]);
    }


}
