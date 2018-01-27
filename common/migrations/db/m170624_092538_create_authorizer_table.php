<?php

use yii\db\Migration;

/**
 * Handles the creation of table `authorizer`.
 */
class m170624_092538_create_authorizer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('wx_authorizer', [
            'id' => $this->primaryKey(),
            'authorizer_appid' => $this->string(64)->comment('授权方Appid'), // appid
            'authorizer_refresh_token' => $this->string(128)->comment('刷新Token'),
            'func_info' => $this->text()->comment('授权的权限集'), // 1,2,...
            'user_name' => $this->string(64)->comment('原始ID'), // gh_id
            'nick_name' => $this->string(128)->comment('授权方昵称'),
            'head_img' => $this->string(512)->comment('授权方头像'),
            'service_type_info' => $this->integer()->comment('公众号类型'),
            'verify_type_info' => $this->integer()->comment('认证类型'),
            'alias' => $this->string(128)->comment('微信号'),
            'qrcode_url' => $this->string(512)->comment('二维码图片URL'),
            'business_info' => $this->text()->comment('功能开通状况'),
            'principal_name' => $this->string(512)->comment('主体名称'),
            'signature' => $this->text()->comment('帐号介绍'),
            'miniprograminfo' => $this->text()->comment('小程序信息'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ]);
        $this->createIndex('authorizer_appid', 'wx_authorizer', ['authorizer_appid'], true);
        $this->createIndex('user_name', 'wx_authorizer', ['user_name'], true);

        //$this->addColumn('wx_gh', 'use_open_platform', $this->boolean()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('wx_authorizer');
    }
}
