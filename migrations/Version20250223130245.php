<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223130245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE measurement (id INT AUTO_INCREMENT NOT NULL, wine_id INT NOT NULL, sensor_id INT NOT NULL, value VARCHAR(255) NOT NULL, unit VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2CE0D81128A2BD76 (wine_id), INDEX IDX_2CE0D811A247991F (sensor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D81128A2BD76 FOREIGN KEY (wine_id) REFERENCES wine (id)');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D811A247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D81128A2BD76');
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D811A247991F');
        $this->addSql('DROP TABLE measurement');
    }
}
