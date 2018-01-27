<?php

use yii\db\Migration;

class m161123_073759_add_sj_policy extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 ENGINE=myisam';
        }

        $this->createTable('sj_policy', [
            'id' => $this->primaryKey(),
            'generate_policy_sid' => $this->string(32),
            'imei' => $this->string(32)->notNull()->defaultValue(''),            
            'openid' => $this->string(64)->notNull()->defaultValue(''),
            'mobile' => $this->string(11),
            'imgPath' => $this->string(1024),            
            'clerk_id' => $this->string(32)->comment('Outlet employee SID'),            
            'brand_id' => $this->integer(),            
            'model_id' => $this->integer(),            
            'state' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),  
    	    'start_time' => $this->timestamp()->defaultValue(null),
    	    'end_time' => $this->timestamp()->defaultValue(null),
        ], $tableOptions);
        
        $this->createIndex('generate_policy_sid', 'sj_policy', ['generate_policy_sid'], true);        
        $this->createIndex('clerk_id', 'sj_policy', ['clerk_id']);        
        $this->createIndex('brand_id', 'sj_policy', ['brand_id']);                
        
    }

    public function down()
    {    
        $this->dropTable('sj_policy');
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
