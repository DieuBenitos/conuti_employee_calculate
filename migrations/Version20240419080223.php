<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419080223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benefits ADD monetary_benefit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE benefits_type ADD is_monetary_benefit TINYINT(1) NOT NULL, DROP monetary_benefit');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benefits_type ADD monetary_benefit VARCHAR(255) NOT NULL, DROP is_monetary_benefit');
        $this->addSql('ALTER TABLE benefits DROP monetary_benefit');
    }
}
