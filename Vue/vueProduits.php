<?php
	@session_start();
	if (isset($_SESSION['infoUtilisateur']))	#Vérification d'une session existante
	{
?>
<!DOCTYPE html>
<html>
<head>
	<title>AladIN</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Autre/Style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script type="text/javascript" src="Autre/fonction.js"></script>
  	<script type="text/javascript">
<?php
	$tvaLib = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "");
	$tvaPourcent = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "");
	$y = 0;
	$z = 0;
	while ($resultat = $produits->fetch(PDO::FETCH_ASSOC))	#Récupération des libellés et des pourcentages de TVA  
	{
		if (!in_array($resultat['tva_libelle'], $tvaLib)) 
		{
			$tvaLib[$y] = $resultat['tva_libelle'];
			$y++;
		}
		if (!in_array($resultat['tva_pourcentage'], $tvaPourcent)) 
		{
			$tvaPourcent[$z] = $resultat['tva_pourcentage'];
			$z++;
		}
	}
	$produits->closeCursor();
	$produits->execute();
	$lstProduits = [["referenceProd" => "",
					"libelleProd" => "",
					"descriptionProd" => "",
					"familleProd" => "",
					"prixProd" => "",
					"tvaLibelleProd" => "",
					"tvaProd" => "",
					"actifProd" => "",
					"rechLibelleProd" => "",
					"rechFamilleProd" => "",
					"fournisseurProd" => ""
					]];
	$categorie = array();
	$FJ = array();
	$lstFournisseurs = array();
	$saveF = [];
	$i = 0;
	$y = 0;
	$z = 0;
	
	while ($resultatP = $produits->fetch(PDO::FETCH_ASSOC))	#Récupération des informations des produits
	{
		$y = 0;
		$lstProduits[$i]["referenceProd"] = $resultatP['pro_reference'];
		$lstProduits[$i]["libelleProd"] = $resultatP['pro_libelle'];
		$lstProduits[$i]["descriptionProd"] = str_replace(CHR(13),"*",str_replace(CHR(10),"*",str_replace(CHR(13).CHR(10),"*",$resultatP['pro_description'])));
		$lstProduits[$i]["familleProd"] = $resultatP['fam_libelle'];
		$lstProduits[$i]["prixProd"] = $resultatP['pro_pxvente_ht'];
		$lstProduits[$i]["tvaLibelleProd"] = $resultatP['tva_libelle'];
		$lstProduits[$i]["tvaProd"] = $resultatP['tva_pourcentage'];
		$lstProduits[$i]["actifProd"] = $resultatP['pro_iactif'];
		$lstProduits[$i]["rechLibelleProd"] = $resultatP['pro_lib_rech'];
		$lstProduits[$i]["rechFamilleProd"] = $resultatP['fam_lib_rech'];
		$i++;
	}
	$i = -1;
	while ($resultatF = $fournisseurs->fetch(PDO::FETCH_ASSOC)) 	#Récupération des informations des fournisseurs
	{
		if (!in_array($resultatF['ent_raison_sociale'], $saveF))
		{
			$i++;
		}
		if (!in_array($resultatF['ent_raison_sociale'], $saveF))
		{
			$lstFournisseurs[$i]["raisonSociale"] = $resultatF['ent_raison_sociale'];
			if ($resultatF['pro_lib_rech'] != null)
			{
				$lstFournisseurs[$i]["produit"][sizeof($lstFournisseurs[$i]["produit"])] = $resultatF['pro_lib_rech'];
			}
			$lstFournisseurs[$i]["codeEntite"] = $resultatF['ent_code_entite'];
			$lstFournisseurs[$i]["contact"] = $resultatF['ent_lib_contact'];
			$lstFournisseurs[$i]["siret"] = $resultatF['ent_siret'];
			$lstFournisseurs[$i]["abreviation"] = $resultatF['ent_abreviation'];
			$lstFournisseurs[$i]["clefPhonetique"] = $resultatF['ent_clefphonetique'];
			$lstFournisseurs[$i]["categorie"] = $resultatF['cat_libelle'];
			$lstFournisseurs[$i]["adresse"] = str_replace(CHR(13).CHR(10),"*", $resultatF['adr_complete']);
			$lstFournisseurs[$i]["commentaire"] = str_replace(CHR(13).CHR(10),"*", $resultatF['fou_commentaire']);
			$lstFournisseurs[$i]["formeJuridique"] = $resultatF['foj_libelle'];
			$lstFournisseurs[$i]["email"] = $resultatF['lstm_mail'];
			$lstFournisseurs[$i]["rechCategorie"] = $resultatF['cat_lib_rech'];
			$lstFournisseurs[$i]["rechFormeJuridique"] = $resultatF['foj_lib_rech'];
			$lstFournisseurs[$i]["rechRaisonSociale"] = $resultatF['ent_raison_rech'];
			$lstFournisseurs[$i]["telephoneFixe"] = "";
			$lstFournisseurs[$i]["fax"] = "";
			$lstFournisseurs[$i]["mobile"] = "";
			switch ($resultatF['tpi_indicateur'])
			{
				case 1:
					$lstFournisseurs[$i]["telephoneFixe"] = $resultatF['lstt_numero'];
					break;
				case 2:
					$lstFournisseurs[$i]["fax"] = $resultatF['lstt_numero'];
					break;
				case 3:
					$lstFournisseurs[$i]["mobile"] = $resultatF['lstt_numero'];
					break;
			}
			$saveF[] = $resultatF['ent_raison_sociale'];
		}
		else
		{
			$bool = true;
			for ($j = 0; $j <= sizeof($lstFournisseurs[$i]['produit'])-1; $j++)
			{
				if ($resultatF['pro_lib_rech'] == $lstFournisseurs[$i]['produit'][$j])
				{
					$bool = false;
				}
			}
			if($bool)
			{
				$lstFournisseurs[$i]["produit"][] = $resultatF['pro_lib_rech'];
			}
			switch ($resultatF['tpi_indicateur'])
			{
				case 1:
					$lstFournisseurs[$i]["telephoneFixe"] = $resultatF['lstt_numero'];
					break;
				case 2:
					$lstFournisseurs[$i]["fax"] = $resultatF['lstt_numero'];
					break;
				case 3:
					$lstFournisseurs[$i]["mobile"] = $resultatF['lstt_numero'];
					break;
			}
		}
	}

	$i = 0;
	$x = 0;
	$AfficheProduits = "var lstProduits = {\n";
	foreach ($lstProduits as $key => $value)  #Conversion de la liste PHP de produit et de fournisseur en une liste produit JavaScript
	{
		$z = 0;
		$AfficheProduits.= $i.":{".'referenceProd:"'.$value["referenceProd"].'", libelleProd: "'.$value["libelleProd"].'", descriptionProd: "'.$value["descriptionProd"].'", familleProd: "'.$value['familleProd'].'", prixProd: "'.$value["prixProd"].'", tvaLibelleProd: "'.$value['tvaLibelleProd'].'", tvaProd: "'.$value["tvaProd"].'", actifProd: "'.$value['actifProd'].'", rechLibelleProd: "'.$value['rechLibelleProd'].'", rechFamilleProd: "'.$value['rechFamilleProd'].'",';
		for ($y = 0; $y <= sizeof($lstFournisseurs)-1; $y++)
		{
			for ($k = 0; $k <= sizeof($lstFournisseurs[$y]['produit'])-1; $k++)
			{
				if ($lstFournisseurs[$y]['produit'][$k] == $value['rechLibelleProd'])
				{
					if ($z == 0) 
					{
						$AfficheProduits.= 'fournisseurProd: {';
						
					}
					$x++;
					$AfficheProduits.= $z.':{raisonSociale: "'.$lstFournisseurs[$y]['raisonSociale'].'", produitFournisseur: "'.$value['rechLibelleProd'].'", codeEntite: "'.$lstFournisseurs[$y]['codeEntite'].'", contact: "'.$lstFournisseurs[$y]['contact'].'", siret: "'.$lstFournisseurs[$y]['siret'].'", abreviation: "'.$lstFournisseurs[$y]['abreviation'].'", clefPhonetique: "'.$lstFournisseurs[$y]['clefPhonetique'].'", categorie: "'.$lstFournisseurs[$y]['categorie'].'", adresse: "'.$lstFournisseurs[$y]['adresse'].'", commentaire: "'.$lstFournisseurs[$y]['commentaire'].'", formeJuridique: "'.$lstFournisseurs[$y]['formeJuridique'].'", email: "'.$lstFournisseurs[$y]['email'].'", rechCategorie: "'.$lstFournisseurs[$y]['rechCategorie'].'", rechFormeJuridique: "'.$lstFournisseurs[$y]['rechFormeJuridique'].'", rechRaisonSociale: "'.$lstFournisseurs[$y]['rechRaisonSociale'].'", telephoneFixe: "'.$lstFournisseurs[$y]['telephoneFixe'].'", mobile: "'.$lstFournisseurs[$y]['mobile'].'", fax : "'.$lstFournisseurs[$y]['fax'].'"},';
					$z++;
				}
			}
		}
		if ($z != 0) 
		{
			$x = 0;
			$AfficheProduits[strlen($AfficheProduits)-1] = "}";
		}
		$AfficheProduits.="},\n";
		$i++;
	}
	
	$fournisseurs->closeCursor();
	$fournisseurs->execute();
	while ($resultat = $fournisseurs->fetch(PDO::FETCH_ASSOC)) 	
	{
		if (!in_array($resultat['cat_libelle'], $categorie)) 	#Récupération des catégories de fournisseur
		{
			$categorie[] = $resultat['cat_libelle'];
		}
		if (!in_array($resultat['foj_libelle'], $FJ)) 	#Récupération des forme juridique de fournisseur
		{
			$FJ[] = $resultat['foj_libelle'];
		}
	}
	$y = 0;
	$z = 0;
	while ($clients->fetch(PDO::FETCH_ASSOC))	#Intialisation de la liste des clients
	{
		$lstClients[] = ["T" =>"",
						 "code" => "",
                   		 "TypeCli" => "",
                   		 "Categorie" => "",
                   		 "typeRechCliNom" => "",
                   		 "raisonSociale" => "",
                   		 "typeRechCliPrenom" => "",
                   		 "Abreviation" => "",
                   		 "Contact" => "",
                   		 "Entite" => "",
                   		 "prenom" => "",
                   		 "rechNom" => "",
                   		 "rechPrenom" => "",
                   		 "Commentaires" => "",
                   		 "actifPhonetique" => "",
                   		 "SIRET" => "",
                   		 "FOJ" => "",
                   		 "Adresse" => array("adressePrincipale"=>"","adresseSecondaire"=>"","adresseBureau"=>"","adresseCourrier"=>""),
                   		 "Ville" => "",
                   		 "Site Internet" => "",
                   		 "tel" => array("telephoneSociete"=>"","telephoneDomicile"=>"","mobileProfessionnel"=>"","mobilePersonnel"=>"","mobileSociete"=>"","telephoneBureau"=>"","faxDomicile"=>"","faxSociete"=>"","faxBureau"=>""),
                   		 "mail" => "",
                 		];
	}
    $saveTM = array();
    $Devis = array();
    $Facture = array();
    $categorieCli = array();
    $FJCli = array();
    $i = 0;
    
	$clients->closeCursor();
	$clients->execute();

    while ($resultatC = $clients->fetch(PDO::FETCH_ASSOC))	#Récupération des informations des clients
    {
        $lstClients[$i]["code"] = $resultatC['per_code_personne'];
        $lstClients[$i]["Categorie"] = $resultatC['cat_libelle'];
        $lstClients[$i]["typeRechCliNom"] = str_replace('"',"'",$resultatC['per_nomfamille']);
        $lstClients[$i]["actifPhonetique"] = $resultatC['cat_iactif'];
        $lstClients[$i]["T"] = $resultatC['per_titre'];
        $lstClients[$i]["rechNom"] = str_replace('"',"'",$resultatC['rech_nom_maj']);
        $lstClients[$i]["rechPrenom"] = str_replace('"',"'",$resultatC['rech_prenom_maj']);
        $lstClients[$i]["prenom"] = $resultatC['per_prenoms'];
        $lstClients[$i]["SIRET"] = $resultatC['ent_siret'];
        $lstClients[$i]["Abreviation"] = $resultatC['ent_abreviation'];
        $lstClients[$i]["Contact"] = $resultatC['ent_lib_contact'];
        $lstClients[$i]["Commentaires"] =str_replace(CHR(13).CHR(10),"*",$resultatC['cli_commentaires']);
        if ($resultatC["per_titre"] >3)
        {
        	$lstClients[$i]["FOJ"] = $resultatC['foj_libelle'];
        }
        else
        {
        	$lstClients[$i]["FOJ"] = $resultatC['per_titre'];
        }
        if (!in_array($resultatC['cat_libelle'], $categorieCli)) 	#Récupération des catégories de client
		{
			$categorieCli[] = $resultatC['cat_libelle'];
		}
        $i++;
    }
	$SaveC = [];
	for ($j=0; $j<=sizeof($lstClients)-1; $j++)
	{
		$SaveM = [];
		while ($resultatTM = $telMails->fetch(PDO::FETCH_ASSOC)) 	#Récupération des mails et différents numéros de téléphones du client
		{
			if ($resultatTM["ent_code_entite"] == $lstClients[$j]["code"])
			{
				if (!in_array($resultatTM['lstm_mail'], $SaveM))
				{
					$lstClients[$j]["mail"] = $resultatTM['lstm_mail'];
					$SaveM[] = $resultatTM['lstm_mail'];
				}				
				if (!in_array($resultatTM["ent_code_entite"], $SaveC))
				{
					$i = 0;
					switch ($resultatTM["lstt_idco_tp_indicateur"]) 
					{
						case '1':
							$lstClients[$j]["tel"]["telephoneDomicile"]=$resultatTM["lstt_numero"];
							break;
						case '2':
							$lstClients[$j]["tel"]["telephoneBureau"]=$resultatTM["lstt_numero"];
							break;
						case '3':
							$lstClients[$j]["tel"]["faxDomicile"]=$resultatTM["lstt_numero"];
							break;
						case '4':
							$lstClients[$j]["tel"]["faxBureau"]=$resultatTM["lstt_numero"];
							break;
						case '5':
							$lstClients[$j]["tel"]["mobilePersonnel"]=$resultatTM["lstt_numero"];
							break;
						case '6':
							$lstClients[$j]["tel"]["mobileProfessionnel"]=$resultatTM["lstt_numero"];
							break;
						case '7':
							$lstClients[$j]["tel"]["faxSociete"]=$resultatTM["lstt_numero"];
							break;
						case '8':
							$lstClients[$j]["tel"]["telephoneSociete"]=$resultatTM["lstt_numero"];
							break;
						case '9':
							$lstClients[$j]["tel"]["mobileSociete"]=$resultatTM["lstt_numero"];
							break;
					}
					$SaveC[] = $resultatTM["ent_code_entite"];
				}
				else
				{
					$i = sizeof($lstClients[$j]["tel"])-1;
					switch ($resultatTM["lstt_idco_tp_indicateur"]) 
					{
						case '1':
							$lstClients[$j]["tel"]["telephoneDomicile"]=$resultatTM["lstt_numero"];
							break;
						case '2':
							$lstClients[$j]["tel"]["telephoneBureau"]=$resultatTM["lstt_numero"];
							break;
						case '3':
							$lstClients[$j]["tel"]["faxDomicile"]=$resultatTM["lstt_numero"];
							break;
						case '4':
							$lstClients[$j]["tel"]["faxBureau"]=$resultatTM["lstt_numero"];
							break;
						case '5':
							$lstClients[$j]["tel"]["mobilePersonnel"]=$resultatTM["lstt_numero"];
							break;
						case '6':
							$lstClients[$j]["tel"]["mobileProfessionnel"]=$resultatTM["lstt_numero"];
							break;
						case '7':
							$lstClients[$j]["tel"]["faxSociete"]=$resultatTM["lstt_numero"];
							break;
						case '8':
							$lstClients[$j]["tel"]["telephoneSociete"]=$resultatTM["lstt_numero"];
							break;
						case '9':
							$lstClients[$j]["tel"]["mobileSociete"]=$resultatTM["lstt_numero"];
							break;
					}
				}
			}
		}
		$telMails->closeCursor();
		$telMails->execute();
	}
	while ($resultatVille = $adresses->fetch(PDO::FETCH_ASSOC)) 	#Récupération des informations de coordonnée du client
	{
		for ($indexClient=0; $indexClient<=sizeof($lstClients)-1; $indexClient++)
		{
			if ($resultatVille["ent_code_entite"] == $lstClients[$indexClient]["code"])
			{
				switch($resultatVille["lsta_idco_tp_indicateur"])
				{
					case "12":
						$lstClients[$indexClient]["Adresse"]["adressePrincipale"] = str_replace(CHR(13).CHR(10),"*",$resultatVille["adr_complete"]);
						break;
					case "13":
						$lstClients[$indexClient]["Adresse"]["adresseSecondaire"] = str_replace(CHR(13).CHR(10),"*",$resultatVille["adr_complete"]);
						break;
					case "14":
						$lstClients[$indexClient]["Adresse"]["adresseBureau"] = str_replace(CHR(13).CHR(10),"*",$resultatVille["adr_complete"]);
						break;
					case "15":
						$lstClients[$indexClient]["Adresse"]["adresseCourrier"] = str_replace(CHR(13).CHR(10),"*",$resultatVille["adr_complete"]);
						break;
				}
				$lstClients[$indexClient]["Ville"] = $resultatVille["com_nom_min"];
				$lstClients[$indexClient]["Site Internet"] = $resultatVille["co_adrinternet"];
			}
		}	
	}	
		

	$i = 0;
	$x = 0;
	$AfficheClients = "var lstClients = {\n";
	foreach ($lstClients as $key => $value) 	#Conversion de la liste PHP client en liste JavaScript
	{
		$AfficheClients.= $i.":{".'T: "'.$value["T"].'", code:"'.$value["code"].'", Categorie: "'.$value["Categorie"].'", typeRechCliNom: "'.$value["typeRechCliNom"].'", prenom: "'.$value["prenom"].'", actifPhonetique: "'.$value['actifPhonetique'].'", SIRET: "'.$value["SIRET"].'", FOJ: "'.$value['FOJ'].'", ville: "'.$value["Ville"].'", rechNom: "'.$value['rechNom'].'", rechPrenom: "'.$value['rechPrenom'].'", abreviation: "'.$value["Abreviation"].'", contact: "'.$value["Contact"].'", website: "'.$value["Site Internet"].'", commentaires: "'.$value["Commentaires"].'",mail:"'.$value['mail'].'",  tel: {telephoneDomicile: "'.$value['tel']["telephoneDomicile"].'", telephoneBureau: "'.$value['tel']["telephoneBureau"].'", faxDomicile: "'.$value['tel']["faxDomicile"].'", faxBureau: "'.$value['tel']["faxBureau"].'", mobilePersonnel: "'.$value['tel']["mobilePersonnel"].'", mobileProfessionnel: "'.$value['tel']["mobileProfessionnel"].'", faxSociete: "'.$value['tel']["faxSociete"].'", telephoneSociete: "'.$value['tel']["telephoneSociete"].'", mobileSociete: "'.$value['tel']["mobileSociete"]. '"}, adresseBureau: "'.$value["Adresse"]["adresseBureau"].'", adressePrincipale: "'.$value["Adresse"]["adressePrincipale"].'", adresseSecondaire: "'.$value["Adresse"]["adresseSecondaire"].'", adresseCourrier: "'.$value["Adresse"]["adresseCourrier"].'"},';
		$AfficheClients.="\n";
		$i++;
	}
	$i = 0;
	$AfficheClients[strlen($AfficheClients)-2] = "}";
	$AfficheClients[strlen($AfficheClients)-1] =";";
	$AfficheProduits[strlen($AfficheProduits)-2] = "}";
	echo $AfficheProduits.";\n";
	echo $AfficheClients."\n\n\n";
