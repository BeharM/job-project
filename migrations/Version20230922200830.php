<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922200830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Db Migrations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jobs 
        (
        id INT AUTO_INCREMENT NOT NULL, 
        title VARCHAR(255) NOT NULL, 
        description LONGTEXT DEFAULT NULL, 
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('CREATE TABLE user_jobs 
        (
        id INT AUTO_INCREMENT NOT NULL, 
        status SMALLINT DEFAULT NULL, 
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('CREATE TABLE users 
        (
        id INT AUTO_INCREMENT NOT NULL, 
        full_name VARCHAR(255) NOT NULL, 
        password VARCHAR(45) NOT NULL, 
        email VARCHAR(45) NOT NULL, 
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        UNIQUE INDEX unique_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql('CREATE TABLE zones 
        (
        id INT AUTO_INCREMENT NOT NULL, 
        name VARCHAR(65) NOT NULL, 
        time_zone VARCHAR(40) NULL, 
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE user_jobs');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE zones');
    }
}
