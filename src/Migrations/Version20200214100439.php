<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200214100439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE service service VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EF5B7AF75');
        $this->addSql('DROP INDEX IDX_1F1B251EF5B7AF75 ON item');
        $this->addSql('ALTER TABLE item CHANGE address_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EA76ED395 ON item (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EA76ED395');
        $this->addSql('DROP INDEX IDX_1F1B251EA76ED395 ON item');
        $this->addSql('ALTER TABLE item CHANGE user_id address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EF5B7AF75 ON item (address_id)');
        $this->addSql('ALTER TABLE user CHANGE service service VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
