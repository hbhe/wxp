<?php

namespace common\models;

use common\wosotech\base\ActiveRecord;
use common\wosotech\Util;
use Yii;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "wx_brand".
 *
 * @property integer $id
 * @property integer $client_id
 * @property string $name
 * @property integer $sort_order
 */
class WxBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'sort_order'], 'integer'],
            [['name'], 'string', 'max' => 32],

            [['client_id', 'sort_order'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'client_id' => '客户 ID',
            'name' => '品牌名称',
            'sort_order' => '显示顺序',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['client_id'],
                ],
                'value' => function ($event) {
                    return $this->client_id ?: Util::getSessionGh()->client->id;
                }
            ],
        ];
    }

    public function getClient()
    {
        return $this->hasOne(WxClient::className(), ['id' => 'client_id']);
    }

    public function getModels()
    {
        return $this->hasMany(WxModel::className(), ['brand_id' => 'id']);
    }

    public function getModelCount()
    {
        return $this->hasMany(WxModel::className(), ['brand_id' => 'id'])->count();
    }
    
}
