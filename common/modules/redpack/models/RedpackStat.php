<?php

namespace common\modules\redpack\models;

use Yii;

/**
 * This is the model class for table "wx_redpack_stat".
 *
 * @property integer $id
 * @property string $gh_id
 * @property integer $recommend_revenue_amount
 * @property integer $recommend_fan_count
 * @property integer $recommend_fan_revenue_count
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class RedpackStat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_redpack_stat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id'], 'required'],
            [['recommend_revenue_amount', 'recommend_fan_count', 'recommend_fan_revenue_count', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 64],
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
            'recommend_revenue_amount' => '红包金额',
            'recommend_fan_count' => '总粉丝数',
            'recommend_fan_revenue_count' => '计酬粉丝数',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
