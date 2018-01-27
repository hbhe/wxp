<?php

namespace common\models;

use common\modules\outlet\models\Fans;
use common\models\WxUser;
use Yii;
use yii\db\Query;

use common\modules\redpack\models\RedpackLog;

/**
 * This is the model class for table "wx_member".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $openid
 * @property string $tel
 * @property integer $vermicelli
 * @property integer $is_binding
 * @property integer $member_type
 * @property integer $member_grade
 * @property string $created_at
 * @property string $updated_at
 */
class WxMember extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vermicelli', 'is_binding', 'member_type', 'member_grade'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['gh_id'], 'string', 'max' => 32],
            [['openid'], 'string', 'max' => 64],
            ['tel' , 'match' , 'pattern' => '/^1\d{10}$/' , 'message' => '手机号码格式错误'],
            [['tel'], 'required'],

            [['redpack_status', 'redpack_balance', 'redpack_revenue_amount', 'redpack_consume_amount'], 'integer'],            
            [['redpack_status', 'redpack_balance', 'redpack_revenue_amount', 'redpack_consume_amount', 'member_grade', 'member_type', 'is_binding', 'vermicelli'], 'default', 'value' => 0],            
        ];
    }

    const REDPACK_STATUS_OK = 0;
    const REDPACK_STATUS_FRONZEN = 1;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gh_id' => 'Gh ID',
            'openid' => 'Openid',
            'tel' => '手机号',
            'vermicelli' => '粉丝数',
            'is_binding' => 'Is Binding',
            'member_type' => 'Member Type',
            'member_grade' => 'Member Grade',
            'created_at' => '首绑时间',
            'updated_at' => 'Updated At',
            'redpack_status' => '红包账号状态',
            'redpack_balance' => '余额',    // 红包账号余额  
            'redpack_revenue_amount' => '总收入',      //'红包收入总额
            'redpack_consume_amount' => '总支出', // 红包提现总额         
        ];
    }
    
    public static function is_xgdxtel($tel) {
    	$tels=array(1776257,1776258,1776259,1776260,1776263,1776264,1776265,1776266,1776267,1330729, 1332990, 1332991, 1333983, 1333984, 1334981, 1336712, 1337783, 1338529, 1338767, 1339618, 1339727, 1530729, 1532706, 1532707, 1532708, 1532709, 1532756, 1532757, 1533407, 1533408, 1533409, 1533574, 1533575, 1533576, 1533732, 1534250, 1534251, 1534252, 1534253, 1534254, 1534255, 1534256, 1534257, 1534709, 1537710, 1538721, 1538722, 1734342, 1735266, 1735267, 1735336, 1735337, 1735446, 1736235, 1736266, 1736267, 1736336, 1736337, 1736411, 1736421, 1737120, 1737121, 1737122, 1737123, 1737137, 1737138, 1738620, 1738621, 1738622,1738667, 1739820, 1770712, 1772024, 1772025, 1772046, 1772047, 1776261, 1776262, 1776303, 1776428, 1777120, 1777121, 1777122, 1778629, 1779830, 1800729, 1806231, 1806416, 1807118, 1807119, 1807146, 1807179, 1810712, 1812029, 1812030, 1812046, 1814061, 1815434, 1815446, 1816282, 1816283, 1816284, 1816316, 1816317, 1816318, 1816358, 1816413, 1817154, 1817155, 1817160, 1817161, 1818694, 1818695, 1890729, 1897194, 1897195, 1897196, 1897197, 1897264, 1897265, 1897266, 1897267, 1897268, 1897269, 1898645, 1898646, 1898647, 1898648, 1898649, 1898650, 1899569, 1899570,
);
    	$is_tel=substr($tel, 0, 7);
    	if (in_array($is_tel, $tels)) {
    		return true;
    	} else {
    	    return false;
    	}
    }
    
    public static function is_xgdx_phone($tel){
        $responseArray = \Yii::$app->juhe->apiGetMobile($tel);
        /*if ($responseArray['province'] == '湖北' && $responseArray['city'] == '孝感') {
            return true;
        } else {
            return false;
        }*/
        if ($responseArray['province'] == '湖北' && $responseArray['city'] == '孝感' && $responseArray['company'] == '电信') {
            return true;
        } else {
        	return false;
        }
    }
    public static function is_xgdx_phone_local($tel){
        $responseArray = \Yii::$app->juhe->apiGetMobile($tel);
        if ($responseArray['province'] == '湖北' && $responseArray['city'] == '孝感') {
            return true;
        } else {
            return false;
        }
    }
    
    public static function is_xndx_phone($tel){
        $responseArray = \Yii::$app->juhe->apiGetMobile($tel);
        if ($responseArray['province'] == '湖北' && $responseArray['company'] == '电信') {
            return true;
        } else {
            return false;
        }
    }


    
    public static function getGhidOptionName($key=null)
    {
       /* $arr = array(
                'gh_82df8393167b' => 'xgdx',
                'gh_4b9887a417ef' => 'jmdx',
                'gh_dacd2ee0dede' => 'xndx',
                'gh_6b9b67032eb6' => 'sjdx',
                'gh_e0e3087cfc39' => 'xwmt',
                'gh_a3f376f3f2e2' => 'hsdx',
        );*/
        $arr = array(
                'gh_82df8393167b' => '47707',
                'gh_4b9887a417ef' => '47711',
                'gh_dacd2ee0dede' => '47714',
                'gh_6b9b67032eb6' => '47729',
                'gh_e0e3087cfc39' => '47730',
                'gh_a3f376f3f2e2' => '47713',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    //key值
    public static function getGhkeyOptionName($key=null)
    {
        $arr = array(
            'gh_e0e3087cfc39' => 'c91137d0-87a2-11e7-87f0-bbec155ee86d',
            'gh_a3f376f3f2e2' => '73b2fcd0-88a9-11e7-991d-9db8133a84a0',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    //文章ID
    public static function getGharticleOptionName($key=null)
    {
        $arr = array(
            'gh_e0e3087cfc39' => '3b70a150',
            'gh_a3f376f3f2e2' => 'c4cb88b0',
        );
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }
    
    
    public static function is_xiaogan() {
        $gh_id = \common\wosotech\Util::getSessionGhid();
        if ($gh_id == 'gh_82df8393167b') {
        	return true;
        } else if ($gh_id == 'gh_4b9887a417ef') {
        	return false;
        }
    }
    
    public function getWxUser(){
        return $this->hasOne(\common\models\WxUser::className(), ['openid' => 'openid']);
    }
    
    public function getWxQrlimit() {
        return $this->hasOne(\common\models\WxQrlimit::className(), ['action_name' => 'openid']);
    }

    public function getRedpackLogs() {
        return $this->hasMany(\common\modules\redpack\models\RedpackLog::className(), ['openid' => 'openid']);
    }

    public function afterDelete() {
        foreach ($this->redpackLogs as $log) {
            $log->delete();
        }
    }

    //绑定或者关注加积分请求
    public static function Integral($openid=null,$phone=null,$type=null,$share_openid=null,$gh_id="gh_a3f376f3f2e2"){
        /* $key = "c91137d0-87a2-11e7-87f0-bbec155ee86d";
         $openid = "oFVnsjjK_r7XLpSXnlLKo6jelAF8";
         $phone = "13657209591";
         $type = "bind_user";//绑定 bind_fan 发展粉丝
         $articleId = "3b70a150";
         $gh_id = "gh_e0e3087cfc39";
        */
        //$gh_id = \common\wosotech\Util::getSessionGhid();
        $name = WxUser::findOne(['openid' => $openid])->nickname;
        $time = time();
        $sign = md5($time.self::getGhkeyOptionName($gh_id));
        $url = "http://wx.mysite.com/api/v3/share_score_logs?ghid=".$gh_id."&timespan=".$time."&sign=".$sign;

        $str="openid=".$openid."&mobile=".$phone."&type=".$type."&articleId=".self::getGharticleOptionName($gh_id)."&share_openid=".$share_openid."&name=".$name;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);

        $result= curl_exec ($ch);
        curl_close ($ch);
        return $result;
    }

    //查询积分
    public static function Inter($openid=null,$gh_id="gh_a3f376f3f2e2"){
        //$gh_id = \common\wosotech\Util::getSessionGhid();
        $time = time();
        $sign = md5($time.self::getGhkeyOptionName($gh_id));
        $url = "http://wx.mysite.com/api/v3/share_score_logs/sum_score?ghid=".$gh_id."&timespan=".$time."&sign=".$sign;
        $str="openid=".$openid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);

        $result = curl_exec ($ch);
        curl_close ($ch);
        $result = json_decode($result,true);

        return $result;
    }

    public static function White($mobile=null,$gh_id="gh_a3f376f3f2e2"){
        //$gh_id = \common\wosotech\Util::getSessionGhid();
        $time = time();
        $sign = md5($time.self::getGhkeyOptionName($gh_id));
        $url = "http://wx.mysite.com/api/v3/share_white_list/getinfo?ghid=".$gh_id."&timespan=".$time."&sign=".$sign;
        $str="mobile=".$mobile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result = curl_exec ($ch);
        //获取curl中的错误信息
        //$a=curl_error($ch);
        curl_close ($ch);
        $result = json_decode($result,true);
        return $result;
    }


    //永乐话费充值接口   //自动
    const WX_YONGLE_YOLLYID = "65548282";
    const WX_YONGLE_YOLLYKEY = "171F2DE4A84D476CB5E27EB36DDF2D42";
    const WX_YONGLE_CIPHERTEXT = "yolly2.0";
    public static function YongLe($mobile,$money='300',$type='1'){
        $reurl="http://wx4776f6dc70c1aca0.mysite.com/index.php?r=xg-member/result";
        $time = time();
        $res= \yii::$app->db->createCommand("select * from wx_yongle_record where mobile=".$mobile)->queryOne();
        if ($res['flow']) {
            $flow = $res['flow'];
        }else{
            $flow = $time.$mobile;
        }
        $text= md5(self::WX_YONGLE_YOLLYID.$flow.$time.$reurl.$mobile.$money.$type.self::WX_YONGLE_YOLLYKEY);
        $url ='http://third.yolly.cn/third/interfaceNew/recharge.do?YOLLYID='.self::WX_YONGLE_YOLLYID.'&YOLLYFLOW='.$flow.'&YOLLYTIME='.$time.'&YOLLYURL='.$reurl.'&MOBILE='.$mobile.'&MONEY='.$money.'&TYPE='.$type.'&CIPHERTEXT='.self::WX_YONGLE_CIPHERTEXT.'&MD5TEXT='.$text;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec ($ch);
        curl_close ($ch);
        $result=urldecode(urldecode($result));
        $result=simplexml_load_string($result);
        $result = json_decode(json_encode($result),true);
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $db = \yii::$app->db->createCommand();
        $db->insert('wx_yongle_record',['gh_id'=>$gh_id,"flow"=>$result['YOLLYFLOW'],"mobile"=>$mobile,"status"=>$result['RESPONSE']['RSPCODE'],'place'=>$result['RESPONSE']['RSPDESC']])->execute();
        return $result;
    }

    //永乐  手动充值
    public static function YongLes($mobile,$money='300',$type='1'){
        $reurl="http://wx4776f6dc70c1aca0.mysite.com/index.php?r=xg-member/result";
        $time = time();
        $flow = $time.$mobile;
        /*$res= \yii::$app->db->createCommand("select * from wx_yongle_record where mobile=".$mobile)->queryOne();
        if ($res['flow']) {
            $flow = $res['flow'];
        }else{
            $flow = $time.$mobile;
        }*/
        $text= md5(self::WX_YONGLE_YOLLYID.$flow.$time.$reurl.$mobile.$money.$type.self::WX_YONGLE_YOLLYKEY);
        $url ='http://third.yolly.cn/third/interfaceNew/recharge.do?YOLLYID='.self::WX_YONGLE_YOLLYID.'&YOLLYFLOW='.$flow.'&YOLLYTIME='.$time.'&YOLLYURL='.$reurl.'&MOBILE='.$mobile.'&MONEY='.$money.'&TYPE='.$type.'&CIPHERTEXT='.self::WX_YONGLE_CIPHERTEXT.'&MD5TEXT='.$text;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec ($ch);
        curl_close ($ch);
        $result=urldecode(urldecode($result));
        $result=simplexml_load_string($result);
        $result = json_decode(json_encode($result),true);
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $db = \yii::$app->db->createCommand();
        $db->insert('wx_yongle_record',['gh_id'=>$gh_id,"flow"=>$result['YOLLYFLOW'],"mobile"=>$mobile,"status"=>$result['RESPONSE']['RSPCODE'],'place'=>$result['RESPONSE']['RSPDESC']])->execute();
        return $result;
    }

    //1元，3元，5元，10元充值接口
    const WX_HUYI_USERNAME = "71695840";
    const WX_HUYI_APIKEY = "49o3yYpz3oQ15OUm5Vur";
    const WX_HUYI_BASICURL = "http://f.ihuyi.com/phone?action=recharge&%s";
    public static function HuYi($mobile,$package,$type=1)
    {
        if ($type == 2) {
            $res= \yii::$app->db->createCommand("select * from wx_huyi_record where type=2 and mobile=".$mobile)->queryOne();
            if ($res) {
                if ($res["code"] == 1) {
                    return \yii\helpers\Json::encode(['code' => 0, 'message' => '该用户已经充过值了！']);
                }
            }
        }

        //$basicUrl = "http://f.ihuyi.com/phone?action=recharge&%s";
        // $username = '71695840';
        //$apikey = '49o3yYpz3oQ15OUm5Vur';
        // $mobile = '13657209591';
        //$package = 10;
        $orderid = 'TEST_' . date("YmdHis") . mt_rand(100, 1000);
        $dataGet = array();
        $dataGet['package'] = $package;
        $dataGet['username'] = self::WX_HUYI_USERNAME;
        $dataGet['timestamp'] = date("YmdHis");
        $dataGet['mobile'] = $mobile;
        $dataGet['orderid'] = $orderid;
        $dataGet['sign'] = md5(
            sprintf("apikey=%s&mobile=%s&orderid=%s&package=%s&timestamp=%s&username=%s"
                , self::WX_HUYI_APIKEY
                , $mobile
                , $orderid
                , $package
                , date("YmdHis")
                , self::WX_HUYI_USERNAME)
        );
        $dataReturn = array();
        foreach ($dataGet as $key => $row) {
            $dataReturn[] = sprintf("%s=%s", $key, $row);
        }
        $urlGet = sprintf(self::WX_HUYI_BASICURL, implode("&", $dataReturn));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlGet); //定义表单提交地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //60秒
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_REFERER, 'http://' . $_SERVER['HTTP_HOST']);
        curl_setopt($ch, CURLOPT_POST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        $dataRet = json_decode($data, 1);
        //var_dump($dataRet);exit;
        $gh_id = \common\wosotech\Util::getSessionGhid();
        $db = \yii::$app->db->createCommand();
        if (empty($dataRet['taskid'])) {
            $dataRet['taskid'] = '';
        }
        $db->insert('wx_huyi_record',['gh_id'=>$gh_id,"mobile"=>$mobile,"package"=>$package,"message"=>$dataRet['message'],'code'=>$dataRet['code'],'taskid'=>$dataRet['taskid'],'type'=>$type])->execute();
        return $data;
    }

    public function getUserRevenueAmount() {
        return $this->redpack_revenue_amount;
    }    

    public function getUserConsumeAmount() {
        return $this->redpack_consume_amount;
    }    

    public static function bindMobileAjax($params)
    {
        Yii::error('xxxxx1111');

        return \yii\helpers\Json::encode(['code' => 0, 'msg' => 'OK']);

        $verifycode_cache = \Yii::$app->cache->get('SMS-VERIFY-CODE' . $params['mobile']);
        if (empty($verifycode_cache)) {
            return \yii\helpers\Json::encode(['code' => -1, 'msg' => '验证码失效！']);
        } else if ($verifycode_cache !== $params['verifycode']) {
            return \yii\helpers\Json::encode(['code' => -2, 'msg' => '验证码错误！']);
        }

        if (null !== WxMember::findOne(['tel' => $params['mobile']])) {
            return \yii\helpers\Json::encode(['code' => 1, 'msg' => '此手机号已被使用!']);        
        }

        if (null === ($user = WxUser::findOne(['openid' => $params['openid']]))) {
            return \yii\helpers\Json::encode(['code' => 1, 'msg' => '无此用户']);                
        }
        
        $model = WxMember::findOne(['openid' => $params['openid']]);
        if (null === $model) {
            $model = new WxMember();
        }
        $model->gh_id = $user->gh_id;
        $model->openid = $user->openid;
        $model->tel = $params['mobile'];  
        $model->is_binding = 1;
        $result = $model->save();
        if ( true !== $result) {
            yii::error(print_r($model->getErrors(), true));
            return \yii\helpers\Json::encode(['code' => 1, 'msg' => '保存错误']);
        }

        return \yii\helpers\Json::encode(['code' => 0, 'msg' => 'OK']);
    }

    public function getStaff()
    {
        return $this->hasOne(Fans::className(), ['gh_id' => 'gh_id', 'phone' => 'tel']);
    }

    public function getFanMembers()
    {
        return $this->hasMany(\common\models\WxMemberfans::className(), ['gh_id' => 'gh_id', 'scene_str' => 'scene_str'])->viaTable('wx_qrlimit', ['action_name' => 'openid']);
    }

    public function extraFields()
    {
        return ['wxUser', 'wxQrlimit', 'staff', 'fanmembers'];
    }

    public static function puturl($openid){
        $url = "http://wx.mysite.com/api/v1/ActivityAddCount?ghid=gh_82df8393167b";
        $data = $data = array(
            "type"=>"bind_fan",
            "openid"=>$openid,
            "acid"=>20,
            "count"=>1
        );;
        $data = json_encode($data);
        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"PUT"); //设置请求方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }


}
