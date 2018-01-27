<?php

namespace common\modules\bargain\models;

use common\wosotech\base\ActiveRecord;
use common\wosotech\Util;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\dynattribute\DynamicAttributeBehavior;

/**
 * This is the model class for table "wx_bargain_item".
 *
 * @property integer $id
 * @property string $gh_id
 * @property integer $topic_id
 * @property string $cat
 * @property string $title
 * @property string $image_url
 * @property integer $price
 * @property integer $end_price
 * @property integer $end_number
 * @property integer $total_count
 * @property integer $rest_count
 * @property integer $send_item_cat
 * @property string $send_how
 * @property string $send_where
 * @property integer $send_date_range_type
 * @property string $send_date_start
 * @property string $send_date_end
 * @property integer $send_date_active_in_days
 * @property integer $send_date_active_len
 * @property string $send_week
 * @property string $sub_title
 * @property string $send_customer_service_tel
 * @property string $send_faq
 * @property integer $send_btn_style
 * @property string $send_btn_label
 * @property string $send_btn_url
 * @property integer $sort
 * @property integer $status
 * @property string $remark
 * @property string $created_at
 * @property string $updated_at
 */
class BargainItem extends ActiveRecord
{
    const CAT_ITEM = 0;
    const CAT_COUPON = 1;
    const RANGE_TYPE_ABSOLUTE = 0;
    const RANGE_TYPE_RELATIVE = 1;
    const SEND_ITEM_CAT_OFFLINE = 0;
    const SEND_ITEM_CAT_WECHAT = 1;
    const SEND_ITEM_CAT_WEB = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_bargain_item';
    }

    public static function getCatOptionName($key = null)
    {
        $arr = [
            static::CAT_ITEM => '实物商品',
            static::CAT_COUPON => '优惠券',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getRangeTypeOptionName($key = null)
    {
        $arr = [
            static::RANGE_TYPE_ABSOLUTE => '绝对时间',
            static::RANGE_TYPE_RELATIVE => '相对时间',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getSendCatOptionName($key = null)
    {
        $arr = [
            static::SEND_ITEM_CAT_OFFLINE => '线下兑奖',
            static::SEND_ITEM_CAT_WECHAT => '公众号兑奖',
            static::SEND_ITEM_CAT_WEB => '网页兑奖',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat', 'status', 'image_id', 'sort', 'price'], 'default', 'value' => 0],

            //[['topic_id', 'price', 'end_price', 'end_number', 'total_count', 'rest_count', 'cat', 'sort', 'status', 'image_id'], 'integer'],
            [['topic_id', 'cat', 'sort', 'status', 'image_id'], 'integer'],
            [['created_at', 'updated_at', 'price'], 'safe'],
            [['remark'], 'string'],
            [['gh_id'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],
            [['topic_id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => BargainTopic::className(), 'targetAttribute' => 'id'],

            [[
                'end_price', 'end_number', 'total_count', 'rest_count',
                'send_item_cat', 'send_how_offline', 'send_where_offline', 'send_how_web', 'send_where_web',
                'send_date_range_type', 'send_date_start', 'send_date_end', 'send_date_active_in_days', 'send_date_active_len',
                'send_week', 'sub_title', 'send_customer_service_tel', 'send_faq',
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gh_id' => '公众号ID',
            'topic_id' => '活动ID',
            'cat' => '奖品类型', // 0:商品, 1:优惠券
            'title' => '商品名称',
            'image_id' => '图片',
            'price' => '商品价格(元)',
            'params' => '参数',
            'sort' => '排序',
            'status' => '状态',
            'remark' => '备注',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',

            'end_price' => '砍价目标（元）',
            'end_number' => '砍价次数',
            'total_count' => '奖品总量',
            'rest_count' => '剩余奖品总数',
            'send_item_cat' => '兑奖方式', // offline:线下兑奖, wechat:公众号兑奖, web:网页兑奖'
            'send_how_offline' => '操作提示',
            'send_where_offline' => '兑奖地址',
            'send_how_web' => '操作提示',
            'send_where_web' => '网页链接',
            'send_date_range_type' => '兑奖期限类型', // 0:绝对时间, 1:相对时间',
            'send_date_start' => '领奖开始日期', // 2017-08-01
            'send_date_end' => '领奖结束日期', // 2017-08-09
            'send_date_active_in_days' => '领取后多少天后开始生效', // 选相对时间时有效, 0-90, 0表示立即生效',
            'send_date_active_len' => '生效后多少天内有效', // 选相对时间时有效, 1-90',
            'send_week' => '领奖时间', // 1,2,3表示周1，周2，周3；为空字符串表示不限时间
            'sub_title' => '副标题',
            'send_customer_service_tel' => '客服电话',
            'send_faq' => '兑奖须知',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['gh_id'],
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
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['rest_count'],
                ],
                'value' => function ($event) {
                    return $this->total_count;
                }
            ],

            [
                'class' => DynamicAttributeBehavior::className(),
                'storageAttribute' => 'params',
                'saveDynamicAttributeDefaults' => false,
                //'allowRandomDynamicAttribute' => true,
                'dynamicAttributeDefaults' => [
                    'total_count' => 1000,
                    'end_price' => 0,
                    'end_number' => 20,
                    'rest_count' => 0, // 冗余字段, 此字段值初始值取total_count, 中奖一个减1
                    'send_item_cat' => 'offline',
                    'send_how_offline' => '凭券联系现场工作人员兑奖',
                    'send_where_offline' => '请填写您的兑奖地址或者门店地址',
                    'send_how_web' => '点击[立即兑奖]跳转到兑奖界面',
                    'send_where_web' => 'http://',
                    'send_date_range_type' => 0,
                    'send_date_start' => '',
                    'send_date_end' => '',
                    'send_date_active_in_days' => 0,
                    'send_date_active_len' => 30,
                    'send_week' => '',
                    'sub_title' => '',
                    'send_customer_service_tel' => '',
                    'send_faq' => '',
                ],
            ],

        ];

    }

    public function getBargainTopic()
    {
        return $this->hasOne(BargainTopic::className(), [
            'id' => 'topic_id',
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields = array_merge($fields, array_keys($this->dynamicAttributes));
        foreach (['image_id'] as $field) {
            $fields[$field] = function ($model) use ($field) {
                return $this->getImgUrl($field);
            };
        }
        unset($fields['params']);

        return $fields;
    }

    public function getImgUrl($field, $w = 9999, $h = 9999)
    {
        if (!empty($this->$field)) {
            return Yii::$app->imagemanager->getImagePath($this->$field, $w, $h);
        }
        return '';
    }

    public function extraFields()
    {
        return ['bargainTopic'];
    }

    public function getImageUrl($w = 9999, $h = 9999)
    {
        return $this->getImgUrl('image_id', $w, $h);
    }

    /*public function afterFind(){
        $this->image_id = Yii::$app->imagemanager->getImagePath($this->image_id, 9999, 9999);
        return parent::afterFind();
    }*/
}
