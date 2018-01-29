<?php

use common\models\WxGh;
use common\wosotech\WX;
use yii\db\Migration;

class m161116_115156_create_init_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 ENGINE=myisam';
        }

        $this->createTable('wx_user', [
            'id' => $this->primaryKey(),        
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),
            'openid' => $this->string(64)->notNull()->defaultValue(''),
            'unionid' => $this->string(64),
            'subscribe' => $this->smallInteger(),
            'subscribe_time' => $this->integer()->unsigned(),
            'nickname' => $this->string(),
            'sex' => $this->smallInteger(),
            'city' => $this->string(32),
            'country' => $this->string(32),
            'province' => $this->string(32),
            'headimgurl' => $this->string(),
            'groupid' => $this->integer(),
            'remark' => $this->string(),
            'mobile' => $this->string(11)->comment('手机号'),      
            'points' => $this->integer()->defaultValue(0),            
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),            
        ], $tableOptions);
        $this->createIndex('idx_gh_id', 'wx_user', ['gh_id']);        
        $this->createIndex('idx_openid', 'wx_user', ['openid'], true);        
        $this->createIndex('idx_mobile', 'wx_user', ['mobile'], true);        
        
        $this->createTable('wx_client', [
            'id' => $this->primaryKey(),
            'codename' => $this->string(16)->notNull()->unique(),
            'fullname' => $this->string()->notNull()->defaultValue(''),
            'shortname' => $this->string()->notNull()->defaultValue(''),
            'city' => $this->string(32)->defaultValue(''),
            'province' => $this->string(32)->defaultValue(''),
            'country' => $this->string(32)->defaultValue(''),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
            'logo_id' => $this->integer()->comment('Logo'),
        ], $tableOptions);
        
        $this->createTable('wx_gh', [
            'id' => $this->primaryKey(),        
            'sid' => $this->string(32),
            'title' => $this->string(32),
            'client_id' => $this->integer(),
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),
            'appId' => $this->string(64)->notNull()->defaultValue(''),
            'appSecret' => $this->string(64)->notNull()->defaultValue(''),
            'token' => $this->string(32)->notNull()->defaultValue(''),
            //'use_open_platform' => $this->boolean()->defaultValue(0),
            'accessToken' => $this->string(512)->defaultValue(''),
            'accessToken_expiresIn' => $this->integer(),
            'encodingAESKey' => $this->string(43),
            'encodingMode' => $this->smallInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),            
            'jsapiTicket' => $this->string(512),     
            'jsapiTicket_expiresIn' => $this->integer(),            
            'wxPayMchId' => $this->string(32),     
            'wxPayApiKey' => $this->string(64),                 
            'wxcardapiTicket' => $this->string(512),                 
            'wxcardapiTicket_expiresIn' => $this->integer(),
            'sms_template' => $this->string(12),
            'platform' => $this->smallInteger()->notNull()->defaultValue(0)->comment('平台'),
            'is_service' => $this->smallInteger()->notNull()->defaultValue(1)->comment('服务号'),
            'is_authenticated' => $this->smallInteger()->notNull()->defaultValue(1)->comment('已认证'),
            'qr_image_id' => $this->integer()->comment('二维码图片ID'),
        ], $tableOptions);        
        $this->createIndex('idx_gh_id', 'wx_gh', ['gh_id'], true);
        $this->createIndex('sid', 'wx_gh', ['sid'], true);

        $model = new WxGh();
        $model->setAttributes([
            'title' => '测试公众号',
            'gh_id' => WxGh::WXGH_DEMO,
            'appId' => 'wxfadd14294fa1624f',
            'appSecret' => 'xxx',
            'sid' => 'wxfadd14294fa1624f',
        ]);
        $model->save();

        $this->createTable('wx_msg_log', [
            'id' => $this->primaryKey(),
            'ToUserName' => $this->string(64)->notNull(),
            'FromUserName' => $this->string(64)->notNull(),
            'CreateTime' => $this->integer(),
            'MsgType' => $this->string(),
            'WholeMsg' => $this->string(1024),
            'created_at' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->defaultValue(0),
            'elapsetime' => $this->float()            
        ], $tableOptions);

        $this->createTable('wx_menu', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(64)->notNull(),
            'name' => $this->string(40)->notNull(),
            'parent_id' => $this->integer(),
            'type' => $this->string(32),
            'key' => $this->string(512),
            'order' => $this->smallInteger(),
            'sub_button_flag' => $this->smallInteger(),
        ], $tableOptions);

        $this->createTable('wx_point_log', [
            'id' => $this->primaryKey(),
            'openid' => $this->string(32)->notNull(),
            'amount' => $this->integer(),
            'category' => $this->string(32),
            'comment' => $this->string(64),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

/*        $this->createTable('wx_outlet', [
            'id' => $this->primaryKey(),
            'sid' => $this->string(32),            
            'client_id' => $this->integer()->notNull(),
            'business_name' => $this->string(),
            'branch_name' => $this->string(),
            'categories' => $this->string(),
            'province' => $this->string(16),
            'city' => $this->string(16),
            'district' => $this->string(16),
            'address' => $this->string(),
            'longitude' => $this->double(),
            'latitude' => $this->double(),
            'offset_type' => $this->smallInteger()->defaultValue(1),
            'telephone' => $this->string(),
            'introduction' => $this->text(),
            'recommend' => $this->string(),
            'special' => $this->string(),
            'open_time' => $this->string(),
            'avg_price' => $this->integer(),
            'self_operated' => $this->smallInteger()->notNull()->defaultValue(0),
            'online' => $this->smallInteger()->notNull()->defaultValue(1),            
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);

        $this->createTable('wx_outlet_employee', [
            'id' => $this->primaryKey(),      
            'outlet_id' => $this->integer()->notNull(),            
            'employee_name' => $this->string(),
            'employee_mobile' => $this->string(11)->notNull(),
            'employee_role' => $this->string(32)->notNull()->defaultValue('employee'),
        ], $tableOptions);
        $this->createIndex('idx_outlet_id_mobile', 'wx_outlet_employee', ['outlet_id', 'employee_mobile'], true);      

        $this->createTable('wx_gh_outlet', [
            'id' => $this->primaryKey(),              
            'gh_id' => $this->string(32)->notNull(),
            'outlet_id' => $this->integer()->notNull(),
            'poi_id' => $this->string(32),
            'available_state' => $this->integer(),
            'update_status' => $this->integer(),
            'photo_list' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);    
        $this->createIndex('idx_gh_id_outet_id', 'wx_gh_outlet', ['gh_id', 'outlet_id'], true);      
*/
        $this->createTable('wx_template_id', [
            'gh_id' => $this->string(64)->notNull(),
            'template_id_short' => $this->string(32)->notNull(),
            'template_id' => $this->string(128)->notNull(),
        ], $tableOptions);

        $this->createTable('wx_brand', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull()->defaultValue(0),
            'name' => $this->string(32),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createTable('wx_model', [
            'id' => $this->primaryKey(),
            'brand_id' => $this->integer(),
            'name' => $this->string(32),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);
        $this->createIndex('brand_id', 'wx_model', ['brand_id']);

        $this->createTable('wx_member', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),
            'openid' => $this->string(64)->notNull()->defaultValue(''),
            'tel' => $this->string(11)->notNull()->defaultValue(''),
            'vermicelli' => $this->integer()->notNull()->defaultValue(0)->comment('粉丝量'),
            'is_binding' => $this->smallInteger()->notNull()->defaultValue(0)->comment('是否绑定手机号，0：未绑定，1：已绑定'),
            'member_type' => $this->smallInteger()->notNull()->defaultValue(0)->comment('会员类型，0：普通会员，1：高级会员...'),
            'member_grade' => $this->smallInteger()->notNull()->defaultValue(0)->comment('会员等级'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('idx_openid', 'wx_member', ['openid']);
        $this->createIndex('idx_gh_id', 'wx_member', ['gh_id']);

        $this->createTable('wx_memberfans', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),
            'openid' => $this->string(64)->notNull()->defaultValue(''),
            'scene_str' => $this->string(16)->notNull()->defaultValue(''),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('idx_openid', 'wx_memberfans', ['openid']);
        $this->createIndex('idx_scene_str', 'wx_memberfans', ['scene_str']);
        $this->createIndex('idx_gh_id', 'wx_memberfans', ['gh_id']);

    }

    public function down()
    {
        $this->dropTable('wx_template_id');

        $this->dropTable('wx_point_log');
    
        $this->dropTable('wx_menu');
        
        $this->dropTable('wx_msg_log');
    
        $this->dropTable('wx_gh');
    
        $this->dropTable('wx_client');
    
        $this->dropTable('wx_user');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
