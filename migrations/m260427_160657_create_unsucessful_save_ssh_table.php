<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unsucessful_save_ssh}}`.
 */
class m260427_160657_create_unsucessful_save_ssh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unsucessful_save_ssh}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex(
            'idx-unsuccessful-save-created-at',
            '{{%unsucessful_save_ssh}}',
            'created_at'
        );        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unsucessful_save_ssh}}');
    }
}