?>
	$(document).ready(function(){ 	
		$("body").keydown(function(event){		//Initialisation de Raccource clavier
			if (event.keyCode == 27)	// 27 => Touche Echap. (Fermeture de fenêtre)
			{
				switch ("visible")
				{
					case (document.getElementById('rechCli').style.visibility):
						document.getElementById('transparenceProd').style.visibility = 'hidden';
						document.getElementById('rechCli').style.visibility = 'hidden';
						break;
					case (document.getElementById('rechFamilleProd').style.visibility):
						document.getElementById('transparenceProd').style.visibility = 'hidden';
						document.getElementById('rechFamilleProd').style.visibility = 'hidden';
						break;
					case (document.getElementById('rechCategorieFourn').style.visibility):
						document.getElementById('transparenceFourn').style.visibility = 'hidden';
						document.getElementById('rechCategorieFourn').style.visibility = 'hidden';
						break;
					case (document.getElementById('rechFormeJuridiqueFourn').style.visibility):
						document.getElementById('transparenceFourn').style.visibility = 'hidden';
						document.getElementById('rechFormeJuridiqueFourn').style.visibility = 'hidden';
						break;
					case (document.getElementById('infoProd').style.visibility):
						document.getElementById('transparence').style.visibility = 'hidden';
						document.getElementById('infoProd').style.visibility = 'hidden';
						break;
					case (document.getElementById('infoFourn').style.visibility):
						document.getElementById('transparence').style.visibility = 'hidden';
						document.getElementById('infoFourn').style.visibility = 'hidden';
						break;
				}
			}
		});
		$("#containerProd1 input").keydown(function(e){	//Initialisation de Raccourci clavier
			if (e.keyCode == 13) // 13 => Touche Entrée (Lance une recherche)
			{
				rechercher();
			}
		});

		$("#btnSave").mouseenter(function()
		{
			$(this).css("backgroundColor", "#11BB44");
		});
		$("#btnSave").mouseleave(function()
		{
			$(this).css("backgroundColor", "#22CC55");
		});
	})
	var lstChangements = "";
	var lstSaveCurrentInfoProd = {famille: "",
								  familleActif: 0,
								  reference: "",
								  designation: "",
								  description: "",
								  prixHT: 0,
								  TVA: 0,
								  prixTTC: 0,
								  note: ""
								 };
	var lstSaveCurrentInfoFourn = [];
	var backupDel = [];
	var backupAdd = [];
  	</script>
