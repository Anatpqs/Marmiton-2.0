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
INSERT INTO `mydb`.`Recette` (`IdRecette`, `Nom`, `IdCréateur`, `Notemoy`, `Nb_personne`, `Temps_prep`, `Temps_cuis`, `Description`, `Instruction`, `Image`) VALUES (DEFAULT, 'cookies', 2, 4, 5, 15, 20, 'De délicieu cookies mouelleux et croquant', '\n\n    Sortir le beurre du frigo environ 20 minutes avant pour qu\'il ramollisse\n    Mélanger le beurre avec le sucre glace, le sucre roux pendant 1 minute\n    Ajouter la vanille liquide et l\'oeuf, mélanger 30 secondes\n    Ajouter la farine, levure, et amandes et mélanger encore 1 minute jusqu\'à ce que le mélange soit homogène\n    Ajouter les pépites et mélanger un peu pour bien les répartir\n    Mettre au réfrigérateur 15 minutes (optionel)\n    Préchauffer le four à 170°C et préparer les plaques de cuisson avec du papier cuisson (sulfurisé)\n    Une fois que le four a atteint la température, sortir les cookies du réfrigérateur et former des boulettes de 3-4 cm de diamètre\n    Déposer les boulettes et légèrement les aplatir pour donner une forme de cookie\n    Cuire pendant environ 8 minutes, bien surveiller et les sortir une fois qu\'ils sont dorés et que les bords brunissent légèrement. Ne les laisser pas plus longtemps sinon ils vont devenir sablés au lieu de moelleux', NULL);

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
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Four', 1);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, 'Chocolat', 1);
INSERT INTO `mydb`.`Tag` (`IdTag`, `Mot_clé`, `Recette_assoc`) VALUES (DEFAULT, '', DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `mydb`.`Rectte_pref`
-- -----------------------------------------------------
START TRANSACTION;
USE `mydb`;
INSERT INTO `mydb`.`Rectte_pref` (`Id_recette`, `Id_utilisateur`) VALUES (1, 3);

COMMIT;

