<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "wx_menu".
 *
 * @property integer $id
 * @property string $gh_id
 * @property string $name
 * @property integer $parent_id
 * @property string $type
 * @property string $key
 */
class WxMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gh_id', 'name'], 'required'],
            [['parent_id'], 'integer'],
            [['gh_id'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 40],
            [['type'], 'string', 'max' => 32],
            [['key'], 'string', 'max' => 512],
            [['menuPosition'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gh_id' => '公众号原始ID',
            'name' => '菜单名称',
            'parent_id' => '菜单级别',
            'type' => '类型',
            'key' => '键值',
        ];
    }
    
    public function getParentItems() {
        $items['parent-1'] = '一级菜单(1)';
        $items['parent-2'] = '一级菜单(2)';
        $items['parent-3'] = '一级菜单(3)';
        $menus = self::find()->where([
            'gh_id' => $this->gh_id,
            'parent_id' => NULL,
        ])->orderBy('order')->all();
        foreach ($menus as $menu) {
            $items[$menu->id .'-1'] = '二级菜单：' . $menu->name . "(1)";
            $items[$menu->id .'-2'] = '二级菜单：' . $menu->name . "(2)";
            $items[$menu->id .'-3'] = '二级菜单：' . $menu->name . "(3)";
            $items[$menu->id .'-4'] = '二级菜单：' . $menu->name . "(4)";
            $items[$menu->id .'-5'] = '二级菜单：' . $menu->name . "(5)";
        }
        return $items;
    }
    
    public function getKeyString() {
        return substr($this->key, 0, 100);
    }
    
    public function setMenuPosition($menuPosition) {
        list($parent_id, $order) = explode('-', $menuPosition);
        if ('parent' === $parent_id) 
            $this->parent_id = NULL;
        else
            $this->parent_id = $parent_id;
        $this->order = $order;
    }
    public function getMenuPosition() {
        if (empty($this->parent_id)) {
            return '一级菜单'."({$this->order})";
        }
        $parent = self::findOne(['id' => $this->parent_id]);
        return '二级菜单：'. $parent->name . "({$this->order})";
    }
    
    public function getMenuType() {
        $menuTypes = $this->menuTypes;
        return empty($menuTypes[$this->type]) ? '父菜单': $menuTypes[$this->type];
    }
    public function getMenuTypes() {
        return [
            'parent' => '父菜单',
            'click' => '发送文字',
            'view' => '跳转网页',
            'scancode_push' => '扫码事件',
            'scancode_waitmsg' => '扫码消息',
            'pic_sysphoto' => '拍照发图',
            'pic_photo_or_album' => '手机相册发图',
            'pic_weixin' => '微信相册发图',
            'location_select' => '发送地理位置',
            'media_id' => '下发素材',
            'view_limited' => '下发图文',            
        ];
    }
    
    public static function clear($gh_id) {
        $sql = 'delete from wx_menu where gh_id = :gh_id';
        Yii::$app->db->createCommand($sql, [':gh_id' => $gh_id])->execute();
    }
    
    private static function getTypeKey($type) {
        switch ($type) {
            case 'view':
                return 'url';
            case 'media_id':
            case 'view_limited':
                return 'media_id';
            default:
                return 'key';
        }
    }
    
    private static function getKeyValue($button) {
        switch ($button['type']) {
            case 'click':
            case 'location_select':
                return $button['key'];
            case 'view':
                return $button['url']; 
            case 'media_id':
                return $button['media_id'];
            default:
                return $button['name'];
        }
    }
    public static function fromWx($gh_id, $responseArray) {
        $level1 = 1;
        foreach ($responseArray['button'] as $button) {
            $wxmenu = new self;
            $wxmenu->gh_id = $gh_id;
            $wxmenu->name = $button['name'];
            $wxmenu->order = $level1++;
            if (empty($button['sub_button'])) {
                $wxmenu->type = $button['type'];
                $wxmenu->key = self::getKeyValue($button);
                $wxmenu->save(false);
            } else {
                $level2 = 1;
                $wxmenu->save(false);
                $wxmenu->refresh();
                foreach ($button['sub_button']['list'] as $sub_button) {
                    $wxsubmenu = new self;
                    $wxsubmenu->gh_id = $gh_id;
                    $wxsubmenu->name = $sub_button['name'];
                    $wxsubmenu->type = $sub_button['type'];
                    $wxsubmenu->key = self::getKeyValue($sub_button);
                    $wxsubmenu->parent_id = $wxmenu->id;
                    $wxsubmenu->order = $level2++;
                    $wxsubmenu->save(false);
                }
            }
        }
    } 
    
    public function getGh() {
        return $this->hasOne(WxGh::className(), ['gh_id' => 'gh_id']);
    }
    
    public static function toWx($gh_id) {
        $toWxArray['button'] = [];
        $buttons = self::find()->where([
            'gh_id' => $gh_id,
            'parent_id' => null,
        ])->orderBy('order')->limit(3)->all();
        foreach ($buttons as $button) {
            $sub_buttons = self::find()->where([
                'parent_id' => $button->id,
            ])->orderBy('order')->limit(5)->all();
            if (empty($sub_buttons)) {
                $toWxArray['button'][] = [
                    'name' => $button->name,
                    'type' => $button->type,
                    self::getTypeKey($button->type) => $button->key,
                ];
            } else {
                $tmpArray =  [
                    'name' => $button->name,
                    'sub_button' => [],
                ];
                foreach($sub_buttons as $sub_button) {
                    $tmpArray['sub_button'][] = [
                        'name' => $sub_button->name,
                        'type' => $sub_button->type,
                        self::getTypeKey($sub_button->type) => $sub_button->key,
                    ];
                }
                $toWxArray['button'][] = $tmpArray;                
            }            
        }
        return $toWxArray;
    }
    
    public function getParent() {
        return self::findOne(['id' => $this->parent_id]);
    }
    
    public static function getViewMenuPathInfo($gh_id, $viewurl) {
        $menu = self::findOne([
            'gh_id' => $gh_id,
            'type' => 'view',
            'key' => $viewurl,
        ]);
        if (empty($menu)) {
            return '历史跳转网页菜单：'.$viewurl;
        } else {
            $strRet = '访问菜单：';
            if (!empty($menu->parent)) {
                $strRet .= $menu->parent->name . '->';
            }
            $strRet .= $menu->name;
            return $strRet;
        }
    } 
}
