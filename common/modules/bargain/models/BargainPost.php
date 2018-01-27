<?php

namespace common\modules\bargain\models;

use common\models\WxUser;
use common\wosotech\base\ActiveRecord;
use common\wosotech\Util;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "wx_bargain_post".
 *
 * @property integer $id
 * @property string $gh_id
 * @property integer $topic_id
 * @property string $openid
 * @property string $name
 * @property string $phone
 * @property integer $item_id
 * @property integer $rest_price
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class BargainPost extends ActiveRecord
{
    const STATUS_ACTIVED = 0;
    const STATUS_DISABLED = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_WINNER = 3;
    const STATUS_RECEIVE = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_bargain_post';
    }

    public static function getStatusOptionName($key = null)
    {
        $arr = [
            static::STATUS_ACTIVED => '有效',
            static::STATUS_DISABLED => '无效',
            static::STATUS_SUCCESS => '已砍成功',
            static::STATUS_WINNER => '已中奖未领取', // 即砍成功并获奖
            static::STATUS_RECEIVE => '已领取',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic_id', 'item_id', 'rest_price', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 64],
            [['name', 'phone'], 'string', 'max' => 11],
            [['ip'], 'ip'],

            [['status', 'rest_price'], 'default', 'value' => 0],
            [['topic_id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => BargainTopic::className(), 'targetAttribute' => 'id'],
            [['item_id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => BargainItem::className(), 'targetAttribute' => 'id'],

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
            'openid' => '活动创建者openid',
            'name' => 'Name',
            'phone' => '手机号码',
            'item_id' => '活动创建者挑选的商品ID',
            'rest_price' => '商品剩余价格',
            'status' => '状态',
            'ip' => 'IP',
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
        if ($insert) {
            //判定库存
            if ($this->bargainItem->rest_count <= 0) {
                $this->addError('item_id', '该商品已经给别人砍完了，请换一件试试！');
                return false;
            }

            //判定活动开始结束时间
            if (time() < strtotime($this->bargainTopic->start_time) || time() > strtotime($this->bargainTopic->end_time)) {
                $this->addError('topic_id', '不在活动时间范围内！');
                return false;
            }

            //判定活动 状态
            if ($this->bargainTopic->status != 1) {
                $this->addError('topic_id', '该活动不在进行中，可能已暂停或者结束！');
                return false;
            }

            // 判定总参与人数限定
            $count = BargainPost::find()->andWhere(['topic_id' => $this->topic_id])->count("id");
            if ($this->bargainTopic->post_num_limit == $count) {
                $this->addError('topic_id', '该活动参与人数已达最大上限！');
                return false;
            }

            //判定是否需要强制关注
            if ($this->bargainTopic->need_subscribe == 1) {
                if ($this->wxUser->subscribe != 1) {
                    $this->addError('topic_id', '您还没有关注，暂时还不能挑战此商品！');
                    return false;
                }
            }

            //判定总参与机会
            $selfcount = BargainPost::find()->andWhere(['topic_id' => $this->topic_id, 'openid' => $this->openid])->count("id");
            if ($this->bargainTopic->post_num_limit_per_person == $selfcount) {
                $this->addError('topic_id', '非常抱歉，您参与该活动的累积次数已超上限！');
                return false;
            }

            //判定每人每天参与机会
            $datacount = BargainPost::find()->andWhere(['topic_id' => $this->topic_id, 'openid' => $this->openid])->andWhere(['like', 'created_at', date('Y-m-d', time())])->count("id");
            if ($this->bargainTopic->post_num_limit_per_person_per_day == $datacount) {
                $this->addError('topic_id', '非常抱歉，您今天的挑战次数已经用完了，请明天再来吧！');
                return false;
            }

            //判定是否能重复挑战商品
            if ($this->bargainTopic->post_can_select_same_item == 0) {
                $sameItem = BargainPost::find()->andWhere(['topic_id' => $this->topic_id, 'openid' => $this->openid, 'item_id' => $this->item_id])->count("id");
                if ($sameItem >= 1) {
                    $this->addError('item_id', '非常抱歉，每件商品您只能挑战一次，请换一件试试！');
                    return false;
                }
            }
        }

        //判定参与地市
        if ($insert) {
            $this->gh_id = Util::getSessionGhid();
            if (empty($this->gh_id)) {
                $this->gh_id = $this->bargainTopic->gh_id;
            }
            $this->rest_price = $this->bargainItem->price - $this->bargainItem->end_price;
        }

        return parent::beforeSave($insert);
    }

    public function afterDelete()
    {
        foreach ($this->bargainComments as $bargainComment) {
            $bargainComment->delete();
        }
        parent::afterDelete();
    }

    public function getWxUser()
    {
        return $this->hasOne(WxUser::className(), [
            'openid' => 'openid',
        ]);
    }

    public function getBargainTopic()
    {
        return $this->hasOne(BargainTopic::className(), [
            'id' => 'topic_id',
        ]);
    }

    public function getBargainCommentsCount()
    {
        return $this->getBargainComments()->count();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBargainComments()
    {
        return $this->hasMany(BargainComment::className(), [
            'post_id' => 'id',
        ]);
    }

    // 帮砍者的帮砍次数
    public function getUserBargainCommentsCount($openid)
    {
        return $this->getBargainComments()->andWhere(['openid' => $openid])->count();
    }

    public function getBargainCommentsTotalPrice()
    {
        $totalPrice = 0;
        foreach ($this->bargainComments as $bargainComment) {
            $totalPrice += $bargainComment->bargain_price;
        }
        return $totalPrice;
    }

    public function getBargainItem()
    {
        return $this->hasOne(BargainItem::className(), [
            'id' => 'item_id',
        ]);
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'bargainCommentsTotalPrice';
        $fields[] = 'bargainCommentsCount';

        return $fields;
    }

    public function extraFields()
    {
        return ['bargainComments', 'bargainTopic', 'bargainItem', 'wxUser'];
    }


}
