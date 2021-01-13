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
                    if($row_SESSION["Utilisateur_Groupe"] == 2) {
                    ?>
                        <div class="Div1"> 
                        <h2>Liste des Modérateurs :</h2>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Pseudo</th>
                                        <th>ID </th>
                                        <th colspan="2">Actions</th>
                                    <tr>
                                </thead>
                                <tbody>
                                <?php
                                    $BDD_Select_Groupe1 = $BDD -> query("SELECT * FROM `utilisateur` WHERE `Utilisateur_Groupe`=1") or exit(mysql_error()) ; // Requêtes BDD
                                    while ($row_Panel = $BDD_Select_Groupe1 -> fetch()) {
                                    ?>
                                        <form action="" method="post">
                                            <tr>
                                                <td> <?= $row_Panel["Utilisateur_Name"] ?> </td>
                                                <td class="TC"> <?= $row_Panel["Utilisateur_ID"] ?> </td>
                                                <td> <input type="hidden"   name="USER_ID"  value="<?= $row_Panel["Utilisateur_ID"] ?>"> </td>
                                                <td> <input type="submit"   name="UN_RANK"  value="Dégrader"> </td>
                                            </tr>
                                        </form>
                                    <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
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
                                                <td> <input type="submit"   name="UP_RANK"  value="Promouvoir"> </td>
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
                                                <td> <input type="hidden"   name="USER_ID"          value="<?= $row_Panel["Utilisateur_ID"] ?>"> </td>
                                                <td> <input type="submit"   name="UN_BAN"           value="Débannir"> </td>
                                                <td> <input type="submit"   name="DELET_USER"       value="Supprimer Compte"> </td>
                                                <td> <input type="checkbox" name="DELET_CONFIRM"    value="Confirme"> <label>❌</label> </td>
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
                        <p><a href='Compte'>Vous n'êtes pas Administrateur, vous ne pouvez pas accéder à cette page.</a></p>
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
            if(isset($_REQUEST['UN_RANK'])) {       // Pour les Un-Rank (-) de Modérateur.
                if($row_SESSION["Utilisateur_Groupe"] == 2) {
                    $BDD_UN_RANK_USER = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Groupe` = '0' WHERE `utilisateur`.`Utilisateur_ID` =" .$_REQUEST['USER_ID']) or exit(mysql_error()) ;  // Requêtes BDD
                    $BDD_UN_RANK_USER -> execute() ;
                    $Msg_LOG = "Utilisateur Mise à jours !" ;
                    echo "<meta http-equiv='refresh' content='0'>" ;
                }
            }
            if(isset($_REQUEST['UP_RANK'])) {       // Pour les Up-Rank (+) de Modérateur.
                if($row_SESSION["Utilisateur_Groupe"] == 2) {
                    $BDD_UP_RANK_USER = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Groupe` = '1' WHERE `utilisateur`.`Utilisateur_ID` =" .$_REQUEST['USER_ID']) or exit(mysql_error()) ;  // Requêtes BDD
                    $BDD_UP_RANK_USER -> execute() ;
                    $Msg_LOG = "Utilisateur Mise à jours !" ;
                    echo "<meta http-equiv='refresh' content='0'>" ;
                }
            }
            if(isset($_REQUEST['BAN'])) {           // Pour les Bannisement.
                if($row_SESSION["Utilisateur_Groupe"] == 2) {
                    $BDD_BAN_USER = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Groupe` = '-1' WHERE `utilisateur`.`Utilisateur_ID` =" .$_REQUEST['USER_ID']) or exit(mysql_error()) ;  // Requêtes BDD
                    $BDD_BAN_USER -> execute() ;
                    $Msg_LOG = "Utilisateur Mise à jours !" ;
                    echo "<meta http-equiv='refresh' content='0'>" ;
                }
            }
            if(isset($_REQUEST['UN_BAN'])) {        // Pour les Débannisement.
                if($row_SESSION["Utilisateur_Groupe"] == 2) {
                    $BDD_UN_BAN_USER = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Groupe` = '0' WHERE `utilisateur`.`Utilisateur_ID` =" .$_REQUEST['USER_ID']) or exit(mysql_error()) ;  // Requêtes BDD
                    $BDD_UN_BAN_USER -> execute() ;
                    $Msg_LOG = "Utilisateur Mise à jours !" ;
                    echo "<meta http-equiv='refresh' content='0'>" ;
                }
            }
            if(isset($_REQUEST['DELET_USER'])) {      // Pour les Suppresions de Compte.
                if(isset($_POST['DELET_CONFIRM'])) {
                    if($_POST['DELET_CONFIRM'] == "Confirme") {       // Confirmation de la Check Box
                        if($row_SESSION["Utilisateur_Groupe"] == 2) {
                            $BDD_DELET_USER = $BDD -> query("DELETE FROM utilisateur WHERE Utilisateur_ID=" . $_REQUEST['USER_ID']) or exit(mysql_error()) ;  // Requêtes BDD
                            $BDD_DELET_USER -> execute() ;
                            $Msg_LOG = "Utilisateur Supprimé !" ;
                            echo "<meta http-equiv='refresh' content='0'>" ;
                        }
                    }
                }
            }
        }
        Footer() ;
        ?>
    </body>
</html>