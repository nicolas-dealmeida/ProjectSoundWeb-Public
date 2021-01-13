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
           <p>Liste des Soundboard "Voix" :</p>
        </div>

        <?php
            Footer() ;
        ?>

    </body>
</html>