-- New insertions :

INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
VALUES 
('Alice Dupont', 'alice@freelance.com', 'hashedpassword1', 'freelance'),
('Bob Martin', 'bob@client.com', 'hashedpassword2', 'client'),
('Claire Moreau', 'claire@freelance.com', 'hashedpassword3', 'freelance'),
('David Leroy', 'david@client.com', 'hashedpassword4', 'client');
INSERT INTO profils (utilisateur_id, bio, tarif_horaire, disponible)
VALUES 
(1, 'Développeuse full-stack avec 5 ans d’expérience.', 45.00, TRUE),
(3, 'Designer UX/UI spécialisé dans les applications mobiles.', 50.00, TRUE);
INSERT INTO competences (nom)
VALUES ('Laravel'), ('React'), ('Photoshop'), ('Figma');
INSERT INTO profil_competence (profil_id, competence_id)
VALUES 
(1, 1), -- Alice - Laravel
(1, 2), -- Alice - React
(2, 3), -- Claire - Photoshop
(2, 4); -- Claire - Figma
INSERT INTO projets (client_id, titre, description, budget, delai)
VALUES 
(2, 'Site vitrine pour PME', 'Création d’un site vitrine responsive.', 1500.00, 14),
(4, 'Refonte d’application mobile', 'Améliorer l’expérience utilisateur.', 3000.00, 21);
INSERT INTO offres (projet_id, freelance_id, prix_propose, delai_propose, message)
VALUES 
(1, 1, 1400.00, 12, 'Je peux livrer rapidement avec une qualité top.'),
(2, 3, 2800.00, 20, 'Refonte en Figma + guidelines UX incluses.');
INSERT INTO missions (offre_id, date_debut, date_fin, statut)
VALUES 
(1, '2025-04-01', '2025-04-13', 'livré'),
(2, '2025-04-03', '2025-04-22', 'en cours');
INSERT INTO factures (mission_id, montant, date_emission, paye)
VALUES 
(1, 1400.00, '2025-04-14', TRUE),
(2, 2800.00, '2025-04-23', FALSE);
INSERT INTO transactions (facture_id, moyen_paiement, date_transaction, montant)
VALUES 
(1, 'Carte bancaire', '2025-04-15', 1400.00);
INSERT INTO evaluations (mission_id, note, commentaire, date_evaluation)
VALUES 
(1, 5, 'Très satisfaite, travail rapide et soigné.', '2025-04-16');
INSERT INTO categories (nom)
VALUES ('Développement web'), ('Design graphique');
INSERT INTO projet_categorie (projet_id, categorie_id)
VALUES 
(1, 1),
(2, 2);
INSERT INTO langues (nom)
VALUES ('Français'), ('Anglais'), ('Espagnol');
INSERT INTO profil_langue (profil_id, langue_id, niveau)
VALUES 
(1, 1, 'avancé'),
(1, 2, 'intermédiaire'),
(2, 1, 'avancé'),
(2, 3, 'débutant');
INSERT INTO pays (nom)
VALUES ('France'), ('Maroc'), ('Canada');
INSERT INTO adresses (utilisateur_id, pays_id, ville, code_postal)
VALUES 
(1, 1, 'Paris', '75001'),
(2, 2, 'Casablanca', '20000'),
(3, 3, 'Montréal', 'H2X 1S1');
INSERT INTO notifications (utilisateur_id, message)
VALUES 
(1, 'Votre mission a été validée.'),
(2, 'Nouvelle offre reçue sur votre projet.');
INSERT INTO historique_connexions (utilisateur_id, date_connexion, ip)
VALUES 
(1, NOW(), '192.168.1.10'),
(2, NOW(), '192.168.1.11');
INSERT INTO sessions (utilisateur_id, token, date_expiration)
VALUES 
(1, 'randomtoken123', '2025-05-01 23:59:59');
INSERT INTO messages (expediteur_id, destinataire_id, contenu)
VALUES 
(1, 2, 'Bonjour ! J’ai une question concernant le projet.'),
(2, 1, 'Pas de souci, je suis là pour répondre.');

-- Afficher les freelances qui parlent l’anglais (langues.nom = 'Anglais') avec un niveau avancé.

SELECT utilisateurs.nom, langues.nom AS langue, profil_langue.niveau
FROM utilisateurs
JOIN profils ON utilisateurs.id=profils.utilisateur_id
JOIN profil_langue ON profil_langue.id=profil_langue.profil_id
JOIN langues ON profil_langue.langue_id=langues.id
WHERE utilisateurs.role='freelance' AND langues.nom='Anglais' AND profil_langue.niveau='avancé';

-- Lister les freelances ayant plus de 3 compétences.

SELECT utilisateurs.nom, COUNT(pc.competence_id) AS nbr_competences
FROM utilisateurs
JOIN profils ON utilisateurs.id = profils.utilisateur_id
JOIN profil_competence pc ON profils.id = pc.profil_id
WHERE utilisateurs.role = 'freelance'
GROUP BY utilisateurs.id
HAVING nbr_competences > 3

-- Afficher les freelances disponibles, leur tarif horaire et leur ville.

SELECT utilisateurs.nom, profils.tarif_horaire, adresses.ville
FROM utilisateurs
JOIN profils ON utilisateurs.id = profils.utilisateur_id
JOIN adresses ON utilisateurs.id = adresses.utilisateur_id
WHERE utilisateurs.role = 'freelance' AND profils.disponible=1

-- Lister tous les utilisateurs qui ne possèdent pas encore de profil.

SELECT *
FROM utilisateurs
left JOIN profils ON utilisateurs.id = profils.utilisateur_id
WHERE profils.id IS NULL;

-- Afficher les clients qui n'ont jamais publié de projet.

SELECT utilisateurs.nom
FROM utilisateurs
LEFT JOIN projets ON utilisateurs.id= projets.client_id
WHERE utilisateurs.role ='client' AND projets.id IS NULL;

-- Afficher les projets ouverts avec leur budget et leur nombre total d’offres reçues.

SELECT projets.titre, projets.budget, COUNT(offres.id) AS nbr_offres
FROM projets
LEFT JOIN offres ON projets.id=offres.projet_id
WHERE projets.statut='ouvert'
GROUP BY projets.id

-- Lister les offres envoyées par des freelances dont le tarif horaire est inférieur à 100 MAD.

SELECT utilisateurs.*, offres.prix_propose, profils.tarif_horaire
FROM offres
JOIN utilisateurs ON offres.freelance_id = utilisateurs.id
JOIN profils ON utilisateurs.id = profils.utilisateur_id
WHERE profils.tarif_horaire>100

-- Afficher les projets qui ont reçu au moins 3 offres.

SELECT projets.titre, COUNT(offres.id) AS nbr_offres
FROM projets
JOIN offres ON projets.id = offres.projet_id
GROUP BY projets.id
HAVING nbr_offres>=2

-- Afficher les freelances qui ont postulé sur plus de 5 projets différents.

SELECT utilisateurs.nom, COUNT(offres.projet_id) AS nbr_projets
FROM utilisateurs
JOIN offres ON utilisateurs.id=offres.freelance_id
WHERE utilisateurs.role = 'freelance'
GROUP BY utilisateurs.id
HAVING nbr_projets>5

-- Afficher les projets terminés avec les dates de début et de fin des missions associées.

SELECT projets.titre, missions.date_debut, missions.date_fin
FROM projets
JOIN offres ON projets.id = offres.projet_id
JOIN missions ON offres.id = missions.offre_id
WHERE projets.statut='terminé';

-- Lister les factures payées avec le nom du freelance, le montant, et le moyen de paiement.