</head>
<body onload="initDetectRechCli();">
	<div class="container" id="dev"></div>

	<div class="container" id="transparenceMSG" style="z-index: 9"></div>
	<div class="container" id="containerMSG">
		<div class="row" style="width: 100%; height: 30px; margin: 0">
			<div class="col-sm-12" style="height: 100%; width: 100%;"><strong style="color: red;" id="TitreMSG">Erreur:</strong></div>
		</div>
		<div class="row" style="width: 100%; height: 65%; margin: 0; overflow: auto; overflow-x: hidden;">
			<div class="col-sm-12" style="width: 100%; height: 100%; padding-left: 35px; padding-right: 35px;" id="MSG"></div>
		</div>
		<div class="row" style="width: 100%; height: 30px; margin: 0">
			<div class="col-sm-12" style="text-align: center; margin: 0;">
				<button class="btn btn-default" onclick="document.getElementById('containerMSG').style.visibility = 'hidden';document.getElementById('transparenceMSG').style.visibility = 'hidden';" id="ok">OK</button>

				<button class="btn btn-default" onclick="addEditProd('ok')" style="display: none" id="Save">Sauvegarder</button>
				<button class="btn btn-default" onclick="infoProd('close')" style="display: none" id="Cancel">Annuler</button>
			</div>
		</div>
	</div>



	<div class="container" id="containerProd1">
		<div class="row" style="border-bottom: 1px solid black; height: 40px; padding-top: 2px;">
			<div class="col-sm-12" style="color: #990099; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>Critère</strong></div>
		</div>
		<div class="row">
			<div class="col-sm-12" style="margin: 2% 0 2% 0;">
				<button onclick="effacer()" class="btn btn-default" style="width: 45%; font-size: 12px; font-weight: bold; height: 45px; color: #990099; background-color: #0088FF;">
					<span class="glyphicon glyphicon-erase"></span><br> Effacer
				</button>
				<button onclick="rechercher()"  style="width: 45%; font-size: 12px; font-weight: bold; height: 45px; color: #990099; background-color: #0088FF;" class="btn btn-info" id="rech">
					<span class="glyphicon glyphicon-search"></span><br> Rechercher
				</button>
			</div>
			<div class="col-sm-12"></div>
		</div>	
		<div class="row">
			<div class="col-sm-12" style="color: #0033FF; background-image: linear-gradient(to bottom, white -20%, orange 70%);">Produit</div>
		</div>

		<div id="haut" style="margin: 0; padding: 0;"></div>



		<div id="formulaireProd" style="margin: 0; padding: 0; position: relative; top: 1%; width: 100%; height: 32%;">
			<div class="row ">
				<div class="col-sm-12" style="padding-left: 10%;">Référence</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><input type="text" id="Reference"></div>
			</div>
			<div class="row" style="margin-top: 5%;">
				<div class="col-sm-12">Famille</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="Famille">
						<option selected value="*">Toute</option>
