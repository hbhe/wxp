<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mkt_template_id".
 *
 * @property string $gh_id
 * @property string $template_id_short
 * @property string $template_id
 */
class WxTemplateId extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_template_id';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'template_id_short', 'template_id'], 'required'],
            [['gh_id'], 'string', 'max' => 64],
            [['template_id_short'], 'string', 'max' => 32],
            [['template_id'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gh_id' => 'Gh ID',
            'template_id_short' => 'Template Id Short',
            'template_id' => 'Template ID',
        ];
    }

}
