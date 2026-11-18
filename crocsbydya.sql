-- =============================================
--  SCRIPT SQL — Base de données CROCS BY DYA
-- =============================================



-- CRÉATION DES TABLES 

CREATE TABLE Fournisseur (
  numfour   INT          PRIMARY KEY AUTO_INCREMENT,
  nomfour   VARCHAR(100) NOT NULL,
  adrfour   VARCHAR(200) NOT NULL
);

CREATE TABLE Client (
  numcli    INT          PRIMARY KEY AUTO_INCREMENT,
  nomcli    VARCHAR(100) NOT NULL,
  adrcli    VARCHAR(200) NOT NULL
);

CREATE TABLE Crocs (
  numcrocs  INT            PRIMARY KEY AUTO_INCREMENT,
  modele    VARCHAR(100)   NOT NULL,
  couleur   VARCHAR(50)    NOT NULL,
  prix      DECIMAL(8,2)   NOT NULL,
  numfour   INT            NOT NULL,
  FOREIGN KEY (numfour) REFERENCES Fournisseur(numfour)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE Commande (
  numcom    INT  PRIMARY KEY AUTO_INCREMENT,
  datecom   DATE NOT NULL,
  numcli    INT  NOT NULL,
  FOREIGN KEY (numcli) REFERENCES Client(numcli)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE Contenir (
  numcom    INT NOT NULL,
  numcrocs  INT NOT NULL,
  quantite  INT NOT NULL,
  PRIMARY KEY (numcom, numcrocs),
  FOREIGN KEY (numcom)   REFERENCES Commande(numcom)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (numcrocs) REFERENCES Crocs(numcrocs)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

-- REMPLISSAGE DES TABLES

-- FOURNISSEURS (3) 
INSERT INTO Fournisseur (nomfour, adrfour) VALUES
  ('Crocs Inc.',    '6328 Monarch Park Place, Niwot, Colorado, USA'),
  ('EuroCrocs',     '12 rue du Commerce, 75015 Paris, France'),
  ('CrocsDistrib',  '45 avenue de la Mode, 69002 Lyon, France');

-- CLIENTS (5) 
INSERT INTO Client (nomcli, adrcli) VALUES
  ('Alice Martin',    '3 avenue de la Paix, 69001 Lyon'),
  ('Bob Dupont',      '8 rue de Rivoli, 75001 Paris'),
  ('Clara Fontaine',  '15 rue des Lilas, 33000 Bordeaux'),
  ('David Moreau',    '27 boulevard Victor Hugo, 06000 Nice'),
  ('Emma Leclerc',    '5 impasse des Roses, 59000 Lille');

-- CROCS — CATALOGUE (8 paires) 
INSERT INTO Crocs (modele, couleur, prix, numfour) VALUES
  ('Classic Clog',    'Rouge',       39.99, 1),
  ('Classic Clog',    'Noir',        39.99, 1),
  ('Classic Clog',    'Blanc',       39.99, 1),
  ('Literide Pacer',  'Gris',        59.99, 2),
  ('Literide Pacer',  'Noir',        59.99, 2),
  ('Bistro Pro',      'Noir',        49.99, 3),
  ('Crocband',        'Blanc/Rouge', 45.99, 3),
  ('Classic Sandal',  'Jaune',       34.99, 2);

-- COMMANDES (5) 
INSERT INTO Commande (datecom, numcli) VALUES
  ('2024-01-10', 1),
  ('2024-02-14', 2),
  ('2024-03-05', 3),
  ('2024-03-20', 4),
  ('2024-04-08', 5);

-- CONTENIR — DÉTAIL DES COMMANDES (9 lignes) 
INSERT INTO Contenir (numcom, numcrocs, quantite) VALUES
  (1, 1, 2),   -- Alice : 2x Classic Clog Rouge
  (1, 4, 1),   -- Alice : 1x Literide Gris
  (2, 6, 1),   -- Bob   : 1x Bistro Pro Noir
  (2, 7, 2),   -- Bob   : 2x Crocband Blanc/Rouge
  (3, 2, 1),   -- Clara : 1x Classic Clog Noir
  (3, 8, 1),   -- Clara : 1x Classic Sandal Jaune
  (4, 5, 2),   -- David : 2x Literide Pacer Noir
  (5, 3, 1),   -- Emma  : 1x Classic Clog Blanc
  (5, 6, 1);   -- Emma  : 1x Bistro Pro Noir