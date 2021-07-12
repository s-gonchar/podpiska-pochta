<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210712093657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            create OR REPLACE view view_magazines as
            select
               m.id, title, price, pages, weight, annotation, publisher_name, publication_code, quality, publisher_legal_address, age_category, mass_media_reg_num, mass_media_reg_date, image,
               string_agg(t.name, ';')
            from magazines m
                    left join magazines_themes mt on m.id = mt.magazine_id
                    left join themes t on mt.theme_id = t.id
            group by m.id
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop view view_magazines');
    }
}
