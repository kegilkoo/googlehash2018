<?php

require_once('fonctions.php'); 

// '$grille' est un tableau qui contient les données principales du probleme : les dimensions du terrain, le nbr de vehicule, le nbr de trajets à affectuer, le bonus gagné lorsqu'un véhicule arrive à l'étape de départ à l'heure, et l'heure max où tous les trajets doivent avoir été effectués 
$grille = array();

// '$trajets' est un tableau qui contient les données de tous les trajets à effectuer : les coordonnées du lieu de dépat et d'arrivée, l'heure idéale de départ et l'heure max idéale d'arrivée
$trajets = array();


/******************************
 * Le coeur principal du script 
 ******************************/

	// On check si le paramètre d'entrée au script est entré et si il s'agit d'un fichier qui existe bien
	$file_name = check_parameter($argc, $argv);
	if($file_name == false) exit;

	dataFile_to_arrays($file_name);

	// On affiche les informations stockées dans les tableaux grille et trajets créés à partir du fichier data
	grilleToString($grille);
	trajetsToString($trajets);

	// La suite de l'algorithme ici, à vous de jouer :) 

?> 
