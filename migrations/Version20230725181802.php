<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230725181802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_7CE748AA76ED395 ON reset_password_request');
        $this->addSql('DROP INDEX `primary` ON reset_password_request');
        $this->addSql('ALTER TABLE reset_password_request CHANGE id id_reset INT AUTO_INCREMENT NOT NULL, CHANGE user_id id_user INT NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748A6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('CREATE INDEX IDX_7CE748A6B3CA4B ON reset_password_request (id_user)');
        $this->addSql('ALTER TABLE reset_password_request ADD PRIMARY KEY (id_reset)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request MODIFY id_reset INT NOT NULL');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748A6B3CA4B');
        $this->addSql('DROP INDEX IDX_7CE748A6B3CA4B ON reset_password_request');
        $this->addSql('DROP INDEX `PRIMARY` ON reset_password_request');
        $this->addSql('ALTER TABLE reset_password_request CHANGE id_reset id INT AUTO_INCREMENT NOT NULL, CHANGE id_user user_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('ALTER TABLE reset_password_request ADD PRIMARY KEY (id)');
    }
}
