<?php

namespace FormaLibre\PlatformAdministrationBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/08/06 04:54:17
 */
class Version20150806165416 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE formalibre__platform (
                id INT AUTO_INCREMENT NOT NULL, 
                friend_request_id INT DEFAULT NULL, 
                savedData LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json_array)', 
                modification_date DATETIME NOT NULL, 
                UNIQUE INDEX UNIQ_ADB667DCEC394CA1 (friend_request_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE formalibre__platform 
            ADD CONSTRAINT FK_ADB667DCEC394CA1 FOREIGN KEY (friend_request_id) 
            REFERENCES claro_friend_request (id) 
            ON DELETE SET NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE formalibre__platform
        ");
    }
}