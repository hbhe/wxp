<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sj_phone_model".
 *
 * @property integer $id
 * @property integer $brand_id
 * @property string $name
 * @property integer $sort_order
 */
class WxModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_model';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'sort_order'], 'integer'],
            [['name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => '品牌ID',
            'name' => '型号名称',
            'sort_order' => '排序',
        ];
    }

    public function getBrand()
    {
        return $this->hasOne(WxBrand::className(), ['id' => 'brand_id']);
    }
    
}
