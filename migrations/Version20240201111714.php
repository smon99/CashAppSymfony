<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201111714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction CHANGE paypal_order_id paypal_order_id VARCHAR(100) DEFAULT NULL, CHANGE paypal_payer_id paypal_payer_id VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction CHANGE paypal_order_id paypal_order_id VARCHAR(100) NOT NULL, CHANGE paypal_payer_id paypal_payer_id VARCHAR(100) NOT NULL');
    }
}
