<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117123621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY orderdetails_ibfk_1');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT FK_489AFCDCCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders ADD reference VARCHAR(20) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEEAEA34913 ON orders (reference)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_E52FFDEEAEA34913 ON orders');
        $this->addSql('ALTER TABLE orders DROP reference');
        $this->addSql('ALTER TABLE orderdetails DROP FOREIGN KEY FK_489AFCDCCFFE9AD6');
        $this->addSql('ALTER TABLE orderdetails ADD CONSTRAINT orderdetails_ibfk_1 FOREIGN KEY (orders_id) REFERENCES orders (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