<?php 
	while ($resultat = $familles->fetch(PDO::FETCH_ASSOC)) 
	{ 
		echo '<option value="'.$resultat["fam_libelle"].'">'.$resultat["fam_libelle"].'</option>\n'; 
	}
?>

					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Produit</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="typeRechProd">
						<option selected value="*">Commence par</option>
						<option value="Egal">Égal à</option>
						<option value="Contient">Contient</option>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12"><input type="text" id="Libelle"  placeholder="Libellé du produit"></div>
			</div>
			<div class="row" style="margin-top: 5%;">
				<div class="col-sm-12">Prix</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="PrixProd" onchange="ChangePrix()">
						<option selected value="*">Aucun</option>
						<option value="Egal">Égal à</option>
						<option value="Super">Supérieur à</option>
						<option value="Infer">Inférieur à</option>
						<option value="Entre">Entre</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12" id="InPrix"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12" style="margin-top: 1%; color: #0033FF; background-image: linear-gradient(to bottom, white -20%, orange 70%)">Fournisseur</div>
		</div>

		<div id="formulaireFourn" style="margin: 0; padding: 0; width: 100%; height: 40%;">
			<div class="row" style="margin-top: 2%;">
				<div class="col-sm-12">Nom du Fournisseur</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12">
					<select id="typeRechFourn">
						<option selected value="*">Commence par</option>
						<option value="Egal">Égal à</option>
						<option value="Contient">Contient</option>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12"><input type="text" id="NomRaison"  placeholder="Nom ou Raison sociale"></div>
			</div>
			<div class="row">
				<div class="col-sm-12" style="margin-top: 5%;">Catégorie</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="Cat">
						<option selected value="*">Toute</option>
	<?php 
		while ($resultat = $categories->fetch(PDO::FETCH_ASSOC)) 
		{ 
			echo '<option value="'.$resultat["cat_libelle"].'">'.$resultat["cat_libelle"].'</option>\n'; 
		}
	?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12" style="margin-top: 5%;">Forme juridique</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="FormeJuridique">
						<option selected value="*">Toute</option>
<?php 
	while ($resultat = $formesJuridiques->fetch(PDO::FETCH_ASSOC)) 
	{ 
		echo '<option value="'.$resultat["foj_libelle"].'">'.$resultat["foj_libelle"].'</option>\n'; 
	} 
?>
					</select>
				</div>
			</div>
		</div>

		<div id="FourniClick" style="margin: 0; padding: 0;"></div>

		<div class="row" style="position: absolute; bottom: 0; right: 0; left: 0; margin-bottom: 2%">
			<div class="col-sm-12">
				<button onclick="effacer()" style="width: 45%; font-size: 12px; font-weight: bold; height: 45px; color: #990099; background-color: #0088FF;" class="btn btn-info">
					<span class="glyphicon glyphicon-erase"></span><br> Effacer
				</button>
				<button onclick="rechercher()"  style="width: 45%; font-size: 12px; font-weight: bold; height: 45px; color: #990099; background-color: #0088FF;" class="btn btn-info" id="rech">
					<span class="glyphicon glyphicon-search"></span><br> Rechercher
				</button>
			</div>
		</div>
	</div>





	<div class="container" id="containerProd2">
		<div class="row" style="border-bottom: 1px solid black; height: 40px; padding-top: 2px;">
			<div class="col-sm-9" style="padding-left: 8%; font-weight: bold; font-size: 13px; color: #990099; height: 30px; padding-top: 2px; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>Résultat <span id="NumDuProd">0</span>/<span id="NbProdMax">0</span></strong></div>
			<div class="col-sm-2">
				<button class="btn btn-danger" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;" disabled id="DelProd"><span class="glyphicon glyphicon-minus" onclick="delProd()"></span></button>
				<button class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;" disabled id="AddProd" onclick="addEditProd('add')"><span class="glyphicon glyphicon-plus"></span></button>
			</div>	
		</div>
		<div class="row">
			<div class="row" style="overflow: scroll; overflow-x: hidden;">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row" style="margin-top: 1%;">
						<div class="col-sm-3" style="height: 25px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Référence</strong></div>
						<div class="col-sm-3" style="height: 25px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Désignation</strong></div>
						<div class="col-sm-3" style="height: 25px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Famille</strong></div>
						<div class="col-sm-3" style="height: 25px; border: 1px solid black; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Prix</strong></div>
					</div>
				</div>
				<div class="col-sm-1"></div>
			</div>
		</div>
		<input type="number" id="SaveLigneProd" hidden value="0">
		<div class="row" style="overflow: auto; overflow-x: hidden; height: 75%;">
<?php
	for ($i = 0; $i <= 300; $i++) 
	{ 
?>
			<div class="row">
				<input type="number" id="indexProduit<?= $i ?>" value="0" hidden>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-3" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="ref<?= $i ?>"></div>
						<div class="col-sm-3" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="lib<?= $i ?>"></div>
						<div class="col-sm-3" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="fam<?= $i ?>"></div>
						<div class="col-sm-3" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-top: none; background-color: white;" id="prix<?= $i ?>"></div>
					</div>
				</div>
				<div class="col-sm-1"></div>
			</div>
<?php
	}
?>
		</div>
	</div>






	<div class="container" id="containerProd3">
		<div class="row">
			<div class="col-sm-12" style="color: #990099; font-weight: bold; font-size: 15px; padding-left: 8%;"><strong>Fournisseur</strong></div>
		</div>
		<div class="row" style="overflow: scroll; overflow-x: hidden;">
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row" style="margin-top: 1%;">
						<div class="col-sm-3" style="height: 25px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Forme</strong></div>
						<div class="col-sm-6" style="height: 25px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Raison Sociale</strong></div>
						<div class="col-sm-3" style="height: 25px; border: 1px solid black; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Catégorie</strong></div>
					</div>
				</div>
				<div class="col-sm-1"></div>
			</div>
		</div>
		<div class="row" style="overflow: auto; overflow-x: hidden; height: 62%;">
<?php
	for ($i = 0; $i <= 300; $i++) 
	{ 
?>
			<div class="row">
			<input type="number" id="indexFourn<?= $i ?>" value="0" hidden>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-3" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="forme<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
						<div class="col-sm-6" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="raiSoc<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
						<div class="col-sm-3" style="height: 20px; border: 1px solid black; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="cat<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
					</div>
				</div>
				<div class="col-sm-1"></div>
			</div>
<?php
	}
