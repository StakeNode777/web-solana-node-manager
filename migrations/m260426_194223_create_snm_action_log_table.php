<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%snm_action_log}}`.
 */
class m260426_194223_create_snm_action_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%snm_action_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'validator_id' => $this->integer()->notNull(),
            'action' => $this->string()->notNull(),
            'params' => $this->text()->notNull(),
            'result' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Indexes
        $this->createIndex('{{%idx-snm_action_log-user_id}}', '{{%snm_action_log}}', 'user_id');
        $this->createIndex('{{%idx-snm_action_log-validator_id}}', '{{%snm_action_log}}', 'validator_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-snm_action_log-user_id}}', '{{%snm_action_log}}');
        $this->dropIndex('{{%idx-snm_action_log-validator_id}}', '{{%snm_action_log}}');

        $this->dropTable('{{%snm_action_log}}');
    }
}
