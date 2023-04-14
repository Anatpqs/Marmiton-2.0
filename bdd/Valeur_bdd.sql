-- -----------------------------------------------------
-- Data for table `mydb`.`Utilisateur`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`, `Photo`) VALUES (DEFAULT, 'a', 'Utilisateur Suprimé', 'Utilisateur Suprimé', 1, NULL);
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`, `Photo`) VALUES (DEFAULT, 'b', 'Julian@.com', 'Julian', 0, NULL);
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`, `Photo`) VALUES (DEFAULT, 'c', 'Anatole@.com', 'Anatole', 0, NULL);
INSERT INTO `mydb`.`Utilisateur` (`IdUtilisateur`, `Mdp`, `Login`, `Pseudo`, `Droit`, `Photo`) VALUES (DEFAULT, 'd', 'Andrew@.com', 'Andrew', 0, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`Recette`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Recette` (`IdRecette`, `Nom`, `IdCréateur`, `Notemoy`, `Nb_personne`, `Temps_prep`, `Temps_cuis`, `Description`, `Instruction`, `Image`) VALUES (DEFAULT, 'cookies', 2, 4, 5, 15, 20, 'De délicieu cookies mouelleux et croquant', '\n\n    Sortir le beurre du frigo environ 20 minutes avant pour qu\'il ramollisse\n    Mélanger le beurre avec le sucre glace, le sucre roux pendant 1 minute\n    Aj', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`Commentaire`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Commentaire` (`IdCommentaire`, `Commentaire`, `Note`, `IdAuteur`, `Recette_com`) VALUES (DEFAULT, 'c\'etait bon + facile a préparer', 5, 2, 1);
INSERT INTO `mydb`.`Commentaire` (`IdCommentaire`, `Commentaire`, `Note`, `IdAuteur`, `Recette_com`) VALUES (DEFAULT, 'je recommande', 4, 3, 1);

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

COMMIT;


-- -----------------------------------------------------
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

COMMIT;

-- -----------------------------------------------------
-- Data for table `mydb`.`Rectte_pref`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Rectte_pref` (`Id_recette`, `Id_utilisateur`) VALUES (1, 3);

COMMIT;
