<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121170919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE benefits (id INT AUTO_INCREMENT NOT NULL, month VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, creation_date DATE NOT NULL, modification_date DATE NOT NULL, monetary_benefit VARCHAR(255) DEFAULT NULL, private_portion VARCHAR(255) DEFAULT NULL, info LONGTEXT DEFAULT NULL, employee_id INT DEFAULT NULL, benefits_type_id INT DEFAULT NULL, INDEX IDX_965A49FE8C03F15C (employee_id), INDEX IDX_965A49FE7925A21D (benefits_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE benefits_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, is_monetary_benefit TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE bonus (id INT AUTO_INCREMENT NOT NULL, month VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, modification_date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, percent DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, is_add TINYINT(1) NOT NULL, is_subtract TINYINT(1) NOT NULL, info LONGTEXT DEFAULT NULL, employee_id INT DEFAULT NULL, INDEX IDX_9F987F7A8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, employee_number INT NOT NULL, target_salary DOUBLE PRECISION DEFAULT NULL, fix_portion DOUBLE PRECISION DEFAULT NULL, entry DATE DEFAULT NULL, resignation DATE DEFAULT NULL, target_variable_portion DOUBLE PRECISION DEFAULT NULL, annual_working_time DOUBLE PRECISION DEFAULT NULL, fix_variable_portion DOUBLE PRECISION DEFAULT NULL, fix_variable_entry DATE DEFAULT NULL, info LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE goodies (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, total_amount DOUBLE PRECISION NOT NULL, divider INT NOT NULL, month INT NOT NULL, year VARCHAR(255) NOT NULL, modification_date DATE NOT NULL, charged TINYINT(1) NOT NULL, changed TINYINT(1) NOT NULL, partial_amounts DOUBLE PRECISION NOT NULL, amortization JSON DEFAULT NULL, info LONGTEXT DEFAULT NULL, employee_id INT DEFAULT NULL, INDEX IDX_1379DF998C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE hourly_rate (id INT AUTO_INCREMENT NOT NULL, hourly_rate DOUBLE PRECISION NOT NULL, month VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, modification_date DATETIME DEFAULT NULL, info LONGTEXT DEFAULT NULL, employee_id INT DEFAULT NULL, INDEX IDX_D1ED4BBC8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE hours (id INT AUTO_INCREMENT NOT NULL, month INT DEFAULT NULL, year INT DEFAULT NULL, modification_date DATE NOT NULL, variable_hours DOUBLE PRECISION DEFAULT NULL, info LONGTEXT DEFAULT NULL, employee_id INT DEFAULT NULL, INDEX IDX_8A1ABD8D8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE incentive_rate (id INT AUTO_INCREMENT NOT NULL, incentive_rate DOUBLE PRECISION NOT NULL, month VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, modification_date DATETIME DEFAULT NULL, info LONGTEXT DEFAULT NULL, employee_id INT DEFAULT NULL, INDEX IDX_47C5858F8C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE riffle (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION NOT NULL, month VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, modification_date DATE NOT NULL, acquired_name VARCHAR(255) NOT NULL, acquired_firstname VARCHAR(255) NOT NULL, acquired_entry DATE NOT NULL, is_acquired_hours TINYINT(1) DEFAULT NULL, info LONGTEXT DEFAULT NULL, acquired_hours_collection JSON DEFAULT NULL, employee_id INT DEFAULT NULL, INDEX IDX_C58DB6648C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE variable_portion (id INT AUTO_INCREMENT NOT NULL, portions DOUBLE PRECISION NOT NULL, payout_month VARCHAR(255) DEFAULT NULL, payout_year VARCHAR(255) DEFAULT NULL, created_at DATE NOT NULL, fix_variable_portion DOUBLE PRECISION DEFAULT NULL, goody_portion DOUBLE PRECISION DEFAULT NULL, bonus_portion DOUBLE PRECISION DEFAULT NULL, riffle_portion DOUBLE PRECISION DEFAULT NULL, payout_portion DOUBLE PRECISION DEFAULT NULL, calc_incentive_rate DOUBLE PRECISION DEFAULT NULL, calc_border_hour_value DOUBLE PRECISION DEFAULT NULL, info LONGTEXT DEFAULT NULL, employee_id INT DEFAULT NULL, INDEX IDX_8E6444448C03F15C (employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE benefits ADD CONSTRAINT FK_965A49FE8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE benefits ADD CONSTRAINT FK_965A49FE7925A21D FOREIGN KEY (benefits_type_id) REFERENCES benefits_type (id)');
        $this->addSql('ALTER TABLE bonus ADD CONSTRAINT FK_9F987F7A8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE goodies ADD CONSTRAINT FK_1379DF998C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE hourly_rate ADD CONSTRAINT FK_D1ED4BBC8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE hours ADD CONSTRAINT FK_8A1ABD8D8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE incentive_rate ADD CONSTRAINT FK_47C5858F8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE riffle ADD CONSTRAINT FK_C58DB6648C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE variable_portion ADD CONSTRAINT FK_8E6444448C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benefits DROP FOREIGN KEY FK_965A49FE8C03F15C');
        $this->addSql('ALTER TABLE benefits DROP FOREIGN KEY FK_965A49FE7925A21D');
        $this->addSql('ALTER TABLE bonus DROP FOREIGN KEY FK_9F987F7A8C03F15C');
        $this->addSql('ALTER TABLE goodies DROP FOREIGN KEY FK_1379DF998C03F15C');
        $this->addSql('ALTER TABLE hourly_rate DROP FOREIGN KEY FK_D1ED4BBC8C03F15C');
        $this->addSql('ALTER TABLE hours DROP FOREIGN KEY FK_8A1ABD8D8C03F15C');
        $this->addSql('ALTER TABLE incentive_rate DROP FOREIGN KEY FK_47C5858F8C03F15C');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE riffle DROP FOREIGN KEY FK_C58DB6648C03F15C');
        $this->addSql('ALTER TABLE variable_portion DROP FOREIGN KEY FK_8E6444448C03F15C');
        $this->addSql('DROP TABLE benefits');
        $this->addSql('DROP TABLE benefits_type');
        $this->addSql('DROP TABLE bonus');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE goodies');
        $this->addSql('DROP TABLE hourly_rate');
        $this->addSql('DROP TABLE hours');
        $this->addSql('DROP TABLE incentive_rate');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE riffle');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE variable_portion');
    }
}
