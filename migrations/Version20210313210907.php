<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210313210907 extends AbstractMigration
{
    public function isTransactional(): bool
    {
        return false;
    }
    
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race_runner (race_id INT NOT NULL, runner_id INT NOT NULL, INDEX IDX_937D95ED6E59D40D (race_id), INDEX IDX_937D95ED3C7FB593 (runner_id), PRIMARY KEY(race_id, runner_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE race_runner ADD CONSTRAINT FK_937D95ED6E59D40D FOREIGN KEY (race_id) REFERENCES race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE race_runner ADD CONSTRAINT FK_937D95ED3C7FB593 FOREIGN KEY (runner_id) REFERENCES runner (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE race_runner');
    }
}
