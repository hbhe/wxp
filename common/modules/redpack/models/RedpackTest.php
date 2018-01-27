<?php

namespace common\modules\redpack\models;

use Yii;

/**
 * This is the model class for table "wx_redpack_test".
 *
 * @property integer $id
 * @property integer $type1
 * @property integer $type2
 * @property string $factor
 * @property integer $real
 * @property integer $sum
 */
class RedpackTest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_redpack_test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type1', 'type2', 'real', 'sum'], 'integer'],
            [['factor'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type1' => '分类',
            'type2' => '分类2',
            'factor' => '因子',
            'real' => '实际',
            'sum' => '总量',
        ];
    }
}
