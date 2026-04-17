<?php

use yii\db\Migration;

class m260413_151356_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $q = "CREATE TABLE IF NOT EXISTS `site_alert` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `type` smallint(6) DEFAULT NULL,
            `message` varchar(2048) DEFAULT NULL,
            `user_id` int(11) unsigned DEFAULT NULL,
            `url` varchar(255) DEFAULT NULL,
            `referrer` varchar(255) DEFAULT NULL,
            `custom` text,
            `comment` text,
            `status` tinyint(4) NOT NULL DEFAULT '0',
            `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_message_status_url` (`message`(100),`status`,`url`),
            KEY `idx_status_type` (`status`,`type`),
            KEY `idx_type` (`type`),
            KEY `idx_created_status` (`created`,`status`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
        $this->execute($q);

        $q = "CREATE TABLE IF NOT EXISTS `user` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `email` varchar(255) NOT NULL,
            `roles` smallint(6) NOT NULL,
            `auth_key` varchar(32) NOT NULL,
            `password_hash` varchar(255) NOT NULL,
            `password_reset_token` varchar(255) DEFAULT NULL,
            `status` smallint(6) NOT NULL DEFAULT '10',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `custom` text,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`),
            UNIQUE KEY `password_reset_token` (`password_reset_token`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8";
        $this->execute($q);
        
        //$q = "";
        //$this->execute($q);  
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('site_alert');
        $this->dropTable('user');  
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250807_151356_init cannot be reverted.\n";

        return false;
    }
    */
}
