<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520140750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quarter (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property ADD quarter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEBED4A2B2 FOREIGN KEY (quarter_id) REFERENCES quarter (id)');
        $this->addSql('CREATE INDEX IDX_8BF21CDEBED4A2B2 ON property (quarter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEBED4A2B2');
        $this->addSql('DROP TABLE quarter');
        $this->addSql('DROP INDEX IDX_8BF21CDEBED4A2B2 ON property');
        $this->addSql('ALTER TABLE property DROP quarter_id');
    }
}
