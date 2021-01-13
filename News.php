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
           <h3 class="TC">News du <i>10/01/2021</i></h3>
           <h4 class="TC"><b>Ouverture du site !</b></h4>
           <p class="TC"><b>ProjectSoundWeb</b> est maintenant en ligne sur <i><a href='https://biprini.discord.lu/'>Biprini.Discord.lu</a></i> !</p>
           <p class="TC">Le site n'est cependant pas encore complet, il n'est toujours pas possible d'upload des sons ou d'en télécharger, mais l'équipe du site y travail.</p>
           <p class="TC">Nous espérons donc pouvoir vite grandir et devenir <b>LA RÉFÉRENCE</b> en matière de Soundboard Française !</p>
        </div>

        <?php
            Footer() ;
        ?>

    </body>
</html>