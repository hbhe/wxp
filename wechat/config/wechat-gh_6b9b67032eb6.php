<?php
return [
    'behaviors' => [
        wechat\models\WxWallBehavior::className(),
        wechat\models\WxWallSignBehavior::className(),
        wechat\models\WxWallShakeBehavior::className(),
        wechat\models\XgMemberBehavior::className(),
        wechat\models\EchoBehavior::className(),
        wechat\models\XgdxUnBehavior::className(),
        common\modules\redpack\models\XgMemberTriggerRedpackBehavior::className(),
        //wechat\models\WxCeshiBehavior::className(),
        //wechat\models\NearestOfficeBehavior::className(),
    ],    
];

