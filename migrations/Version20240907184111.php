<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240907184111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create basic database structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<'SQL'
                CREATE TABLE installment (
                    id CHAR(36) NOT NULL COMMENT '(DC2Type:installment_id)',
                    sequence INT NOT NULL,
                    repayment_schedule_id CHAR(36) NOT NULL COMMENT '(DC2Type:repayment_schedule_id)',
                    date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                    total_value INT NOT NULL,
                    total_currency VARCHAR(255) NOT NULL,
                    capital_value INT NOT NULL,
                    capital_currency VARCHAR(255) NOT NULL,
                    interest_value INT NOT NULL,
                    interest_currency VARCHAR(255) NOT NULL,
                    INDEX IDX_4B778ACD10C4551 (repayment_schedule_id),
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
                
                CREATE TABLE repayment_schedule (
                    id CHAR(36) NOT NULL COMMENT '(DC2Type:repayment_schedule_id)',
                    installment_count INT NOT NULL,
                    schedule_type VARCHAR(255) NOT NULL,
                    is_active TINYINT(1) NOT NULL,
                    created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                    total_amount_value INT NOT NULL,
                    total_amount_currency VARCHAR(255) NOT NULL,
                    interest_rate_percentage_points INT NOT NULL,
                    total_interest_amount_value INT NOT NULL,
                    total_interest_amount_currency VARCHAR(255) NOT NULL,
                    PRIMARY KEY(id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

                ALTER TABLE installment 
                ADD CONSTRAINT FK_4B778ACD10C4551 FOREIGN KEY (repayment_schedule_id) REFERENCES repayment_schedule (id) ON DELETE CASCADE;
                SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE installment DROP FOREIGN KEY FK_4B778ACD10C4551');
        $this->addSql('DROP TABLE installment');
        $this->addSql('DROP TABLE repayment_schedule');
    }
}
