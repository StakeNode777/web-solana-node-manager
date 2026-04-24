<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%validator}}`.
 */
class m260420_135849_create_validator_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('validator', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name' => $this->string(255),
            'cluster' => "ENUM('Testnet','Mainnet') NOT NULL",            
            'health' => "ENUM('OK','DELINQUENT','UNDEFINED') NOT NULL DEFAULT 'OK'",
            'img_url' => $this->text(),
            'identity' => $this->string(44)->notNull(),
            'vote_account' => $this->string(44)->notNull(),
            'configured' => $this->boolean()->notNull()->defaultValue(false),
            'snm_server' => $this->string(45)->notNull()->defaultValue(''),
            'snm_ssh_login' => $this->string(255),
            'snm_ssh_password' => $this->string(255),            
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('validator');
    }
}
