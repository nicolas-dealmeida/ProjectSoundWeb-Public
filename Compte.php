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
        <!-- Unlogin et Salutation -->
        <?php
        if(isset($_SESSION['User_Login'])) {	// Vérifie que Session existe.

            $ID = $_SESSION['User_Login'] ;

            $BDD_Select_Auth = $BDD -> prepare("SELECT * FROM $BDD_Utilisateur_Table WHERE $BDD_Utilisateur_UserID=:uid") ;
            $BDD_Select_Auth -> execute(array(":uid" => $ID)) ;

            $row=$BDD_Select_Auth -> fetch(PDO::FETCH_ASSOC) ;

            if(isset($_SESSION['User_Login'])) {
            ?>
            <div class="Div1">
                <h2>Salutation, 
            <?php
                echo $row[$BDD_Utilisateur_Name] ;
            }
            ?>
                </h2>
                <p><a href="src/Function-RegLog/logout.php">Se déconnecter</a></p>
            </div>
            <div class="Div1">
                <form method="post">
                    <h3>Supprimer son compte</h3>
                    <input type="submit"    name="Button_DELET_USER"    value="Supprimer son compte">
                    <input type="checkbox"  name="DELET_CONFIRM"        value="Confirme"> <label>❌</label>
                </form>
            </div>
            <div class="Div1">
                <form method="post">
                    <h3>Modifier sa visibilité</h3>
                    <input type="submit"    name="Button_ON_Visibility"     value="Être Visible">
                    <input type="submit"    name="Button_OFF_Visibility"    value="Être Invisible">
                </form>
                <p>Visibilité présente sur la liste des Utilisateurs du site, ainsi que sur les Soundboards Uploads.</p>
            </div>
            <?php
            // Code Delet User
            if(isset($_REQUEST['Button_DELET_USER'])) {         // Pour les Suppresions de Compte.
                if(isset($_POST['DELET_CONFIRM'])) {
                    if($_POST['DELET_CONFIRM'] == "Confirme") { // Confirmation de la Check Box
                        $BDD_DELET_USER = $BDD -> query("DELETE FROM utilisateur WHERE Utilisateur_ID=" . $_SESSION['User_Login']) or exit(mysql_error()) ;  // Requêtes BDD
                        $BDD_DELET_USER -> execute() ;
                        $Msg_LOG = "Votre compte est bien supprimé !" ;
                        session_destroy() ; // Destruction Session pour ne pas avoir de Session "vide"  
                        echo "<meta http-equiv='refresh' content='0'>" ;
                    }
                }
            }
            // Code Mise à jours Visibilitité : Visible
            if(isset($_REQUEST['Button_ON_Visibility'])) {
                $BDD_UPDATE_ON_Visibility = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Visibility` = '1' WHERE `utilisateur`.`Utilisateur_ID`=" . $_SESSION['User_Login']) or exit(mysql_error()) ;  // Requêtes BDD
                $BDD_UPDATE_ON_Visibility -> execute() ;
                $Msg_LOG = "Votre compte est maintenant visible !" ;
                echo "<meta http-equiv='refresh' content='0'>" ;
            }
            // Code Mise à jours Visibilitité : Invisible
            if(isset($_REQUEST['Button_OFF_Visibility'])) {
                $BDD_UPDATE_OFF_Visibility = $BDD -> query("UPDATE `utilisateur` SET `Utilisateur_Visibility` = '0' WHERE `utilisateur`.`Utilisateur_ID`=" . $_SESSION['User_Login']) or exit(mysql_error()) ;  // Requêtes BDD
                $BDD_UPDATE_OFF_Visibility -> execute() ;
                $Msg_LOG = "Votre compte est maintenant invisible !" ;
                echo "<meta http-equiv='refresh' content='0'>" ;
            }
        }
        // Page Connection et Inscription
        else {
        ?>
            <div> 
                <div class="Div1"> 
                    <h2>Connexion :</h2>
                    <?php
                    // Back
                    if(isset($_REQUEST['Button_Login'])) {	// Le nom du Bouton est "Button_Login".

                        $Utilisateur_Name   = strip_tags($_REQUEST["INPUT_LOG_Pseudo_Mail"]) ;	// Définition de la Zone de Texte correspondante.
                        $Utilisateur_Mail   = strip_tags($_REQUEST["INPUT_LOG_Pseudo_Mail"]) ;	// Définition de la Zone de Texte correspondante.
                        $Utilisateur_MDP    = strip_tags($_REQUEST["INPUT_LOG_MDP"]) ;			    // Définition de la Zone de Texte correspondante.

                        if(empty($Utilisateur_Name)) {						
                            $Msg_LOG_Erreur[] = "Entrez un Identifiant ou Mail." ;				// Vérifie que Non Vide.
                        }
                        else if(empty($Utilisateur_Mail)) {
                            $Msg_LOG_Erreur[] = "Entrez un Identifiant ou Mail." ;				// Vérifie que Non Vide.
                        }
                        else if(empty($Utilisateur_MDP)) {
                            $Msg_LOG_Erreur[] = "Entrez un Mots de Passe." ;						// Vérifie que Non Vide.
                        }
                        else {
                            try {
                                $BDD_Select_Auth = $BDD -> prepare("SELECT * FROM $BDD_Utilisateur_Table WHERE $BDD_Utilisateur_Name=:uname OR $BDD_Utilisateur_Mail=:uemail") ;	// Requête SQL.
                                $BDD_Select_Auth -> execute(array(':uname' => $Utilisateur_Name, ':uemail' => $Utilisateur_Mail)) ;	// Query avec mes Variables.
                                $row=$BDD_Select_Auth -> fetch(PDO::FETCH_ASSOC) ;

                                if($BDD_Select_Auth->rowCount() > 0) {
                                    if($Utilisateur_Name == $row["$BDD_Utilisateur_Name"] OR $Utilisateur_Mail == $row["$BDD_Utilisateur_Mail"]) { // Vérifie que $Utilisateur_Name est égal à un Pseudo ou Mail présent dans la BDD.
                                        if(password_verify($Utilisateur_MDP, $row["$BDD_Utilisateur_MDP"])) { // Vérifie que le MDP est bien le bon.
                                            $_SESSION["User_Login"] = $row["$BDD_Utilisateur_UserID"] ; // Définition de la Session.
                                            $loginMsg = "Vous êtes maintenant connecté !" ;
                                            echo "<meta http-equiv='refresh' content='0'>" ;
                                        }
                                        else {
                                            $Msg_LOG_Erreur[]   = "Mot de Passe Incorrect." ;
                                        }
                                    }
                                    else {
                                        $Msg_LOG_Erreur[]       = "Identifiant ou Mail Incorrect" ;
                                    }
                                }
                                else {
                                    $Msg_LOG_Erreur[]           = "Identifiant ou Mail Incorrect" ;
                                }
                            }
                            catch(PDOException $e) {
                                $e -> getMessage() ;
                            }		
                        }
                    }
                    // Affichage Erreur
                    if(isset($Msg_LOG_Erreur)) {
                        foreach($Msg_LOG_Erreur as $LOG_Erreur) {
                        ?>
                            <div>
                                <p class="Rouge">Erreur : <?php echo $LOG_Erreur ?></p>
                            </div>
                        <?php
                        }
                    }
                    if(isset($loginMsg)) {
                    ?>
                        <div>
                            <p><?php echo $loginMsg ?></p>
                        </div>
                    <?php
                    }
                    ?>
                    <form method="post"><!-- Formulaire Login -->
                        <div>
                            <label>Pseudo ou Mail</label>
                            <div>
                                <input type="text" name="INPUT_LOG_Pseudo_Mail" placeholder="Votre Pseudo ou Mail." />
                            </div>
                        </div>
                        <div>
                            <label>Mot de Passe</label>
                            <div>
                                <input type="password" name="INPUT_LOG_MDP" placeholder="Votre Mot de Passe." />
                            </div>
                        </div>
                        <div>
                            <div>
                                <input type="submit" name="Button_Login" value="Login">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="Div1">
                    <h2>Inscription :</h2>
                    <?php
                    // Back
                    if(isset($_REQUEST['Button_Register'])) {	// Le nom du bouton est "Button_Register"

                        $Utilisateur_Name	    = strip_tags($_REQUEST['INPUT_REG_Pseudo']) ;	    // Définition de la Zone de Texte correspondante.
                        $Utilisateur_Mail	    = strip_tags($_REQUEST['INPUT_REG_Mail']) ;		    // Définition de la Zone de Texte correspondante.
                        $Utilisateur_MDP	    = strip_tags($_REQUEST['INPUT_REG_MDP']) ;		    // Définition de la Zone de Texte correspondante.
                        $Utilisateur_MDP_VERIF	= strip_tags($_REQUEST['INPUT_LOG_MDP_VERIF']) ;    // Définition de la Zone de Texte correspondante.

                        if(empty($Utilisateur_Name)) {
                            $Msg_REG_Erreur[] = "Entrez un Pseudo." ;						// Vérifie que Non Vide.
                        }
                        else if(empty($Utilisateur_Mail)) {
                            $Msg_REG_Erreur[] = "Entrez un Mail." ;							// Vérifie que Non Vide.
                        }
                        else if(!filter_var($Utilisateur_Mail, FILTER_VALIDATE_EMAIL)) {
                            $Msg_REG_Erreur[] = "Entrez un Mail valide." ;					// Vérifie que Mail Valide.
                        }
                        else if(empty($Utilisateur_MDP)) {
                            $Msg_REG_Erreur[] = "Entrez un Mot de Passe." ;					// Vérifie que Non Vide.
                        }
                        else if($Utilisateur_MDP !== $Utilisateur_MDP_VERIF) {
                            $Msg_REG_Erreur[] = "Le mots de passe ne correspond pas." ;		// Vérifie que les deux cases sont identiques.
                        }
                        else if(strlen($Utilisateur_MDP) < 7) {
                            $Msg_REG_Erreur[] = "Le Mot de Passe doit avoir 7 caractères au minimums." ;    // Vérifie que le MPD a +7 caractères.
                        }
                        else {	
                            try {	
                                $BDD_Select_Auth = $BDD -> prepare("SELECT $BDD_Utilisateur_Name, $BDD_Utilisateur_Mail FROM $BDD_Utilisateur_Table WHERE $BDD_Utilisateur_Name=:uname OR $BDD_Utilisateur_Mail=:uemail") ;

                                $BDD_Select_Auth -> execute(array(':uname' => $Utilisateur_Name, ':uemail' => $Utilisateur_Mail)) ;
                                $row=$BDD_Select_Auth -> fetch(PDO::FETCH_ASSOC) ;	

                                if($row["$BDD_Utilisateur_Name"] == $Utilisateur_Name) {
                                    $Msg_REG_Erreur[] = "Ce Pseudo est déja pris." ;	// Vérifie non existant.
                                }
                                else if($row["$BDD_Utilisateur_Mail"] == $Utilisateur_Mail) {
                                    $Msg_REG_Erreur[] = "Ce Mail est déja attribué." ;	// Vérifie non existant.
                                }
                                else if(!isset($Msg_REG_Erreur)) { // Si Pas d'Erreur, alors se connecte.

                                    $Utilisateur_MDP_HASH = password_hash($Utilisateur_MDP, PASSWORD_DEFAULT) ; // Hash le MDP et le resort en Crypté

                                    $BDD_Insert_Auth = $BDD -> prepare("INSERT INTO $BDD_Utilisateur_Table ($BDD_Utilisateur_Name,$BDD_Utilisateur_Mail,$BDD_Utilisateur_MDP) VALUES (:uname,:uemail,:upassword)") ;			

                                    if($BDD_Insert_Auth -> execute(array( ':uname' => $Utilisateur_Name, ':uemail' => $Utilisateur_Mail, ':upassword' => $Utilisateur_MDP_HASH))) {

                                        $registerMsg="Inscription faites ! Vous pouvez maintenant vous connecter." ; // Insert Réussit.
                                    }
                                }
                            }
                            catch(PDOException $e)
                            {
                                echo $e -> getMessage();
                            }
                        }
                    }
                    // Affichage Erreur
                    if(isset($Msg_REG_Erreur)) {
                        foreach($Msg_REG_Erreur as $REG_Erreur) {
                        ?>
                            <div>
                                <p class="Rouge">Erreur : <?php echo $REG_Erreur?></p>
                            </div>
                        <?php
                        }
                    }
                    if(isset($registerMsg)) {
                    ?>
                        <div>
                            <p class="Vert"><?php echo $registerMsg ?></p>
                        </div>
                    <?php
                    }
                    ?>
                    <form method="post"><!-- Formulaire Register -->
                        <div>
                            <label>Pseudo</label>
                            <div>
                                <input type="text" name="INPUT_REG_Pseudo" placeholder="Votre Pseudo." />
                            </div>
                        </div>
                        <div>
                            <label>Mail</label>
                            <div>
                                <input type="text" name="INPUT_REG_Mail" placeholder="Votre Mail." />
                            </div>
                        </div>
                        <div>
                            <label>Mot de Passe</label>
                            <div>
                                <input type="password" name="INPUT_REG_MDP" placeholder="Votre Mot de Passe." />
                            </div>
                            <div>
                                <input type="password" name="INPUT_LOG_MDP_VERIF" placeholder="Confirmer Mot de Passe." />
                            </div>
                        </div>
                        <div>
                            <input type="submit"  name="Button_Sound_Send" value="SEND">
                        </div>
                    </form>
                </div>
            </div>
        <?php
        }
        ?>
        <?php
            Footer() ;
        ?>
    </body>
</html>