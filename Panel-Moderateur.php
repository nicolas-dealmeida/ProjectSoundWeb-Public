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

                    $BDD_SESSION = $BDD -> prepare("SELECT * FROM $BDD_Utilisateur_Table WHERE $BDD_Utilisateur_UserID=:uid") ;
                    $BDD_SESSION -> execute(array(":uid" => $User_ID)) ;

                    $row_SESSION = $BDD_SESSION -> fetch(PDO::FETCH_ASSOC) ;
                }
                // Affichage de la page si Administrateur.
                if(isset($row_SESSION)) {
                    if($row_SESSION["Utilisateur_Groupe"] == 2 || $row_SESSION["Utilisateur_Groupe"] == 1) {
                    ?>
                        <div class="Div1"> 
                        <h2>Liste des Utilisateurs :</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Pseudo</th>
                                        <th>ID </th>
                                        <th colspan="3">Actions</th>
                                    <tr>
                                </thead>
                                <tbody>
                                <?php
                                    $BDD_Select_Groupe0 = $BDD -> query("SELECT * FROM `utilisateur` WHERE `Utilisateur_Groupe`=0") or exit(mysql_error()) ; // Requêtes BDD
                                    while ($row_Panel = $BDD_Select_Groupe0 -> fetch()) {
                                    ?>
                                        <form action="" method="post">
                                            <tr>
                                                <td> <?= $row_Panel["Utilisateur_Name"] ?> </td>
                                                <td class="TC"> <?= $row_Panel["Utilisateur_ID"] ?> </td>
                                                <td> <input type="hidden"   name="USER_ID"  value="<?= $row_Panel["Utilisateur_ID"] ?>"> </td>
                                                <td> <input type="submit"   name="BAN"      value="Bannir"> </td>
                                            </tr>
                                        </form>
                                    <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="Div1"> 
                        <h2>Liste des Bannis :</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Pseudo</th>
                                        <th>ID </th>
                                        <th colspan="3">Actions</th>
                                    <tr>
                                </thead>
                                <tbody>
                                <?php
                                    $BDD_Select_Groupe0 = $BDD -> query("SELECT * FROM `utilisateur` WHERE `Utilisateur_Groupe`=-1") or exit(mysql_error()) ; // Requêtes BDD
                                    while ($row_Panel = $BDD_Select_Groupe0 -> fetch()) {
                                    ?>
                                        <form action="" method="post">
                                            <tr>
                                                <td> <?= $row_Panel["Utilisateur_Name"] ?> </td>
                                                <td class="TC"> <?= $row_Panel["Utilisateur_ID"] ?> </td>
                                                <td> <input type="hidden"   name="USER_ID"          value=" <?= $row_Panel["Utilisateur_ID"] ?>"> </td>
                                                <td> <input type="submit"   name="UN_BAN"           value="Débannir"> </td>
                                            </tr>
                                        </form>
                                    <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                    }
                    else {
                    ?>
                        <p><a href='Compte'>Vous n'êtes pas Modérateur, vous ne pouvez pas accéder à cette page.</a></p>
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
        if(isset($row_SESSION)) {
            if(isset($_REQUEST['BAN'])) {       // Pour les Bannisement.
                if($row_SESSION["Utilisateur_Groupe"] == 2 || $row_SESSION["Utilisateur_Groupe"] == 1) {
                    $BDD_BAN_USER = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Groupe` = '-1' WHERE `utilisateur`.`Utilisateur_ID` =" .$_REQUEST['USER_ID']) or exit(mysql_error()) ;  // Requêtes BDD
                    $BDD_BAN_USER -> execute() ;
                    $Msg_LOG = "Utilisateur Mise à jours !" ;
                    echo "<meta http-equiv='refresh' content='0'>" ;
                }
            }
            if(isset($_REQUEST['UN_BAN'])) {    // Pour les Débannisement.
                if($row_SESSION["Utilisateur_Groupe"] == 2 || $row_SESSION["Utilisateur_Groupe"] == 1) {
                    $BDD_UN_BAN_USER = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Groupe` = '0' WHERE `utilisateur`.`Utilisateur_ID` =" .$_REQUEST['USER_ID']) or exit(mysql_error()) ;  // Requêtes BDD
                    $BDD_UN_BAN_USER -> execute() ;
                    $Msg_LOG = "Utilisateur Mise à jours !" ;
                    echo "<meta http-equiv='refresh' content='0'>" ;
                }
            }
        }
        Footer() ;
        ?>
    </body>
</html>