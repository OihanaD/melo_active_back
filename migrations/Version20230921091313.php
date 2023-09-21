<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230921091313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, activity VARCHAR(255) NOT NULL, objectives LONGTEXT DEFAULT NULL, problems LONGTEXT DEFAULT NULL, repetition_per_month INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients_coaching_session (id INT AUTO_INCREMENT NOT NULL, client_id_id INT DEFAULT NULL, coaching_session_id_id INT DEFAULT NULL, is_paid TINYINT(1) NOT NULL, INDEX IDX_B99FFB2BDC2902E0 (client_id_id), INDEX IDX_B99FFB2B86E2065F (coaching_session_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, information LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coaching_session (id INT AUTO_INCREMENT NOT NULL, coach_id INT DEFAULT NULL, date_session DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, activity_session VARCHAR(255) NOT NULL, recap_of_coaching LONGTEXT DEFAULT NULL, objectif_of_coaching LONGTEXT DEFAULT NULL, INDEX IDX_7BAAADB43C105691 (coach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, userclient_id INT DEFAULT NULL, usercoach_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649D7A6C841 (userclient_id), UNIQUE INDEX UNIQ_8D93D6491055A9E6 (usercoach_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clients_coaching_session ADD CONSTRAINT FK_B99FFB2BDC2902E0 FOREIGN KEY (client_id_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE clients_coaching_session ADD CONSTRAINT FK_B99FFB2B86E2065F FOREIGN KEY (coaching_session_id_id) REFERENCES coaching_session (id)');
        $this->addSql('ALTER TABLE coaching_session ADD CONSTRAINT FK_7BAAADB43C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D7A6C841 FOREIGN KEY (userclient_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491055A9E6 FOREIGN KEY (usercoach_id) REFERENCES coach (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients_coaching_session DROP FOREIGN KEY FK_B99FFB2BDC2902E0');
        $this->addSql('ALTER TABLE clients_coaching_session DROP FOREIGN KEY FK_B99FFB2B86E2065F');
        $this->addSql('ALTER TABLE coaching_session DROP FOREIGN KEY FK_7BAAADB43C105691');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D7A6C841');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491055A9E6');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE clients_coaching_session');
        $this->addSql('DROP TABLE coach');
        $this->addSql('DROP TABLE coaching_session');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
