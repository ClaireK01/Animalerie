<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517083655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE habite DROP FOREIGN KEY habite_ibfk_2');
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY categorie_ibfk_1');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY produit_ibfk_2');
        $this->addSql('ALTER TABLE commande1 DROP FOREIGN KEY commande1_ibfk_1');
        $this->addSql('ALTER TABLE habite DROP FOREIGN KEY habite_ibfk_1');
        $this->addSql('ALTER TABLE liste_fav DROP FOREIGN KEY liste_fav_ibfk_1');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY photo_ibfk_1');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY produit_ibfk_1');
        $this->addSql('ALTER TABLE commande1 DROP FOREIGN KEY commande1_ibfk_2');
        $this->addSql('ALTER TABLE liste_fav DROP FOREIGN KEY liste_fav_ibfk_2');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY photo_ibfk_2');
        $this->addSql('ALTER TABLE adresse DROP FOREIGN KEY adresse_ibfk_1');
        $this->addSql('CREATE TABLE reset_password (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, is_reset TINYINT(1) NOT NULL, INDEX IDX_B9983CE5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password ADD CONSTRAINT FK_B9983CE5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande1');
        $this->addSql('DROP TABLE habite');
        $this->addSql('DROP TABLE liste_fav');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE ville');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (idAdresse VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, rue VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, numero_rue INT DEFAULT NULL, idVille INT NOT NULL, INDEX idVille (idVille), PRIMARY KEY(idAdresse)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie (idCategorie INT NOT NULL, nom_categorie VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, idsous_cat INT DEFAULT NULL, idCategorie_1 INT NOT NULL, INDEX idCategorie_1 (idCategorie_1), PRIMARY KEY(idCategorie)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE client (idClient INT NOT NULL, prenom_client VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, nom_client VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, date_naissance DATE DEFAULT NULL, mail VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, mot_de_pass_client VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, genre TINYINT(1) DEFAULT NULL, PRIMARY KEY(idClient)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande1 (idClient INT NOT NULL, idProduit INT NOT NULL, numCommande INT DEFAULT NULL, total VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, quantité_prod INT DEFAULT NULL, facture VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, etat VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, paiement TINYINT(1) DEFAULT NULL, INDEX idProduit (idProduit), INDEX IDX_AD060890A455ACCF (idClient), PRIMARY KEY(idClient, idProduit)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE habite (idClient INT NOT NULL, idAdresse VARCHAR(50) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, INDEX idAdresse (idAdresse), INDEX IDX_5195946BA455ACCF (idClient), PRIMARY KEY(idClient, idAdresse)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liste_fav (idClient INT NOT NULL, idProduit INT NOT NULL, INDEX idProduit (idProduit), INDEX IDX_95AF865BA455ACCF (idClient), PRIMARY KEY(idClient, idProduit)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE marque (idMarque INT NOT NULL, nom_marque VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(idMarque)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE photo (idPhoto INT NOT NULL, nom_photo VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, description_photo VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, idMarque INT NOT NULL, idProduit INT NOT NULL, INDEX idProduit (idProduit), INDEX idMarque (idMarque), PRIMARY KEY(idPhoto)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (idProduit INT NOT NULL, libelle_produit VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, prix DOUBLE PRECISION DEFAULT NULL, description TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, quantité INT DEFAULT NULL, actif TINYINT(1) DEFAULT NULL, idMarque INT NOT NULL, idCategorie INT NOT NULL, INDEX idCategorie (idCategorie), INDEX idMarque (idMarque), PRIMARY KEY(idProduit)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ville (idVille INT NOT NULL, nom_ville VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, code_postale VARCHAR(50) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, PRIMARY KEY(idVille)) DEFAULT CHARACTER SET latin1 COLLATE `latin1_swedish_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE adresse ADD CONSTRAINT adresse_ibfk_1 FOREIGN KEY (idVille) REFERENCES ville (idVille)');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT categorie_ibfk_1 FOREIGN KEY (idCategorie_1) REFERENCES categorie (idCategorie)');
        $this->addSql('ALTER TABLE commande1 ADD CONSTRAINT commande1_ibfk_1 FOREIGN KEY (idClient) REFERENCES client (idClient)');
        $this->addSql('ALTER TABLE commande1 ADD CONSTRAINT commande1_ibfk_2 FOREIGN KEY (idProduit) REFERENCES produit (idProduit)');
        $this->addSql('ALTER TABLE habite ADD CONSTRAINT habite_ibfk_1 FOREIGN KEY (idClient) REFERENCES client (idClient)');
        $this->addSql('ALTER TABLE habite ADD CONSTRAINT habite_ibfk_2 FOREIGN KEY (idAdresse) REFERENCES adresse (idAdresse)');
        $this->addSql('ALTER TABLE liste_fav ADD CONSTRAINT liste_fav_ibfk_1 FOREIGN KEY (idClient) REFERENCES client (idClient)');
        $this->addSql('ALTER TABLE liste_fav ADD CONSTRAINT liste_fav_ibfk_2 FOREIGN KEY (idProduit) REFERENCES produit (idProduit)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT photo_ibfk_1 FOREIGN KEY (idMarque) REFERENCES marque (idMarque)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT photo_ibfk_2 FOREIGN KEY (idProduit) REFERENCES produit (idProduit)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT produit_ibfk_1 FOREIGN KEY (idMarque) REFERENCES marque (idMarque)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT produit_ibfk_2 FOREIGN KEY (idCategorie) REFERENCES categorie (idCategorie)');
        $this->addSql('DROP TABLE reset_password');
    }
}
