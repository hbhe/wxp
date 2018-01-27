<?php

use yii\db\Migration;
use yii\db\Schema;

//php yii migrate/up --migrationPath=@common/modules/wall/migrations
class m161208_135832_create_table_wx_wall extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 ENGINE=myisam';
        }
    
        $this->createTable('wx_wall', [
            'id' => $this->primaryKey(),        
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),
            'openid' => $this->string(64)->notNull()->defaultValue(''),            
            'content' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'is_checked' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'is_wall' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',            
            'is_from_openid' => $this->smallInteger()->notNull()->defaultValue(1),            
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),            
        ], $tableOptions);
        
        $this->createIndex('idx_gh_id', 'wx_wall', ['gh_id']);              
    }

    public function down()
    {
        $this->dropTable('wx_wall');
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
