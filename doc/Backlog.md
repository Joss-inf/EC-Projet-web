
# 📌 User Stories – ÉcoStat – Suivi des Déchets par Entreprise

## 1. 🔐 Login (Connexion)

**En tant qu’utilisateur**,  
je veux pouvoir me connecter avec mes identifiants  
pour accéder à mon compte et visualiser mes statistiques personnelles.

**🎯 Critères d’acceptation :**
- Formulaire avec champs pour email et mot de passe.
- Validation des informations d’identification.
- Message d’erreur en cas de tentative échouée.

---

## 2. 📝 Register (Inscription)

**En tant que nouvel utilisateur**,  
je veux pouvoir créer un compte  
pour accéder aux fonctionnalités du site.

**🎯 Critères d’acceptation :**
- Formulaire : nom, email, mot de passe (confirmation), entreprise.
- Email unique et mot de passe sécurisé.
- Message de succès ou d’erreur à l'inscription.

---

## 3. 🏠 Page d'accueil

**En tant qu’utilisateur**,  
je veux voir une présentation du site claire et accessible,  
afin de comprendre immédiatement sa finalité.

**🎯 Critères d’acceptation :**
- Présentation concise du projet.
- Mise en page cohérente et attractive.
- Navigation intuitive.

---

## 4. 🛠️ Page admin

**En tant qu’administrateur**,  
je veux gérer les utilisateurs, les entreprises, et les données statistiques,  
pour modérer et organiser la plateforme.

**🎯 Critères d’acceptation :**
- Liste des utilisateurs (activation/désactivation).
- Gestion des statistiques par entreprise et date.
- Importation de données via fichiers CSV.

---

## 5. 📊 Affichage des statistiques

**En tant qu’utilisateur**,  
je veux voir des graphiques et tableaux de données environnementales,  
pour analyser la production de déchets.

**🎯 Critères d’acceptation :**
- Tableau dynamique : type de déchets, quantité, date.
- Graphiques clairs : barres, camemberts, lignes.

---

## 6. 🧮 Filtres pour les statistiques

**En tant qu’utilisateur**,  
je veux filtrer les données par date, entreprise ou ordre,  
afin d’analyser précisément les informations.

**🎯 Critères d’acceptation :**
- Filtres par période ou dates personnalisées.
- Filtrage par entreprise (admin).
- Tri croissant/décroissant.
- Statistiques mises à jour dynamiquement.

---

## 7. 💬 Commentaires sur les statistiques

**En tant qu’utilisateur**,  
je veux pouvoir commenter les statistiques,  
pour poser des questions ou apporter des explications.

**🎯 Critères d’acceptation :**
- Zone de texte sous les stats.
- Édition ou suppression de ses commentaires.
- Affichage : date + auteur.

---

## 8. 🛡️ Modération des commentaires

**En tant qu’administrateur**,  
je veux modérer les commentaires utilisateurs,  
pour éviter les propos inappropriés.

**🎯 Critères d’acceptation :**
- Suppression de commentaires signalés.
- Système de signalement visible par tous.

---

## 9. 🧪 Page de sensibilisation aux polluants

**En tant qu’utilisateur**,  
je veux accéder à des fiches d’explication sur les polluants,  
afin de comprendre leurs impacts.

**🎯 Critères d’acceptation :**
- Liste des polluants avec recherche/tri.
- Fiches par polluant : origine, effets, source industrielle.
- Lien avec les données de l’entreprise.
- Design éducatif et fluide.

---

## 10. 👤 Page de profil utilisateur

**En tant qu’utilisateur connecté**,  
je veux consulter et modifier mes informations,  
et suivre mon activité sur la plateforme.

**🎯 Critères d’acceptation :**
- Affichage : nom, email, entreprise, rôle.
- Modification possible (nom, mot de passe, entreprise).
- Historique : connexions, stats vues, commentaires postés.
- Déconnexion et sécurité assurées.

---

## 11. 📱 Responsive design global

**En tant qu’utilisateur**,  
je veux un site adapté à tout type d’écran,  
pour une navigation fluide quel que soit mon appareil.

**🎯 Critères d’acceptation :**
- Responsive sur smartphone, tablette et PC.
- Éléments réorganisés et lisibles sans zoom.
- Menu responsive (burger).
- Tableaux et graphiques adaptatifs ou scrollables.
- Testé sur navigateurs modernes (Chrome, Firefox, Safari).

---

## 12. 💾 Connexion sécurisée à la base de données

**En tant que développeur**,  
je veux établir une connexion sécurisée à la base de données MySQL  
pour gérer les utilisateurs, les statistiques et les commentaires.

**🎯 Critères d'acceptation :**
- Connexion PDO avec gestion des erreurs.
- Paramètres stockés dans un fichier `.env` ou sécurisé.
- Requêtes préparées pour éviter l’injection SQL.

---

## 13. 🔄 Requêtes Ajax pour les statistiques

**En tant qu'utilisateur**,  
je veux voir les statistiques mises à jour dynamiquement sans recharger la page,  
afin d’avoir une expérience fluide.

**🎯 Critères d’acceptation :**
- Appels Ajax pour filtrer ou charger les données (date, entreprise...).
- Réponse JSON traitée côté client (JavaScript).
- Affichage des données mis à jour instantanément.

---

## 14. 🔐 Hachage des mots de passe

**En tant que développeur**,  
je veux que les mots de passe soient stockés de façon sécurisée,  
pour protéger les comptes utilisateurs.

**🎯 Critères d’acceptation :**
- Utilisation de `password_hash()` et `password_verify()`.
- Mot de passe non visible dans la BDD.
- Interface de changement de mot de passe sécurisée.

---
