<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260923154234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD description LONGTEXT DEFAULT NULL, ADD icon VARCHAR(60) DEFAULT NULL');

        $seed = [
            'SCI-FI' => ['tabler:rocket', "Vaisseaux, réseaux, futurs proches et lointains."],
            'Fantastic' => ['tabler:sparkles', "Le réel se fissure : un détail étrange, et plus rien n'est tout à fait normal."],
            'Romance' => ['tabler:heart', "Histoires d'amour contemporaines ou d'époque, à feu doux ou très vif."],
            'Mystery' => ['tabler:search', "Énigmes, secrets de famille et questions qui ne lâchent pas le lecteur."],
            'Humourous' => ['tabler:mood-happy', "Des récits qui ne se prennent pas au sérieux, à lire le sourire aux lèvres."],
            'Horror' => ['tabler:ghost', "Peurs lentes ou brutales, maisons qui craquent et nuits trop longues."],
            'Action' => ['tabler:bolt', "Poursuites, combats et chapitres qui se lisent d'une traite."],
            'Adventure' => ['tabler:compass', "Voyages, expéditions et héros qui partent loin de chez eux."],
            'Thriller' => ['tabler:eye', "Tension, fausses pistes et compte à rebours jusqu'au dernier chapitre."],
            'Drama' => ['tabler:masks-theater', "Vies ordinaires, choix difficiles et émotions à fleur de peau."],
            'Comedy' => ['tabler:mood-smile', "Situations absurdes, dialogues vifs et personnages attachants."],
            'Animation' => ['tabler:movie', "Des histoires pensées comme des films d'animation, colorées et pleines d'élan."],
            'Family' => ['tabler:home-heart', "Liens de sang, héritages et retrouvailles, d'une génération à l'autre."],
            'Crime' => ['tabler:fingerprint', "Enquêtes, coupables et policiers, du vol simple au crime parfait."],
            'Historical' => ['tabler:building-castle', "Des récits ancrés dans le passé, des cours royales aux tranchées."],
            'Fantasy' => ['tabler:wand', "Mondes inventés et quêtes qui durent quarante chapitres."],
            'Documentary' => ['tabler:camera', "Récits proches du réel, observés de près et racontés avec précision."],
            'War' => ['tabler:swords', "Soldats, civils et champs de bataille, pendant et après les conflits."],
            'Musical' => ['tabler:music', "Des histoires où la musique mène la danse, sur scène comme en coulisses."],
            'Biography' => ['tabler:user', "Des vies racontées, réelles ou imaginées, du premier au dernier chapitre."],
        ];
        foreach ($seed as $name => [$icon, $description]) {
            $this->addSql('UPDATE category SET icon = ?, description = ? WHERE name = ?', [$icon, $description, $name]);
        }
        $this->addSql('ALTER TABLE chapter CHANGE status status ENUM(\'published\', \'in_progress\', \'scheduled\')');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP description, DROP icon');
        $this->addSql('ALTER TABLE chapter CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
    }
}
