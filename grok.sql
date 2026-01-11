CREATE DATABASE IF NOT EXISTS bibliotheque CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bibliotheque;

CREATE TABLE livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    auteur VARCHAR(100) NOT NULL,
    description TEXT,
    maison_edition VARCHAR(100),
    nombre_exemplaire INT DEFAULT 0
);

CREATE TABLE lecteurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE liste_lecture (
    id_livre INT NOT NULL,
    id_lecteur INT NOT NULL,
    date_emprunt DATE NOT NULL,
    date_retour DATE NULL,
    PRIMARY KEY (id_livre, id_lecteur),
    FOREIGN KEY (id_livre) REFERENCES livres(id) ON DELETE CASCADE,
    FOREIGN KEY (id_lecteur) REFERENCES lecteurs(id) ON DELETE CASCADE
);

-- Données de test
INSERT INTO livres (titre, auteur, description, maison_edition, nombre_exemplaire) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Un conte poétique et philosophique.', 'Gallimard', 10),
('1984', 'George Orwell', 'Roman dystopique sur la surveillance totale.', 'Secker & Warburg', 5),
('Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Premier tome des aventures du jeune sorcier.', 'Bloomsbury', 15),
('L\'Étranger', 'Albert Camus', 'Roman existentialiste.', 'Gallimard', 8);

INSERT INTO lecteurs (nom, prenom, email, mot_de_passe) VALUES
('Admin', 'Bibliothèque', 'admin@bibliotheque.com', '$2y$10$zOexampleHashedPasswordHere'); -- mot de passe : admin123 (à changer)