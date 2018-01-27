<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\Json;

/**
 * This is the model class for table "wx_user".
 *
 * @property string $gh_id
 * @property string $openid
 * @property string $unionid
 * @property integer $subscribe
 * @property integer $subscribe_time
 * @property string $nickname
 * @property integer $sex
 * @property string $city
 * @property string $country
 * @property string $province
 * @property string $language
 * @property string $headimgurl
 * @property integer $groupid
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class WxUser extends \yii\db\ActiveRecord {

    const OPENID_SJDX_HBHE = 'o71PJsyvn-RHNoMFU-vywmjGsPis';
    const OPENID_SJDX_XIAO = 'o71PJs8rcuCxHTFxaE7529Fv3RQk';
    const CACHE_DURATION = 1800;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wx_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['gh_id', 'openid'], 'required'],
            [['subscribe', 'subscribe_time', 'sex', 'groupid', 'member', 'member_time'], 'integer'],
            [['gh_id', 'city', 'country', 'province'], 'string', 'max' => 32],
            [['openid', 'unionid'], 'string', 'max' => 64],
            [['created_at', 'updated_at'], 'safe'],
            
            [['nickname', 'headimgurl', 'remark'], 'string', 'max' => 255]
        ];
    }
    
    const MEMBER_NONE = 0;
    const MEMBER_VALID = 1;
    const MEMBER_LOCAL = 2;

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'gh_id' => 'Gh ID',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'subscribe' => '已关注',
            'subscribe_time' => '关注时间',
            'nickname' => '昵称',
            'sex' => '性别',
            'city' => '城市',
            'country' => '国家',
            'province' => '省会',
            'language' => '语言',
            'headimgurl' => '头像',
            'groupid' => '组ID',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function isTester($openid) {
        return in_array($openid, [self::OPENID_SJDX_HBHE,self::OPENID_SJDX_XIAO]);
    }

    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }
        
    public function getLastAccessTime() {
        $msglog = WxMsgLog::find()->where([
            'ToUserName' => $this->gh_id,
            'FromUserName' => $this->openid,
        ])->orderBy('created_at DESC')->one();
        return empty($msglog) ? NULL : $msglog->created_at;
    }
        
    public function getOutlets() {
//        return $this->hasMany(Outlet::className(), ['sid' => 'outlet_sid'])
//                        ->viaTable('outlet_employee', ['employee_mobile' => 'mobile'])
//                        ->viaTable('wx_user_mobile', ['gh_id' => 'gh_id', 'openid' => 'openid']);        
        $query = Outlet::find()->joinWith('employees')->where(['employee_mobile' => $this->getMobilesAsArray()]);
        return $query->all();
    }
    
    public function getGh() {
        return $this->hasOne(WxGh::className(), ['gh_id' => 'gh_id']);
    }
    
    public function getJifen() {
        $point_logs = PointLog::findAll([
            'gh_id' => $this->gh_id,
            'openid' => $this->openid,
            'category' => PointLog::CATEGORY_INIT,
        ]);
        if (empty($point_logs) && $this->member > self::MEMBER_NONE) {
            PointLog::initializeWxUser($this->gh_id, $this->openid);
        }
        return $this->points;
    }
    
    public static function getJifenTotal($gh_id) {
        $data = \Yii::$app->cache->get(self::className() . __METHOD__ . $gh_id);
        if (false === $data) {
            $data = self::find()->where([
                        'gh_id' => $gh_id,
                    ])->sum('points');
            \Yii::$app->cache->set(self::className() . __METHOD__ . $gh_id, $data, 30 * 60);
        }
        if ($data > 50000) return $data;
        else               return 50000;
    }

    public static function getByMobile($gh_id, $mobile) {
        $query = new Query;
        $record = $query->select('*')
                        ->from('wx_user')
                        ->leftJoin('wx_user_mobile', 'wx_user.gh_id = wx_user_mobile.gh_id and wx_user.openid = wx_user_mobile.openid')
                        ->where([
                            'wx_user.gh_id' => $gh_id,
                            'mobile' => $mobile,
                        ])->one();
        if (false == $record)
            return false;
        return self::findOne(['gh_id' => $gh_id, 'openid' => $record['openid']]);
    }

    public static function getWxUserTotal($gh_id, $start_date = NULL, $end_date = NULL) {
        $query = self::find()->where([
            'gh_id' => $gh_id,
        ]);
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'created_at', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'created_at', strtotime($end_date)
            ]);

//        return $query->count();
        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, self::CACHE_DURATION);
        return $result;
    }

    public static function getWxUserActive($gh_id, $start_date = NULL, $end_date = NULL) {
        $query = self::find()->where([
            'gh_id' => $gh_id,
            'subscribe' => 1,
        ]);
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'created_at', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'created_at', strtotime($end_date)
            ]);

        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, self::CACHE_DURATION);
        return $result;

    }

    public static function getMemberTotal($gh_id, $start_date = NULL, $end_date = NULL) {
//        $query = self::find()->joinWith('mobiles', true)->where([
//                    'wx_user.gh_id' => $gh_id,
//                ])->andWhere([
//            'not', ['mobile.mobile' => NULL]
//        ]);
//
//        if (NULL !== $start_date)
//            $query = $query->andWhere([
//                '>', 'wx_user_mobile.created_at', strtotime($start_date)
//            ]);
//        if (NULL !== $end_date)
//            $query = $query->andWhere([
//                '<', 'wx_user_mobile.created_at', strtotime($end_date)
//            ]);
//
//        return $query->groupBy('wx_user.gh_id, wx_user.openid')->count();
        $query = self::find()->where([
            'gh_id' => $gh_id,
        ])->andWhere([
            'member' => [self::MEMBER_VALID,  self::MEMBER_LOCAL],
        ]);
        
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'member_time', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'member_time', strtotime($end_date)
            ]);

        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, self::CACHE_DURATION);
        return $result;

    }

    public static function getMemberActive($gh_id, $start_date = NULL, $end_date = NULL) {
        $query = self::find()->where([
            'gh_id' => $gh_id,
            'subscribe' => 1,
        ])->andWhere([
            'member' => [self::MEMBER_VALID, self::MEMBER_LOCAL],
        ]);
        
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'member_time', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'member_time', strtotime($end_date)
            ]);

        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, self::CACHE_DURATION);
        return $result;
    
    }

    public static function getMemberLocalTotal($gh_id, $start_date = NULL, $end_date = NULL) {
        $query = self::find()->where([
            'gh_id' => $gh_id,
        ])->andWhere([
            'member' => self::MEMBER_LOCAL,
        ]);
        
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'member_time', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'member_time', strtotime($end_date)
            ]);

        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, self::CACHE_DURATION);
        return $result;

    }

    public static function getMemberLocalActive($gh_id, $start_date = NULL, $end_date = NULL) {
//        $gh = WxGh::findOne(['gh_id' => $gh_id]);
//
//        $query = self::find()->joinWith('mobiles', true)->where([
//                    'wx_user.gh_id' => $gh_id,
//                    'wx_user.subscribe' => 1,
//                ])->andWhere([
//            'not', ['mobile.mobile' => NULL]
//        ]);
//
//        if (!empty($gh->client)) {
//            $query = $query->andWhere([
//                'mobile.province' => $gh->client->province,
//                'mobile.city' => $gh->client->city,
//            ]);
//        }
//
//        if (NULL !== $start_date)
//            $query = $query->andWhere([
//                '>', 'wx_user_mobile.created_at', strtotime($start_date)
//            ]);
//        if (NULL !== $end_date)
//            $query = $query->andWhere([
//                '<', 'wx_user_mobile.created_at', strtotime($end_date)
//            ]);
//
//        return $query->groupBy('wx_user.gh_id, wx_user.openid')->count();
        $query = self::find()->where([
            'gh_id' => $gh_id,
            'subscribe' => 1,
        ])->andWhere([
            'member' => self::MEMBER_LOCAL,
        ]);
        
        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'member_time', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'member_time', strtotime($end_date)
            ]);

        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, self::CACHE_DURATION);
        return $result;
    }
    
    public static function getMemberLocalUnicomActive($gh_id, $start_date = NULL, $end_date = NULL) {
        $gh = WxGh::findOne(['gh_id' => $gh_id]);

        $query = self::find()->joinWith('mobiles', true)->where([
                    'wx_user.gh_id' => $gh_id,
                    'wx_user.subscribe' => 1,
                    'wx_user.member' => self::MEMBER_LOCAL,
                ])->andWhere([
                    'mobile.company' => '中国联通',
                ]);

        if (NULL !== $start_date)
            $query = $query->andWhere([
                '>', 'wx_user_mobile.created_at', strtotime($start_date)
            ]);
        if (NULL !== $end_date)
            $query = $query->andWhere([
                '<', 'wx_user_mobile.created_at', strtotime($end_date)
            ]);

        $query = $query->groupBy('wx_user.gh_id, wx_user.openid');
       
        $db = Yii::$app->db;
        $result = $db->cache(function ($db) use($query) {
            return $query->count();        
        }, self::CACHE_DURATION);
        return $result;
    }

    public static function badgeDataAjax($gh_id, $start_date, $end_date) {
        $key = __METHOD__ . "$gh_id, $start_date, $end_date";
        $data = yii::$app->cache->get($key);
        if ($data !== false) {
            return $data;
        }
        $data = Json::encode([
                    'data1' => self::getMemberLocalActive($gh_id, $start_date, $end_date),
                    'data2' => self::getMemberLocalTotal($gh_id, $start_date, $end_date),
                    'data3' => self::getMemberActive($gh_id, $start_date, $end_date),
                    'data4' => self::getMemberTotal($gh_id, $start_date, $end_date),
                    'data5' => self::getWxUserActive($gh_id, $start_date, $end_date),
                    'data6' => self::getWxUserTotal($gh_id, $start_date, $end_date),
                    'data7' => self::getMemberLocalUnicomActive($gh_id, $start_date, $end_date),
        ]);
        yii::$app->cache->set($key, $data, self::CACHE_DURATION);
        return $data;

    }

    public static function getMemberTimelineAjax($gh_id, $start_date, $end_date, $accumulated_flag) {

        $key = __METHOD__ ."$gh_id,$start_date,$end_date,$accumulated_flag";
        $data = yii::$app->cache->get($key);
        if ($data !== false) {
            return $data;
        }

        $results = [];
        $startTimestamp = strtotime($start_date);
        $endTimestamp = strtotime($end_date);
        $start_localmembers = 0;
        $start_localmembers_active = 0;
        $start_members = 0;
        $start_members_active = 0;
        $start_wxusers = 0;
        $start_wxusers_active = 0;
        $start_localunicom_active = 0;
        $length = 0;

        if ($accumulated_flag) {
            $start_localunicom_active = self::getMemberLocalUnicomActive($gh_id, NULL, $start_date);
            $start_localmembers_active = self::getMemberLocalActive($gh_id, NULL, $start_date);
            $start_localmembers = self::getMemberLocalTotal($gh_id, NULL, $start_date);
            $start_members_active = self::getMemberActive($gh_id, NULL, $start_date);
            $start_members = self::getMemberTotal($gh_id, NULL, $start_date);
            $start_wxusers_active = self::getWxUserActive($gh_id, NULL, $start_date);
            $start_wxusers = self::getWxUserTotal($gh_id, NULL, $start_date);
        }

        while ($startTimestamp < $endTimestamp) {
            $day_localmembers_active = self::getMemberLocalActive($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            $day_localmembers = self::getMemberLocalTotal($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            $day_members_active = self::getMemberActive($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            $day_members = self::getMemberTotal($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            $day_wxusers_active = self::getWxUserActive($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            $day_wxusers = self::getWxUserTotal($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            $day_localunicom_active = self::getMemberLocalUnicomActive($gh_id, date('Y-m-d H:i:s', $startTimestamp), date('Y-m-d H:i:s', $startTimestamp + 24 * 3600));
            if ($accumulated_flag) {
                $results['data1'][] = [$startTimestamp * 1000, $start_localmembers_active + $day_localmembers_active];
                $results['data2'][] = [$startTimestamp * 1000, $start_localmembers + $day_localmembers];
                $results['data3'][] = [$startTimestamp * 1000, $start_members_active + $day_members_active];
                $results['data4'][] = [$startTimestamp * 1000, $start_members + $day_members];
                $results['data5'][] = [$startTimestamp * 1000, $start_wxusers_active + $day_wxusers_active];
                $results['data6'][] = [$startTimestamp * 1000, $start_wxusers + $day_wxusers];
                $results['data7'][] = [$startTimestamp * 1000, $start_localunicom_active + $day_localunicom_active];
            } else {
                $results['data1'][] = [$startTimestamp * 1000, $day_localmembers_active];
                $results['data2'][] = [$startTimestamp * 1000, $day_localmembers];
                $results['data3'][] = [$startTimestamp * 1000, $day_members_active];
                $results['data4'][] = [$startTimestamp * 1000, $day_members];
                $results['data5'][] = [$startTimestamp * 1000, $day_wxusers_active];
                $results['data6'][] = [$startTimestamp * 1000, $day_wxusers];
                $results['data7'][] = [$startTimestamp * 1000, $day_localunicom_active];
            }
            $length += 1;
            $startTimestamp += 24 * 3600;
            if ($accumulated_flag) {
                $start_localmembers_active += $day_localmembers_active;
                $start_localunicom_active += $day_localunicom_active;
                $start_localmembers += $day_localmembers;
                $start_members_active += $day_members_active;
                $start_members += $day_members;
                $start_wxusers_active += $day_wxusers_active;
                $start_wxusers += $day_wxusers;
            }
        }
        $results['length'] = $length;
        $data = Json::encode($results);
        yii::$app->cache->set($key, $data, self::CACHE_DURATION);
        return $data;
    }

    public static function getMemberRegionPieDataAjax($gh_id, $start_date = NULL, $end_date = NULL) {
        $key = __METHOD__. "$gh_id, $start_date, $end_date";
        $data = yii::$app->cache->get($key);
        if ($data !== false) {
            return $data;
        }

        $results = [];
        $query = (new \yii\db\Query())
                ->select('mobile.province, mobile.city, count(*) as c')
                ->from('wx_user_mobile')
                ->leftJoin('mobile', 'mobile.mobile = wx_user_mobile.mobile')
                ->leftJoin('wx_user', 'wx_user.gh_id = wx_user_mobile.gh_id and wx_user.openid = wx_user_mobile.openid')
                ->where(['wx_user_mobile.gh_id' => $gh_id])
                ->andWhere(['wx_user.subscribe' => 1])
                ->andWhere(['not', ['mobile.province' => NULL]])
                ->andWhere(['not', ['mobile.city' => NULL]]);
        if (NULL !== $start_date)
            $query = $query->andWhere (['>', 'created_at', strtotime($start_date)]);
        if (NULL !== $end_date)
            $query = $query->andWhere (['<', 'created_at', strtotime($end_date)]);    
        $query = $query->groupBy('mobile.province, mobile.city')
                ->orderBy('c desc');
        $rows = $query->limit(10)->all();
        foreach ($rows as $row) {
            $results[] = [
                'label' => $row['province'].$row['city'],
                'data' => $row['c'],
            ];
        }
        $data = Json::encode($results);
        yii::$app->cache->set($key, $data, self::CACHE_DURATION);
        return $data;

    }

    public static function getMemberCarrierPieDataAjax($gh_id, $start_date = NULL, $end_date = NULL) {
        $key = __METHOD__. "$gh_id, $start_date, $end_date";
        $data = yii::$app->cache->get($key);
        if ($data !== false) {
            return $data;
        }

        $results = [];
        $query = (new \yii\db\Query())
                ->select('company, count(*) as c')
                ->from('wx_user_mobile')
                ->leftJoin('mobile', 'mobile.mobile = wx_user_mobile.mobile')
                ->leftJoin('wx_user', 'wx_user.gh_id = wx_user_mobile.gh_id and wx_user.openid = wx_user_mobile.openid')
                ->where(['wx_user_mobile.gh_id' => $gh_id])
                ->andWhere(['not', ['mobile.company' => NULL]])
                ->andWhere(['wx_user.subscribe' => 1]);
        if (NULL !== $start_date)
            $query = $query->andWhere (['>', 'created_at', strtotime($start_date)]);
        if (NULL !== $end_date)
            $query = $query->andWhere (['<', 'created_at', strtotime($end_date)]);    
        $query = $query->groupBy('company')
                ->orderBy('c desc');
        $rows = $query->all();
        foreach ($rows as $row) {
            $results[] = [
                'label' => $row['company'],
                'data' => $row['c'],
            ];
        }
        $data = Json::encode($results);
        yii::$app->cache->set($key, $data, self::CACHE_DURATION);
        return $data;

    }

    public function updateReferenceTicket($ticket) {
        $this->updateAttributes(['subscribe_qrticket' => $ticket]);
    }
    
    public function resetReferenceTicket() {
        if (!empty($this->subscribe_qrticket)) {
            $qrlimit_log = new \common\models\WxQrlimitLog;
            $qrlimit_log->gh_id = $this->gh_id;
            $qrlimit_log->openid = $this->openid;
            $qrlimit_log->qrticket = $this->subscribe_qrticket;
            $qrlimit_log->action = 'unsubscribe';
            $qrlimit_log->save(false);
            
            $qrlimit = \common\models\WxQrlimit::findOne([
                'gh_id' => $this->gh_id,
                'ticket' => $this->subscribe_qrticket,
            ]);
            if (!empty($qrlimit)) {
                $qrlimit->updateCounters(['subscribe_counter' => -1]);
            }
        }
    }

    public static function floattostr($val)
    {
        preg_match( "#^([+-]|)([0-9]*)(.([0-9]*?)|)(0*)$#", trim($val), $o );
        return $o[1].sprintf('%d',$o[2]).($o[3]!='.'?$o[3]:'');
    }

    public static function WxUserLocationAjax($gh_id, $latitude, $longitude) {

        $ak = "qW23l8uZjYxkTLUa0pBYxD7Y";
        $requestUrl2 = "http://api.map.baidu.com/geocoder/v2/?ak=".$ak."&location=".$latitude.",".$longitude."&output=json&pois=1";
        \Yii::warning(\yii\helpers\Json::encode(Y::curl($requestUrl2)));
        return \yii\helpers\Json::encode(Y::curl($requestUrl2));
    }
    
    public function getWxMember(){
        return $this->hasOne(\common\models\WxMember::className(), ['openid' => 'openid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWxQrlimit() {
        return $this->hasOne(\common\models\WxQrlimit::className(), ['action_name' => 'openid']);
    }

    public function getRealWxQrlimitUrl() {
        $qrlimit = $this->wxQrlimit;
        if (null === $qrlimit) {
            $qrlimit = new WxQrlimit();
            $qrlimit->gh_id = $this->gh_id;
            $qrlimit->action_name = $this->openid;
            $qrlimit->scene_str = uniqid();
            $qrlimit->save(false);
        }        
        $wxapp = $this->gh->getWxApp('snsapi_base', false)->getApplication();
        $qrcode = $wxapp->qrcode;        
        $url = $qrcode->url($qrlimit->getTicketFromCache());
        
        return $url;        
    }
    
    public function getWxMemberfans(){
        return $this->hasOne(\common\models\WxMemberfans::className(), ['openid' => 'openid']);
    }
    
    public function getSjScorecard(){
        return $this->hasOne(\common\modules\scorecard\models\SjScorecard::className(), ['openid' => 'openid']);
    }
    
    public function getWxWall(){
        $this->hasMany(\common\modules\wall\models\WxWall::className(), ['openid' => 'openid']);
    }
    
    public function getWxWalls(){
        $this->hasMany(\common\modules\walls\models\WxWalls::className(), ['openid' => 'openid']);
    }

    public function getWxWallSign(){
        $this->hasMany(\common\modules\wall\models\WxWallSign::className(), ['openid' => 'openid']);
    }

    public function getWxWallShake(){
        $this->hasMany(\common\modules\wall\models\WxWallShake::className(), ['openid' => 'openid']);
    }

    public function getHeadimgurl($size) {
        return \common\wosotech\Util::getWxUserHeadimgurl($this->headimgurl, $size);
    }

    public function extraFields()
    {
        return ['WxMember', 'wxQrlimit'];
    }


}