?>
		</div>
		<input type="number" hidden id="TbF" value="0">
		<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%;">
		<div class="row"><div class="col-sm-12" style="border-top: 1px solid black; width: 100%; margin-top: -0.6%;"></div></div>
			<div class="col-sm-12" style="text-align: center; padding: 0;">
				<div class="alert alert-danger" role="alert" id="modif"><strong>Rappel !</strong> Des modifications ont été apportées, n'oubliez pas d'enregistrer.</div>
				<button onclick="window.close()" style="width:70px; float: right; height: 45px; padding: 2px; margin-right: 2%;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Quitter</button>
				<button onclick="enregistrer()" style="width: 100px; border: 5px solid lightblue; height: 45px; margin-right: 15px; background-color: #22CC55; padding: 1px; border: 1px solid lightgreen; color: #444444; float: right; font-size: 15px;" class="btn btn-warning" id="btnSave"><span class="glyphicon glyphicon-floppy-disk"></span><br>Enregistrer</button>
			</div>
		</div>
	</div>





	<div class="container" id="transparence"></div>





	<div class="container" id="infoProd" style="visibility: hidden;">

		<div class="container" id="transparenceProd"></div>
		<div class="container" id="rechCli" style="border-radius: 15px; background-color: #DDDDDD">
			<div class="container" id="containerRechCliHeader" style="width: 100%; height: 10%; border-bottom: 1px solid black; overflow: auto; overflow-x: hidden">
				<div class="row" style="height: 50%;">
					<div class="col-sm-2">
						<div class="row">
							<div class="col-sm-12" style="text-align: center;">Code</div>
						</div>
						<div class="row">
							<div class="col-sm-12" style="text-align: center; padding: 0 5px 0 5px;"><input type="text" style=" width: 100%; height: 25px;" id="rechCodeCli"></div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="row">
							<div class="col-sm-12" style="text-align: center;">Nom / Raison sociale</div>
						</div>
						<div class="row">
							<div class="col-sm-6" style="text-align: center; padding: 0 5px 0 5px;">
								<select style="width: 100%; height: 25px;" id="typeRechNomCli">
									<option value="*" selected>Commence par</option>
									<option value="Egal a">Égal à</option>
									<option value="Contient">Contient</option>
								</select>
							</div>
							<div class="col-sm-6" style="text-align: center; padding: 0 5px 0 5px;"><input type="text" style=" width: 100%; height: 25px;" id="rechNomCli"></div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="row">
							<div class="col-sm-12" style="text-align: center;">Type</div>
						</div>
						<div class="row">
							<div class="col-sm-12" style="text-align: center; padding: 0 5px 0 5px;">
								<select style="width: 100%; height: 25px;" id="rechTypeCli" disabled>
									<option value="Entite" selected>Entité</option>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="row">
							<div class="col-sm-12" style="text-align: center;">Catégorie</div>
						</div>
						<div class="row">
							<div class="col-sm-12" style="text-align: center; padding: 0 5px 0 5px;">
								<select style="width: 100%; height: 25px;" id="rechCatCli">
									<option value="*" selected>Non renseigné</option>
<?php 
	for($i = 0; $i <= sizeof($categorieCli)-1; $i++) 
	{
		if ($categorieCli[$i] != "") 
		{
			echo "<option value='".$categorieCli[$i]."'>".$categorieCli[$i]."</option>\n";
		}
	}
?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="row">
							<div class="col-sm-12" style="text-align: center;">SIRET</div>
						</div>
						<div class="row">
							<div class="col-sm-12" style="text-align: center; padding: 0 5px 0 5px;"><input type="text" style=" width: 100%; height: 25px;" id="rechSiretCli"></div>
						</div>
					</div>
				</div>
			</div>
			<input type="number" value="0" hidden id="ligneCli">
			<div class="container" id="containerRechCliBody" style="height: 80%; width: 100%; padding: 5px 0 5px 0;">
				<div class="row" style="overflow: hidden; margin-left: 3%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden; margin-right: -20px;">
						<div class="row" style="width: 100%; padding: 0 30px 0 30px">
							<div class="col-sm-2" style="padding: 0; height: 25px; border: 1px solid black; text-align: center; border-right: none; background-color: #222222; opacity: 0.9; color: white;">Code</div>
							<div class="col-sm-2" style="padding: 0; height: 25px; border: 1px solid black; text-align: center; border-right: none; background-color: #222222; opacity: 0.9; color: white;">Titre / Forme</div>
							<div class="col-sm-5" style="padding: 0; height: 25px; border: 1px solid black; text-align: center; border-right: none; background-color: #222222; opacity: 0.9; color: white;">Identité</div>
							<div class="col-sm-1" style="padding: 0; height: 25px; border: 1px solid black; text-align: center; border-right: none; background-color: #222222; opacity: 0.9; color: white;">Catégorie</div>
							<div class="col-sm-2" style="padding: 0; height: 25px; border: 1px solid black; text-align: center; background-color: #222222; opacity: 0.9; color: white;">Ville</div>
						</div>
					</div>
				</div>
				<div class="row" style="height: 85%; overflow: hidden; margin-left: 3%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden; margin-right: -20px;">
<?php
	for ($i = 0; $i <= 300; $i++)
	{
?>
						<div class="row" style="width: 100%; padding: 0 30px 0 30px">
							<div class="col-sm-2" style="padding: 0; border: 1px solid black; text-align: center; border-right: none; border-top: none; height: 50px;" id="codeCli<?php echo $i; ?>" ondblclick="selectCli(<?php echo $i; ?>);"></div>
							<div class="col-sm-2" style="padding: 0; border: 1px solid black; text-align: center; border-right: none; border-top: none; height: 50px;" id="formeCli<?php echo $i; ?>"ondblclick="selectCli(<?php echo $i; ?>);"></div>
							<div class="col-sm-5" style="padding: 0; border: 1px solid black; text-align: center; border-right: none; border-top: none; height: 50px;" id="identiteCli<?php echo $i; ?>"ondblclick="selectCli(<?php echo $i; ?>);"></div>
							<div class="col-sm-1" style="padding: 0; border: 1px solid black; text-align: center; border-right: none; border-top: none; height: 50px;" id="categorieCli<?php echo $i; ?>"ondblclick="selectCli(<?php echo $i; ?>);"></div>
							<div class="col-sm-2" style="padding: 0; border: 1px solid black; text-align: center; border-top: none; height: 50px;" id="villeCli<?php echo $i; ?>"ondblclick="selectCli(<?php echo $i; ?>);"></div>
						</div>
<?php
	}
?>
					</div>
				</div>
				<div class="row" style="margin-top: 1%;">
					<div style="height: 25px; width: 100%; padding: 0; text-align: center;">Double clique sur l'un des clients pour l'ajouter à la liste des fournisseurs</div>
				</div>
			</div>

			<div class="container" id="containerRechCliFooter" style="height: 10%; width: 100%; border-top: 1px solid black;">
				<button onclick="$('#rechCli').css('visibility', 'hidden');$('#transparenceProd').css('visibility', 'hidden');" style="width: 70px; height: 90%; margin: 0.3% 0 0.3% 0; float: right;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
			</div>
		</div>



		<div class="container" id="rechFamilleProd">
			<div class="container" id="transparenceFamille"></div>
			<div class="container" id="containerAddFamille">
				<div class="row" style="width: 100%; margin: 0;">
					<div class="col-sm-12"><h3 style="text-transform: uppercase; text-indent: 0.5em; color: #990099; font-weight: bold; text-shadow: 5px 5px #AAAAAA;">Détail d'une famille</h3></div>
				</div>
				<div class="row" style="width: 100%; margin: 20px 0 0 0;">
					<div class="col-sm-1"></div>
					<div class="col-sm-11" style="font-weight: bold; font-size: 13px;">Libellé <input type="text" style="width: 60%; margin-left: 2%;"></div>
				</div>
				<div class="row" style="width: 100%; margin: 1% 0 0 0;">
					<div class="col-sm-1"></div>
					<div class="col-sm-11" style="padding-left: 13.5%; font-weight: bold; font-size: 13px;"><input type="checkbox" style="width: 15px; height: 15px; margin: 0;"> Actif</div>
				</div>
				<div class="row" style="margin: 0; position: absolute; bottom: 0; left: 0; right: 0; width: 100%; height: 16%; padding: 0.5%; border-top: 1px solid black;">
					<div class="col-sm-12" style="text-align: right; width: 100%; padding: 0; margin: 0;">
						<button style="padding: 1px 1px 2px 1px; width: 70px; height: 99%; margin-right: 2%;">Enregistrer</button>
						<button onclick="$('#containerAddFamille').css('visibility', 'hidden'); $('#transparenceFamille').css('visibility', 'hidden');" style="padding: 1px 1px 2px 1px; width: 70px; height: 99%; float: right;">Fermer</button>
					</div>
				</div>
			</div>

			<div class="container" id="containerFamilleHeader" style="height: 8%;"></div>
			<div class="container" id="containerFamilleBody" style="height: 82%; overflow: auto; overflow-x: hidden; padding-bottom: 20px; width: 100%;">
				<div class="row" style="margin-bottom: 1%;">
					<div class="col-sm-8"></div>
					<div class="col-sm-3">
						<button class="btn btn-danger" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-minus"></span></button>
						<button onclick="$('#transparenceFamille').css('visibility', 'visible'); $('#containerAddFamille').css('visibility', 'visible');" class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;"><span class="glyphicon glyphicon-plus"></span></button>
					</div>
					<div class="col-sm-1"></div>
				</div>
				<div class="row" style="overflow: hidden; margin-left: 2%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-8" style="width: 65.3%; height: 25px; border: 1px solid black; border-right: none; font-weight: bold; background-color: #222222; color: white; opacity: 0.9; background-color: #222222; opacity: 0.9; color: white;">Famille</div>
							<div class="col-sm-2" style="width: 16.3%; height: 25px; border: 1px solid black; font-weight: bold; background-color: #222222; color: white; opacity: 0.9; text-align: center; background-color: #222222; opacity: 0.9; color: white;">Actif</div>
						</div>
					</div>
				</div>
				<div class="row" style="height: 80%; overflow: hidden; margin-left: 2%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
