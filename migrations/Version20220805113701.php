<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220805113701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_team (player_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_66FAF62C99E6F5DF (player_id), INDEX IDX_66FAF62C296CD8AE (team_id), PRIMARY KEY(player_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_team ADD CONSTRAINT FK_66FAF62C99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_team ADD CONSTRAINT FK_66FAF62C296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player DROP teams');
        $this->addSql('ALTER TABLE team DROP players');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE player_team');
        $this->addSql('ALTER TABLE player ADD teams VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE team ADD players VARCHAR(255) DEFAULT NULL');
    }
}
