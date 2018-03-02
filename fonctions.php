<?php
	
/*********************************
 * Les déclarations des fonctions
 * *******************************/

// fonction check_parameter : Check si un paramètre d'entrée est bien saisie et si il s'agit bien d'un fichier qui existe. 
	// retourne le nom du fichier si tout est OK 
	// retourne false si il y a un problème
function check_parameter($argc, $argv){
	
	// Par défaut, $argc est égal à 1 car $argc contient par défaut le nom du script. Donc on test si $argc est égal à 2 : le nom du script + un paramètre d'entré qui est le fichier data à tester
	if ($argc != 2) {
	  echo "Vous devez utiliser ce script en soumettant en entrée un fichier data. Par exemple : php main data_example_1.txt\n";
	  echo "Les fichiers data dosponibles sont : \n";
	  echo shell_exec('ls | grep data');
	  echo "\n";
	  return false;
	}

	$file_name = $argv[1]; //$argv[0] contient le nom du script (main.php). $argv[1] contient le paramètre donné à PHP lors de son exécution

	//on vérifie si le fichier. La fonction php file_exists return false if the file given in parameter does not exist
	$is_fileExist = file_exists($file_name);

	if($is_fileExist == false){
		//le nom de fichier entré n'est pas bon. On retourne 'false'
		echo "the file ${file_name} does not exist, script aborded !\n";
		return false;
	}

	echo "the file ${file_name} exist\n";

	//fin de la function, on retourne le nom du fichier
	return $file_name;
}

// fonction dataFile_to_arrays : rentre les données du fichier data $file_name dans les tableaux $grille et $trajets
function dataFile_to_arrays($file_name){

	global $grille;
	global $trajets;

	//On ouvre le fichier
	$file = fopen($file_name,"r");

	//On le lit ligne par ligne. On fait un traitement particulier pour la première ligne
	$compteur = 1;
	while(true){

		//On stoque la ligne qu'on lit dans la variable $ligne
		$ligne_actuelle = fgets($file);
		$ligne_actuelle_toArray = explode(" ",$ligne_actuelle);

		//Si la ligne actuelle ne contient pas de valeur, cela signifie qu'on est arrivé à la fin du fichier. Donc on sort de la boucle while
		if($ligne_actuelle == "") break;

		//Si on lit la première ligne
		if($compteur == 1){
			$grille["nombre_ligne"] = $ligne_actuelle_toArray[0];
			$grille["nombre_colonne"] = $ligne_actuelle_toArray[1];
			$grille["nombre_vehicules"] = $ligne_actuelle_toArray[2];
			$grille["nombre_trajets"] = $ligne_actuelle_toArray[3];
			$grille["bonus_depart_a_l_heure"] = $ligne_actuelle_toArray[4];
			$grille["heure_max"] = trim($ligne_actuelle_toArray[5]); //je fais un trim ici, car la dernière donnée de la ligne contient un retour à la ligne, et moi je veux seulement avoir la donnée, par la donnée + la caractère 'retour a la ligne'
		}
		//Si on lit les lignes après la ligne 1
		else{
			$trajets[] = array(
				"etape_depart_ligne" => $ligne_actuelle_toArray[0],
				"etape_depart_colonne" => $ligne_actuelle_toArray[1],
				"etape_finale_ligne" => $ligne_actuelle_toArray[2],
				"etape_finale_colonne" => $ligne_actuelle_toArray[3],
				"etape_depart_heurePrevue" => $ligne_actuelle_toArray[4],
				"etape_finale_heurePrevue" => trim($ligne_actuelle_toArray[5]) //je fais un trim ici, car la dernière donnée de la ligne contient un retour à la ligne, et moi je veux seulement avoir la donnée, par la donnée + la caractère 'retour a la ligne'
			);
		}
		$compteur++;
	}

	//On ferme proprement le fichier
	fclose($file);
}

// fonction trajetsToString : affiche simplement de manière verbeuse les données contenues dans le tableau $trajets
function trajetsToString($trajets){
	echo "Les données des trajets sont les suivantes : \n";
	foreach($trajets as $i => $trajet){
		$heure_fin = intVal($trajet['etape_finale_heurePrevue']) + 1;
		echo "\tTrajet numero $i\n";
		echo "\tL'étape de départ se situe au coorodonnées (${trajet['etape_depart_ligne']};${trajet['etape_depart_colonne']}) est doit doit être effectuée idéalement à l'heure ${trajet['etape_depart_heurePrevue']}\n";
		echo "\tL'étape de fin se situe au coorodonnées (${trajet['etape_finale_ligne']};${trajet['etape_finale_colonne']}) est doit être effectuée idéalement avant l'heure ${heure_fin}\n";
	}
	echo "\n";
}

// fonction grilleToString : affiche simplement de manière verbeuse les données contenues dans le tableau $trajets
function grilleToString($grille){
	echo "les données globale sont : \n";
	echo "\tLa grille est de taille : ${grille['nombre_ligne']} x ${grille['nombre_colonne']} \n";
	echo "\tLa flotte de véhicule est composée de ${grille['nombre_vehicules']} véhicules \n";
	echo "\tLe nombre de trajet à finalisé est de : ${grille['nombre_trajets']} \n";
	echo "\tSi un véhicule arrive à l'heure à l'étape de départ, un bonus de ${grille['bonus_depart_a_l_heure']} est gagné \n";
	echo "\tA l'heure ${grille['heure_max']} tous les trajets doivent avoir été fini\n\n";
}
