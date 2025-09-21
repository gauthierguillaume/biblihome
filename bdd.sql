

DROP DATABASE IF EXISTS questionnaire;

CREATE DATABASE IF NOT EXISTS questionnaire;
ALTER DATABASE questionnaire charset=utf8mb4;


USE questionnaire;



DROP TABLE IF EXISTS sujets, questions, rep_poss, question_rep_poss, question_types, reponses, reponse_question, utilisateur;



CREATE TABLE sujets (
  PRIMARY KEY (id_sujet),
  id_sujet      INT AUTO_INCREMENT,
  sujet_nom     VARCHAR(50) NOT NULL,
  sujet_date    DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE questions (
  PRIMARY KEY (id_question),
  id_question       INT AUTO_INCREMENT,
  question_question VARCHAR(255) NOT NULL,
  id_sujet          INT NOT NULL,
  question_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rep_poss (
  PRIMARY KEY (id_rep_poss),
  id_rep_poss       INT AUTO_INCREMENT,
  rep_poss_reponse  VARCHAR(255) NOT NULL,
  id_question_type           INT NOT NULL
);

CREATE TABLE question_rep_poss (
  PRIMARY KEY(id_question_rep_poss),
  id_question_rep_poss INT AUTO_INCREMENT,
  id_question          INT NOT NULL,
  id_rep_poss          INT NOT NULL
);

CREATE TABLE question_types (
  PRIMARY KEY (id_question_type),
  id_question_type    INT AUTO_INCREMENT,
  question_type_nom   VARCHAR(50) NOT NULL
);

CREATE TABLE reponses (
  PRIMARY KEY (id_reponse),
  id_reponse        INT AUTO_INCREMENT,
  reponse_reponse   TEXT NULL,
  id_utilisateur    INT NOT NULL
);

CREATE TABLE reponse_question (
  PRIMARY KEY (id_reponse_question),
  id_reponse_question     INT AUTO_INCREMENT,
  id_question             INT NOT NULL,
  id_reponse              INT NOT NULL,
  reponse_question_date   DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE utilisateur (
  PRIMARY KEY (id_utilisateur),
  id_utilisateur          INT AUTO_INCREMENT,
  utilisateur_mail        VARCHAR(100) NOT NULL,
  utilisateur_societe     VARCHAR(100) NOT NULL,
  utilisateur_type        INT DEFAULT 0 NOT NULL,
  utilisateur_date        DATETIME DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE questions ADD FOREIGN KEY (id_sujet) REFERENCES sujets (id_sujet);
ALTER TABLE question_rep_poss ADD FOREIGN KEY (id_question) REFERENCES questions (id_question);
ALTER TABLE question_rep_poss ADD FOREIGN KEY (id_rep_poss) REFERENCES rep_poss (id_rep_poss);
ALTER TABLE rep_poss ADD FOREIGN KEY (id_question_type) REFERENCES question_types(id_question_type);

ALTER TABLE reponse_question ADD FOREIGN KEY (id_question) REFERENCES questions (id_question);
ALTER TABLE reponse_question ADD FOREIGN KEY (id_reponse) REFERENCES reponses (id_reponse);
 
ALTER TABLE reponses ADD FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur);