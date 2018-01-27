<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "gh_outlet".
 *
 * @property string $gh_id
 * @property integer $outlet_id
 * @property string $poi_id
 * @property integer $available_state
 * @property integer $update_status
 * @property string $photo_list
 * @property integer $created_at
 * @property integer $updated_at
 */
class GhOutlet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_gh_outlet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'outlet_id', 'created_at', 'updated_at'], 'required'],
            [['outlet_id', 'available_state', 'update_status'], 'integer'],
            [['gh_id', 'poi_id'], 'string', 'max' => 32],
            [['created_at', 'updated_at'], 'safe'],
            
            [['photo_list'], 'string', 'max' => 255]
        ];
    }
    
    public function getOutletName() {
        return $this->outlet->business_name . $this->outlet->branch_name;
    }
    
    public function getOutletAddress() {
        return $this->outlet->province . $this->outlet->city . $this->outlet->district . $this->outlet->address;
    }
    
    public function getOutletSid() {
        return $this->outlet->sid;
    }
    
    public function getOutletSelfOperated() {
        return $this->outlet->self_operated;
    }
    
    public function getOutletOnline() {
        return $this->outlet->online;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gh_id' => '公众号ID',
            'outlet_id' => '门店ID',
            'poi_id' => '微信门店ID',
            'available_state' => '审核状态',
            'update_status' => '更新状态',
            'photo_list' => '图片列表',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'outletName' => '门店名称',
            'outletAddress' => '门店地址',
            'outletSid' => '门店唯一标识码',
            'outletSelfOperated' => '自营厅',
            'outletOnline' => '承接微商城订单',
        ];
    }
/*    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
*/
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }    
    
    public function getOutlet() {
        return $this->hasOne(Outlet::className(), ['id' => 'outlet_id']);
    }
    
    public function getGh() {
        return $this->hasOne(WxGh::className(), ['gh_id' => 'gh_id']);
    }
    
    public static function removeAll($gh_id) {
        \Yii::$app->db->createCommand("DELETE FROM ".self::tableName()." WHERE gh_id = :gh_id")
                ->bindParam(":gh_id", $gh_id)
                ->execute();
    }
    
   public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
//        if ($insert) {
//            $arrayRet = $this->gh->apiPOIAddPoi(
//                    $this->outlet->sid, 
//                    $this->outlet->business_name, 
//                    $this->outlet->branch_name, 
//                    $this->outlet->province, 
//                    $this->outlet->city, 
//                    $this->outlet->district,
//                    $this->outlet->address, 
//                    $this->outlet->telephone, 
//                    Json::decode($this->outlet->categories), 
//                    $this->outlet->offset_type, 
//                    $this->outlet->longitude,
//                    $this->outlet->latitude, 
//                    Json::decode($this->photo_list), 
//                    $this->outlet->recommend, 
//                    $this->outlet->special, 
//                    $this->outlet->introduction,
//                    $this->outlet->open_time, 
//                    $this->outlet->avg_price
//                );
//            if ($arrayRet['errcode'] !== 0) {
//                throw new \yii\web\HttpException(404, '增加门店失败：'.$arrayRet['errcode'].'-'.$arrayRet['errmsg']);
//            }
//        }
          
    }
}
