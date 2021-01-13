<?php
    // Déclaration des Variables PHP
    require_once "src/Function-Web.php" ; // Include des Fonctions Web.
    require_once "src/Function-RegLog/BDD_Connect.php" ; // Include de la BDD.
    
	$FILE_LOCAL = basename(__FILE__) ; // Définition d'une variable ayant le nom de la page.
    $File_Name = basename(__FILE__, ".php") ; // Définition du Nom de la page.
?>

<!DOCTYPE html>
<html lang="fr">
    <?php
        Head($FILE_LOCAL) ;
    ?>
    <body>
        <?php
            Menu() ;
        ?>

        <div>
            <h1 class="TITRE">
                <?php
                    echo "$File_Name" ;
                ?>
            </h1>
        </div>

        <div class="Div1"> 
           <h2>Liste des Administrateurs :</h2>

           <?php
                $BDD_Select_Groupe2 = $BDD -> query("SELECT * FROM `utilisateur` WHERE `Utilisateur_Groupe`=2 AND `Utilisateur_Visibility`=1 ORDER BY `Utilisateur_Date`") or exit(mysql_error()) ; // Requêtes BDD
                while ($row_Select_Groupe2 = $BDD_Select_Groupe2 -> fetch()) {
                    ?>
                        <p> <?= $row_Select_Groupe2["Utilisateur_Name"] ?> </p>
                    <?php
                }
            ?>

        </div>

        <div class="Div1"> 
           <h2>Liste des Modérateurs :</h2>

           <?php
                $BDD_Select_Groupe1 = $BDD -> query("SELECT * FROM `utilisateur` WHERE `Utilisateur_Groupe`=1 AND `Utilisateur_Visibility`=1 ORDER BY `Utilisateur_Date`") or exit(mysql_error()) ; // Requêtes BDD
                while ($row_Select_Groupe1 = $BDD_Select_Groupe1 -> fetch()) {
                    ?>
                        <p> <?= $row_Select_Groupe1["Utilisateur_Name"] ?> </p>
                    <?php
                }
            ?>

        </div>

        <div class="Div1"> 
           <h2>Liste des Utilisateurs :</h2>

            <?php
                $BDD_Select_Groupe0 = $BDD -> query("SELECT * FROM `utilisateur` WHERE `Utilisateur_Groupe`=0 AND `Utilisateur_Visibility`=1 ORDER BY `Utilisateur_Date`") or exit(mysql_error()) ; // Requêtes BDD
                while ($row_Select_Groupe0 = $BDD_Select_Groupe0 -> fetch()) {
                    ?>
                        <p> <?= $row_Select_Groupe0["Utilisateur_Name"] ?> </p>
                    <?php
                }
                $BDD_Select_Groupe0 = $BDD -> query("SELECT * FROM `utilisateur` WHERE `Utilisateur_Groupe`='-1' AND `Utilisateur_Visibility`=1 ORDER BY `Utilisateur_Date`") or exit(mysql_error()) ; // Requêtes BDD
                while ($row_Select_Groupe01 = $BDD_Select_Groupe0 -> fetch()) {
                    ?>
                        <p> <?= $row_Select_Groupe01["Utilisateur_Name"] ?> </p>
                    <?php
                }
            ?>

        </div>

        <?php
            Footer() ;
        ?>

    </body>
</html>