<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160912191050 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ebook CHANGE isbn10 isbn10 VARCHAR(10) DEFAULT NULL, CHANGE isbn13 isbn13 VARCHAR(13) DEFAULT NULL, CHANGE coverimg coverimg VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ebook CHANGE isbn10 isbn10 VARCHAR(10) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE isbn13 isbn13 VARCHAR(13) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE coverimg coverimg VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
