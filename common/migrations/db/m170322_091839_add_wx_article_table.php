<?php

use yii\db\Migration;

class m170322_091839_add_wx_article_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8';
        }

        $this->createTable('wx_article', [
            'id' => $this->primaryKey(),        
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),            
            'title' => $this->string(128)->notNull()->defaultValue('')->comment('标题'),
            'author' => $this->string(128)->notNull()->defaultValue('')->comment('作者'),
            'digest' => $this->string(1024)->notNull()->defaultValue('')->comment('摘要'),
            'content' => $this->text()->comment('内容'),
            'photo_id' => $this->integer(),            
            'content_source_url' => $this->string(256)->notNull()->defaultValue('')->comment('原文链接'),
            'show_cover_pic' => $this->smallInteger()->notNull()->defaultValue(0)->comment('封面'),     
            'status' => $this->smallInteger()->notNull()->defaultValue(0),                                    
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),            
        ], $tableOptions);
        $this->createIndex('idx_gh_id', 'wx_article', ['gh_id']);        

        $this->createTable('wx_article_mult', [
            'id' => $this->primaryKey(),        
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),            
            'title' => $this->string(128)->notNull()->defaultValue('')->comment('标题'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),                                                
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),            
        ], $tableOptions);
        $this->createIndex('idx_gh_id', 'wx_article_mult', ['gh_id']);        


        $this->createTable('wx_article_mult_article', [
            'id' => $this->primaryKey(),        
            'gh_id' => $this->string(32)->notNull()->defaultValue(''),            
            'article_mult_id' => $this->integer()->notNull()->defaultValue(0),            
            'article_id' => $this->integer()->notNull()->defaultValue(0),            
            'sort_order' => $this->integer()->notNull()->defaultValue(0),                                    
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultValue(null),            
        ], $tableOptions);
        $this->createIndex('idx_gh_id', 'wx_article_mult_article', ['gh_id']);        

    }

    public function down()
    {
        $this->dropTable('wx_article');
        $this->dropTable('wx_article_mult');
        $this->dropTable('wx_article_mult_article');        
        return true;
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
