<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418095248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE benefits DROP FOREIGN KEY FK_965A49FE9749932E');
        $this->addSql('DROP INDEX IDX_965A49FE9749932E ON benefits');
        $this->addSql('ALTER TABLE benefits CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE benefits ADD CONSTRAINT FK_965A49FE8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_965A49FE8C03F15C ON benefits (employee_id)');
        $this->addSql('ALTER TABLE bonus DROP FOREIGN KEY FK_9F987F7A9749932E');
        $this->addSql('DROP INDEX IDX_9F987F7A9749932E ON bonus');
        $this->addSql('ALTER TABLE bonus CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bonus ADD CONSTRAINT FK_9F987F7A8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_9F987F7A8C03F15C ON bonus (employee_id)');
        $this->addSql('ALTER TABLE goodies DROP FOREIGN KEY FK_1379DF999749932E');
        $this->addSql('DROP INDEX IDX_1379DF999749932E ON goodies');
        $this->addSql('ALTER TABLE goodies CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goodies ADD CONSTRAINT FK_1379DF998C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_1379DF998C03F15C ON goodies (employee_id)');
        $this->addSql('ALTER TABLE hourly_rate DROP FOREIGN KEY FK_D1ED4BBC9749932E');
        $this->addSql('DROP INDEX IDX_D1ED4BBC9749932E ON hourly_rate');
        $this->addSql('ALTER TABLE hourly_rate CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hourly_rate ADD CONSTRAINT FK_D1ED4BBC8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_D1ED4BBC8C03F15C ON hourly_rate (employee_id)');
        $this->addSql('ALTER TABLE hours DROP FOREIGN KEY FK_8A1ABD8D9749932E');
        $this->addSql('DROP INDEX IDX_8A1ABD8D9749932E ON hours');
        $this->addSql('ALTER TABLE hours CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hours ADD CONSTRAINT FK_8A1ABD8D8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_8A1ABD8D8C03F15C ON hours (employee_id)');
        $this->addSql('ALTER TABLE incentive_rate DROP FOREIGN KEY FK_47C5858F9749932E');
        $this->addSql('DROP INDEX IDX_47C5858F9749932E ON incentive_rate');
        $this->addSql('ALTER TABLE incentive_rate CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE incentive_rate ADD CONSTRAINT FK_47C5858F8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_47C5858F8C03F15C ON incentive_rate (employee_id)');
        $this->addSql('ALTER TABLE riffle DROP FOREIGN KEY FK_C58DB6649749932E');
        $this->addSql('DROP INDEX IDX_C58DB6649749932E ON riffle');
        $this->addSql('ALTER TABLE riffle CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE riffle ADD CONSTRAINT FK_C58DB6648C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_C58DB6648C03F15C ON riffle (employee_id)');
        $this->addSql('ALTER TABLE variable_portion DROP FOREIGN KEY FK_8E6444449749932E');
        $this->addSql('DROP INDEX IDX_8E6444449749932E ON variable_portion');
        $this->addSql('ALTER TABLE variable_portion CHANGE employee_id_id employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE variable_portion ADD CONSTRAINT FK_8E6444448C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_8E6444448C03F15C ON variable_portion (employee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incentive_rate DROP FOREIGN KEY FK_47C5858F8C03F15C');
        $this->addSql('DROP INDEX IDX_47C5858F8C03F15C ON incentive_rate');
        $this->addSql('ALTER TABLE incentive_rate CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE incentive_rate ADD CONSTRAINT FK_47C5858F9749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_47C5858F9749932E ON incentive_rate (employee_id_id)');
        $this->addSql('ALTER TABLE hourly_rate DROP FOREIGN KEY FK_D1ED4BBC8C03F15C');
        $this->addSql('DROP INDEX IDX_D1ED4BBC8C03F15C ON hourly_rate');
        $this->addSql('ALTER TABLE hourly_rate CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hourly_rate ADD CONSTRAINT FK_D1ED4BBC9749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_D1ED4BBC9749932E ON hourly_rate (employee_id_id)');
        $this->addSql('ALTER TABLE variable_portion DROP FOREIGN KEY FK_8E6444448C03F15C');
        $this->addSql('DROP INDEX IDX_8E6444448C03F15C ON variable_portion');
        $this->addSql('ALTER TABLE variable_portion CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE variable_portion ADD CONSTRAINT FK_8E6444449749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_8E6444449749932E ON variable_portion (employee_id_id)');
        $this->addSql('ALTER TABLE bonus DROP FOREIGN KEY FK_9F987F7A8C03F15C');
        $this->addSql('DROP INDEX IDX_9F987F7A8C03F15C ON bonus');
        $this->addSql('ALTER TABLE bonus CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bonus ADD CONSTRAINT FK_9F987F7A9749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_9F987F7A9749932E ON bonus (employee_id_id)');
        $this->addSql('ALTER TABLE hours DROP FOREIGN KEY FK_8A1ABD8D8C03F15C');
        $this->addSql('DROP INDEX IDX_8A1ABD8D8C03F15C ON hours');
        $this->addSql('ALTER TABLE hours CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hours ADD CONSTRAINT FK_8A1ABD8D9749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_8A1ABD8D9749932E ON hours (employee_id_id)');
        $this->addSql('ALTER TABLE riffle DROP FOREIGN KEY FK_C58DB6648C03F15C');
        $this->addSql('DROP INDEX IDX_C58DB6648C03F15C ON riffle');
        $this->addSql('ALTER TABLE riffle CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE riffle ADD CONSTRAINT FK_C58DB6649749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_C58DB6649749932E ON riffle (employee_id_id)');
        $this->addSql('ALTER TABLE goodies DROP FOREIGN KEY FK_1379DF998C03F15C');
        $this->addSql('DROP INDEX IDX_1379DF998C03F15C ON goodies');
        $this->addSql('ALTER TABLE goodies CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE goodies ADD CONSTRAINT FK_1379DF999749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_1379DF999749932E ON goodies (employee_id_id)');
        $this->addSql('ALTER TABLE benefits DROP FOREIGN KEY FK_965A49FE8C03F15C');
        $this->addSql('DROP INDEX IDX_965A49FE8C03F15C ON benefits');
        $this->addSql('ALTER TABLE benefits CHANGE employee_id employee_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE benefits ADD CONSTRAINT FK_965A49FE9749932E FOREIGN KEY (employee_id_id) REFERENCES employee (id)');
        $this->addSql('CREATE INDEX IDX_965A49FE9749932E ON benefits (employee_id_id)');
    }
}
