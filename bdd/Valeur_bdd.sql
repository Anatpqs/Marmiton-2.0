-- -----------------------------------------------------
-- Data for table `mydb`.`Utilisateur`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`) VALUES (DEFAULT, 'a', 'Utilisateur Suprimé', 'Utilisateur Suprimé', 1);
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`) VALUES (DEFAULT, '$2y$12$2VfeBTfiqQYPRCvj3gYa2OnIOoJXsic88vXazY0Em5i677d89LExa', 'Julian@.com', 'Julian', 0);
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`) VALUES (DEFAULT, '$2y$12$qQ6CiOa/d.WezeiDhncQuevXpQ1cYM.CxHLEZ/EvMobAQidZhkzTC', 'Anatole@.com', 'Anatole', 0);
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`) VALUES (DEFAULT, '$2y$12$FAsob45UsaU9Vm7RarG4TeuXsOM1t8q7b2YFzzHoyg.CGjuhMZx5y', 'Andrew@.com', 'Andrew', 0);


COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`Recette`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Recette` (`IdRecette`, `Nom`, `IdCréateur`, `Notemoy`, `Nb_personne`, `Temps_prep`, `Temps_cuis`, `Description`, `Instruction`, `État`) VALUES (DEFAULT, 'cookies', 2, 4, 5, 15, 20, 'De délicieu cookies mouelleux et croquant', '\n\n    Sortir le beurre du frigo environ 20 minutes avant pour qu\'il ramollisse\n    Mélanger le beurre avec le sucre glace, le sucre roux pendant 1 minute\n    Ajouter la vanille liquide et l\'oeuf, mélanger 30 secondes\n    Ajouter la farine, levure, et amandes et mélanger encore 1 minute jusqu\'à ce que le mélange soit homogène\n    Ajouter les pépites et mélanger un peu pour bien les répartir\n    Mettre au réfrigérateur 15 minutes (optionel)\n    Préchauffer le four à 170°C et préparer les plaques de cuisson avec du papier cuisson (sulfurisé)\n    Une fois que le four a atteint la température, sortir les cookies du réfrigérateur et former des boulettes de 3-4 cm de diamètre\n    Déposer les boulettes et légèrement les aplatir pour donner une forme de cookie\n    Cuire pendant environ 8 minutes, bien surveiller et les sortir une fois qu\'ils sont dorés et que les bords brunissent légèrement. Ne les laisser pas plus longtemps sinon ils vont devenir sablés au lieu de moelleux', 1);
INSERT INTO `mydb`.`Recette` (`IdRecette`, `Nom`, `IdCréateur`, `Notemoy`, `Nb_personne`, `Temps_prep`, `Temps_cuis`, `Description`, `Instruction`, `État`) VALUES (DEFAULT, 'Tarte pomme de terre chèvre', 3, 3, 4, 15, 45, 'Une tarte délicieuse pour l\'hiver', '    Étape 1\n\n    Détailler votre pâte brisée à la dimension de votre moule à tartelettes pour la précuire une dizaine de minutes à 180°C.\n    Étape 2\n\n    Dans une casserole, plonger des lamelles de pommes de terre (3mm) dans un mélange eau et lait et faites cuire environ 10 min.\n    Étape 3\n\n    Placer des tranches de pommes de terre dans les moules avec la pâte précuite. Ajouter des morceaux de fromage de chèvre puis une tranche de pancetta.\n    Étape 4\n\n    Battez les œufs et la crème. Verser la préparation dans les moules.\n    Étape 5\n\n    Enfournez environ 15 min au four à 180°.', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`Commentaire`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Commentaire` (`IdCommentaire`, `Commentaire`, `Note`, `IdAuteur`, `Recette_com`) VALUES (DEFAULT, 'c\'etait bon + facile a préparer', 5, 2, 1);
INSERT INTO `mydb`.`Commentaire` (`IdCommentaire`, `Commentaire`, `Note`, `IdAuteur`, `Recette_com`) VALUES (DEFAULT, 'je recommande', 4, 3, 1);
INSERT INTO `mydb`.`Commentaire` (`IdCommentaire`, `Commentaire`, `Note`, `IdAuteur`, `Recette_com`, `Date`) VALUES (DEFAULT, 'Vraiment bon et facile', 4, 2, 2, NULL);
INSERT INTO `mydb`.`Commentaire` (`IdCommentaire`, `Commentaire`, `Note`, `IdAuteur`, `Recette_com`, `Date`) VALUES (DEFAULT, 'Trop salé', 2, 4, 2, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`Ingrédient`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Farine', 225, 'gramme', 1, 1);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Beurre', 110, 'gramme', 1.5, 1);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Sucre roux', 50, 'gramme', 0.5, 1);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'pépite de chocolat', 75, 'gramme', 0.75, 1);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'oeuf', 1, NULL, 0.36, 1);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Levure chimique', 5, 'gramme', 0.2, 1);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'vanille liquide', 0.5, 'cuillère', 1, 1);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Pate brisée', 1, NULL, 1.5, 2);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Pomme de terre', 0.3, 'kg', 0.5, 2);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Fromage de chèvre', 0.2, 'kg', 2, 2);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'Tranche de lard', 4, NULL, 0.3, 2);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'oeuf', 3, NULL, 0.36, 2);
INSERT INTO `mydb`.`Ingrédient` (`IdIngrédient`, `Nom`, `Quantité`, `Unité`, `Prix`, `Recette`) VALUES (DEFAULT, 'lait', 0.2, 'L', 1, 2);

COMMIT;


-- Data for table `mydb`.`Tag`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Chaud', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Froid', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Été', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Hiver', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Four', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Facile', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Bon marché', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Poisson', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Viande', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Végétarien', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Entré', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Plat', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Desert', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Colation', NULL);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Desert', 1);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Chocolat', 1);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Facile', 1);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Plat', 2);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Four', 2);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Tarte', 2);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Chaud', 2);
COMMIT;

-- -----------------------------------------------------
-- Data for table `mydb`.`Recette_pref`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Recette_pref` (`Id_recette`, `Id_utilisateur`) VALUES (1, 4);
INSERT INTO `mydb`.`Recette_pref` (`Id_recette`, `Id_utilisateur`) VALUES (2, 2);

COMMIT;
