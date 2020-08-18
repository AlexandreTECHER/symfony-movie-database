<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317125636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE crew_member DROP FOREIGN KEY FK_F4D78A97BE04EA9');
        $this->addSql('ALTER TABLE crew_member CHANGE job_id job_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE crew_member ADD CONSTRAINT FK_F4D78A97BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE crew_member DROP FOREIGN KEY FK_F4D78A97BE04EA9');
        $this->addSql('ALTER TABLE crew_member CHANGE job_id job_id INT NOT NULL');
        $this->addSql('ALTER TABLE crew_member ADD CONSTRAINT FK_F4D78A97BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
    }
}
