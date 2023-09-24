<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924113715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jobs (
        id INT AUTO_INCREMENT NOT NULL, 
        title VARCHAR(255) NOT NULL, 
        description LONGTEXT DEFAULT NULL, 
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('CREATE TABLE user_jobs (
        id INT AUTO_INCREMENT NOT NULL, 
        user_id INT DEFAULT NULL, 
        job_id INT DEFAULT NULL, 
        status SMALLINT DEFAULT NULL,
        assessment LONGTEXT DEFAULT NULL,
        scheduled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        INDEX IDX_D212210CA76ED395 (user_id), INDEX IDX_D212210CBE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql('CREATE TABLE users (
        id INT AUTO_INCREMENT NOT NULL, 
        full_name VARCHAR(255) DEFAULT NULL, 
        password VARCHAR(255) NOT NULL, 
        email VARCHAR(180) NOT NULL, 
        username VARCHAR(180) NOT NULL, 
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        zone VARCHAR(255) NOT NULL, 
        roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\',
        UNIQUE INDEX unique_email (email), UNIQUE INDEX unique_username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE user_jobs ADD CONSTRAINT FK_D212210CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_jobs ADD CONSTRAINT FK_D212210CBE04EA9 FOREIGN KEY (job_id) REFERENCES jobs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_jobs DROP FOREIGN KEY FK_D212210CA76ED395');
        $this->addSql('ALTER TABLE user_jobs DROP FOREIGN KEY FK_D212210CBE04EA9');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE user_jobs');
        $this->addSql('DROP TABLE users');
    }
}
