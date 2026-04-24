<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%servers}}`.
 */
class m260621_111038_create_servers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%server}}', [
            'id' => $this->primaryKey(),
            'validator_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'ip' => $this->string()->notNull(),
            'status' => $this->string(16)->notNull()->defaultValue('OK'),
            'status_msg' => $this->string()->null(),
            'is_active' => $this->boolean()->notNull()->defaultValue(true),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-server-validator_id}}',
            '{{%server}}',
            'validator_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%server}}');
    }
}
