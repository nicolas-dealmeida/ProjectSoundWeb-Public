<?php

#############################################
#											#
#		Condition de fontionnement :		#
#											#
#############################################
#
#		Intégrer dans une balise PHP :
#
#	// Déclaration des Variables PHP
#	include "../src/Function-Web.php" ; // Include des fonctions
#	$FILE_LOCAL = basename(__FILE__) ; // Définition d'une variable ayant le nom de la page.
#	$File_Name = basename(__FILE__, ".php") ; // Définition du Nom de la page, avec retrait du ".php".
#	
############################################################################################


Function Head($FILE_LOCAL) // Fonction pour le Head et les metas de page.
{

	error_reporting(E_ALL) ;							//
	ini_set('display_errors', 1) ;						//

	$File_Format = "UTF-8" ;							// Définition du Format d'encodage.
	$File_CSS = "src/global.css" ;						// Définition du Fichier CSS correspondant.
	$File_Name = basename($FILE_LOCAL, ".php") ;		// Définition du Nom de la page.
	$File_Description = "ProjectSound - $File_Name" ;	// Définition de la Description de la page.
	$File_Icon = "src/Icon.png" ;						// Définition de l'Icon de page.
	$File_Author = "ProjectSound" ;						// Définition de l'Auteur de la page.

	session_start() ; // Ouverture Session

	?>

	<head>
				<!-- SEO et Affichagse Classiques -->
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<meta charset='<?= $File_Format ?>'>
		<link rel='stylesheet' type='text/css' media='screen' href='<?= $File_CSS ?>'>
		<title>ProjectSound - <?= $File_Name ?></title>
		<meta name='description' content='<?= $File_Description ?>'>
		<link rel='shortcut icon' href='<?= $File_Icon ?>'>
		<meta name='author' content='<?= $File_Author ?>'>

				<!-- Intégration Facebook -->
		<meta property='og:title' content='ProjectSound - <?= $File_Name ?>'>
		<meta property='og:description' content='<?= $File_Description ?>'>
		<meta property='og:image' content='<?= $File_Icon ?>'>

				<!-- Intégration Twitter -->
		<meta name='twitter:title' content='ProjectSound - <?= $File_Name ?>'>
		<meta name='twitter:description' content='<?= $File_Description ?>'>
		<meta name='twitter:image' content='<?= $File_Icon ?>'>
	</head>
	<?php
}