<?php
	$familles->closeCursor();
	$familles->execute();
	while ($resultat = $familles->fetch(PDO::FETCH_ASSOC)) 
	{ 
?>
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-8" style="border: 1px solid black; width: 65.3%;  border-right: none; border-top: 0; height: 25px;"><?php echo $resultat['fam_libelle']; ?></div>
							<div class="col-sm-2" style="border: 1px solid black; width: 16.3%; height: 25px; border-top: 0; text-align: center;"><?php if ($resultat['fam_iactif'] == 1){ echo "<span class='glyphicon glyphicon-ok' style='color: green'></span>"; } else { echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>"; } ?></div>
							<div class="col-sm-1"></div>
						</div>
<?php 
	}
	for ($i = 0; $i <= 19; $i++) 
	{ 
?>
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-8" style="border: 1px solid black; width: 65.3%;  border-right: none; border-top: 0; height: 25px;"></div>
							<div class="col-sm-2" style="border: 1px solid black; width: 16.3%; height: 25px; border-top: 0;"></div>
							<div class="col-sm-1"></div>
						</div>
<?php
	}
?>
					</div>
				</div>
			</div>
			<div class="container" id="containerFamilleFooter" style="height: 10%; border-top: 1px solid black; width: 100%;">
				<div class="row" style="height: 100%;">
					<div class="col-sm-10"></div>
					<div class="col-sm-2" style="padding: 0.5% 0.5% 0.5% 0; height: 100%">
						<button onclick="document.getElementById('rechFamilleProd').style.visibility = 'hidden'; document.getElementById('transparenceProd').style.visibility= 'hidden';" style="padding: 1px 1px 2px 1px; width: 70px; height: 99%; float: right;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
					</div>
				</div>
			</div>
		</div>
		<input type="number" id="SaveLigneFourn" hidden value="0">





		<div class="container" id="infoProdHeader" style="width: 100%; position: absolute; top: 0; left:0; right: 0; margin: 0; height: 8%; border-bottom: 1px solid black;">
			<div class="row" style="width: 100%; height: 100%;">
				<div class="col-sm-5"></div>
				<div class="col-sm-7">
					<p style="width: 100%; height: inherit;">
						<span id="infoRefProd" style="float: left; padding: 14px; padding-right: 0;">Test</span> 
						<span style="float: left; padding: 14px 0 14px 0;">&nbsp;-&nbsp;</span>
						<span id="infoLibProd" style="float: left; padding: 14px; padding-left: 0;">Test</span>
					</p>
				</div>
			</div>
		</div>



		<div class="container" id="infoProdBody" style="position: absolute; top: 8%; right: 0; left: 0; margin: 0; overflow: auto; overflow-x: hidden; height: 84%;">
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-2"><h4 style="font-weight: bold; font-size: 15px;">IDENTIFICATION</h4></div>
				<div class="col-sm-8"></div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-3">Famille</div>
					<div class="col-sm-7">
						<select id="infoFamProd" style="width: 120%; height: 25px;" onchange="">
							<option value="*">Non renseignée</option>
<?php
	$familles->closeCursor();
	$familles->execute();
	while ($resultat = $familles->fetch(PDO::FETCH_ASSOC)) 
	{ 
		echo '<option value="'.$resultat["fam_libelle"].'">'.$resultat["fam_libelle"].'</option>'."\n"; 
	}
?>
						</select>
					</div>
					<div class="col-sm-2" style="padding-left: 8%;">
						<button onclick="document.getElementById('transparenceProd').style.visibility = 'visible'; document.getElementById('rechFamilleProd').style.visibility = 'visible';" style="width: 25px; height: 25px; padding: 0;" id="btnRechFamille"><span class="glyphicon glyphicon-search"></span></button>
					</div>
				</div>
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-5"></div>
					<div class="col-sm-2" style="padding-right: 0">Actif</div>
					<div class="col-sm-1" style="padding-left: 0"><input type="checkbox" value="1" id="actif"></div>
					<div class="col-sm-4"></div>
				</div>
				<div class="col-sm-2" style=" margin-bottom: 5px;"></div>
			</div>
			<div class="row">
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-3">Designation</div>
					<div class="col-sm-7"><input type="text" id="infoLibProdModif" style="width: 120%;"></div>
					<div class="col-sm-2"></div>
				</div>
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-3"></div>
					<div class="col-sm-2" style="padding-left: 0">Référence</div>
					<div class="col-sm-5"><input type="text" id="infoRefProdModif"></div>
					<div class="col-sm-2"></div>
				</div>
				<div class="col-sm-2" style=" margin-bottom: 5px;"></div>
			</div>
			<div class="row">
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-3">Description</div>
					<div class="col-sm-7"><textarea id="infoDescriptionProd" style="width: 120%;
					height: 100px;"></textarea></div>
					<div class="col-sm-2"></div>
				</div>
				<div class="col-sm-7"></div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-3"><h4 style="font-weight: bold; font-size: 15px;">PRIX</h4></div>
				<div class="col-sm-9"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<div class="col-sm-6" style="padding-right: 0">Prix HT</div>
					<div class="col-sm-6" style="padding-left: 0"><input type="number" id="infoPrixProd" onchange="calculTva()">&nbsp;&euro;</div>
				</div>
				<div class="col-sm-3">
					<div class="col-sm-3" style="padding-right: 0">TVA</div>
					<div class="col-sm-9" style="padding-left: 0">
						<select id="infoTvaPourcentage" style="width: 110%;" onchange="calculTva()">
							<option selected value="*">Non renseigné</option>
<?php
	for($i = 0; $i <= sizeof($tvaLib)-1; $i++) 
	{
		if ($tvaLib[$i] != "") 
		{
			echo "<option value='".$tvaPourcent[$i]."'>".$tvaLib[$i]."(".$tvaPourcent[$i]."%)</option>\n";
		}
	}
?>
						</select>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="col-sm-4">Prix TTC</div>
					<div class="col-sm-6" style="padding-left: 0; margin-left: 8%;"><input type="number" id="infoTvaPrix">&nbsp;&euro;</div>
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="row" style="margin-top: 1.2%;">
				<div class="col-sm-2"><h4 style="font-weight: bold; font-size: 15px;">FOURNISSEUR</h4></div>
				<div class="col-sm-5">
						<button onclick="pdf()" class="btn btn-danger" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 0; margin-right: 10%;" id="delSelectFourn">PDF</button>
						<button onclick="delSelectFournProd()" class="btn btn-danger" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px; margin-right: 1%;" id="delSelectFourn" disabled><span class="glyphicon glyphicon-minus"></span></button>
						<button onclick="$('#rechCli').css('visibility', 'visible');$('#transparenceProd').css('visibility', 'visible'); effaceAllCli();"class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;"><span class="glyphicon glyphicon-plus"></span></button>
				</div>
				<div class="col-sm-5"></div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-7">
					<div class="row">
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-10">
								<div class="row" style="background-color: white">
									<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white;"><strong>Forme</strong></div>
									<div class="col-sm-5" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white;"><strong>Raison Sociale</strong></div>
									<div class="col-sm-5" style="height: 20px; border: 1px solid black; background-color: #222222; opacity: 0.9; color: white;"><strong>Catégorie</strong></div>
								</div>
							</div>
						</div>
					</div>
					<input type="text" id="saveLigneFournProd" hidden value="0">
					<div class="row">
<?php
	for ($i = 0; $i <= 6; $i++) 
	{ 
?>
					<div class="row">
						<input type="number" id="numFournisseur<?= $i; ?>" hidden>
						<input type="text" id="codeFourn<?= $i ?>" hidden>
						<div class="col-sm-1"></div>
						<div class="col-sm-10">
							<div class="row" style="background-color: white">
								<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; border-top: none;" id="infoFormeFournisseur<?= $i ?>" onclick="selectFournProd(<?= $i; ?>); commentaireFournisseur(<?= $i; ?>);"></div>
								<div class="col-sm-5" style="height: 20px; border: 1px solid black; border-right: none; border-top: none;" id="infoRaisonSocialeFournisseur<?= $i ?>" onclick="selectFournProd(<?= $i; ?>); commentaireFournisseur(<?= $i; ?>);"></div>
								<div class="col-sm-5" style="height: 20px; border: 1px solid black; border-top: none;" id="infoCategorieFournisseur<?= $i ?>" onclick="selectFournProd(<?= $i; ?>); commentaireFournisseur(<?= $i; ?>);"></div>
							</div>
						</div>
					</div>
<?php
	}
?>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="row">
						<div class="col-sm-8" style="margin-left: 2.5%;">Note concernant le fournisseur séléctionné</div>
						<div class="col-sm-4"></div>
					</div>
					<div class="row">
						<div class="col-sm-8" style="margin-left: 2.5%;"><textarea style="width: 80%; height: 120px;" id="commentaire"></textarea></div>
						<div class="col-sm-4"></div>
					</div>
				</div>
			</div>
		</div>

		<input type="text" hidden value="" id="newProd">
		<div class="container" id="infoProdFooter">
			<div class="row" style="position: absolute; bottom: 0; left: 0; right: 0; margin: 0; height: 8.5%; border-top: 1px solid black">
				<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%;">
					<div class="row"><div class="col-sm-12"></div></div>
					<div class="col-sm-8"></div>
					<div class="col-sm-4" style="text-align: right;">
						<button onclick="infoProd('close')" style="width: 70px; height: 45px; float: right;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
						<button onclick="addEditProd()" style="padding: 1px 1px 2px 1px; width: 80px; height: 99%; float: right; margin-right: 10px;" class="btn btn-warning"  id="btnEnregistrement"><span class="glyphicon glyphicon-home"></span><br>Enregistrer</button>
					</div>
				</div>
			</div>
		</div>
	</div>





	<div class="container" id="infoFourn" style="visibility: hidden">

		<div class="container" id="transparenceFourn" style="visibility: hidden"></div>

		<div class="container" id="rechCategorieFourn" style="visibility: hidden">
			<div class="container" id="containerCategorieHeader" style="height: 8%;"></div>
			<div class="container" id="containerCategorieBody" style="height: 82%; overflow: auto; overflow-x: hidden; width: 100%;">
				<div class="row" style="margin-bottom: 1%;">
					<div class="col-sm-8"></div>
					<div class="col-sm-3">
						<button class="btn btn-danger" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-minus"></span></button>
						<button class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;"><span class="glyphicon glyphicon-plus"></span></button>
					</div>
					<div class="col-sm-1"></div>
				</div>
				<div class="row" style="overflow: hidden; margin-left: 2%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-5" style="text-align: center; height: 25px; border: 1px solid black; border-right: none; font-weight: bold; background-color: #222222; opacity: 0.9; color: white;">Catégorie</div>
							<div class="col-sm-3" style="text-align: center; height: 25px; border: 1px solid black; border-right: none; font-weight: bold; background-color: #222222; opacity: 0.9; color: white;">Type</div>
							<div class="col-sm-2" style="text-align: center; height: 25px; border: 1px solid black; font-weight: bold; background-color: #222222; opacity: 0.9; color: white;">Actif</div>
							<div class="col-sm-1"></div>
						</div>
					</div>
				</div>
				<div class="row" style="height: 80%; overflow: hidden; margin-left: 2%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
<?php
	$categories->closeCursor();
	$categories->execute();
	while ($resultat = $categories->fetch(PDO::FETCH_ASSOC)) 
	{ 
		if ($resultat['cat_libelle'] != "Non renseignée") 
		{
?>
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-5" style="border: 1px solid black;  border-right: none; border-top: none; height: 25px;"><?php echo $resultat['cat_libelle']; ?></div>
							<div class="col-sm-3" style="border: 1px solid black; border-right: none; border-top: none; height: 25px;">Fournisseur</div>
							<div class="col-sm-2" style="border: 1px solid black; height: 25px; border-top: none; text-align: center;"><?php if ($resultat['cat_iactif'] == 1){ echo "<span class='glyphicon glyphicon-ok' style='color: green'></span>"; } else { echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>"; } ?></div>
							<div class="col-sm-1"></div>
						</div>
<?php 
		}
	}
	for ($i = 0; $i <= 19; $i++) 
	{ 
?>

						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-5" style="border: 1px solid black; height: 25px; border-right: none; border-top: none;"></div>
							<div class="col-sm-3" style="border: 1px solid black; height: 25px; border-right: none; border-top: none;"></div>
							<div class="col-sm-2" style="border: 1px solid black; height: 25px; border-top: none;"></div>
							<div class="col-sm-1"></div>
						</div>
<?php
	}
?>
					</div>
				</div>
			</div>
			<div class="container" id="containerCategorieFooter" style="height: 10%; border-top: 1px solid black; width: 100%;">
				<div class="row" style="height: 100%;">
					<div class="col-sm-10"></div>
					<div class="col-sm-2" style="padding: 0.5% 0.5% 0.5% 0; height: 100%">
						<button onclick="document.getElementById('rechCategorieFourn').style.visibility = 'hidden'; document.getElementById('transparenceFourn').style.visibility= 'hidden';" style="padding: 1px 1px 2px 1px; width: 70px; height: 99%; float: right;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
					</div>
				</div>
			</div>
		</div>




		<div class="container" id="rechFormeJuridiqueFourn" style="visibility: hidden">
			<div class="container" id="containerFormeJuridiqueHeader" style="height: 8%;"></div>
			<div class="container" id="containerFormeJuridiqueBody" style="height: 82%; overflow: auto; overflow-x: hidden; width: 100%;">
				<div class="row" style="margin-bottom: 1%;">
					<div class="col-sm-8"></div>
					<div class="col-sm-3">
						<button class="btn btn-danger" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-minus"></span></button>
						<button class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;"><span class="glyphicon glyphicon-plus"></span></button>
					</div>
					<div class="col-sm-1"></div>
				</div>
				<div class="row" style="overflow: hidden; margin-left: 2%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-8" style="text-align: center; height: 25px; border: 1px solid black; border-right: none; font-weight: bold; background-color: #222222; opacity: 0.9; color: white;">Forme Juridique</div>
							<div class="col-sm-2" style="text-align: center; height: 25px; border: 1px solid black; font-weight: bold; background-color: #222222; opacity: 0.9; color: white;">Actif</div>
						</div>
					</div>
				</div>
				<div class="row" style="height: 80%; overflow: hidden; margin-left: 2%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
<?php
	$formesJuridiques->closeCursor();
	$formesJuridiques->execute();
	while ($resultat = $formesJuridiques->fetch(PDO::FETCH_ASSOC)) 
	{ 
?>
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-8" style="border: 1px solid black;  border-right: none; border-top: none; height: 25px;"><?php echo $resultat['foj_libelle']; ?></div>
							<div class="col-sm-2" style="border: 1px solid black; height: 25px; border-top: none; text-align: center;"><?php if ($resultat['foj_iactif'] == 1){ echo "<span class='glyphicon glyphicon-ok' style='color: green'></span>"; } else { echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>"; } ?></div>
						</div>
<?php 
	}
	for ($i = 0; $i <= 19; $i++) 
	{ 
?>

						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-8" style="border: 1px solid black; height: 25px; border-right: none; border-top: none;"></div>
							<div class="col-sm-2" style="border: 1px solid black; height: 25px; border-top: none;"></div>
						</div>
<?php
	}
?>
					</div>
				</div>
			</div>
			<div class="container" id="containerFormeJuridiqueFooter" style="height: 10%; border-top: 1px solid black; width: 100%;">
				<div class="row" style="height: 100%;">
					<div class="col-sm-10"></div>
					<div class="col-sm-2" style="padding: 0.5% 0.5% 0.5% 0; height: 100%">
						<button onclick="document.getElementById('rechFormeJuridiqueFourn').style.visibility = 'hidden'; document.getElementById('transparenceFourn').style.visibility= 'hidden';" style="padding: 1px 1px 2px 1px; width: 70px; height: 99%; float: right;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
					</div>
				</div>
			</div>
		</div>




		<div class="container" id="infoFournHeader" style="border-bottom: 1px black solid; height: 8%; width: 100%; padding: 16px;">
			<div class="row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6" style="text-align: center;"><strong id="infoNomFournisseur">NomFournisseur</strong></div>
				<div class="col-sm-3"></div>
			</div>
		</div>



		<div class="container" id="infoFournBody" style="overflow: auto; overflow-x: hidden; height: 83.5%;">
			<input type="text" id="currentFourn" hidden>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-12"><strong>Identification</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-5">
					<div class="col-sm-4">Type fournisseur</div>
					<div class="col-sm-8">
						<select style="height: 26px;">
							<option value="entite" selected>Entité</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="col-sm-5">Catégorie fournisseur</div>
					<div class="col-sm-7">
						<select style="height: 26px; width: 76.5%;" id="infoCatFournisseur">
							<option value="*">Non renseignée</option>
<?php
	for ($i = 0; $i <= sizeof($categorie)-1; $i++)
	{
		if ($categorie[$i] != "") 
		{
			echo "<option value='".$categorie[$i]."'>".$categorie[$i]."</option>\n";
		}
	}
		
?>
						</select>
					</div>
				</div>
				<div class="col-sm-2" style="padding-left: 0; margin-left: -7%;">
					<button onclick="document.getElementById('transparenceFourn').style.visibility = 'visible'; document.getElementById('rechCategorieFourn').style.visibility = 'visible';" style="width: 25px; height: 25px; padding: 0;" id="btnRechCategorie"><span class="glyphicon glyphicon-search"></span></button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><strong>Détail de l'entité</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-5">
					<div class="col-sm-4">Raison sociale</div>
					<div class="col-sm-8"><input type="text" id="infoRaiSocFourn"></div>
				</div>
				<div class="col-sm-5">
					<div class="col-sm-4">Abréviation</div>
					<div class="col-sm-8"><input type="text" id="infoAbreviationFourn"></div>
				</div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-5">
					<div class="col-sm-4">Forme juridique</div>					
					<div class="col-sm-8">
						<select style="height: 26px;" id="infoFjFourn">
							<option value="*" selected>Non renseignée</option>
<?php
	for ($i = 0; $i <= sizeof($FJ)-1; $i++)
	{
		if ($FJ[$i] != "") 
		{
			echo "<option value='".$FJ[$i]."'>".$FJ[$i]."</option>\n";
		}
	}
?>
						</select>
						<button onclick="document.getElementById('transparenceFourn').style.visibility = 'visible'; document.getElementById('rechFormeJuridiqueFourn').style.visibility = 'visible';" style="width: 25px; height: 25px; padding: 0; margin-left: 1px;" id="btnRechFormeJuridique"><span class="glyphicon glyphicon-search"></span></button>
					</div>					
				</div>
				<div class="col-sm-5">
					<div class="col-sm-4">SIRET / SIREN</div>
					<div class="col-sm-8"><input type="text" id="infoSiretFourn"></div>
				</div>
				<div class="col-sm-2">
				</div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-5">
					<div class="col-sm-4">Code entité</div>
					<div class="col-sm-8"><input type="text" id="infoCodeEntiteFourn"></div>
				</div>
				<div class="col-sm-5">
					<div class="col-sm-4">Contact</div>
					<div class="col-sm-8"><input type="text" id="infoContactFourn"></div>
				</div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row">
				<div class="col-sm-12"><strong>Commentaires</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-12">
					<textarea style="width: 76.75%; height: 50px;" id="infoCommentaireFourn"></textarea>
				</div>
			</div>
			<div class="row" style="margin-top: 0.2%;">
				<div class="col-sm-12"><strong>Les coordonnées</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-4" style="padding-right: 0">
					<div class="col-sm-5">Adresse</div>
					<div class="col-sm-7" style="padding-left: 2.5%">
						<select style="width: 100%;  height: 26px;" id="infoTypeAdresseFourn">
							<option value="Adresse bureau">Adresse bureau</option>
							<option value="Adresse courrier">Adresse courrier</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding-left: 0">
					<div class="col-sm-8" style="padding: 0;"><input type="checkbox" style="margin-left: 10px; width: 12px; height: 15px; float: left; margin-right: 5px;" id="infoAdresseDefautFourn"><p style="float: left;">Coordonnée par défaut</p></div>
				</div>
			</div>
			<div class="row" style="margin-top: 0.4%; padding-left: 2%">
				<div class="col-sm-4">
					<div class="col-sm-5"></div>
					<div class="col-sm-7" style="padding: 0; margin-left: -1%;">
						<textarea style="width: 150%; height: 80px;" id="infoAdresseFourn"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><strong>Communication</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-4">
					<div class="col-sm-5">Téléphone</div>
					<div class="col-sm-7">
						<select style="width: 100%;  height: 26px;" onchange="changeNum();" id="infoSelectNumFourn">
							<option value="Téléphone" id="TPsoc" selected>Téléphone société</option>
							<option value="Télécopie" id="TCsoc">Télécopie société</option>
							<option value="Mobile" id="Msoc">Mobile société</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding: 0;">
					<div class="col-sm-4" style="padding: 0;"><input type="tel" style="width: 90%; padding-left: 0.3em;" pattern="^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$" id="infoTelephoneFourn"></div>
					<div class="col-sm-8" style="padding: 2px;"><input type="checkbox" style="margin-left: 10px; width: 12px; height: 15px; float: left; margin-right: 5px;" id="infoTelephoneDefautFourn"><p style="float: left;">Coordonnée par défaut</p></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="col-sm-5">Courriel</div>
					<div class="col-sm-7">
						<select style="width: 100%;  height: 26px;">
							<option value="1" selected>Principale</option>
							<option value="2">Secondaire</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding: 0;">
					<div class="col-sm-12" style="padding: 0;"><input type="text" style="width: 80%; padding-left: 0.3em;" id="infoEmailFournisseur"></div>
				</div>
			</div>
			<div class="row" style="margin-top: 0.5%; margin-bottom: 20px">
				<div class="col-sm-8" style="padding-right: 0;">
					<div class="col-sm-2" style="padding-right: 0;">Site internet</div>
					<div class="col-sm-10" style="padding: 0 0 0 4.9%;"><input type="text" style="width: 100%; padding-left: 3%;"></div>
				</div>
			</div>
		</div>



		<div class="container" id="infoFournFooter" style="position: absolute; bottom: 0; border-top: 1px solid black; height: 8.5%; width: 100%;">
			<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%; height: 100%">
				<div class="row"><div class="col-sm-12"></div></div>
				<div class="col-sm-8"></div>
				<div class="col-sm-4" style="text-align: right; height: 80%; margin-top: 0.65%;">
					<button onclick="infoFourn('close')" style="width: 70px; height: 100%; padding: 1px; line-height: 15px" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<?php
	}
	else
	{
		header('Location: index.php?action=Connexion');
	}
?>
