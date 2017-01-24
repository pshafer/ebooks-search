<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160912163233 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, is_active TINYINT(1) NOT NULL, role VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ebook (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, summary LONGTEXT NOT NULL, isbn10 VARCHAR(10) NOT NULL, isbn13 VARCHAR(13) NOT NULL, url VARCHAR(255) NOT NULL, coverimg VARCHAR(255) NOT NULL, authors LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ebooks_subjects (ebook_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_B5FBA82776E71D49 (ebook_id), INDEX IDX_B5FBA82723EDC87 (subject_id), PRIMARY KEY(ebook_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ebooks_subjects ADD CONSTRAINT FK_B5FBA82776E71D49 FOREIGN KEY (ebook_id) REFERENCES ebook (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ebooks_subjects ADD CONSTRAINT FK_B5FBA82723EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ebooks_subjects DROP FOREIGN KEY FK_B5FBA82776E71D49');
        $this->addSql('ALTER TABLE ebooks_subjects DROP FOREIGN KEY FK_B5FBA82723EDC87');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ebook');
        $this->addSql('DROP TABLE ebooks_subjects');
        $this->addSql('DROP TABLE subject');
    }
}
