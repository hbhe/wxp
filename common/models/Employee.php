<?php

namespace common\models;

use common\wosotech\Util;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\linkmany\LinkManyBehavior;

/**
 * This is the model class for table "wx_employee".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $name
 * @property string $mobile
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'name', 'gh_id'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 16],

            [['department_ids'], 'safe'], // array in linkGroupBehavior
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
            'name' => '员工姓名',
            'mobile' => '员工手机号',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['gh_id'],
                ],
                'value' => function ($event) {
                    return $this->gh_id ?: Util::getSessionGhid();
                }
            ],

            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s'),
            ],

            'linkGroupBehavior' => [
                'class' => LinkManyBehavior::className(),
                'relation' => 'departments',
                'relationReferenceAttribute' => 'department_ids',
            ],

        ];

    }

    public function load($data, $formName = null)
    {
        if (isset($data['department_ids']) && is_string($data['department_ids'])) {
            $data['department_ids'] = explode(',', $data['department_ids']);
        }
        return parent::load($data, $formName);
    }

    public function getDepartments()
    {
        return $this->hasMany(Department::className(), [
            'id' => 'department_id',
        ])->viaTable('wx_department_employee', ['employee_id' => 'id']);
    }
}
