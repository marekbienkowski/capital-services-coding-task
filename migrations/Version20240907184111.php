<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907184111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE installment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:installment_id)\', repayment_schedule_id CHAR(36) NOT NULL COMMENT \'(DC2Type:repayment_schedule_id)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_value INT NOT NULL, total_currency VARCHAR(255) NOT NULL, capital_value INT NOT NULL, capital_currency VARCHAR(255) NOT NULL, interest_value INT NOT NULL, interest_currency VARCHAR(255) NOT NULL, INDEX IDX_4B778ACD10C4551 (repayment_schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repayment_schedule (id CHAR(36) NOT NULL COMMENT \'(DC2Type:repayment_schedule_id)\', installment_count INT NOT NULL, schedule_type VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_amount_value INT NOT NULL, total_amount_currency VARCHAR(255) NOT NULL, interest_rate_percentage_points INT NOT NULL, total_interest_amount_value INT NOT NULL, total_interest_amount_currency VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE installment ADD CONSTRAINT FK_4B778ACD10C4551 FOREIGN KEY (repayment_schedule_id) REFERENCES repayment_schedule (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE installment DROP FOREIGN KEY FK_4B778ACD10C4551');
        $this->addSql('DROP TABLE installment');
        $this->addSql('DROP TABLE repayment_schedule');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
