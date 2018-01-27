<?php

use yii\db\Migration;
use yii\db\Schema;

class m161215_143346_create_table_wx_wall_sign extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 ENGINE=myisam';
        }
        
        $this->createTable('wx_wall_sign', [
                'id' => $this->primaryKey(),
                'gh_id' => $this->string(32)->notNull()->defaultValue(''),
                'openid' => $this->string(64)->notNull()->defaultValue(''),
                'is_display' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
                'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);                
    }

    public function down()
    {
       $this->dropTable('wx_wall_sign');
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
