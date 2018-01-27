<?php

namespace common\modules\policy\models;
use common\models\WxModel;
use common\models\WxBrand;
use common\models\WxGh;
use common\models\WxUser;
use Yii;

/**
 * This is the model class for table "sj_policy".
 *
 * @property integer $id
 * @property string $generate_policy_sid
 * @property string $imei
 * @property string $openid
 * @property string $mobile
 * @property string $imgPath
 * @property string $clerk_id
 * @property integer $brand_id
 * @property integer $model_id
 * @property integer $state
 * @property string $created_at
 * @property string $updated_at
 */
class SjPolicy extends \common\wosotech\base\ActiveRecord
{

    const SJ_POLICY_STATUS_INIT = 0;
    const SJ_POLICY_STATUS_OK = 1;   
    const SJ_POLICY_STATUS_USED = 2;                
    const SJ_POLICY_STATUS_EXPIRED = 3;            
    const SJ_POLICY_STATUS_DELETED = 4;        

    const SJ_POLICY_PAY_KIND_WECHAT = 0;
    const SJ_POLICY_PAY_KIND_CASH = 1;    
    
    public $imgPaths;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sj_policy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'model_id', 'state'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['generate_policy_sid', 'imei', 'clerk_id'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 64],
            ['mobile' , 'match' , 'pattern' => '/^1\d{10}$/' , 'message' => '手机号码格式错误'],
            [['imgPath'], 'string', 'max' => 1024],
            [['generate_policy_sid'], 'unique'],
            [['brand_id', 'model_id', 'clerk_id', 'imei', 'mobile'], 'required'],
            [['imgPath', 'imgPaths'], 'safe'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'generate_policy_sid' => '屏保单号',
//            'imei' => '邮箱',
            'imei' => '手机IMEI号',            
            'openid' => 'Openid',
            'mobile' => '手机号码',
            'imgPath' => '图片',
            'clerk_id' => '营业员工号',
            'brand_id' => '品牌ID',
            'model_id' => '型号ID',
            'state' => '保单状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return SjPolicyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SjPolicyQuery(get_called_class());
    }

    public function getOutletEmployee() {
        return $this->hasOne(OutletEmployee::className(), ['id' => 'clerk_id']);
    }

    public function getGh() {
        return $this->hasOne(WxGh::className(), ['id' => 'gh_id']);
    }

    public function getWxUser() {
        return $this->hasOne(WxUser::className(), ['openid' => 'openid']);
    }

    public function getBrand() {
        return $this->hasOne(WxBrand::className(), ['id' => 'brand_id']);
    }
    
    public function getSjPhoneModel() {
        return $this->hasOne(WxModel::className(), ['id' => 'model_id']);
    }

    public function afterFind() {
        if (!empty($this->imgPath)) {
            $this->imgPaths = explode(';', $this->imgPath);
        }
        parent::afterFind();        
    }
    
    public function beforeSave($insert) {
        if ($insert) {
        	$time=substr(time(),-6);            
            $this->state = self::SJ_POLICY_STATUS_INIT;
            $this->generate_policy_sid = strtoupper(date("Ymd") . $this->clerk_id . $time);
            $this->updated_at = date("Y-m-d H:i:s", strtotime("+15 day"));
        }
        return parent::beforeSave($insert);
    }

    public static function getStatusOption($key = null)
    {
        $arr = [
            static::SJ_POLICY_STATUS_INIT => '待付款',
            static::SJ_POLICY_STATUS_OK => '正常(未使用)',
            static::SJ_POLICY_STATUS_USED => '已受理维修',            
            static::SJ_POLICY_STATUS_EXPIRED => '保单到期',
            static::SJ_POLICY_STATUS_DELETED => '删除',     
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public static function changeStatusFromWxpaynotify($event)
    {
        $order = SjPolicy::findOne(['generate_policy_sid' => $event->orderWxpay->out_trade_no]);
        if (empty($order)) {
            yii::warning(['no order!', $event->orderWxpay->out_trade_no]);
            return false;
        } 
        
        $order->state = SjPolicy::SJ_POLICY_STATUS_OK;
        //$order->pay_kind = SjPolicy::SJ_POLICY_PAY_KIND_WECHAT;
        if (!$order->save()) {
            yii::error(['failed to save order', $order]);
        }
    }
    
}