Function Menu() // Fonction affichant le menu.
{
	// Définition de la BDD de manière général.
	$BDD_Host		=	"******" ;		// Adresse du Serveur.
	$BDD_User		=	"******" ;			// Nom User BDD.
	$BDD_Password	=	"******" ;	// MDP User BDD.
	$BDD_Name		=	"******" ;			// Nom de la BDD.

	// Définition de la BDD pour la partie du compte utilisateur.
	$BDD_Utilisateur_Table	=	"utilisateur"			; 	// Table pour l'utilisateur.
	$BDD_Utilisateur_UserID	=	"Utilisateur_ID"		;	// Tuple pour l'ID de l'utilisateur.
	$BDD_Utilisateur_Groupe	=	"Utilisateur_Groupe"	;	// Tuple pour le Groupe de l'utilisateur.
	$BDD_Utilisateur_Name	=	"Utilisateur_Name"		;	// Tuple pour le Nom de l'utilisateur.
	$BDD_Utilisateur_Mail	=	"Utilisateur_Mail"		;	// Tuple pour le Mail de l'utilisateur.
	$BDD_Utilisateur_MDP	=	"Utilisateur_MDP"		;	// Tuple pour le MDP de l'utilisateur.
	$BDD_Utilisateur_Date	=	"Utilisateur_Date"		;	// Tuple pour la date de création de l'utilisateur.

	try {
		$BDD = NEW PDO("mysql:host={$BDD_Host};dbname={$BDD_Name}",$BDD_User, $BDD_Password) ;
		$BDD -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
	}
	catch(PDOEXCEPTION $e) {
		$e -> getMessage() ; // Si Erreur, Affiche Erreur
	}

    include_once "Function-RegLog/BDD_Connect.php" ; // Include de la BDD.

	if(isset($_SESSION['User_Login'])) {	// Vérifie que Session existe.
		$User_ID = $_SESSION['User_Login'] ;

		$BDD_SELECT_Menu = $BDD -> prepare("SELECT * FROM $BDD_Utilisateur_Table WHERE $BDD_Utilisateur_UserID=:uid") ;
		$BDD_SELECT_Menu -> execute(array(":uid" => $User_ID)) ;

		$row_Menu = $BDD_SELECT_Menu -> fetch(PDO::FETCH_ASSOC) ;
	}

	?>
		<header>
			<div class='Menu-Nav'>
				<nav>
					<ul class='Menu'>
						<li class='Sous-Menu-1'><a href='/'>Accueil</a></li>
						<li class='Sous-Menu-1'><a href='News'>News</a></li>
						<li class='Sous-Menu-1 Desktop'><a href='#'>Liste des Sons</a>
							<ul class='Sous-Menu-S1 Desktop'>
								<li class='Sous-Menu-1 Desktop'><a href='Voix'>Voix</a></li>
								<li class='Sous-Menu-1 Desktop'><a href='Musiques'>Musiques</a></li>
								<li class='Sous-Menu-1 Desktop'><a href='Sons-Divers'>Sons Divers</a></li>
							</ul>
						</li>
						<li class='Média'><a href='Voix'>Voix</a></li>
						<li class='Média'><a href='Musiques'>Musiques</a></li>
						<li class='Média'><a href='Sons-Divers'>Sons Divers</a></li>
						<?php
						if(isset($row_Menu)) {
							if($row_Menu["Utilisateur_Groupe"] == 0 || $row_Menu["Utilisateur_Groupe"] == '-1') {
							?>
								<li class='Sous-Menu-1'><a href='Panel-Utilisateur'>Panel Utilisateur</a></li>
							<?php
							}
							elseif($row_Menu["Utilisateur_Groupe"] == 1) {
							?>
								<li class='Sous-Menu-1 Desktop'><a href='#'>Liste des Panels</a>
									<ul class='Sous-Menu-S1 Desktop'>
										<li class='Sous-Menu-1 Desktop'><a href='Panel-Utilisateur'>Panel Utilisateur</a></li>
										<li class='Sous-Menu-1 Desktop'><a href='Panel-Modérateur'>Panel Modérateur</a></li>
									</ul>
								</li>
								<li class='Média'><a href='Panel-Utilisateur'>Panel Utilisateur</a></li>
								<li class='Média'><a href='Panel-Modérateur'>Panel Modo</a></li>
							<?php
							}
							elseif($row_Menu["Utilisateur_Groupe"] == 2) {
								?>
									<li class='Sous-Menu-1 Desktop'><a href='#'>Liste des Panels</a>
										<ul class='Sous-Menu-S1-A Desktop'>
											<li class='Sous-Menu-1 Desktop'><a href='Panel-Utilisateur'>Panel Utilisateur</a></li>
											<li class='Sous-Menu-1 Desktop'><a href='Panel-Modérateur'>Panel Modérateur</a></li>
											<li class='Sous-Menu-1 Desktop'><a href='Panel-Admin'>Panel Administrateur</a></li>
										</ul>
									</li>
									<li class='Sous-Menu-1 Média'><a href='Panel-Utilisateur'>Panel Utilisateur</a></li>
									<li class='Sous-Menu-1 Média'><a href='Panel-Modérateur'>Panel Modo</a></li>
									<li class='Sous-Menu-1 Média'><a href='Panel-Admin'>Panel Admin</a></li>
								<?php
							}
						}
						?>
						<li class='Sous-Menu-1'><a href='Liste-Utilisateur'>Liste des Utilisateurs</a></li>
						<?php
						if(isset($_SESSION['User_Login'])) {
							?>
								<li class='Sous-Menu-1'><a href='Compte'><h2><?php echo $row_Menu["Utilisateur_Name"] ?></h2></a></li>
							<?php
						}
						else {
							?>
								<li class='Sous-Menu-1'><a href='Compte'>Authentification</a></li>
							<?php
						}
						?>
					</ul>
				</nav>
			</div>
		</header>
	<?php
}


Function Footer() // Fonction pour le Footer.
{
	?>
	<footer>
		
	</footer>
	<?php
}


?>