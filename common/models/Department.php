<?php

namespace common\models;

use common\wosotech\Util;
use paulzi\adjacencyList\AdjacencyListBehavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "wx_department".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $sid
 * @property string $gh_id
 * @property string $title
 * @property string $detail
 * @property string $address
 * @property double $longitude
 * @property double $latitude
 * @property integer $offset_type
 * @property string $telephone
 * @property string $open_time
 * @property integer $is_public
 * @property integer $is_visible
 * @property integer $is_self_operated
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'gh_id'], 'required'],
            [['pid', 'offset_type', 'is_public', 'is_visible', 'is_self_operated', 'status', 'sort_order'], 'integer'],
            [['detail'], 'string'],
            [['longitude', 'latitude'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['sid'], 'string', 'max' => 32],
            [['gh_id'], 'string', 'max' => 64],
            [['title', 'address', 'telephone', 'open_time'], 'string', 'max' => 255],
            [['sid'], 'unique'],

            [['pid', 'offset_type', 'is_public', 'is_visible', 'is_self_operated', 'status', 'sort_order'], 'default', 'value' => 0],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '上级部门ID',
            'sid' => '编码',
            'gh_id' => 'Gh ID',
            'title' => '名称',
            'detail' => '简介',
            'address' => '地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'offset_type' => '坐标类型',
            'telephone' => '联系电话',
            'open_time' => '营业时间',
            'is_public' => '对外部门',
            'is_visible' => '是否上线展示',
            'is_self_operated' => '是否公众号自营',
            'sort_order' => '排序',
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

            [
                'class' => SluggableBehavior::className(),
                'slugAttribute' => 'sid',
                'attribute' => ['title'],
                'ensureUnique' => true,
                'immutable' => true,
            ],
/*
            [
                'class' => PositionBehavior::className(),
                'positionAttribute' => 'sort_order',
                'groupAttributes' => [
                    'pid'
                ],
            ],
*/
            [
                'class' => AdjacencyListBehavior::className(),
                'parentAttribute' => 'pid',
                'sortable' => [
                    'sortAttribute' => 'sort_order',
                ]
            ],

        ];

    }

    public function getEmployeesCount()
    {
        return $this->getEmployees()->count();
    }

    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), [
            'id' => 'employee_id',
        ])->viaTable('wx_department_employee', ['department_id' => 'id']);
    }

}
