<?php

namespace common\modules\bargain\models;

use common\models\WxUser;
use common\wosotech\base\ActiveRecord;
use common\wosotech\Util;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wx_bargain_comment".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property integer $post_id
 * @property integer $bargain_price
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class BargainComment extends ActiveRecord
{
    const STATUS_ENABLE = 0;
    const STATUS_DISABLED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_bargain_comment';
    }

    public static function getStatusOptionName($key = null)
    {
        $arr = [
            static::STATUS_ENABLE => '有效', // 本次砍价有效
            static::STATUS_DISABLED => '无效', // 本次砍价属无效记录，不记入分数
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'bargain_price', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 64],
            [['status', 'bargain_price'], 'default', 'value' => 0],
            [['ip'], 'ip'],

            [['sex'], 'integer'],
            [['nickname', 'city', 'country', 'province'], 'string', 'max' => 64],
            [['headimgurl'], 'string', 'max' => 255],

            [['post_id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => BargainPost::className(), 'targetAttribute' => 'id'],
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
            'openid' => '砍价者openid',
            'post_id' => '发起者创建的活动ID',
            'bargain_price' => '砍价的金额',
            'status' => '状态',
            'ip' => 'IP',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s'),
            ],

            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['ip'],
                ],
                'value' => function ($event) {
                    if (Yii::$app->request->isConsoleRequest) {
                        return '127.0.0.1';
                    }
                    return $this->ip ?: Util::getIpAddr();
                }
            ],
        ];

    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if (empty($this->openid)) {
                    $this->addError('openid', '用户openid不能为空');
                    return false;
                }
                $this->gh_id = Util::getSessionGhid();
                if (empty($this->gh_id)) {
                    $this->gh_id = $this->bargainPost->gh_id;
                }
                // 如参与者创建的活动已失效
                if (empty($this->bargainPost->bargainTopic)) {
                    $this->addError('post_id', '无此商品！');
                    return false;
                }

                if (!in_array($this->bargainPost->status, [BargainPost::STATUS_ACTIVED])) {
                    $this->addError('post_id', '此参与者的活动无效或者已结束');
                    return false;
                }

                // 如公众号创建的活动已关闭
                if (!in_array($this->bargainPost->bargainTopic->status, [BargainTopic::STATUS_DOING])) {
                    $this->addError('post_id', '此活动不在进行中');
                    return false;
                }

                // 开始时间
                if (time() < strtotime($this->bargainPost->bargainTopic->start_time)) {
                    $this->addError("bargain_price", "活动还没有开始");
                    return false;
                }

                // 结束时间
                if (time() > strtotime($this->bargainPost->bargainTopic->end_time)) {
                    $this->addError("bargain_price", "活动已经结束");
                    return false;
                }

                // 库存已完是否还让玩?
                if ($this->bargainPost->bargainItem->rest_count <= 0) {
                    $this->addError("bargain_price", "该商品已经给别人抢先一步砍完了！");
                    return false;
                }

                // 砍价者的帮砍次数超过n
                if ($this->bargainPost->getUserBargainCommentsCount($this->openid) >= 1) {
                    $this->addError("bargain_price", "您已经砍过了不能再砍了!");
                    return false;
                }
                $this->bargain_price = $this->getPrice();

                if (!Yii::$app->request->isConsoleRequest) {
                    // 从cache中取出获得和粉丝昵称，图像等，也保存到帮砍记录中
                    if (!empty($openidInfo = Yii::$app->cache->get('openidInfo-' . $this->openid))) {
                        $this->load($openidInfo, '');
                    }
                }
            }
            return true;
        }

        return false;
    }

    public function getPrice()
    {
        // 检查是不是最后一次
        $count = $this->bargainPost->bargainCommentsCount + 1;
        if ($count >= $this->bargainPost->bargainItem->end_number) {
            return $this->bargainPost->rest_price;
        } else {
            // 不是最后一次,求剩余次数
            $surplus = $this->bargainPost->bargainItem->end_number - $this->bargainPost->bargainCommentsCount;
            if ($surplus % 2 == 0) {
                $pow = 1 - pow(10 / ($this->bargainPost->bargainItem->price - $this->bargainPost->bargainItem->end_price), 1 / $this->bargainPost->bargainItem->end_number);
                return round($this->bargainPost->rest_price * $pow);
            } else {
                return round(mt_rand($this->bargainPost->rest_price / (2 * $surplus), $this->bargainPost->rest_price / $surplus));
            }
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->bargainPost->rest_price = $this->bargainPost->rest_price - $this->bargain_price;
        // 砍完后, 如果奖品剩余价格为0
        if ($this->bargainPost->rest_price <= 0) {
            // 如此商品还有剩余数, 即宣布中奖, 同时剩余数减1
            if ($this->bargainPost->bargainItem->rest_count > 0) {
                $this->bargainPost->status = BargainPost::STATUS_WINNER;
                $this->bargainPost->bargainItem->rest_count--;
                if (!$this->bargainPost->bargainItem->save()) {
                    Yii::error(['save bargainItem rest_count error', __METHOD__, __LINE__, $this->bargainPost->bargainItem->getErrors()]);
                }
            } else {
                // 有时奖品剩余数为0了，但是还是支持继续砍，砍完后只是成功，并不表示获奖
                $this->bargainPost->status = BargainPost::STATUS_SUCCESS;
            }
        }

        if (!$this->bargainPost->save()) {
            Yii::error(['modify post rest_price error', __METHOD__, __LINE__, $this->bargainPost->getErrors()]);
        }
        return true;
    }

    public function getWxUser()
    {
        return $this->hasOne(WxUser::className(), [
            'openid' => 'openid',
        ]);
    }

    public function getBargainPost()
    {
        return $this->hasOne(BargainPost::className(), [
            'id' => 'post_id',
        ]);
    }

    public function extraFields()
    {
        return ['wxUser'];
    }
}
