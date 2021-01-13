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
            <?php
                // Select dans la BDD.
                if(isset($_SESSION['User_Login'])) {    // Vérifie que Session existe.
                    $User_ID = $_SESSION['User_Login'] ;

                    $BDD_SELECT_Panel = $BDD -> prepare("SELECT * FROM $BDD_Utilisateur_Table WHERE $BDD_Utilisateur_UserID=:uid") ;
                    $BDD_SELECT_Panel -> execute(array(":uid" => $User_ID)) ;

                    $row_Panel = $BDD_SELECT_Panel -> fetch(PDO::FETCH_ASSOC) ;
                }
                // Affichage de la page si Utilisateur/Modérateur/Administrateur.
                if(isset($row_Panel)) {
                    if($row_Panel["Utilisateur_Groupe"] == 0 || $row_Panel["Utilisateur_Groupe"] == 1 || $row_Panel["Utilisateur_Groupe"] == 2) {
                    ?>
                        <h2 class='TC'>Panel utilisateur permetant d'upload des Sons.</h2>
                        <form action="/src/Upload.php" method="POST">
                            <h3>Groupe de Sounboard :</h3>
                            <p>
                                <?php
                                    $BDD_SELECT_GROUPE_SB = $BDD -> query("SELECT * FROM `groupe_son`") ;
                                
                                    while ($row_SELECT_GROUPE_SB = $BDD_SELECT_GROUPE_SB -> fetch()) {
                                        ?>
                                            <input type="radio" id="<?=$row_SELECT_GROUPE_SB["Groupe_Son_ID"]?>" name="groupe"> <label for="<?=$row_SELECT_GROUPE_SB["Groupe_Son_ID"]?>"><?=$row_SELECT_GROUPE_SB["Groupe_Son_Name"]?></label>
                                        <?php
                                    }
                                ?>
                            </p>
                            <input type="submit"  name="Button_Register" value="Register">
                        </form>
                    <?php
                    }
                    else {
                    ?>
                        <p><a href='Compte'>Vous ne ne pouvez pas utiliser le Panel utilisateur.</a></p>
                        <p><a href='Compte'>Contactez le support pour plus d'informations.</a></p>
                    <?php
                    }
                }
                else {
                ?>
                    <p><a href='Compte'>Vous n'êtes pas connecté, vous ne pouvez pas accéder à cette page.</a></p>
                <?php
                }
            ?>
        </div>
        
        <?php
            Footer() ;
        ?>

    </body>
</html>