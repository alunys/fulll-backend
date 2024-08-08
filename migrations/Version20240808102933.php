<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808102933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fleet (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A05E1E47A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fleet_vehicle (fleet_id VARCHAR(255) NOT NULL, vehicle_id VARCHAR(255) NOT NULL, INDEX IDX_3DD2DF8D4B061DF9 (fleet_id), INDEX IDX_3DD2DF8D545317D1 (vehicle_id), PRIMARY KEY(fleet_id, vehicle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id VARCHAR(255) NOT NULL, plate_number VARCHAR(255) NOT NULL, location_lat DOUBLE PRECISION DEFAULT NULL, location_long DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_1B80E486FCFF3785 (plate_number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fleet_vehicle ADD CONSTRAINT FK_3DD2DF8D4B061DF9 FOREIGN KEY (fleet_id) REFERENCES fleet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fleet_vehicle ADD CONSTRAINT FK_3DD2DF8D545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fleet_vehicle DROP FOREIGN KEY FK_3DD2DF8D4B061DF9');
        $this->addSql('ALTER TABLE fleet_vehicle DROP FOREIGN KEY FK_3DD2DF8D545317D1');
        $this->addSql('DROP TABLE fleet');
        $this->addSql('DROP TABLE fleet_vehicle');
        $this->addSql('DROP TABLE vehicle');
    }
}
