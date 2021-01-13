<?php


Function BDD_Logout() // Fonction pour se déconnecter.
{
    session_start() ;

    header("location:../../Compte") ; // Revioent de -1 dans l'historique.

    session_destroy() ;
}


BDD_Logout() ;


?>