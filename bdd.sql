/*suppression de la base de données existante*/

DROP DATABASE IF EXISTS biblihome;

/*création de la base de données*/
CREATE DATABASE IF NOT EXISTS biblihome;
ALTER DATABASE biblihome charset=utf8mb4;

/*on indique au système quelle base de données utiliser*/
USE biblihome; 

/* on supprime les tables qu'on va créer si elles existent */
DROP TABLE IF EXISTS
    civilites,
    abonnements,
    users,
    carnet_adresses,
    emprunt,
    exemplaires,
    livres,
    genres,
    auteurs,
    series, 
    langues,
    livres_genres,
    livres_auteurs,
    livres_series,
    favoris, 
    faq;

/*on crée les nouvelles tables*/
CREATE TABLE civilites (
    PRIMARY KEY (id_civilite),
    id_civilite              INT AUTO_INCREMENT,
    civilite_nom             VARCHAR(20) NOT NULL,
    civilite_label           VARCHAR(20) NOT NULL
);

CREATE TABLE abonnements (
    PRIMARY KEY (id_abonnement),
    id_abonnement       INT AUTO_INCREMENT,
    abonnement_nom      VARCHAR(20) NOT NULL,
    abonnement_prix     DECIMAL(10,2) NOT NULL,
    abonnement_duree    VARCHAR(20) NOT NULL,
    abonnement_blurb    VARCHAR(255) NOT NULL,
    abonnement_desc     TEXT NOT NULL,
    abonnement_perks    TEXT NOT NULL
);

CREATE TABLE users (
    PRIMARY KEY (id_user),
    id_user                 INT AUTO_INCREMENT,
    user_nom                VARCHAR(100) NOT NULL,
    user_prenom             VARCHAR(100) NOT NULL,
    user_mail               VARCHAR(255) NOT NULL,
    user_mdp                BINARY(64) NOT NULL,
    user_num_employe        INT NULL, /*number for bo role assignment, can also be used to signify an employee left the organization & lock login*/
    user_role               VARCHAR(100) NULL,
    user_date_naissance     DATETIME NULL,
    user_date_creation      DATETIME DEFAULT CURRENT_TIMESTAMP,
    user_date_abonnement    DATETIME NULL,
    user_img                VARCHAR(255) NULL,
    id_civilite             INT NOT NULL,
    id_abonnement           INT NULL
);


CREATE TABLE carnet_adresses (
    PRIMARY KEY (id_carnet_a),
    id_carnet_a                INT AUTO_INCREMENT,
    carnet_a_nom               VARCHAR(100) NOT NULL,
    carnet_a_prenom            VARCHAR(100) NOT NULL,
    carnet_a_ligne_une         VARCHAR(255) NOT NULL,
    carnet_a_ligne_deux        VARCHAR(255) NULL,
    carnet_a_ville             VARCHAR(255) NOT NULL,
    carnet_a_cp                INT NOT NULL,
    carnet_a_bool_relais       BOOLEAN NULL DEFAULT 0,
    carnet_a_tel               VARCHAR(20) NOT NULL,
    id_user                    INT NOT NULL
);


CREATE TABLE emprunt (
    PRIMARY KEY (id_emprunt),
    id_emprunt                          INT AUTO_INCREMENT,
    emprunt_delais_retour               DATETIME NULL,
    emprunt_date_emprunt                DATETIME DEFAULT CURRENT_TIMESTAMP,
    emprunt_date_envoi                  DATETIME NULL,
    emprunt_date_reception              DATETIME NULL,
    emprunt_date_retour                 DATETIME NULL,
    emprunt_num_envoi                   VARCHAR(50) UNIQUE NULL,
    emprunt_num_retour                  VARCHAR(50) UNIQUE NULL,
    emprunt_retour_particulier          BOOLEAN NULL DEFAULT 0,
    id_exemplaire                       INT NOT NULL,
    id_carnet_a                         INT NOT NULL,
    id_user                             INT NOT NULL
);


CREATE TABLE exemplaires (
    PRIMARY KEY (id_exemplaire),
    id_exemplaire              INT AUTO_INCREMENT,
    id_livre                   INT NOT NULL,
    exemplaire_actif           INT DEFAULT 0
);


