<?php

namespace common\models;

use common\modules\outlet\models\FansStat;
use Yii;
use yii\helpers\ArrayHelper;
//use common\models\WxMember;

/**
 * This is the model class for table "wx_memberfans".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property string $scene_str
 * @property string $created_at
 * @property string $updated_at
 */
class WxMemberfans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_memberfans';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 32],
            [['openid', 'scene_str'], 'string', 'max' => 64],
            [['visit_last_time', 'visit_count', 'is_paid'], 'safe'],            
            [['is_paid'], 'default', 'value' => 0],
            [['subscribe'], 'default', 'value' => 1],
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
            'scene_str' => 'Scene_str', // parent scene_str
            'created_at' => '关注时间',
            'subscribe' => '是否关注',//0:否，1;关注
            'updated_at' => 'Updated At',
            'visit_last_time' => 'Last Time',  //最后访问时间
            'visit_count' => 'Visit Count', // 访问次数
            'is_paid' => 'Paid Flag', // 此粉丝已计酬
        ];
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $parent = $this->parentMember;
            if (null === $parent) {
                return;
            }
            $parent->vermicelli += 1;
            $parent->save();
        }            
    }    

    public function getWxQrlimit() {
        return $this->hasOne(\common\models\WxQrlimit::className(), ['gh_id' => 'gh_id', 'scene_str' => 'scene_str']);
    }
    
    public function getWxUser() {
        return $this->hasOne(\common\models\WxUser::className(), ['openid' => 'openid']);
    }

    public function getParentMember()
    {
        return $this->hasOne(\common\models\WxMember::className(), ['openid' => 'action_name'])->viaTable('wx_qrlimit', ['gh_id' => 'gh_id', 'scene_str' => 'scene_str']);
    }

    static public function statScore($gh_id, $date)
    {
        $rows = WxMemberfans::find()
            ->select(['area', 'count(*) AS score', 'created_at'])
            ->andWhere(['gh_id' => $gh_id])
            ->andWhere(['DATE(created_at)' => $date])
            ->groupBy(['area'])
            ->asArray()
            ->all();

        foreach ($rows as $row) {
            if ($score = ArrayHelper::getValue($row, 'score')) {
                $area = ArrayHelper::getValue($row, 'area');
                $model = FansStat::findOne(['gh_id' => $gh_id, 'area' => $area, 'created_at' => $date]);
                if (null === $model) {
                    $model = new FansStat();
                }
                $model->gh_id = $gh_id;
                $model->area = $area;
                $model->score = $score;
                $model->created_at = $date;
                $model->save();
            }
        }
    }

}
