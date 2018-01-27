<?php

namespace common\modules\redpack\models;

use common\models\WxGh;
use common\models\WxUser;
use common\models\WxMember;
use Yii;

/**
 * This is the model class for table "redpack_log".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property string $mobile
 * @property string $category
 * @property integer $amount
 * @property integer $sendtime
 * @property integer $created_at
 * @property integer $updated_at
 */
class RedpackLog extends \yii\db\ActiveRecord {

//    public $sum_amount;

    const STATUS_UNSEND = 1;
    const STATUS_SENT = 2;
    const STATUS_RECV = 3;
    const STATUS_REFUND = 4;
    const STATUS_SEND_ERROR = 9;
    const CATEGORY_NONE = 0;
    const CATEGORY_RECOMMEND = 1;
    const CATEGORY_SUBSCRIBE = 2;
    const CATEGORY_SHARE = 3;
    const CATEGORY_SHARE_TOP10 = 4;
    const CATEGORY_YAOYIYAO = 5;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wx_redpack_log';
    }

    public static function getIsRevenueOption($key = null)
    {
        $arr = array(
            '0' => '提现',
            '1' => '记账',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function getStatusOption($key = null)
    {
        $arr = [
            static::STATUS_UNSEND => '待发放',    // 审核中
            static::STATUS_SENT => '已发放',
            static::STATUS_RECV => '已领取',
            static::STATUS_REFUND => '未领取退回',
            static::STATUS_SEND_ERROR => '异常',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function writeReveneRecommend($gh_id, $openid, $amount, $openid_another, $comment = '') {
        $log = new RedpackLog();
        $log->is_revenue = 1;
        $log->category = self::CATEGORY_RECOMMEND;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->openid_another = $openid_another;
        $log->amount = $amount;
        $log->comment = $comment;
        if (!$log->save(false)) {
            Yii::error([__METHOD__, __LINE__, $log->toArray()]);
            return false;
        }
        return true;
    }

    public static function generateConsumes($gh_id)
    {
        $params = \common\modules\redpack\Module::getParams($gh_id);
        $models = WxMember::find()
            ->select('gh_id, openid, redpack_balance')
            ->andWhere([
                'gh_id' => $gh_id,
                'redpack_status' => WxMember::REDPACK_STATUS_OK,
            ])
            ->andWhere(['>=', 'redpack_balance', $params['redpack.amount.recommend.consume.start']])
            ->all();

        foreach ($models as $model) {
            $amount = intval($model->redpack_balance / $params['redpack.amount.recommend.consume.step']) * $params['redpack.amount.recommend.consume.step'];
            if ($amount >= 100) {
                RedpackLog::writeConsume($model->gh_id, $model->openid, $amount > 20000 ? 20000 : $amount);
            }
        }
    }

    public static function writeConsume($gh_id, $openid, $amount, $comment = '') {
        if ($amount < 1) {
            yii::error([__METHOD__, __LINE__, $gh_id, $openid, $amount, $comment]);
            return false;
        }
        $log = new RedpackLog();
        $log->is_revenue = 0;
        $log->category = self::CATEGORY_NONE;
        $log->status = self::STATUS_UNSEND;
        $log->gh_id = $gh_id;
        $log->openid = $openid;
        $log->amount = $amount;
        $log->comment = $comment;
        if (!$log->save(false)) {
            yii::error([__METHOD__, __LINE__, $log->toArray()]);
            return false;
        }
        return true;
    }

    public static function sendRedpacks($gh_id) {
        $params = \common\modules\redpack\Module::getParams($gh_id);
        $models = RedpackLog::find()
            ->andWhere([
                'gh_id' => $gh_id,
                'status' => RedpackLog::STATUS_UNSEND,
                'is_revenue' => 0,
            ])
            ->andWhere(['>', 'amount', 0])
            ->all();

        $gh = WxGh::findOne(['gh_id' => $gh_id]);
        foreach ($models as $model) {
            if (empty($model->WxMember) || WxMember::REDPACK_STATUS_FRONZEN == $model->WxMember->redpack_status) {
                continue;
            }

            $mch_billno = $gh->sendRedpack($model->openid, $model->amount, $params['redpack.wishing.recommend'], $params['redpack.test']);
            if ($mch_billno) {
                $model->mch_billno = $mch_billno;
                $model->sendtime = date('Y-m-d H:i:s');
                $model->status = RedpackLog::STATUS_SENT;
                if (!$model->save(false)) {
                    yii::error([__METHOD__, __LINE__, $model->toArray(), $model->getErrors()]);
                }

                if (!empty($params['redpack.aftersend.prompt.custom.text'])) {
                    $content = strtr($params['redpack.aftersend.prompt.custom.text'], [
                        '{nickname}' => empty($model->wxUser->nickname) ? '' : $model->wxUser->nickname,
                        '{amount}' => $model->amount,
                        '{gh_title}' => $gh->title,
                    ]);
                    $gh->sendCustomText($model->openid, $content);
                }
            }
        }
    }

    public static function checkSendRedpacks($gh_id) {
        $params = \common\modules\redpack\Module::getParams($gh_id);
        $models = RedpackLog::find()
            ->andWhere([
                'gh_id' => $gh_id,
                'status' => RedpackLog::STATUS_SENT,
                'is_revenue' => 0,
            ])
            ->andWhere(['>', 'created_at', date("Y-m-d H:i:s", strtotime("-1 month"))])
            ->all();

        $gh = WxGh::findOne(['gh_id' => $gh_id]);
        foreach($models as $model) {
            if (empty($model->mch_billno)) {
                continue;
            }
            $result = $gh->querySendRedpack($model->mch_billno);
            if (false === $result) {
                continue;
            }
            if ('RECEIVED' == $result['status']) {
                $model->status = RedpackLog::STATUS_RECV;
                if (!empty($result['hblist']['hbinfo']['rcv_time'])) {
                    $model->recvtime = $result['hblist']['hbinfo']['rcv_time'];
                }
                $model->save(false);
            } else if ('FAILED' == $result['status']) {
                $model->status = RedpackLog::STATUS_SEND_ERROR;
                $model->comment = $result['status'] . ':' . $model->amount;
                $model->amount = 0;
                $model->save(false);
            } else if ('REFUND' == $result['status']) {
                $model->status = RedpackLog::STATUS_REFUND;
                $model->comment = $result['status'] . ':' . $model->amount;
                $model->amount = 0;
                $model->save(false);
            }
        }
    }

    public static function getUserRevenueAmount($openid)
    {
        return RedpackLog::find()->select('amount')
            ->where(['openid' => $openid, 'is_revenue' => 1])
            ->sum('amount');
    }

    public static function getUserConsumeAmount($openid)
    {
        return RedpackLog::find()->select('amount')
            ->where(['openid' => $openid, 'is_revenue' => 0])
            ->sum('amount');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'category'], 'integer'],
            [['mobile'], 'string', 'max' => 16],
            [['comment'], 'string', 'max' => 256],
            [['created_at', 'updated_at', 'sendtime', 'recvtime', 'mch_billno', 'status', 'is_revenue', 'gh_id', 'openid'], 'safe'],
            [['openid_another'], 'string', 'max' => 64],
            [['amount', 'status', 'category', 'is_revenue'], 'default', 'value' => 0],

            [['redpack_balance', 'redpack_revenue_amount', 'redpack_consume_amount'], 'safe'],
        ];
    }

    // 在红包账户明细中记录一条因推荐而产生的收入

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gh_id' => 'Gh ID',
            'openid' => 'Openid', // 红包账户的主人
            'mobile' => '手机',     //unused
            'category' => '来源', // 产生红包的原因,recommend:推荐有礼, subscribe:关注红包, share:分享红包
            'is_revenue' => '收或支', // 表示是否收入性质(即收到红包)或者支出(如提现)
            'amount' => '金额', // 发生额一般总是大于0
            'openid_another' => '哪个OpenID推荐', // 因为推荐了哪个openid而获得红包
            'mch_billno' => '单号', // 支出时才有效, 红包发送成功后会回填此字段和sendtime
            'sendtime' => '发放时间', // 支出时才有效
            'recvtime' => '领取时间', //用户领取红包的时间
            'status' => '状态', // is_revenue为收出时才有效, 1:未发, 2:已发, 3:已领取, 4: 超时未领退回, 9:发送出错
            'comment' => '备注',
            'created_at' => '创建',
            'updated_at' => 'Updated At',
        ];
    }

    // 在红包账户明细中增加一条提现记录

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public function getFanWxUser()
    {
        return $this->hasOne(WxUser::className(), [
            'openid' => 'openid_another',
        ]);
    }

    // 指量派发红包

    public function getWxMember()
    {
        return $this->hasOne(WxMember::className(), [
            'openid' => 'openid',
        ]);
    }

    // 定时批量查询红包的领取情况并处理

    public function getGh()
    {
        return $this->hasOne(WxGh::className(), [
            'gh_id' => 'gh_id',
        ]);
    }

    public function getWxUser()
    {
        return $this->hasOne(WxUser::className(), [
            'openid' => 'openid',
        ]);
    }

    public function getCategoryLabel()
    {
        switch ($this->category) {
            case self::CATEGORY_NONE:
                return '---';
            case self::CATEGORY_RECOMMEND:
                return '推荐粉丝';
            case self::CATEGORY_YAOYIYAO:
                return '摇一摇';
            case self::CATEGORY_SUBSCRIBE:
                return '关注';
            case self::CATEGORY_SHARE:
                return '分享';
            case self::CATEGORY_SHARE_TOP10:
                return '分享上榜';
            default:
                return 'Error';
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (empty($this->WxMember)) {
            return;
        }
        if ($insert) {
            $attributes['redpack_balance'] = $this->WxMember->redpack_balance + ($this->is_revenue ? 1 : -1) * $this->amount;
            if ($this->is_revenue) {
                $attributes['redpack_revenue_amount'] = $this->WxMember->redpack_revenue_amount + $this->amount;
            } else {
                $attributes['redpack_consume_amount'] = $this->WxMember->redpack_consume_amount + $this->amount;
            }
            $this->WxMember->updateAttributes($attributes);
        } else if (key_exists('amount', $changedAttributes)) {
            $attributes['redpack_balance'] = $this->WxMember->redpack_balance  + ($this->is_revenue ? 1 : -1) * ($this->amount - $changedAttributes['amount']);;
            if ($this->is_revenue) {
                $attributes['redpack_revenue_amount'] = $this->WxMember->redpack_revenue_amount + $this->amount;
            } else {
                $attributes['redpack_consume_amount'] = $this->WxMember->redpack_consume_amount + $this->amount;
            }
            $this->WxMember->updateAttributes($attributes);
        }
    }
    
}
