<?php

declare(strict_types=1);

namespace DoctrineMigration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200525122251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE password_recovery_token (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, uuid VARCHAR(6) NOT NULL, token VARCHAR(64) NOT NULL, valid_until DATETIME NOT NULL, UNIQUE INDEX UNIQ_2CC76E689B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE password_recovery_token ADD CONSTRAINT FK_2CC76E689B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)'
        );
        $this->addSql(
            'ALTER TABLE account CHANGE roles roles JSON NOT NULL, CHANGE name name VARCHAR(180) DEFAULT NULL, CHANGE surname surname VARCHAR(180) DEFAULT NULL'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE password_recovery_token');
        $this->addSql(
            'ALTER TABLE account CHANGE name name VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE surname surname VARCHAR(180) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`'
        );
    }
}
