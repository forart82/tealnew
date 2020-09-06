<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606140920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, eid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, matricule VARCHAR(255) NOT NULL, logo LONGBLOB NOT NULL, language VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE csv_key_values (id INT AUTO_INCREMENT NOT NULL, eid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, as_value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emails (id INT AUTO_INCREMENT NOT NULL, eid VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, language VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE keytext (id INT AUTO_INCREMENT NOT NULL, eid VARCHAR(255) NOT NULL, keytext VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, eid VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, denomination VARCHAR(255) NOT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE navigations (id INT AUTO_INCREMENT NOT NULL, svg_id INT DEFAULT NULL, eid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, position INT NOT NULL, sub_position INT DEFAULT NULL, authorisation VARCHAR(10) NOT NULL, translation VARCHAR(255) NOT NULL, INDEX IDX_AD21D8F37517183B (svg_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, subject_id INT DEFAULT NULL, user_id INT DEFAULT NULL, eid VARCHAR(255) NOT NULL, choice INT NOT NULL, notation INT DEFAULT 0 NOT NULL, INDEX IDX_136AC11323EDC87 (subject_id), INDEX IDX_136AC113A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, svg_id INT DEFAULT NULL, eid VARCHAR(255) NOT NULL, question LONGTEXT NOT NULL, answer_one LONGTEXT NOT NULL, answer_two LONGTEXT NOT NULL, answer_three LONGTEXT NOT NULL, answer_four LONGTEXT NOT NULL, answer_five LONGTEXT NOT NULL, position INT NOT NULL, title VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, INDEX IDX_FBCE3E7A7517183B (svg_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE svg (id INT AUTO_INCREMENT NOT NULL, eid VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, svg LONGTEXT DEFAULT NULL, svg_color LONGTEXT DEFAULT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE translation (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, keytext_id INT DEFAULT NULL, eid VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_B469456F82F1BAF4 (language_id), INDEX IDX_B469456F9B873397 (keytext_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, eid VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, language VARCHAR(255) NOT NULL, is_new INT DEFAULT 1 NOT NULL, token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE navigations ADD CONSTRAINT FK_AD21D8F37517183B FOREIGN KEY (svg_id) REFERENCES svg (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC11323EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC113A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A7517183B FOREIGN KEY (svg_id) REFERENCES svg (id)');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F9B873397 FOREIGN KEY (keytext_id) REFERENCES keytext (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456F9B873397');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456F82F1BAF4');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC11323EDC87');
        $this->addSql('ALTER TABLE navigations DROP FOREIGN KEY FK_AD21D8F37517183B');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A7517183B');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC113A76ED395');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE csv_key_values');
        $this->addSql('DROP TABLE emails');
        $this->addSql('DROP TABLE keytext');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE navigations');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE svg');
        $this->addSql('DROP TABLE translation');
        $this->addSql('DROP TABLE user');
    }
}
