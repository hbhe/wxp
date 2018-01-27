<?php

namespace wxpay\models;

use Yii;

/**
 * This is the model class for table "{{%order_wxpay}}".
 *
 * @property string $id
 * @property string $out_trade_no
 * @property string $appid
 * @property string $attach
 * @property string $bank_type
 * @property string $fee_type
 * @property string $is_subscribe
 * @property string $mch_id
 * @property string $openid
 * @property string $result_code
 * @property string $trade_state
 * @property string $time_end
 * @property string $total_fee
 * @property string $coupon_fee
 * @property string $trade_type
 * @property string $transaction_id
 *
 * @property Order $outTradeNo
 */

/*
CREATE TABLE IF NOT EXISTS `wx_order_wxpay` (
    id int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,  
  `out_trade_no` VARCHAR(32) NOT NULL DEFAULT '',
  `appid` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `attach` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `bank_type` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '银行类型, 如CFT, CMB',
  `fee_type` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '货币类型, 如CNY',
  `is_subscribe` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '是否关注, Y-关注, N-未关注',
  `mch_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '商户号',
  `openid` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `result_code` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '业务结果: SUCCESS, FAIL',
  `trade_state` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SUCCESS,REFUND,NOTPAY, CLOSED, REVOKED, USERPAYING, NOPAY, PAYERROR',
  `time_end` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '支付完成时间',
  `total_fee` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单总金额，单位为分',
  `coupon_fee` bigint(20) NOT NULL DEFAULT '0' COMMENT '现金券支付金额',
  `trade_type` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '交易类型: JSAPI, NATIVE, MICROPAY, APP',
  `transaction_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '微信支付订单号'
) ENGINE=myisam DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `wx_order_wxpay` ADD UNIQUE KEY `idx_out_trade_no` (`out_trade_no`);

*/

class OrderWxpay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_order_wxpay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['out_trade_no'], 'required'],
            [['total_fee', 'coupon_fee'], 'integer'],
            [['appid', 'mch_id', 'trade_state', 'out_trade_no'], 'string', 'max' => 32],
            [['attach', 'openid'], 'string', 'max' => 128],
            [['bank_type', 'result_code', 'time_end', 'trade_type'], 'string', 'max' => 16],
            [['fee_type'], 'string', 'max' => 8],
            [['is_subscribe'], 'string', 'max' => 2],
            [['transaction_id'], 'string', 'max' => 64],
            [['out_trade_no'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'out_trade_no' => '商户订单号',
            'appid' => 'Appid',
            'attach' => 'Attach',
            'bank_type' => '银行类型, 如CFT, CMB',
            'fee_type' => '货币类型, 如CNY',
            'is_subscribe' => '是否关注, Y-关注, N-未关注',
            'mch_id' => '商户号',
            'openid' => 'Openid',
            'result_code' => '业务结果: SUCCESS, FAIL',
            'trade_state' => 'SUCCESS,REFUND,NOTPAY, CLOSED, REVOKED, USERPAYING, NOPAY, PAYERROR',
            'time_end' => '支付完成时间',
            'total_fee' => '订单总金额，单位为分',
            'coupon_fee' => '现金券支付金额',
            'trade_type' => '交易类型: JSAPI, NATIVE, MICROPAY, APP',
            'transaction_id' => '微信支付订单号',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutTradeNo()
    {
        return $this->hasOne(Order::className(), ['id' => 'out_trade_no']);
    }

}
