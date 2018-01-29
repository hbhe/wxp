<?php
namespace wechat\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use common\models\WxGh;
use common\models\WxUser;

use wechat\models\Wechat;
use yii\helpers\Url;

use common\models\HuilongTrait;

/*
 * http://wechat.mysite.com/index.php?r=huilong&gh_id=gh_dacd2ee0dede
 * http://wechat.mysite.com/index.php?r=huilong/response&gh_id=gh_82df8393167b
 * http://127.0.0.1/wxp/wechat/web/index.php?r=huilong&gh_id=gh_dacd2ee0dede
 */
class HuilongController extends Controller
{
    public $enableCsrfValidation = false;    

    public $debug = true;
    
    public function actionIndex() {
        try {        
            if (0)  // just for test 
            {
                $_GET['gh_id'] = 'fhbohpkphhlmdkhagglkgabapdlmikio';
                $_GET['body'] = 'oofgbnnpinnopkohlnhfojcicikhplgafnnhmfjgkifkjppjbnbbbgdhejnilfdhalnndhfgalhlgkcdbkfgjoalgbfbhakdbeaofnpomcggmhkppigkodobeeemjcongecgndpnjdbfhdkjpdlbaniljbmjidkngkojdiebahdlcdkdfmjmcdhlcfomgcdlgjogdeakdnbhbmnjhhflibniaibadiggcjedenjknlolcceigbcjniphbbginoikjhjemakghenggonnjhgbfkiongibpiodbkmgaiieiiibngahmghjhdoodackdanlihogbndegggflmpojkadhbjbfflenjnljiojaoopkfflkodaeddbjfldgmfgbgjocajgpcjbekoibffdflkeehikkifanjlgnmakbmckfjoeaijllfaipigcnjlchglaecmjfajfnkddkiekcfljibppkpbkmhdnockfelagkbflkjlmdpnogmgkanehclhnongjjhhafajnfdiinlgkeddmfpmkioak';
            }
        
            $gh_id = HuilongTrait::decrypt($_GET['gh_id']);
            $xml = HuilongTrait::decrypt($_GET['body']);     

            //if ($this->debug) yii::error([$_GET, $gh_id, $xml, $_POST, file_get_contents("php://input")]);     
            yii::info([$_GET, $gh_id, $xml, $_POST, file_get_contents("php://input")]);     
            
            $arr = \EasyWeChat\Support\XML::parse($xml);
            
            /*            
            yii::error($arr);
            [
                'ToUserName' => 'gh_xxxxx',
                'FromUserName' => 'oNozyw6gl7lc5DYTNs8ob-SNpDX8',
                'CreateTime' => '1493874629',
                'MsgType' => 'event',
                'Event' => 'unsubscribe',
                'EventKey' => null,
            ]            
            */           
            if (!empty($arr['Event'])) {
                if (in_array($arr['Event'], ['TEMPLATESENDJOBFINISH'])) {
                    yii::$app->end();
                }
            }
            
        } catch(\Exception $e) {
            yii::error(['catch error ', $e->getMessage(), $_GET, $gh_id, $xml, $_POST, file_get_contents("php://input")]);       
            return 'panic';
        }

        $gh_ids = yii::$app->db->cache(function ($db) {
            return WxGh::find()->select(['gh_id'])->where(['platform' => WxGh::PLATFORM_HUILONG])->column();            
        }, 3600);

        if (!in_array($gh_id, $gh_ids)) {
            yii::error(['invalid gh_id', $gh_id]);
            return 'error';
        }

        $gh = yii::$app->db->cache(function ($db) use ($gh_id) {
            return WxGh::findOne(['gh_id' => $gh_id]);
        }, 3600);

        $url = Url::to(['site/index', 'gh_id' => $gh_id], true);

        // 将收到的XML转发给另一个微信消息服务器
        $response = \common\wosotech\Util::forwardWechatXML($url, $gh->token, $xml);
        
        return $response;
    }    

    /*
    [
        'r' => 'huilong/response',
        'gh_id' => 'gh_dacd2ee0dede',
    ],
    [
        '<xml><ToUserName><!' => [
            'CDATA[gh_82df8393167b' => '',
        ],
    ],
    '<xml><ToUserName><![CDATA[gh_82df8393167b]]></ToUserName><FromUserName><![CDATA[osHPgsvwJ_P7RB_AyKMpXtjbDJIk]]></FromUserName><CreateTime>1491817255</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[unbind]]></Content><MsgId>6407306322244124549</MsgId></xml>',
    */
    public function actionResponse() {
        yii::info([__METHOD__, __LINE__, $_GET, $_POST, file_get_contents("php://input")]);    
        $gh_id = $_GET['gh_id'];
        $gh = WxGh::findOne(['gh_id' => $gh_id]);   
        $url = Url::to(['site/index', 'gh_id' => $gh_id], true);
        $xml =  file_get_contents("php://input");
        $response = \common\wosotech\Util::forwardWechatXML($url, $gh->token, $xml);

        yii::info([__METHOD__, __LINE__, $response]);
        return $response;        
    }
}
