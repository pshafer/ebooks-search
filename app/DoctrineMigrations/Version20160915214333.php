<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160915214333 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vendor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ebook ADD vendor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ebook ADD CONSTRAINT FK_7D51730DF603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id)');
        $this->addSql('CREATE INDEX IDX_7D51730DF603EE73 ON ebook (vendor_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ebook DROP FOREIGN KEY FK_7D51730DF603EE73');
        $this->addSql('DROP TABLE vendor');
        $this->addSql('DROP INDEX IDX_7D51730DF603EE73 ON ebook');
        $this->addSql('ALTER TABLE ebook DROP vendor_id');
    }
}
