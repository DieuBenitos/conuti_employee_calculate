<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430154019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benefits ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE bonus ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE employee ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE goodies ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE hourly_rate ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE hours ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE incentive_rate ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE riffle ADD info LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE variable_portion ADD info LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incentive_rate DROP info');
        $this->addSql('ALTER TABLE employee DROP info');
        $this->addSql('ALTER TABLE hourly_rate DROP info');
        $this->addSql('ALTER TABLE variable_portion DROP info');
        $this->addSql('ALTER TABLE bonus DROP info');
        $this->addSql('ALTER TABLE hours DROP info');
        $this->addSql('ALTER TABLE riffle DROP info');
        $this->addSql('ALTER TABLE goodies DROP info');
        $this->addSql('ALTER TABLE benefits DROP info');
    }
}
