<?php

namespace common\modules\bargain\models;

use common\models\Activity;
use common\models\WxGh;
use common\models\WxUser;
use common\modules\bargain\Module;
use common\wosotech\base\ActiveRecord;
use common\wosotech\Util;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii2tech\ar\dynattribute\DynamicAttributeBehavior;

/**
 * This is the model class for table "wx_bargain_topic".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $title
 * @property string $detail
 * @property string $start_time
 * @property string $end_time
 * @property string $params
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class BargainTopic extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_bargain_topic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detail', 'params'], 'string'],
            [['start_time', 'end_time', 'created_at', 'updated_at'], 'safe'],
            [['status', 'activity_id'], 'integer'],
            [['gh_id'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 128],

            [['status', 'activity_id'], 'default', 'value' => 0],

            [[
                'post_num_limit',
                'post_num_need_display',
                'post_num_fake',
                'need_subscribe',
                'ad_img_id',
                'display_support',
                'support_name',
                'support_url',
                'post_num_limit_per_person',
                'post_num_limit_per_person_per_day',
                'post_can_select_same_item',
                'when_to_leave_contact',
                'contact_info' => 'name,mobile,address',
                'page_home_bg_img_id',
                'page_home_title_img_id',
                'page_home_help_img_id',
                'page_home_item_bg_img_id',
                'page_home_join_img_id',
                'page_post_top10_btn_img_id',
                'page_post_self_btn_img_id',
                'page_post_share_btn_img_id',
                'page_post_take_btn_img_id',
                'page_post_friend_btn_img_id',
                'page_post_return_home_btn_img_id',
                'page_comment_help_friend_btn_img_id',
                'page_post_failure_img_id',
                'page_post_progress_img_id',
                'page_comment_mp3',
                'page_items_desc',
                'customer_name',
                'customer_url',
                'customer_logo_img_id',
                'display_platform_ad',
                'scroll_winner',
                'location_limit',
                'btn_style',
                'btn_link_label',
                'btn_link_url',
                'btn_qr_label',
                'btn_qr_img_id',
                'weixin_share_img_id',
                'weixin_share_title_before_play',
                'weixin_share_title_after_play',
                'weixin_share_title_describe',

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
            'title' => '活动标题', // 如元旦有礼
            'detail' => '活动说明', // 活动及兑奖说明
            'start_time' => '活动开始时间',
            'end_time' => '活动结束时间',
            'params' => '活动参数',
            'status' => '状态', //0:等待, 1:进行中, 2:已暂停, 3:已结束
            'created_at' => '创建时间',
            'updated_at' => '更新时间',

            // 基础设置
            'post_num_limit' => '总参与人数限制', // 1-99999
            'post_num_need_display' => '前端是否显示总参与人数', // 0不显示，1:显示
            'post_num_fake' => '参与人数虚增多少',// 在实际参与人数基础上增加x人（用于营造活动气氛，只展示，不计入统计）
            'need_subscribe' => '是否需要强制关注', // 0:不需要, 1:必须先关注
            'ad_img_id' => '前置广告页图片URL', // 为空时表示无广告, 否则显示广告图片, 3秒后跳入活动home页
            'display_support' => '是否显示技术支持', // 0:不显示，1:显示, default为0
            'support_name' => '单位名称',
            'support_url' => '链接地址',

            // 派奖方式
            'post_num_limit_per_person' => '总参与机会', // 每人可参与创建此活动的次数, default值6
            'post_num_limit_per_person_per_day' => '每人每日参与机会', // 每人每日可参与创建活动的次数, default值3
            'post_can_select_same_item' => '同一用户是否可以挑战同一商品', // 参与者创建活动时能否选择相同奖品, default为1
            'when_to_leave_contact' => '何时留下联系方式', // 何时留下联系方式, 0:关闭, 1:参与前填写, 2:抽奖前填写, 3:中奖后填写, default为0
            'contact_info' => '需填信息', // 至少勾选1个:姓名,联系电话,联系地址

            // 首页
            'page_home_bg_img_id' => '首页背景图片', // 为空表示取前端默认,  \yii::getAlias('@frontendUrl') . '/img/bargain/home_bg.jpg'
            'page_home_title_img_id' => '活动标题图片', //如好友砍价这个图片, 为空表示由前端做准备,  \yii::getAlias('@frontendUrl') . '/img/bargain/home_title.jpg',
            'page_home_help_img_id' => '活动锦囊图片', //如好友砍价这个图片, 为空表示由前端做准备
            'page_home_item_bg_img_id' => '奖品背景图片', //如好友砍价这个图片, 为空表示由前端做准备
            'page_home_join_img_id' => '我要砍这个按钮', // 如我要砍这个, 为空表示由前端做准备, 挑选好商品后点它

            // 砍价详情页
            'page_post_top10_btn_img_id' => '砍价高手横幅', // 如砍价高手
            'page_post_self_btn_img_id' => '自砍一刀按钮', // 如自砍一刀
            'page_post_share_btn_img_id' => '分享按钮', // 如召唤小伙伴这个图片
            'page_post_take_btn_img_id' => '领取奖品按钮', // 如领取奖品
            'page_post_friend_btn_img_id' => '我也要参与按钮', // 如我也要参与, 好友在看到我在挑战商品后，也想挑战一下，就点这个链接
            'page_post_return_home_btn_img_id' => '返回首页按钮', // 如返回首页
            'page_post_progress_img_id' => '砍价进行中图片', // 砍价进行中的图片
            'page_post_failure_img_id' => '砍价失败图片', // 砍价失败了的图片

            // 好友进入页
            'page_comment_help_friend_btn_img_id' => '帮Ta砍一刀图片', // 如帮Ta砍一刀
            'page_comment_mp3' => '背景音乐URL地址', // 为空表示无

            // 活动奖品页
            'page_items_desc' => '活动奖品说明', // 商品一: 价值100元礼品, 商品二：价值50元礼品, ...

            // 企业信息
            'customer_name' => '主办单位', // 如襄阳移动
            'customer_url' => '链接地址',
            'customer_logo_img_id' => '企业Logo网址',

            // 游戏设置
            'display_platform_ad' => '显示平台广告', // 0：关闭, 1:开启，如在home页显示页面技术由xxx提供, 在奖品页显示“我也要创建活动”
            'scroll_winner' => '轮播获奖信息', // 0：关闭, 1:开启, 有三名以上玩家获奖后，活动首页将左右轮播展示玩家中奖信息，优先展示等级较高的获奖者
            'location_limit' => '可参与地区', // 根据微信地理位置(gps)判断玩家是否可参与, 湖北,湖南,..., 为空表示不限地区

            'btn_style' => '关注或跳转按钮', // 0:关闭， 1：页面跳转，2：一键关注
            'btn_link_label' => '按钮上的文字',
            'btn_link_url' => '跳转链接',
            'btn_qr_label' => '按钮上的文字',
            'btn_qr_img_id' => '公众号二维码',

            // 分享设置
            'weixin_share_img_id' => '微信分享图标',
            'weixin_share_title_before_play' => '未参与时分享内容',
            'weixin_share_title_after_play' => '参与后分享内容',
            'weixin_share_title_describe' => '分享描述',

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
                'class' => DynamicAttributeBehavior::className(),
                'storageAttribute' => 'params',
                'saveDynamicAttributeDefaults' => false,    // 与default相同的值没必要存在db中
                //'allowRandomDynamicAttribute' => true,
                'dynamicAttributeDefaults' => [
                    'post_num_limit' => 10000,
                    'post_num_need_display' => 1,
                    'post_num_fake' => 0,
                    'need_subscribe' => 0,
                    'ad_img_id' => 0,
                    'display_support' => 0,
                    'support_name' => '',
                    'support_url' => '',
                    'post_num_limit_per_person' => 6,
                    'post_num_limit_per_person_per_day' => 3,
                    'post_can_select_same_item' => 1,
                    'when_to_leave_contact' => 0,
                    'contact_info' => 'name,mobile,address',
                    'page_home_bg_img_id' => 0,
                    'page_home_title_img_id' => 0,
                    'page_home_help_img_id' => 0,
                    'page_home_item_bg_img_id' => 0,
                    'page_home_join_img_id' => 0,
                    'page_post_top10_btn_img_id' => 0,
                    'page_post_self_btn_img_id' => 0,
                    'page_post_share_btn_img_id' => 0,
                    'page_post_take_btn_img_id' => 0,
                    'page_post_friend_btn_img_id' => 0,
                    'page_post_progress_img_id' => 0,
                    'page_post_failure_img_id' => 0,
                    'page_post_return_home_btn_img_id' => 0,
                    'page_comment_help_friend_btn_img_id' => 0,
                    'page_comment_mp3' => '',
                    'page_items_desc' => '',
                    'customer_name' => '',
                    'customer_url' => 'http://',
                    'customer_logo_img_id' => 0,
                    'display_platform_ad' => 0,
                    'scroll_winner' => 0,
                    'location_limit' => '',
                    'btn_style' => 0,
                    'btn_link_label' => '企业官网',
                    'btn_link_url' => '',
                    'btn_qr_label' => '企业官微',
                    'btn_qr_img_id' => 0,
                    'weixin_share_img_id' => 0,
                    'weixin_share_title_before_play' => '​轻轻松松就能抽到大奖，积攒多年的人品终于有用了，你也赶紧来抽奖吧！！{nickname}',
                    'weixin_share_title_after_play' => '​{nickname}邀您来帮忙！',
                    'weixin_share_title_describe' => "赶紧分享哦！获得更多的抽奖机会哦！",
                ],
            ],
        ];
    }

    public function getWxGh()
    {
        return $this->hasOne(WxGh::className(), [
            'gh_id' => 'gh_id',
        ]);
    }

    public function getActivity()
    {
        return $this->hasOne(Activity::className(), [
            'id' => 'activity_id',
        ]);
    }

    public function getBargainItemsCount()
    {
        return $this->getBargainItems()->count();
    }

    public function getBargainItems()
    {
        return $this->hasMany(BargainItem::className(), [
            'topic_id' => 'id',
        ]);
    }

    public function getBargainPostsCount()
    {
        return $this->getBargainPosts()->count();
    }

    public function getBargainPosts()
    {
        return $this->hasMany(BargainPost::className(), [
            'topic_id' => 'id',
        ]);
    }

    public function afterDelete()
    {
        foreach ($this->bargainPosts as $bargainPost) {
            $bargainPost->delete();
        }
        foreach ($this->bargainItems as $bargainItem) {
            $bargainItem->delete();
        }
        parent::afterDelete();
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['bargainPostsCount'] = 'bargainPostsCount';
        $fields['bargainItemsCount'] = 'bargainItemsCount';
        $fields = array_merge($fields, array_keys($this->dynamicAttributes));
        $imgFields = [
            'ad_img_id',
            'page_home_bg_img_id',
            'page_home_title_img_id',
            'page_home_help_img_id',
            'page_home_item_bg_img_id',
            'page_home_join_img_id',
            'page_post_top10_btn_img_id',
            'page_post_self_btn_img_id',
            'page_post_share_btn_img_id',
            'page_post_take_btn_img_id',
            'page_post_friend_btn_img_id',
            'page_post_return_home_btn_img_id',
            'page_comment_help_friend_btn_img_id',
            'customer_logo_img_id',
            'btn_qr_img_id',
            'weixin_share_img_id',
            'page_post_failure_img_id',
            'page_post_progress_img_id',
        ];
        foreach ($imgFields as $field) {
            $fields[$field] = function ($model) use ($field) {
                return $this->getImgUrl($field);
            };
        }
        unset($fields['params']);

        return $fields;
    }

    public function extraFields()
    {
        return ['bargainItems', 'bargainPosts'];
    }

    const STATUS_WAIT = 0;
    const STATUS_DOING = 1;
    const STATUS_PAUSE = 2;
    const STATUS_DONE = 3;

    public static function getStatusOptionName($key = null)
    {
        $arr = [
            static::STATUS_WAIT => '等待',
            static::STATUS_DOING => '进行中',
            static::STATUS_PAUSE => '已暂停',
            static::STATUS_DONE => '已结束',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    const CONTACT_CLOSE = 0;
    const CONTACT_TAKE = 1;
    const CONTACT_WINBER = 2;
    const CONTACT_WINAFT = 3;

    public static function getContactOptionName($key = null)
    {
        $arr = [
            static::CONTACT_CLOSE => '关闭',
            static::CONTACT_TAKE => '参与前填写',
            static::CONTACT_WINBER => '中奖前填写',
            static::CONTACT_WINAFT => '中奖后填写',
        ];
        return $key === null ? $arr : (isset($arr[$key]) ? $arr[$key] : '');
    }

    public function getImgUrl($field, $w = 9999, $h = 9999)
    {
        if (!empty($this->$field)) {
            return Yii::$app->imagemanager->getImagePath($this->$field, $w, $h);
        }
        return '';
    }
}
