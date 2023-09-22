<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230922205157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_jobs ADD user_id INT DEFAULT NULL, ADD job_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_jobs ADD CONSTRAINT FK_D212210CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_jobs ADD CONSTRAINT FK_D212210CBE04EA9 FOREIGN KEY (job_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_D212210CA76ED395 ON user_jobs (user_id)');
        $this->addSql('CREATE INDEX IDX_D212210CBE04EA9 ON user_jobs (job_id)');

        $this->addSql('ALTER TABLE users ADD zone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_D212210CBE03EA8 FOREIGN KEY (zone_id) REFERENCES zonees (id)');
        $this->addSql('CREATE INDEX IDX_D212210CBE03EA8 ON users (zone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_jobs DROP FOREIGN KEY FK_D212210CA76ED395');
        $this->addSql('ALTER TABLE user_jobs DROP FOREIGN KEY FK_D212210CBE04EA9');
        $this->addSql('DROP INDEX IDX_D212210CA76ED395 ON user_jobs');
        $this->addSql('DROP INDEX IDX_D212210CBE04EA9 ON user_jobs');
        $this->addSql('ALTER TABLE user_jobs DROP user_id, DROP job_id');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_D212210CBE03EA8');
        $this->addSql('DROP INDEX IDX_D212210CBE03EA8 ON users');
        $this->addSql('ALTER TABLE users');
    }
}
