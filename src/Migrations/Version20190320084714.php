<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190320084714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE measure (id INT AUTO_INCREMENT NOT NULL, time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sensor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mac VARCHAR(32) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sensor_measure (id INT AUTO_INCREMENT NOT NULL, sensor_id INT NOT NULL, measure_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_9D1E9E6FA247991F (sensor_id), INDEX IDX_9D1E9E6F5DA37D00 (measure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sensor_measure ADD CONSTRAINT FK_9D1E9E6FA247991F FOREIGN KEY (sensor_id) REFERENCES sensor (id)');
        $this->addSql('ALTER TABLE sensor_measure ADD CONSTRAINT FK_9D1E9E6F5DA37D00 FOREIGN KEY (measure_id) REFERENCES measure (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sensor_measure DROP FOREIGN KEY FK_9D1E9E6F5DA37D00');
        $this->addSql('ALTER TABLE sensor_measure DROP FOREIGN KEY FK_9D1E9E6FA247991F');
        $this->addSql('DROP TABLE measure');
        $this->addSql('DROP TABLE sensor');
        $this->addSql('DROP TABLE sensor_measure');
    }
}
