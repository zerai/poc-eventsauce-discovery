<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191008224710 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', event_type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, aggregate_root_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:guid)\', aggregate_root_version INT NOT NULL, time_of_recording DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', payload JSON NOT NULL COMMENT \'(DC2Type:json_array)\', INDEX IDX_3BAE0AA7553FE28D (time_of_recording), INDEX IDX_3BAE0AA7745C37BA553FE28D (aggregate_root_id, time_of_recording), INDEX IDX_3BAE0AA7745C37BA (aggregate_root_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE todo_projection (todo_id VARCHAR(36) NOT NULL COLLATE utf8_unicode_ci, todo_text LONGTEXT NOT NULL COLLATE utf8_unicode_ci, user_id VARCHAR(36) NOT NULL COLLATE utf8_unicode_ci, status VARCHAR(15) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(todo_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('CREATE TABLE user_projection (user_id VARCHAR(36) NOT NULL COLLATE utf8_unicode_ci, user_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE event');

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE todo_projection');

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('DROP TABLE user_projection');
    }
}
