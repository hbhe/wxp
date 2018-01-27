<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wx_client".
 *
 * @property integer $id
 * @property string $codename
 * @property string $fullname
 * @property string $shortname
 * @property string $city
 * @property string $province
 * @property string $country
 * @property integer $created_at
 * @property integer $updated_at
 */
class WxClient extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codename', 'fullname', 'shortname'], 'required'],
            [['codename'], 'string', 'max' => 16],
            [['fullname', 'shortname'], 'string', 'max' => 255],
            [['city', 'province', 'country'], 'string', 'max' => 32],
            [['codename'], 'unique'],
            [['logo_id'], 'integer'],                        
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codename' => '代码',
            'fullname' => '全称',
            'shortname' => '简称',
            'city' => '城市',
            'province' => '省份',
            'country' => '国家',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'logo_id' => 'Logo图像',            
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
    
    public function getGhs() {
        return $this->hasMany(WxGh::className(), [
           'client_id' => 'id', 
        ]);
    }
    
    public function getOutlets() {
        return $this->hasMany(Outlet::className(), [
            'client_id' => 'id',
        ]);
    }

    public function getLogoUrl($width = 400, $height = 400, $thumbnailMode = "outbound") {
        return \Yii::$app->imagemanager->getImagePath($this->logo_id, $width, $height, $thumbnailMode);
    }
    
}
