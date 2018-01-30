<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wx_activity".
 *
 * @property integer $id
 * @property integer $holiday
 * @property integer $category
 * @property string $title
 * @property string $detail
 * @property integer $status
 * @property integer $logo_id
 * @property string $created_at
 * @property string $updated_at
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['holiday', 'category', 'status', 'logo_id'], 'integer'],
            [['detail', 'sid'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 128],

            [['holiday', 'category', 'status', 'sort_order', 'logo_id'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '活动ID',
            'sid' => '编码',
            'holiday' => '节日',
            'category' => '类型',
            'title' => '活动标题',
            'detail' => '活动说明',
            'status' => '状态',
            'logo_id' => '活动图片',
            'sort_order' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function behaviors()
    {
        return [
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
        ];

    }

    /**
     * @inheritdoc
     * @return ActivityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActivityQuery(get_called_class());
    }

    const HOLIDAY_DOUBLE_NINTH = 1;
    const HOLIDAY_GHOST = 2;
    const HOLIDAY_NONE = 99;

    public static function getHolidayOptionName($key = null)
    {
        $arr = [
            static::HOLIDAY_DOUBLE_NINTH => '重阳节',
            static::HOLIDAY_GHOST => '万圣节',
            static::HOLIDAY_NONE => '非节日',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    const CATEGORY_LOTTERY = 1;
    const CATEGORY_GAME = 2;
    const CATEGORY_HELP = 3;
    const CATEGORY_VOTE = 4;
    const CATEGORY_SIGN = 5;
    const CATEGORY_OTHER = 99;

    public static function getCategoryOptionName($key = null)
    {
        $arr = [
            static::CATEGORY_LOTTERY => '常规抽奖',
            static::CATEGORY_GAME => '游戏营销',
            static::CATEGORY_HELP => '助力活动',
            static::CATEGORY_VOTE=> '投票竞猜',
            static::CATEGORY_SIGN => '签到活动',
            static::CATEGORY_OTHER => '其它',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public function getLogoUrl($w = 9999, $h = 9999)
    {
        if (!empty($this->logo_id)) {
            return Yii::$app->imagemanager->getImagePath($this->logo_id, $w, $h);
        }
        return '';
    }

    const STATUS_ENABLE = 0;
    const STATUS_DISABLE = 1;

    public static function getStatusOptionName($key = null)
    {
        $arr = [
            static::STATUS_ENABLE => '上架',
            static::STATUS_DISABLE => '下架',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }
}
