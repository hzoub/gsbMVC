<?php
$moisFicheActuel = date("Ym");
$ficheCR = $pdo->getVisiteurFicheCR($moisFicheActuel);
$nbFicheCR = count($ficheCR);

$ficheVA = $pdo->getVisiteurFicheVa();
$nbFicheVA = count($ficheVA);
include("vues/v_sommaire.php");
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date("d/m/Y"));
$numAnnee =substr( $mois,0,4);
$numMois =substr( $mois,4,2);
$action = $_REQUEST['action'];
switch($action){

	case 'saisirFrais':{
		if($pdo->estPremierFraisMois($idVisiteur,$mois)){
			$pdo->creeNouvellesLignesFrais($idVisiteur,$mois);

		}else{
			$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$mois);
			$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$mois);
			include("vues/v_listeFraisForfait.php");
			include("vues/v_listeFraisHorsForfait.php");
		}
		break;
	}

	case 'validerMajFraisForfait':{
		$lesFrais = $_REQUEST['lesFrais'];
		if(lesQteFraisValides($lesFrais)){
	  	 	$pdo->majFraisForfait($idVisiteur,$mois,$lesFrais);
		}
		else{
			ajouterErreur("Les valeurs des frais doivent être numériques");
			include("vues/v_erreurs.php");
		}
	  break;
	}

	case 'validerCreationFrais':{
		$dateFrais = $_REQUEST['dateFrais'];
		$libelle = $_REQUEST['libelle'];
		$montant = $_REQUEST['montant'];
		valideInfosFrais($dateFrais,$libelle,$montant);
		if (nbErreurs() != 0 ){
			include("vues/v_erreurs.php");
		}
		else{
			$pdo->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
		}
		break;
	}

	
	case 'supprimerFrais':{
		$idFrais = $_REQUEST['idFrais'];
	    $pdo->supprimerFraisHorsForfait($idFrais);
		break;
	}

	 case "validerFiche":{

	 	$mois = $_REQUEST['moisSelected']; 
		$idVisiteur = $_REQUEST['idVisSelect'];
	 	//$pdo->majEtatFicheFrais($idVisiteur,$mois,'RB');
		echo "idVisiteur -> ".$idVisiteur;
	 	echo "<br/>";
	 	echo "mois -> ".$mois;
	 	echo "<br/>";
	 	$montant = $_POST['montant'];
	 	echo "Montant -> " .$montant;
	 	//$pdo->majMontantValide($idVisiteur, $mois, $montant);
	 	
        break;
    }
	
	case 'rembourserFiche':{

		$idVisiteur = $_REQUEST['idVisSelect'];
		$mois = $_REQUEST['moisSelected'];
        $pdo->majEtatFicheFrais($idVisiteur,$mois,'RB');
        include("vues/v_RbSucess.php");
		
	 break;
	}
	

}

?>