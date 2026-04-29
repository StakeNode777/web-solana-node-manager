<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ssh_save_counters}}`.
 */
class m260427_114200_create_ssh_save_counters_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%ssh_save_counter}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'per_day' => $this->integer()->defaultValue(0),
            'per_month' => $this->integer()->defaultValue(0),
            'per_month_started_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-ssh_save_counter-user_id',
            '{{%ssh_save_counter}}',
            'user_id'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%ssh_save_counter}}');
    }
}
