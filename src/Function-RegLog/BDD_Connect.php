<?php
	// Fonction pour se connecter à la  BDD.

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
?>