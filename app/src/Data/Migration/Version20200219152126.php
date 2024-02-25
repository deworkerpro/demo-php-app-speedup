<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219152126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auth_user_networks (id UUID NOT NULL, network_name VARCHAR(16) NOT NULL, network_identity VARCHAR(16) NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3EA78C3BA76ED395 ON auth_user_networks (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EA78C3B257EBD71C756D255 ON auth_user_networks (network_name, network_identity)');
        $this->addSql('CREATE TABLE auth_users (id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, email VARCHAR NOT NULL, password_hash VARCHAR(255) DEFAULT NULL, status VARCHAR(16) NOT NULL, new_email VARCHAR DEFAULT NULL, role VARCHAR(16) NOT NULL, join_confirm_token_value VARCHAR(255) DEFAULT NULL, join_confirm_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, password_reset_token_value VARCHAR(255) DEFAULT NULL, password_reset_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, new_email_token_value VARCHAR(255) DEFAULT NULL, new_email_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8A1F49CE7927C74 ON auth_users (email)');
        $this->addSql('ALTER TABLE auth_user_networks ADD CONSTRAINT FK_3EA78C3BA76ED395 FOREIGN KEY (user_id) REFERENCES auth_users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auth_user_networks DROP CONSTRAINT FK_3EA78C3BA76ED395');
        $this->addSql('DROP TABLE auth_user_networks');
        $this->addSql('DROP TABLE auth_users');
    }
}
