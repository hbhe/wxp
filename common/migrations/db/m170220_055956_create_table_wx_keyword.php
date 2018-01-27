<?php

use yii\db\Migration;

class m170220_055956_create_table_wx_keyword extends Migration
{
public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 ENGINE=myisam';
        }
        $this->createTable('wx_keyword', [
                'id' => $this->primaryKey(),
                'gh_id' => $this->string(32)->notNull()->defaultValue('')->comment('公众号ID'),
                'replyway' => $this->string(16)->notNull()->defaultValue('')->comment('回复方式'),
                'keyword' => $this->string(128)->notNull()->defaultValue('')->comment('关键词内容'),
                'match' => $this->string(16)->notNull()->defaultValue('')->comment('匹配规则'),
                'type' => $this->string(32)->notNull()->defaultValue('')->comment('响应类型'),
                'action' => $this->text()->notNull()->comment('内容'),
                'priority' => $this->smallInteger()->notNull()->defaultValue(0)->comment('优先级'),
                'inputEventType' => $this->string(16)->notNull()->defaultValue('')->comment('触发事件类型'),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
                ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('wx_keyword');
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
