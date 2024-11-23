<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419074626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE benefits_type (id INT AUTO_INCREMENT NOT NULL, monetary_benefit VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE benefits ADD benefits_type_id INT DEFAULT NULL, DROP benefits, DROP monetary_benefit');
        $this->addSql('ALTER TABLE benefits ADD CONSTRAINT FK_965A49FE7925A21D FOREIGN KEY (benefits_type_id) REFERENCES benefits_type (id)');
        $this->addSql('CREATE INDEX IDX_965A49FE7925A21D ON benefits (benefits_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benefits DROP FOREIGN KEY FK_965A49FE7925A21D');
        $this->addSql('DROP TABLE benefits_type');
        $this->addSql('DROP INDEX IDX_965A49FE7925A21D ON benefits');
        $this->addSql('ALTER TABLE benefits ADD benefits LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD monetary_benefit DOUBLE PRECISION NOT NULL, DROP benefits_type_id');
    }
}