CREATE TABLE livres (
    PRIMARY KEY (id_livre),
    id_livre                    INT AUTO_INCREMENT,
    livre_titre                 VARCHAR(255) NOT NULL,
    livre_isbn                  VARCHAR(20) UNIQUE NOT NULL,
    livre_couverture            VARCHAR(255) NULL,
    livre_synopsis              TEXT NULL,
    livre_editeur               VARCHAR(100) NULL,
    livre_date_publication      DATE NULL,
    livre_date_creation         DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_langue                   INT NOT NULL
);


CREATE TABLE genres (
    PRIMARY KEY (id_genre),
    id_genre                    INT AUTO_INCREMENT,
    genre_tag                   VARCHAR(100) NOT NULL
);

CREATE TABLE auteurs (
    PRIMARY KEY (id_auteur),
    id_auteur                   INT AUTO_INCREMENT,
    auteur_nom                  VARCHAR(100) NOT NULL,
    auteur_prenom               VARCHAR(100) NULL,
    auteur_image                VARCHAR(255) NULL,
    auteur_nationalite          VARCHAR(100) NULL,
    auteur_biographie           TEXT NULL,
    auteur_date_naissance       DATE NULL,
    auteur_date_deces           DATE NULL
); 


CREATE TABLE series (
    PRIMARY KEY (id_serie),
    id_serie                    INT AUTO_INCREMENT,
    serie_nom                   VARCHAR(255) NOT NULL
);

CREATE TABLE langues (
    PRIMARY KEY (id_langue),
    id_langue                   INT AUTO_INCREMENT,
    langue_nom                  VARCHAR(100) NOT NULL
);


CREATE TABLE livres_genres (
    PRIMARY KEY (id_livre_genre),
    id_livre_genre                  INT AUTO_INCREMENT,
    id_livre                        INT NOT NULL,
    id_genre                        INT NOT NULL
);

CREATE TABLE livres_auteurs (
    PRIMARY KEY (id_livre_auteur),
    id_livre_auteur                 INT AUTO_INCREMENT,
    id_livre                        INT NOT NULL,
    id_auteur                       INT NOT NULL
);


CREATE TABLE livres_series (
    PRIMARY KEY (id_livre_serie),
    id_livre_serie                  INT AUTO_INCREMENT,
    id_livre                        INT NOT NULL,
    id_serie                        INT NOT NULL
);


CREATE TABLE favoris (
    PRIMARY KEY (id_favoris),
    id_favoris                      INT AUTO_INCREMENT,
    id_livre                        INT NOT NULL,
    id_user                         INT NOT NULL
);

CREATE TABLE faq (
    PRIMARY KEY (id_faq),
    id_faq                  INT AUTO_INCREMENT,
    faq_question            TEXT NOT NULL,
    faq_reponse             TEXT NOT NULL
);

/*on gère les jonctions de tables par clés étrangères*/

ALTER TABLE users ADD FOREIGN KEY (id_civilite) REFERENCES civilites (id_civilite);
ALTER TABLE users ADD FOREIGN KEY (id_abonnement) REFERENCES abonnements (id_abonnement);

ALTER TABLE carnet_adresses ADD FOREIGN KEY (id_user) REFERENCES users (id_user);

ALTER TABLE emprunt ADD FOREIGN KEY (id_exemplaire) REFERENCES exemplaires (id_exemplaire);
ALTER TABLE emprunt ADD FOREIGN KEY (id_carnet_a) REFERENCES carnet_adresses (id_carnet_a);
ALTER TABLE emprunt ADD FOREIGN KEY (id_user) REFERENCES users (id_user);

ALTER TABLE exemplaires ADD FOREIGN KEY (id_livre) REFERENCES livres (id_livre);

ALTER TABLE livres ADD FOREIGN KEY (id_langue) REFERENCES langues (id_langue);

ALTER TABLE livres_genres ADD FOREIGN KEY (id_livre) REFERENCES livres (id_livre);
ALTER TABLE livres_genres ADD FOREIGN KEY (id_genre) REFERENCES genres (id_genre);

ALTER TABLE livres_auteurs ADD FOREIGN KEY (id_livre) REFERENCES livres (id_livre);
ALTER TABLE livres_auteurs ADD FOREIGN KEY (id_auteur) REFERENCES auteurs (id_auteur);

ALTER TABLE livres_series ADD FOREIGN KEY (id_livre) REFERENCES livres (id_livre);
ALTER TABLE livres_series ADD FOREIGN KEY (id_serie) REFERENCES series (id_serie);

ALTER TABLE favoris ADD FOREIGN KEY (id_livre) REFERENCES livres (id_livre);
ALTER TABLE favoris ADD FOREIGN KEY (id_user) REFERENCES users (id_user);