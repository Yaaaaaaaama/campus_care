<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924101941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD incident_id INT NOT NULL, ADD user_id INT NOT NULL, DROP incident, DROP user');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C59E53FB9 FOREIGN KEY (incident_id) REFERENCES incident (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526C59E53FB9 ON comment (incident_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('ALTER TABLE incident ADD user_id INT NOT NULL, DROP user');
        $this->addSql('ALTER TABLE incident ADD CONSTRAINT FK_3D03A11AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3D03A11AA76ED395 ON incident (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C59E53FB9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP INDEX IDX_9474526C59E53FB9 ON comment');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('ALTER TABLE comment ADD incident VARCHAR(255) NOT NULL, ADD user VARCHAR(255) NOT NULL, DROP incident_id, DROP user_id');
        $this->addSql('ALTER TABLE incident DROP FOREIGN KEY FK_3D03A11AA76ED395');
        $this->addSql('DROP INDEX IDX_3D03A11AA76ED395 ON incident');
        $this->addSql('ALTER TABLE incident ADD user VARCHAR(255) NOT NULL, DROP user_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
    }
}
