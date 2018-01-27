<?php

use yii\db\Migration;

class m170215_101933_create_table_wx_qrlimit extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=myisam';
        }
        $this->createTable('wx_qrlimit', [
                'id' => $this->primaryKey(),
                'gh_id' => $this->string(32)->notNull()->defaultValue(''),
                'action_name' => $this->string(64)->notNull()->defaultValue(''),
                'scene_str' => $this->string(16)->notNull()->defaultValue(''),
                'ticket' => $this->string(128)->notNull()->defaultValue(''),
                'kind' => $this->smallInteger()->notNull()->defaultValue(0),
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
                ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('wx_qrlimit');
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
