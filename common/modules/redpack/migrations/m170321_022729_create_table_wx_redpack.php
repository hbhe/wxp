<?php
// php yii migrate --migrationPath=@common/modules/redpack/migrations
use yii\db\Migration;

class m170321_022729_create_table_wx_redpack extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=myisam';
        }

        $this->createTable('wx_redpack_log', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(64)->notNull(),
            'openid' => $this->string(64)->notNull(),
            'category' => $this->boolean()->notNull()->defaultValue(0),
            'is_revenue' => $this->boolean()->notNull()->defaultValue(1),
            'amount' => $this->integer()->notNull()->defaultValue(0)->comment('发生额'),
            'comment' => $this->string(256),            
            'mobile' => $this->string(16)->notNull()->defaultValue(''),      
            'openid_another' => $this->string(64)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),            
            'mch_billno' => $this->string(32)->defaultValue(''),                        
            'sendtime' => $this->timestamp()->defaultValue(null),      
            'recvtime' => $this->timestamp()->defaultValue(null),                              
        ], $tableOptions);
        
        $this->createIndex('idx_openid', 'wx_redpack_log', ['openid']);        
        $this->createIndex('idx_gh_id', 'wx_redpack_log', ['gh_id']);        

        $this->addColumn('wx_member', 'redpack_status', $this->boolean()->notNull()->defaultValue(0)->comment('红包账号状态'));
        $this->addColumn('wx_member', 'redpack_balance', $this->integer()->notNull()->defaultValue(0)->comment('红包余额'));
        $this->addColumn('wx_member', 'redpack_revenue_amount', $this->integer()->notNull()->defaultValue(0)->comment('红包收入总额'));
        $this->addColumn('wx_member', 'redpack_consume_amount', $this->integer()->notNull()->defaultValue(0)->comment('红包提现总额'));

        $this->addColumn('wx_memberfans', 'visit_last_time', $this->timestamp()->comment('最后访问时间'));
        $this->addColumn('wx_memberfans', 'visit_count', $this->integer()->notNull()->defaultValue(0)->comment('访问次数'));
        $this->addColumn('wx_memberfans', 'is_paid', $this->boolean()->comment('此粉丝已计酬'));
                
        $this->createTable('wx_redpack_stat', [
            'id' => $this->primaryKey(),
            'gh_id' => $this->string(64)->notNull(),
            'recommend_revenue_amount' => $this->integer()->notNull()->defaultValue(0)->comment('红包金额'),
            'recommend_fan_count' => $this->integer()->notNull()->defaultValue(0)->comment('总粉丝数'),
            'recommend_fan_revenue_count' => $this->integer()->notNull()->defaultValue(0)->comment('计酬粉丝数'),                        
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('created_at', 'wx_redpack_stat', 'created_at');        
        $this->createIndex('idx_gh_id', 'wx_redpack_stat', ['gh_id']);   

        $this->createTable('wx_redpack_vote', [
            'id' => $this->primaryKey(),
            'openid' => $this->string()->comment('OpenID'),
            'gender' => $this->integer()->comment('性别'),
            'age' => $this->integer()->comment('年龄'),
            'expense' => $this->integer()->comment('月消费'),
            'type' => $this->string()->comment('类型'),
            'problem' => $this->string()->comment('问题'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('状态'),
            'ip' => $this->string(128)->notNull()->defaultValue('')->comment('IP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('wx_redpack_log');        

    }

}
