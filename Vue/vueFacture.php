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
	$listeProduit = "var listeProduit = {\n";
	$i = 0;
	$y = 0;
	$z = 0;
	$tvaLib = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "");
	$tvaPourcent = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "", 5 => "");
	$description = "";
	while ($resultat = $produits->fetch(PDO::FETCH_ASSOC))
	{
		$description = $resultat['pro_description'];
		$description = str_replace(CHR(13).CHR(10),"*",$description); 
		$listeProduit.= $i.':{libelle:"'.$resultat['pro_libelle'].'", reference: "'.$resultat['pro_reference'].'", description: "'.$description.'", famille:"'.$resultat['fam_libelle'].'", prix: "'.$resultat['pro_pxvente_ht'].'", tvaLibelle: "'.$resultat['tva_libelle'].'", tva: "'.$resultat['tva_pourcentage'].'", actif: "'.$resultat['pro_iactif'].'", rechLib:"'.$resultat['pro_lib_rech'].'", rechFam:"'.$resultat['fam_lib_rech'].'"},'."\n";
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
		$i++;
		$description = "";
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
	$raisonSocialeFournisseur = array();
	$produitFournisseur = array();
	$numeroTelephoneFournisseur = array();
	$categorie = array();
	$FJ = array();
	$lstFournisseurs = [0 => ""];
	$saveF = [];
	$lstFournisseursnum = array();
	$i = 0;

	while ($resultatP = $produits->fetch(PDO::FETCH_ASSOC))
	{
		$y = 0;
		$lstProduits[$i]["referenceProd"] = $resultatP['pro_reference'];
		$lstProduits[$i]["libelleProd"] = $resultatP['pro_libelle'];
		$lstProduits[$i]["descriptionProd"] = str_replace(CHR(13).CHR(10),"*",$resultatP['pro_description']);
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
	while ($resultatF = $fournisseurs->fetch(PDO::FETCH_ASSOC)) 
	{
		if (!in_array($resultatF['ent_raison_sociale'], $saveF))
		{
			$i++;
		}
		if (!in_array($resultatF['ent_raison_sociale'], $saveF))
		{
			$lstFournisseurs[$i]["raisonSociale"] = $resultatF['ent_raison_sociale'];
			$lstFournisseurs[$i]["produit"] = $resultatF['pro_lib_rech'];
			$lstFournisseurs[$i]["codeEntite"] = $resultatF['ent_code_entite'];
			$lstFournisseurs[$i]["contact"] = $resultatF['ent_lib_contact'];
			$lstFournisseurs[$i]["siret"] = $resultatF['ent_siret'];
			$lstFournisseurs[$i]["abreviation"] = $resultatF['ent_abreviation'];
			$lstFournisseurs[$i]["clefPhonetique"] = $resultatF['ent_clefphonetique'];
			$lstFournisseurs[$i]["categorie"] = $resultatF['cat_libelle'];
			$lstFournisseurs[$i]["adresse"] = str_replace(CHR(13).CHR(10),"*", $resultatF['adr_complete']);
			$lstFournisseurs[$i]["formeJuridique"] = $resultatF['foj_libelle'];
			$lstFournisseurs[$i]["email"] = $resultatF['lstm_mail'];
			$lstFournisseurs[$i]["rechCategorie"] = $resultatF['cat_lib_rech'];
			$lstFournisseurs[$i]["rechFormeJuridique"] = $resultatF['foj_lib_rech'];
			$lstFournisseurs[$i]["rechRaisonSociale"] = $resultatF['ent_raison_rech'];
			$lstFournisseurs[$i]["telephoneFixe"] = "";
			$lstFournisseurs[$i]["telephoneFixeActif"] = "";
			$lstFournisseurs[$i]["fax"] = "";
			$lstFournisseurs[$i]["faxActif"] = "";
			$lstFournisseurs[$i]["mobile"] = "";
			$lstFournisseurs[$i]["mobileActif"] = "";
			if ($resultatF['tpi_indicateur'] == 1) 
			{
				$lstFournisseurs[$i]["telephoneFixe"] = $resultatF['lstt_numero'];
				$lstFournisseurs[$i]["telephoneFixeActif"] = $resultatF['lstt_coord_defaut'];
			}
			else if ($resultatF['tpi_indicateur'] == 2)
			{
				$lstFournisseurs[$i]["fax"] = $resultatF['lstt_numero'];
				$lstFournisseurs[$i]["faxActif"] = $resultatF['lstt_coord_defaut'];
			}
			else if ($resultatF['tpi_indicateur'] == 3)
			{
				$lstFournisseurs[$i]["mobile"] = $resultatF['lstt_numero'];
				$lstFournisseurs[$i]["mobileActif"] = $resultatF['lstt_coord_defaut'];
			}
			$saveF[] = $resultatF['ent_raison_sociale'];
		}
		else
		{
			if ($resultatF['tpi_indicateur'] == 1) 
			{
				$lstFournisseurs[$i]["telephoneFixe"] = $resultatF['lstt_numero'];
				$lstFournisseurs[$i]["telephoneFixeActif"] = $resultatF['lstt_coord_defaut'];
			}
			else if ($resultatF['tpi_indicateur'] == 2)
			{
				$lstFournisseurs[$i]["fax"] = $resultatF['lstt_numero'];
				$lstFournisseurs[$i]["faxActif"] = $resultatF['lstt_coord_defaut'];
			}
			else if ($resultatF['tpi_indicateur'] == 3)
			{
				$lstFournisseurs[$i]["mobile"] = $resultatF['lstt_numero'];
				$lstFournisseurs[$i]["mobileActif"] = $resultatF['lstt_coord_defaut'];
			}
		}
	}

	$i = 0;
	$x = 0;
	$AfficheProduits = "var lstProduits = {\n";
	foreach ($lstProduits as $key => $value) 
	{
		$z = 0;
		$AfficheProduits.= $i.":{".'referenceProd:"'.$value["referenceProd"].'", libelleProd: "'.$value["libelleProd"].'", descriptionProd: "'.$value["descriptionProd"].'", familleProd: "'.$value['familleProd'].'", prixProd: "'.$value["prixProd"].'", tvaLibelleProd: "'.$value['tvaLibelleProd'].'", tva: "'.$value["tvaProd"].'", actifProd: "'.$value['actifProd'].'", rechLibelleProd: "'.$value['rechLibelleProd'].'", rechFamilleProd: "'.$value['rechFamilleProd'].'",';
		for ($y = 0; $y <= sizeof($lstFournisseurs)-1; $y++)
		{
			if ($lstFournisseurs[$y]['produit'] == $value['rechLibelleProd'])
			{
				if ($z == 0) 
				{
					$AfficheProduits.= 'fournisseurProd: {';
					
				}
				$x++;
				$AfficheProduits.= $z.':{raisonSociale: "'.$lstFournisseurs[$y]['raisonSociale'].'", produitFournisseur: "'.$value['rechLibelleProd'].'", codeEntite: "'.$lstFournisseurs[$y]['codeEntite'].'", contact: "'.$lstFournisseurs[$y]['contact'].'", siret: "'.$lstFournisseurs[$y]['siret'].'", abreviation: "'.$lstFournisseurs[$y]['abreviation'].'", clefPhonetique: "'.$lstFournisseurs[$y]['clefPhonetique'].'", categorie: "'.$lstFournisseurs[$y]['categorie'].'", adresse: "'.$lstFournisseurs[$y]['adresse'].'", formeJuridique: "'.$lstFournisseurs[$y]['formeJuridique'].'", email: "'.$lstFournisseurs[$y]['email'].'", rechCategorie: "'.$lstFournisseurs[$y]['rechCategorie'].'", rechFormeJuridique: "'.$lstFournisseurs[$y]['rechFormeJuridique'].'", rechRaisonSociale: "'.$lstFournisseurs[$y]['rechRaisonSociale'].'", telephoneFixe: "'.$lstFournisseurs[$y]['telephoneFixe'].'", telephoneFixeActif: "'.$lstFournisseurs[$y]['telephoneFixeActif'].'", mobile: "'.$lstFournisseurs[$y]['mobile'].'", mobileActif: "'.$lstFournisseurs[$y]['mobileActif'].'", fax : "'.$lstFournisseurs[$y]['fax'].'", faxActif: "'.$lstFournisseurs[$y]['faxActif'].'"},';
				$z++;
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









	while ($resultatF = $fournisseurs->fetch(PDO::FETCH_ASSOC)) 
	{
		if (!in_array($resultatF['ent_raison_sociale'], $raisonSocialeFournisseur)) 
		{
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['raisonSociale'] = $resultat['ent_raison_sociale'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['codeEntite'] = $resultat['ent_code_entite'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['contact'] = $resultat['ent_lib_contact'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['siret'] = $resultat['ent_siret'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['abreviation'] = $resultat['ent_abreviation'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['clefPhonetique'] = $resultat['ent_clefphonetique'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['categorie'] = $resultat['cat_libelle'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['adresse'] = str_replace(CHR(13).CHR(10),"*", $resultat['adr_complete']);
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['FormeJuridique'] = $resultat['foj_libelle'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['email'] = $resultat['lstm_mail'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['rechCategorie'] = $resultat['cat_lib_rech'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['rechFormeJuridique'] = $resultat['foj_lib_rech'];
			$raisonSocialeFournisseur[$resultat['ent_raison_rech']]['rechRaisonSociale'] = $resultat['ent_raison_rech'];
		}
	}
	$fournisseurs->closeCursor();
	$fournisseurs->execute();
	while ($resultat = $fournisseurs->fetch(PDO::FETCH_ASSOC)) 
	{
		if (isset($produitFournisseur[$resultat['ent_raison_rech']])) 
		{
			foreach ($produitFournisseur as  $key => $value) 
			{
				foreach ($value as $key2 => $value2) 
				{
					if ($value2 != $resultat['pro_lib_rech'] && !in_array($resultat['pro_lib_rech'], $produitFournisseur[$resultat['ent_raison_rech']]['produitFournisseur'])) 
					{
						$produitFournisseur[$resultat['ent_raison_rech']]['produitFournisseur'][sizeof($produitFournisseur[$resultat['ent_raison_rech']]['produitFournisseur'])] = $resultat['pro_lib_rech'];
						$produitFournisseur[$resultat['ent_raison_rech']]['commentaire'][sizeof($produitFournisseur[$resultat['ent_raison_rech']])-1] = str_replace(CHR(13).CHR(10),"*", $resultat['fou_commentaire']);
					}
				}
			}
		}
		else
		{
			$produitFournisseur[$resultat['ent_raison_rech']]['produitFournisseur'] = [$resultat['pro_lib_rech']];
			$produitFournisseur[$resultat['ent_raison_rech']]['commentaire'][sizeof($produitFournisseur[$resultat['ent_raison_rech']])-1] = str_replace(CHR(13).CHR(10),"*", $resultat['fou_commentaire']);
		}
		if (!in_array($resultat['cat_libelle'], $categorie)) 
		{
			$categorie[] = $resultat['cat_libelle'];
		}
		if (!in_array($resultat['foj_libelle'], $FJ)) 
		{
			$FJ[] = $resultat['foj_libelle'];
		}
	}
	$fournisseurs->closeCursor();
	$fournisseurs->execute();
	while ($resultat = $fournisseurs->fetch(PDO::FETCH_ASSOC)) 
	{
		if (isset($numeroTelephoneFournisseur[$resultat['ent_raison_rech']])) 
		{
			if (!is_null($resultat['lstt_numero'])) 
			{
				switch ($resultat['tpi_indicateur']) 
				{
					case 1:
						$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['telephoneFixe'] = $resultat['lstt_numero'];
						break;
					case 2:
						$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['mobile'] =  $resultat['lstt_numero'];
						break;
					case 3:
						$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['fax'] = $resultat['lstt_numero'];
						break;
				}
			}
		}
		else
		{
			switch ($resultat['tpi_indicateur']) 
			{
				case 1:
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['telephoneFixe'] = $resultat['lstt_numero'];
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['mobile'] = "";
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['fax'] = "";
					break;
				case 2:
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['telephoneFixe'] = "";
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['mobile'] =  $resultat['lstt_numero'];
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['fax'] = "";
					break;
				case 3:
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['telephoneFixe'] = "";
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['mobile'] = "";
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['fax'] = $resultat['lstt_numero'];
					break;
				default:
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['telephoneFixe'] = "";
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['mobile'] = "";
					$numeroTelephoneFournisseur[$resultat['ent_raison_rech']]['fax'] = "";
					break;
			}
		}
	}
	$listeFournisseur = "";
	$listeProduitFournisseur = "var listeProduitFournisseur = {\n";
	$listeNumeroFournisseur = "var listeNumeroFournisseur = {\n";
	$i = 0;
	foreach ($raisonSocialeFournisseur as $key => $value) 
	{
		$listeFournisseur.= $i.':{raisonSociale: "'.$raisonSocialeFournisseur[$key]['raisonSociale'].'", codeEntite: "'.$raisonSocialeFournisseur[$key]['codeEntite'].'", contact: "'.$raisonSocialeFournisseur[$key]['contact'].'", siret: "'.$raisonSocialeFournisseur[$key]['siret'].'", abreviation: "'.$raisonSocialeFournisseur[$key]['abreviation'].'", clefPhonetique: "'.$raisonSocialeFournisseur[$key]['clefPhonetique'].'", categorie: "'.$raisonSocialeFournisseur[$key]['categorie'].'", adresse: "'.$raisonSocialeFournisseur[$key]['adresse'].'", rechCategorie: "'.$raisonSocialeFournisseur[$key]['rechCategorie'].'", formeJuridique: "'.$raisonSocialeFournisseur[$key]['FormeJuridique'].'", email: "'.$raisonSocialeFournisseur[$key]['email'].'", rechFormeJuridique: "'.$raisonSocialeFournisseur[$key]['rechFormeJuridique'].'", rechRaisonSociale: "'.$raisonSocialeFournisseur[$key]['rechRaisonSociale'].'"},'."\n";
		$i++;
	}
	$i = 0;
	foreach ($produitFournisseur as $key => $value) 
	{
		if (sizeof($produitFournisseur[$key]['produitFournisseur']) == 1) 
		{
			$listeProduitFournisseur.= "'".$key."':{fournisseur: '".$key."', produitFournisseur : '".$produitFournisseur[$key]['produitFournisseur'][0]."', commentaire : '".$produitFournisseur[$key]['commentaire'][0]."'},\n";
		}
		else
		{
			for ($y = 0; $y <= sizeof($produitFournisseur[$key]['produitFournisseur'])-1; $y++)
			{
				$listeProduitFournisseur.= "'".$key."':{fournisseur: '".$key."', produitFournisseur : '".$produitFournisseur[$key]['produitFournisseur'][$y]."', commentaire : '".$produitFournisseur[$key]['commentaire'][$y]."'},\n";
			}
		}
		$i++;
	}
	foreach ($numeroTelephoneFournisseur as $key => $value) 
	{
		$listeNumeroFournisseur.= "'".$key."':{fournisseur: '".$key."', telephoneFixe: '".$value['telephoneFixe']."', mobile: '".$value['mobile']."', fax: '".$value['fax']."'},\n";
	}
	$listeProduitFournisseur[strlen($listeProduitFournisseur)-2] = "}";
	$listeProduit[strlen($listeProduit)-2] = "}";
	$listeNumeroFournisseur[strlen($listeNumeroFournisseur)-2] = "}";
	$AfficheProduits[strlen($AfficheProduits)-2] = "}";
	echo $listeProduit.";\n";
	echo $AfficheProduits.";\n";
	echo $listeNumeroFournisseur.";\n";
	echo $listeProduitFournisseur.";";
?>
  	</script>
</head>
<body>
	<div class="container" id="dev"><?= $AfficheProduits ?></div>
	<p hidden id="Type">Facture</p>
	<div class="container" id="containerProd1">
		<div class="row" style="border-bottom: 1px solid black; height: 40px; padding-top: 2px;">
			<div class="col-sm-12" style="color: #990099; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>Critère</strong></div>
		</div>
		<div class="row">
			<div class="col-sm-12" style="margin: 2% 0 2% 0;">
				<button onclick="effacer()" style="width: 45%; font-size: 12px; height: 45px; color: #990099" class="btn btn-primary">
					<span class="glyphicon glyphicon-erase"></span><br> Effacer
				</button>
				<button onclick="rechercher()"  style="width: 45%; font-size: 12px; height: 45px; color: #990099" class="btn btn-primary">
					<span class="glyphicon glyphicon-search"></span><br> Rechercher
				</button>
			</div>
			<div class="col-sm-12"></div>
		</div>	
		<div class="row">
			<div class="col-sm-12" style="color: #0033FF; background-image: linear-gradient(to bottom, white -20%, orange 70%);">Facture</div>
		</div>

		<div id="haut" style="margin: 0; padding: 0;"></div>



		<div id="formulaireProd" style="margin: 0; padding: 0; position: relative; top: 1%; width: 100%; height: 32%;">
			<div class="row ">
				<div class="col-sm-12" style="padding-left: 10%;">Num&eacute;ro</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><input type="text" id="Numero"></div>
			</div>
			<div class="row" style="margin-top: 5%;">
				<div class="col-sm-12">Dates</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="Dates">
						<option selected value="*">Aucune</option>
<?php 
	/* while ($resultat = $Dates->fetch(PDO::FETCH_ASSOC)) 
	{ 
		echo '<option value="'.$resultat[""].'">'.$resultat[""].'</option>\n'; 
	} */
?>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Montant TTC</div>
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
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Bon de commande</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12"><input type="text" id="Bon commande"  placeholder="N&#146; bon de commande"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12" style="color: #0033FF; background-image: linear-gradient(to bottom, white -20%, orange 70%);">Client</div>
		</div>

		<div id="haut" style="margin: 0; padding: 0;"></div>


		<div id="formulaireProd" style="margin: 0; padding: 0; position: relative; top: 1%; width: 100%; height: 32%;">
			<div class="row ">
				<div class="col-sm-12" style="padding-left: 10%;">Code</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><input type="text" id="Reference"></div>
			</div>
			<div class="row" style="margin-top: 5%;">
				<div class="col-sm-12">Type Client</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="TypeCli">
						<option selected value="*">Toute</option>
<?php 
	/* while ($resultat = $clients->fetch(PDO::FETCH_ASSOC)) 
	{ 
		echo '<option value="'.$resultat["fam_libelle"].'">'.$resultat["fam_libelle"].'</option>\n'; 
	} */
?>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Cat&eacute;gorie</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="Famille">
						<option selected value="*">Toutes</option>
<?php 
	/* while ($resultat = $clients->fetch(PDO::FETCH_ASSOC)) 
	{ 
		echo '<option value="'.$resultat["fam_libelle"].'">'.$resultat["fam_libelle"].'</option>\n'; 
	} */
?>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Client</div>
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
				<div class="col-sm-12"><input type="text" id="Libelle"  placeholder="Nom, Raison sociale ou abr&eacute;viation :"></div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="typeRechProd">
						<option selected value="*">Contient</option>
						<option value="Egal">Commence par</option>
						<option value="Contient">&Eacute;gal &agrave;</option>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12"><input type="text" id="Libelle"  placeholder="Pr&eacute;nom(s) :"></div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input type="checkbox" name="phonetique" value="oui" checked>Phon&eacute;tique
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Entit&eacute;</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12"><input type="text" id="Libelle"  placeholder="SIRET :"></div>
			</div>
		</div>

		<div id="FourniClick" style="margin: 0; padding: 0;"></div>

		<div class="row" style="position: absolute; bottom: 0; right: 0; left: 0; margin-bottom: 2%">
			<div class="col-sm-12">
				<button onclick="effacer()" style="width: 45%; font-size: 12px; height: 45px; font-weight: bold; font-size: 13px; color: #990099;" class="btn btn-primary">
					<span class="glyphicon glyphicon-erase"></span><br> Effacer
				</button>
				<button onclick="rechercher()"  style="width: 45%; font-size: 12px; height: 45px; font-weight: bold; font-size: 13px; color: #990099;" class="btn btn-primary">
					<span class="glyphicon glyphicon-search"></span><br> Rechercher
				</button>
			</div>
		</div>
	</div>



	<div class="container" id="containerProd2">
		<div class="row" style="border-bottom: 1px solid black; height: 40px; padding-top: 2px;">
			<div class="col-sm-9" style="padding-left: 8%; font-weight: bold; font-size: 13px; color: #990099; height: 30px; padding-top: 2px; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>Résultat <span id="NumDuProd">0</span>/<span id="NbProdMax">0</span></strong></div>
			<div class="col-sm-2">
				<button class="btn btn-primary disabled" title="Supprimez la facture s&eacute;lectionn&eacute;e" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-remove-sign"></span></button>
				<button class="btn btn-primary disabled" title="Ajoutez une facture" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-exclamation-sign"></span></button>
			</div>	
		</div>
		<div class="row">
			<div class="row" style="overflow: scroll; overflow-x: hidden;">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row" style="margin-top: 1%;">
						<div class="col-sm-2" style="height: 25px; width: 134px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>N&deg;</strong></div>
						<div class="col-sm-2" style="height: 25px; width: 134px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Client</strong></div>
						<div class="col-sm-2" style="height: 25px; width: 134px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Le</strong></div>
						<div class="col-sm-2" style="height: 25px; width: 134px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>&Eacute;ch&eacute;ance.</strong></div>
						<div class="col-sm-2" style="height: 25px; width: 134px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>BC</strong></div>
						<div class="col-sm-2" style="height: 25px; width: 134px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Total HT</strong></div>
						<div class="col-sm-2" style="height: 25px; width: 134px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Total TTC</strong></div>
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
						<div class="col-sm-1" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; width: 134px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="ref<?= $i ?>"></div>
						<div class="col-sm-1" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; width: 134px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="lib<?= $i ?>"></div>
						<div class="col-sm-1" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; width: 134px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="fam<?= $i ?>"></div>
						<div class="col-sm-1" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; width: 134px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="prix<?= $i ?>"></div>
						<div class="col-sm-1" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; width: 134px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="ref<?= $i ?>"></div>
						<div class="col-sm-1" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; width: 134px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="ref<?= $i ?>"></div>
						<div class="col-sm-1" onclick="selectProd(<?= $i ?>)" oncontextmenu="infoProd('open',<?= $i ?>); return false;" style="height: 20px; width: 134px; border: 1px solid black; border-top: none; background-color: white;" id="ref<?= $i ?>"></div>
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
			<div class="col-sm-5">
				<button style="margin-left: 15%; color: #990099; font-weight: bold; font-size: 15px;"><strong>Produits</strong></button>
				<button style="color: #990099; font-weight: bold; font-size: 15px;""><strong>R&egrave;glements</strong></div>
			<div class="col-sm-6">
				<button class="btn btn-primary disabled" title="Dupliquez le devis s&eacute;lectionn&eacute;" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-duplicate"></span></button>
				<button class="btn btn-primary disabled" title="R&eacute;glez la facture s&eacute;lectionn&eacute;e" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-eur"></span></button>
			</div>
		</div>
		<div class="row" style="overflow: scroll; overflow-x: hidden;">
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row" style="margin-top: 1%;">
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>R&eacute;f&eacute;rence</strong></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>D&eacute;signation</strong></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Qt&eacute;</strong></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>P.U.H.T. (&euro;)</strong></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>TOTAL H.T. (&euro;)</strong></div>
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
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="forme<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="raiSoc<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="cat<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="forme<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
						<div class="col-sm-3" style="height: 20px; width: 20%; border: 1px solid black; border-top: none; background-color: white;" oncontextmenu="infoFourn('open', <?= $i ?>); return false;" id="forme<?= $i ?>" onclick="selectFourn(<?= $i ?>)"></div>
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
			<div class="col-sm-8"></div>
			<div class="col-sm-4" style="text-align: right; padding-right: 30px;">
				<button onclick="window.close()" style="width:70px; height: 45px;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Quitter</button>
			</div>
		</div>
	</div>





	<div class="container" id="transparence"></div>





	<div class="container" id="infoProd">
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



		<div class="container" id="infoProdBody" style="position: absolute; top: 8%; right: 0; left: 0; margin: 0;">
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-2">IDENTIFICATION</div>
				<div class="col-sm-8"></div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-3">Famille</div>
					<div class="col-sm-7">
						<select id="infoFamProd">
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
					<div class="col-sm-2"></div>
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
					<div class="col-sm-7"><input type="text" id="infoLibProdModif"></div>
					<div class="col-sm-2"></div>
				</div>
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-3">Référence</div>
					<div class="col-sm-7"><input type="text" id="infoRefProdModif"></div>
					<div class="col-sm-2"></div>
				</div>
				<div class="col-sm-2" style=" margin-bottom: 5px;"></div>
			</div>
			<div class="row">
				<div class="col-sm-5" style=" margin-bottom: 5px;">
					<div class="col-sm-3">Description</div>
					<div class="col-sm-7"><textarea id="infoDescriptionProf" style="width: 120%;
					height: 100px;"></textarea></div>
					<div class="col-sm-2"></div>
				</div>
				<div class="col-sm-7"></div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-2">PRIX</div>
				<div class="col-sm-10"></div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<div class="col-sm-4" style="padding-right: 0">Prix HT</div>
					<div class="col-sm-8" style="padding-left: 0"><input type="number" id="infoPrixProd" onchange="calculTva()">&nbsp;€</div>
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
					<div class="col-sm-4" style="padding-right: 0">Prix TTC</div>
					<div class="col-sm-8" style="padding-left: 0"><input type="number" id="infoTvaPrix"></div>
				</div>
				<div class="col-sm-3"></div>
			</div>
			<div class="row" style="margin-top: 1.2%;">
				<div class="col-sm-2">FOURNISSEUR</div>
				<div class="col-sm-8"></div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-7">
					<div class="row">
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-10">
								<div class="row" style="background-color: white">
									<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none;"><strong>Forme</strong></div>
									<div class="col-sm-5" style="height: 20px; border: 1px solid black; border-right: none;"><strong>Raison Sociale</strong></div>
									<div class="col-sm-5" style="height: 20px; border: 1px solid black;"><strong>Catégorie</strong></div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
<?php
	for ($i = 0; $i <= 6; $i++) 
	{ 
?>
					<div class="row">
						<input type="number" id="numFournisseur<?= $i; ?>" hidden>
						<div class="col-sm-1"></div>
						<div class="col-sm-10">
							<div class="row" style="background-color: white">
								<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; border-top: none;" id="infoFormeFournisseur<?= $i ?>" onclick="commentaireFournisseur(<?= $i; ?>)"></div>
								<div class="col-sm-5" style="height: 20px; border: 1px solid black; border-right: none; border-top: none;" id="infoRaisonSocialeFournisseur<?= $i ?>" onclick="commentaireFournisseur(<?= $i; ?>)"></div>
								<div class="col-sm-5" style="height: 20px; border: 1px solid black; border-top: none;" id="infoCategorieFournisseur<?= $i ?>" onclick="commentaireFournisseur(<?= $i; ?>)"></div>
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
						<div class="col-sm-8">Note concernant le fournisseur séléctionné</div>
						<div class="col-sm-4"></div>
					</div>
					<div class="row">
						<div class="col-sm-8"><textarea style="width: 80%; height: 120px;" id="commentaire"></textarea></div>
						<div class="col-sm-4"></div>
					</div>
				</div>
			</div>
		</div>


		<div class="container" id="infoProdFooter">
			<div class="row" style="position: absolute; bottom: 0; left: 0; right: 0; margin: 0; height: 8.5%; border-top: 1px solid black">
				<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%;">
					<div class="row"><div class="col-sm-12"></div></div>
					<div class="col-sm-8"></div>
					<div class="col-sm-4" style="text-align: right;">
						<button onclick="infoProd('close')" style="width: 70px; height: 45px; " class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
					</div>
				</div>
			</div>
		</div>
	</div>





	<div class="container" id="infoFourn">
		<div class="container" id="infoFournHeader" style="border-bottom: 1px black solid; height: 8%; width: 100%; padding: 16px;">
			<div class="row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6" style="text-align: center;"><strong id="infoNomFournisseur">NomFournisseur</strong></div>
				<div class="col-sm-3"></div>
			</div>
		</div>



		<div class="container" id="infoFournBody">
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
				<div class="col-sm-2"></div>
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
					<textarea style="width: 67.5%; height: 50px;" id="infoCommentaireFourn"></textarea>
				</div>
			</div>
			<div class="row" style="margin-top: 0.2%;">
				<div class="col-sm-12"><strong>Les coordonnées</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-4" style="padding-right: 0">
					<div class="col-sm-4">Adresse</div>
					<div class="col-sm-8" style="padding-left: 2.5%">
						<select style="width: 100%;  height: 26px;" id="infoTypeAdresseFourn">
							<option value="Adresse bureau">Adresse bureau</option>
							<option value="Adresse courrier">Adresse courrier</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding-left: 0">
					<div class="col-sm-8" style="padding: 0;"><input type="checkbox" style="margin-left: 10px; width: 12px; height: 15px; float: left; margin-right: 5px;" id="infoAdresseDefautFourn"><p style="float: left;">Coordonné par défaut</p></div>
				</div>
			</div>
			<div class="row" style="margin-top: 0.4%; padding-left: 2%">
				<div class="col-sm-5">
					<div class="col-sm-3"></div>
					<div class="col-sm-9" style="padding-left: 0">
						<textarea style="width: 90%; height: 50px;" id="infoAdresseFourn"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12"><strong>Communication</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-4">
					<div class="col-sm-4">Téléphone</div>
					<div class="col-sm-8">
						<select style="width: 100%;  height: 26px;">
							<option value="Téléphone">Téléphone société</option>
							<option value="Télécopie">Télécopie société</option>
							<option value="Mobile">Mobile société</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding: 0;">
					<div class="col-sm-4" style="padding: 0;"><input type="tel" style="width: 100%;" id="infoTelephoneFourn"></div>
					<div class="col-sm-8" style="padding: 2px;"><input type="checkbox" style="margin-left: 10px; width: 12px; height: 15px; float: left; margin-right: 5px;" id="infoTelephoneDefautFourn"><p style="float: left;">Coordonnée par défaut</p></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="col-sm-4">Courriel</div>
					<div class="col-sm-8">
						<select style="width: 100%;  height: 26px;">
							<option value="1" selected>Principale</option>
							<option value="2">Secondaire</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding: 0;">
					<div class="col-sm-12" style="padding: 0;"><input type="text" style="width: 80%;" id="infoEmailFournisseur"></div>
				</div>
			</div>
			<div class="row" style="margin-top: 0.5%;">
				<div class="col-sm-8" style="padding-right: 0;">
					<div class="col-sm-2" style="padding-right: 0;">Site internet</div>
					<div class="col-sm-10" style="padding: 0 0 0 1%;"><input type="text" style="width: 100%;"></div>
				</div>
			</div>
		</div>



		<div class="container" id="infoFournFooter" style="position: absolute; bottom: 0; border-top: 1px solid black; height: 8%; width: 100%;">
			<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%;">
				<div class="row"><div class="col-sm-12"></div></div>
				<div class="col-sm-8"></div>
				<div class="col-sm-4" style="text-align: right;">
					<button onclick="infoFourn('close')" style="width: 70px; height: 45px; " class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>