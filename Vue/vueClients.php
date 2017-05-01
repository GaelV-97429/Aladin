<?php
	@session_start();
	if (isset($_SESSION['infoUtilisateur']))
	{
?>
<!DOCTYPE html>
<html>
<head>
	<title>AladIN</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Autre/Style.css">
	<link rel="stylesheet" href="Autre/fpdf.php">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script type="text/javascript" src="Autre/fonctionCli.js"></script>
  	<script type="text/javascript">


<?php
	$y = 0;
	$z = 0;
	while ($clients->fetch(PDO::FETCH_ASSOC)) #Intialisation de la liste des clients en php
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
                   		 "Prenom" => "",
                   		 "Commentaires" => "",
                   		 "actifPhonetique" => "",
                   		 "SIRET" => "",
                   		 "FOJ" => "",
                   		 "Adresse" => array("adressePrincipale"=>"","adresseSecondaire"=>"","adresseBureau"=>"","adresseCourrier"=>""),
                   		 "Ville" => "",
                   		 "Site Internet" => "",
                   		 "tel" => array("telephoneSociete"=>"","telephoneDomicile"=>"","mobileProfessionnel"=>"","mobilePersonnel"=>"","mobileSociete"=>"","telephoneBureau"=>"","faxDomicile"=>"","faxSociete"=>"","faxBureau"=>""),
                   		 "mail" => array("courrierPrincipal"=>"","courrierSecondaire"=>"")
                 		];
	}
    $saveTM = array();
    $Devis = array();
    $Facture = array();
    $categorie = array();
    $FJ = array();
    $categoriecli = array();
    $i = 0;
    
	$clients->closeCursor();
	$clients->execute();

    while ($resultatC = $clients->fetch(PDO::FETCH_ASSOC)) #Récupération des informations des clients
    {
        $lstClients[$i]["code"] = $resultatC['per_code_personne'];
        $lstClients[$i]["Categorie"] = $resultatC['cat_libelle'];
        $lstClients[$i]["typeRechCliNom"] = str_replace('"',"'",$resultatC['per_nomfamille']);
        $lstClients[$i]["actifPhonetique"] = $resultatC['cat_iactif'];
        $lstClients[$i]["T"] = $resultatC['per_titre'];
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
        if (!in_array($resultatC["cat_libelle"], $categorie))
		{
			$categorie[] = $resultatC["cat_libelle"];
		}
		if (!in_array($resultatC["foj_libelle"], $FJ))
		{
			$FJ[] = $resultatC["foj_libelle"];
		}
        $i++;
    }
	$SaveC = [];
	for ($j=0; $j<=sizeof($lstClients)-1; $j++)
	{
		$SaveM = [];
		while ($resultatTM = $telMail->fetch(PDO::FETCH_ASSOC)) #Récupération des informations liées aux différents téléphones et mails.
		{
			if ($resultatTM["ent_code_entite"] == $lstClients[$j]["code"])
			{
				if ($resultatTM["lstm_idco_tp_indicateur"] == 10)
				{
					$lstClients[$j]["mail"]["courrierPrincipal"]=$resultatTM["lstm_mail"];
				}
				else if ($resultatTM["lstm_idco_tp_indicateur"] == 11)		
				{
					$lstClients[$j]["mail"]["courrierSecondaire"]=$resultatTM["lstm_mail"];
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
							$lstClients[$j]["tel"]["telephoneSociete"]=$resultatTM["lstt_numero"];
							break;
						case '8':
							$lstClients[$j]["tel"]["faxSociete"]=$resultatTM["lstt_numero"];
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
							$lstClients[$j]["tel"]["telephoneSociete"]=$resultatTM["lstt_numero"];
							break;
						case '8':							
							$lstClients[$j]["tel"]["faxSociete"]=$resultatTM["lstt_numero"];
							break;
						case '9':
							$lstClients[$j]["tel"]["mobileSociete"]=$resultatTM["lstt_numero"];
							break;
					}
				}
			}
		}
		$telMail->closeCursor();
		$telMail->execute();
	}
		while ($resultatVille = $villes->fetch(PDO::FETCH_ASSOC)) #Récupération des informations des adresses
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
	foreach ($lstClients as $key => $value) #Conversion de la liste PHP de clients, mails, téléphones et adresses en une liste produit JavaScript
	{
		$AfficheClients.= $i.":{".'T: "'.$value["T"].'", code:"'.$value["code"].'", Categorie: "'.$value["Categorie"].'", typeRechCliNom: "'.$value["typeRechCliNom"].'", actifPhonetique: "'.$value['actifPhonetique'].'", SIRET: "'.$value["SIRET"].'", FOJ: "'.$value['FOJ'].'", ville: "'.$value["Ville"].'", abreviation: "'.$value["Abreviation"].'", contact: "'.$value["Contact"].'", website: "'.$value["Site Internet"].'", commentaires: "'.$value["Commentaires"].'", mail: {courrierPrincipal: "'.$value["mail"]["courrierPrincipal"].'", courrierSecondaire: "'.$value["mail"]["courrierSecondaire"].'"}, tel: {telephoneDomicile: "'.$value['tel']["telephoneDomicile"].'", telephoneBureau: "'.$value['tel']["telephoneBureau"].'", faxDomicile: "'.$value['tel']["faxDomicile"].'", faxBureau: "'.$value['tel']["faxBureau"].'", mobilePersonnel: "'.$value['tel']["mobilePersonnel"].'", mobileProfessionnel: "'.$value['tel']["mobileProfessionnel"].'", faxSociete: "'.$value['tel']["faxSociete"].'", telephoneSociete: "'.$value['tel']["telephoneSociete"].'", mobileSociete: "'.$value['tel']["mobileSociete"]. '"}, adresseBureau: "'.$value["Adresse"]["adresseBureau"].'", adressePrincipale: "'.$value["Adresse"]["adressePrincipale"].'", adresseSecondaire: "'.$value["Adresse"]["adresseSecondaire"].'", adresseCourrier: "'.$value["Adresse"]["adresseCourrier"].'"},';
		$AfficheClients.="\n";
		$i++;
	}
		
	$AfficheClients[strlen($AfficheClients)-2] = "}";
	$AfficheClients[strlen($AfficheClients)-1] =";";
	echo $AfficheClients;
?> 


	$(document).ready(function(){
		$("body").keydown(function(event){            //Initialisation des raccourcis clavier
			if (event.keyCode == 27)                  //27 -> touche Echap (fermeture fenêtre) 
			{
				switch ("visible")
				{
					case (document.getElementById('containerMSG').style.visibility):
						document.getElementById('transparenceMSG').style.visibility = 'hidden';
						document.getElementById('containerMSG').style.visibility = 'hidden';
						break;
					case (document.getElementById('AddDelCategorieCli').style.visibility):
						document.getElementById('rechTransparence').style.visibility = 'hidden';
						document.getElementById('AddDelCategorieCli').style.visibility = 'hidden';
						break;
					case (document.getElementById('rechCategorieCli').style.visibility):
						document.getElementById('transparenceCli').style.visibility = 'hidden';
						document.getElementById('rechCategorieCli').style.visibility = 'hidden';
						break;
					//case (document.getElementById('rechCategorieFourn').style.visibility):
						//document.getElementById('transparenceFourn').style.visibility = 'hidden';
						//document.getElementById('rechCategorieFourn').style.visibility = 'hidden';
						//break;
					case (document.getElementById('AddDelFormeJuridiqueCli').style.visibility):
						document.getElementById('rechFOJTransparence').style.visibility = 'hidden';
						document.getElementById('AddDelFormeJuridiqueCli').style.visibility = 'hidden';
						break;
					case (document.getElementById('rechFormeJuridiqueCli').style.visibility):
						document.getElementById('transparenceCli').style.visibility = 'hidden';
						document.getElementById('rechFormeJuridiqueCli').style.visibility = 'hidden';
						break;
					//case (document.getElementById('infoProd').style.visibility):
						//document.getElementById('transparence').style.visibility = 'hidden';
						//document.getElementById('infoProd').style.visibility = 'hidden';
						//break;
					case (document.getElementById('infoCli').style.visibility):
						document.getElementById('transparence').style.visibility = 'hidden';
						document.getElementById('infoCli').style.visibility = 'hidden';
						break;
				}
			}
		})
	})
	$("#containerProd1 input").keydown(function(e){   //Initialisation des raccourcis clavier
		if (e.keyCode == 13)                          // 13 -> touche Entree (permet de lancer une recherche)
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

	var lstChangements = "";
	var lstSaveCurrentInfoCli = {categorieCli: "",
								  raisonSociale: "",
								  Abreviation: "",
								  formejuridique: "",
								  Siret: "",
								  codeentite: "",
								  contact: "",
								  commentaires: "",
								  adresse: "",
								  defautcoord: 0,
								  adrdefault: "",
								  telephone: "",
								  telCli: "",
								  defauttel: 0,
								  courriel: "",
								  email: "",
								  siteweb: "",
								 };
	var lstSaveCurrentInfoCli = [];
	$(window).bind('beforeunload', function(){
	  return 'Are you sure you want to leave?';
	});
  	</script>
</head>
<body onload="exit()">
	<div class="container" id="dev"><?php var_dump($lstClients)?></div>


	<div class="container" id="transparenceMSG"></div>
	<div class="container" id="containerMSG" style="visibility:hidden">
		<div class="row" style="width: 100%; height: 30px; margin: 0">
			<div class="col-sm-12" style="height: 100%; width: 100%;"><strong style="color: red;" id="TitreMSG">Erreur:</strong></div>
		</div>
		<div class="row" style="width: 100%; height: 65%; margin: 0; overflow: auto; overflow-x: hidden;">
			<div class="col-sm-12" style="width: 100%; height: 100%; padding-left: 35px; padding-right: 35px;" id="MSG"></div>
		</div>
		<div class="row" style="width: 100%; height: 30px; margin: 0">
			<div class="col-sm-12" style="text-align: center; margin: 0;">
				<button class="btn btn-default" onclick="document.getElementById('containerMSG').style.visibility = 'hidden';document.getElementById('transparenceMSG').style.visibility = 'hidden';" id="ok">OK</button>

				<button class="btn btn-default" onclick="addEditCli('ok')" style="display: none" id="Save">Sauvegarder</button>
				<button class="btn btn-default" onclick="infoCli('close')" style="display: none" id="Cancel">Annuler</button>
			</div>
		</div>
	</div>
	<div class="container" id="transparenceRech"></div>
	<div class="container" id="containerRech" style="visibility:hidden">
		<div class="row" style="width: 100%; height: 30px; margin: 0">
			<div class="col-sm-12" style="height: 100%; width: 100%"><strong style="color: red" id="TitreRech">Votre choix ?</strong></div>
		</div>
		<div class="row" style="width: 100%; height: 65%; margin: 0; overflow: auto; overflow-x: hidden;">
			<div class="col-sm-12" style="width: 100%; height: 100%; padding-left: 35px; padding-right: 35px;" id="Rech"></div>
		</div>
		<div class="row" style="width: 100%; height: 30px; margin: 0">
			<div class="col-sm-12" style="text-align: center; margin: 0;">
				<button class="btn btn-default" onclick="document.getElementById('containerRech').style.visibility = 'hidden';document.getElementById('transparenceRech').style.visibility = 'hidden';" oncontextmenu="infoCli('open',<?= $i ?>); return false;" id="ok">oui</button>
				<button class="btn btn-default" onclick="document.getElementById('containerRech').style.visibility = 'hidden';document.getElementById('transparenceRech').style.visibility = 'hidden';" id="non">non</button>

				<button class="btn btn-default" onclick="addEditCli('ok')" style="display: none" id="SaveR">Sauvegarder</button>
				<button class="btn btn-default" onclick="infoCli('close')" style="display: none" id="CancelR">Annuler</button>
			</div>
		</div>
	</div>
	<p hidden id="Type">Client</p>
	<div class="container" id="containerProd1">
		<div class="row" style="border-bottom: 1px solid black; height: 40px; padding-top: 2px;">
			<div class="col-sm-12" style="color: #990099; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>Critère</strong></div>
		</div>
		<div class="row">
			<div class="col-sm-12" style="margin: 2% 0 2% 0;">
				<button onclick="effacer()" style="padding: 2px 0 2px 0; width: 45%; font-size: 12px; height: 45px; font-weight: bold; font-size: 12px; color: #990099; background-color: #0088FF;" class="btn btn-default">
					<span class="glyphicon glyphicon-erase"></span><br> Effacer
				</button>
				<button onclick="rechercher()"  style="padding: 2px 0 2px 0; width: 45%; font-size: 12px; height: 45px; font-weight: bold; font-size: 12px; color: #990099; background-color: #0088FF;" class="btn btn-info">
					<span class="glyphicon glyphicon-search"></span><br> Rechercher
				</button>
			</div>
			<div class="col-sm-12"></div>
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
				<div class="col-sm-12"><input type="text" id="Code"></div>
			</div>
			<div class="row" style="margin-top: 5%;">
				<div class="col-sm-12">Type Client</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="TypeCli">
						<option selected value="*">Toute</option>
						<option value="Personne">Personne</option>
						<option value="Entite">Entit&eacute;</option>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Cat&eacute;gorie</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="Categorie">
						<option selected value="*">Toutes</option>
<?php 
	while ($resultat = $categories->fetch(PDO::FETCH_ASSOC)) 
	{ 
		echo '<option value="'.$resultat["cat_libelle"].'">'.$resultat["cat_libelle"].'</option>\n'; 
	} 
?>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Client</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="typeRechCli">
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
					<select id="typeRechCli1">
						<option value="*">Commence par</option>
						<option value="Egal">Égal à</option>
						<option selected value="Contient">Contient</option>
					</select>
				</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12"><input type="text" id="Prenom"  placeholder="Pr&eacute;nom(s) :"></div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input type="checkbox" id="phonetique" value="oui" checked>Phon&eacute;tique
				</div>
			</div>
			<div class="row" style="margin-top: 5%">
				<div class="col-sm-12">Entit&eacute;</div>
			</div>
			<div class="row" style="margin-top: 1%;">
				<div class="col-sm-12"><input type="text" id="SIRET"  placeholder="SIRET :"></div>
			</div>	
			<div class="row">
				<div class="col-sm-12" style="margin-top: 5%;">Forme juridique</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<select id="FormeJuridique">
						<option  value="*" selected>Toute</option>
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

		<div class="row" style="position: absolute; bottom: 0; right: 0; left: 0; margin-bottom: 2%">
			<div class="col-sm-12">
				<button onclick="effacer()" style="padding: 2px 0 2px 0; width: 45%; font-size: 12px; height: 45px; font-weight: bold; font-size: 12px; color: #990099; background-color: #0088FF;" class="btn btn-default">
					<span class="glyphicon glyphicon-erase"></span><br> Effacer
				</button>
				<button onclick="rechercher()"  style="padding: 2px 0 2px 0; width: 45%; font-size: 12px; height: 45px; font-weight: bold; font-size: 12px; color: #990099; background-color: #0088FF;" class="btn btn-info">
					<span class="glyphicon glyphicon-search"></span><br> Rechercher
				</button>
			</div>
		</div>
	</div>



	<div class="container" id="containerProd2">
		<div class="row" style="border-bottom: 1px solid black; height: 40px; padding-top: 2px;">
			<div class="col-sm-9" style="padding-left: 8%; font-weight: bold; font-size: 13px; color: #990099; height: 30px; padding-top: 2px; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>Résultat <span id="NumDuCli">0</span>/<span id="NbCliMax">0</span></strong></div>
			<div class="col-sm-2">
				<button class="btn btn-danger" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;" disabled id="DelCli"><span class="glyphicon glyphicon-minus" onclick="delCli()"></span></button>
				<button class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;" disabled id="AddCli" onclick="addEditCli('add')"><span class="glyphicon glyphicon-plus"></span></button>
			</div>	
		</div>
		<div class="row">
			<div class="row" style="overflow: scroll; overflow-x: hidden;">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row" style="margin-top: 1%;">
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>T.</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Code</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Titre/Forme</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Identit&eacute;</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Cat&eacute;gorie</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em;"><strong>Ville</strong></div>
					</div>
				</div>
				<div class="col-sm-1"></div>
			</div>
		</div>
		<input type="number" id="SaveLigneCli" hidden value="0">
		<div class="row" style="overflow: auto; overflow-x: hidden; height: 75%;">
<?php
	for ($i = 0; $i <= 300; $i++) 
	{ 
?>
			<div class="row">
				<input type="number" id="indexClient<?= $i ?>" value="0" hidden>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" oncontextmenu="infoCli('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="T<?= $i ?>"></div>
						<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" oncontextmenu="infoCli('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="code<?= $i ?>"></div>
						<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" oncontextmenu="infoCli('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="entite<?= $i ?>"></div>
						<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" oncontextmenu="infoCli('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="nom<?= $i ?>"></div>
						<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" oncontextmenu="infoCli('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" id="dep<?= $i ?>"></div>
						<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" oncontextmenu="infoCli('open',<?= $i ?>); return false;" style="height: 20px; border: 1px solid black; border-top: none; border-top: none; background-color: white;" id="ville<?= $i ?>"></div>
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
			<div class="col-md-4" style="color: #990099; font-weight: bold; font-size: 15px; padding-left: 8%;"><strong>Devis</strong></div><div class="col-md-4" style="color: #990099; font-weight: bold; font-size: 15px; padding-left: 8%;"><strong>Facture</strong></div>
			<div class="col-md-3">
				<button class="btn btn-primary disabled" id="createF" title="Créer une facture pour le client sélectionné" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-pencil"></span></button>
				<button class="btn btn-primary disabled" id="createD" title="Créer un devis pour le client sélectionné" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px;"><span class="glyphicon glyphicon-hand-up"></span></button>
			</div>	
		</div>

		<div class="row" style="overflow: scroll; overflow-x: hidden;">
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row" style="margin-top: 1%;">
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>N&deg;</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Le</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>&Eacute;ch&eacute;ance</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>BC</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Total HT</strong></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Total TTC</strong></div>
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
			<input type="number" id="indexCli<?= $i ?>" value="0" hidden>
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoCli('open', <?= $i ?>); return false;" id="num<?= $i ?>" onclick="selectCli(<?= $i ?>)"></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoCli('open', <?= $i ?>); return false;" id="le<?= $i ?>" onclick="selectCli(<?= $i ?>)"></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoCli('open', <?= $i ?>); return false;" id="echeance<?= $i ?>" onclick="selectCli(<?= $i ?>)"></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoCli('open', <?= $i ?>); return false;" id="bc<?= $i ?>" onclick="selectCli(<?= $i ?>)"></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-right: none; border-top: none; background-color: white;" oncontextmenu="infoCli('open', <?= $i ?>); return false;" id="HT<?= $i ?>" onclick="selectCli(<?= $i ?>)"></div>
						<div class="col-sm-2" style="height: 20px; border: 1px solid black; border-top: none; background-color: white;" oncontextmenu="infoCli('open', <?= $i ?>); return false;" id="TTC<?= $i ?>" onclick="selectCli(<?= $i ?>)"></div>
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





	<div class="container" id="infoCli">
		<div class="container" id="transparenceCli" style="visibility:hidden"></div>

		<div class="container" id="rechCategorieCli" style="visibility:hidden">
			<div class="container" id="rechTransparence" style="visibility:hidden"></div>
				<div class="container" id="AddDelCategorieCli" style="visibility:hidden">
					<div class="row" style="height: 50px; padding-top: 2px;">
						<div class="col-sm-10" style="margin-left: 2%; color: #990099; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>D&eacute;tail d&#146; une cat&eacute;gorie</strong></div>
					</div>
						<div class="container" id="containerCategorieBody" style="margin-top: 5%; position:absolute; top:8%; left:0; right:0; height: 60%; width:100%;overflow: auto; overflow-x: hidden; padding-bottom: 20px; width: 100%;">
							<div class="row" style="margin-bottom: 1%; width:100%"></div>
								<div class="row" style="overflow: hidden; margin-left: 2%; width:100%">
									<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
										<div class="row" style="margin-top: 0.4%;">
											<div class="col-sm-10">
												<div class="col-sm-2">Libelle</div>
												<div class="col-sm-10"><input style="width: 100%; height: 100%" type="text" id="infoLibCategorie"></div>
											</div>
											<div class="col-sm-2" style="margin-bottom: 7%"></div>
										</div>	
									<div class="row" style="margin-top: 0.4%">
										<div class="col-sm-10">
											<div class="col-sm-2">Type</div>	
											<div class="col-sm-7">	
												<select style="height: 26px;" id="infoCategorieCli">
													<option value="*" selected>Aucun</option>
													<option value="Client" id="infoAddCat">Client</option>
													<option value="Fournisseur" id="infoAddFourn">Fournisseur</option>
<?php
	for ($i = 0; $i <= sizeof($categoriecli)-1; $i++)
	{
		if ($categoriecli[$i] != "") 
		{
			echo "<option value='".$categoriecli[$i]."'>".$categoriecli[$i]."</option>\n";
		}
	}
	?>
												</select>
											</div>					
										</div>			
									</div>
									<div class="row" style="margin-top: 0.4%">
										<div class="col-sm-12">
											<div class="col-sm-8" style="padding-left: 0"><input type="checkbox" id="actif" style="width: 14px; height: 14px; margin-left: 27%"> Actif</div>
											<div class="col-sm-2"></div>
										</div>	
									</div>
									</div>
								</div>
						</div>
							<div class="container" id="ContainerCategorieFooter" style="border-top: 1px solid black; position: absolute; bottom: 0; left:0; right:0; height: 25%; width: 100%;">
								<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%;">
									<div class="row"><div class="col-sm-12"></div></div>
									<div class="col-sm-6"></div>
										<div class="col-sm-6" style="text-align: right;">
											<button onclick="document.getElementById('rechTransparence').style.visibility='hidden'; document.getElementById('AddDelCategorieCli').style.visibility='hidden'" style="width: 25%; height: 45px; float:right; " class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
											<button onclick="addEditCli()" style="padding: 1px 1px 2px 1px; width: 30%; height: 99%; float: right; margin-right: 2.5%;" class="btn btn-warning"  id="btnEnregistrement"><span class="glyphicon glyphicon-home"></span><br>Enregistrer</button>
										</div>
								</div>
							</div>
				</div>




			<div class="container" id="containerCategorieHeader" style="height: 8%; width:100%;"></div>
			<div class="container" id="containerCategorieBody" style="position:absolute; top:8%; left:0; right:0; height: 84%; width:100%;overflow: auto; overflow-x: hidden; padding-bottom: 20px; width: 100%;">
				<div class="row" style="margin-bottom: 1%; width:100%">
					<div class="col-sm-2"></div>
					<div class="col-sm-10" style="padding-right:0">
						<button class="btn btn-danger" title="Supprimez la catégorie sélectionnée" id="btnDelCategorie" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px; margin-right: 4%;"><span class="glyphicon glyphicon-minus"></span></button>
						<button onclick="document.getElementById('transparenceCli').style.visibility = 'visible';document.getElementById('rechTransparence').style.visibility='visible'; document.getElementById('AddDelCategorieCli').style.visibility = 'visible';" class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;"><span class="glyphicon glyphicon-plus" title="Ajoutez une catégorie" id="btnAddCategorie"></span></button>
					</div>
					<div class="col-sm-1"></div>
				</div>
				<div class="row" style="overflow: hidden; margin-left: 2%; width:100%">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-6" style=" width: 50.2%; height: 25px; border: 1px solid black; border-right: none; font-weight: bold; background-color: #222222; color: white; opacity: 0.9; background-color: #222222; opacity: 0.9; color: white;">Categorie</div>
							<div class="col-sm-2" style="width: 16.7%; height: 25px; border: 1px solid black; border-right: none; font-weight: bold; background-color: #222222; color: white; opacity: 0.9; background-color: #222222; opacity: 0.9; color: white;">Type</div>
							<div class="col-sm-2" style="width:16.8%; height: 25px; border: 1px solid black; font-weight: bold; background-color: #222222; color: white; opacity: 0.9; text-align: center; background-color: #222222; opacity: 0.9; color: white;">Actif</div>
						</div>
					</div>
				</div>
				<div class="row" style="height: 80%; overflow: hidden; margin-left: 2%;">
					<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
<?php
	$clients->closeCursor();
	$clients->execute();
	$categorieCli = array();
	while ($resultat = $clients->fetch(PDO::FETCH_ASSOC)) 
	{ 
		if (!in_array($resultat['cat_libelle'], $categorieCli)) 
		{
			if ($resultat['cat_libelle'] != "")
			{
?>
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-6" onclick="selectCli(<?= $i ?>)" style="border: 1px solid black; border-right: none; border-top: 0; height: 25px;"><?php echo $resultat['cat_libelle']; ?></div>
							<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" style="border: 1px solid black; height: 25px; border-top: none; border-right: none; text-align: center;">Client</div>
							<div class="col-sm-2" onclick="selectCli(<?= $i ?>)" style="border: 1px solid black; height: 25px; border-top: none; text-align: center;"><?php if ($resultat['cat_iactif'] == 1){ echo "<span class='glyphicon glyphicon-ok' style='color: green'></span>"; } else { echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>"; } ?></div>
							<div class="col-sm-1"></div>
						</div>
<?php 
				$categorieCli[] = $resultat['cat_libelle'];
			}
		}
	}
	for ($i = 0; $i <= 19; $i++) 
	{ 
?>
						<div class="row">
							<div class="col-sm-1"></div>
							<div class="col-sm-6" style="border: 1px solid black; border-right: none; border-top: 0; height: 25px;"></div>
							<div class="col-sm-2" style="border: 1px solid black; border-right: none; height: 25px; border-top: 0;"></div>
							<div class="col-sm-2" style="border: 1px solid black; height: 25px; border-top: 0;"></div>
							<div class="col-sm-1"></div>
						</div>
<?php
	}
?>
					</div>
				</div>
			</div>
			<div class="container" id="containerCategorieFooter" style="position:absolute; bottom:0; left:0; right:0; height: 50px; border-top: 1px solid black; width: 100%;">
				<div class="row" style="height: 100%;">
					<div class="col-sm-10"></div>
					<div class="col-sm-2" style="padding: 0.5% 0.5% 0.5% 0; height: 100%">
						<button onclick="document.getElementById('rechCategorieCli').style.visibility = 'hidden'; document.getElementById('transparenceCli').style.visibility= 'hidden';" style="padding: 2px 1px 2px 1px; width: 70px; height: 42px; line-height: 16px; float: right;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
					</div>
				</div>
			</div>	
		</div>

			<div class="container" id="rechFormeJuridiqueCli" style="visibility: hidden">
				<div class="container" id="rechFOJTransparence" style="visibility:hidden"></div>
					<div class="container" id="AddDelFormeJuridiqueCli" style="visibility:hidden">
						<div class="row" style="height: 50px; padding-top: 2px;">
							<div class="col-sm-10" style="margin-left: 2%; color: #990099; font-size: 18px; text-shadow: 2px 5px #BBBBBB"><strong>D&eacute;tail d&#146; une forme juridique</strong></div>
						</div>
							<div class="container" id="containerFormeJuridiqueBody" style="margin-top: 5%; position:absolute; top:8%; left:0; right:0; height: 60%; width:100%;overflow: auto; overflow-x: hidden; padding-bottom: 20px; width: 100%;">
								<div class="row" style="margin-bottom: 1%; width:100%"></div>
									<div class="row" style="overflow: hidden; margin-left: 2%; width:100%">
										<div class="row" style="height: 100%; overflow: scroll; overflow-x: hidden;">
											<div class="row" style="margin-top: 0.4%;">
												<div class="col-sm-10">
													<div class="col-sm-2">Libelle</div>
													<div class="col-sm-10"><input style="width: 100%; height: 100%" type="text" id="infoLibFormeJuridique"></div>
												</div>
												<div class="col-sm-2" style="margin-bottom: 7%"></div>
											</div>	
										<div class="row" style="margin-top: 0.4%">
											<div class="col-sm-12">
												<div class="col-sm-8" style="padding-left: 0"><input type="checkbox" id="actifFOJ" style="width: 14px; height: 14px; margin-left: 27%"> Actif</div>
												<div class="col-sm-2"></div>
											</div>	
										</div>
										</div>
									</div>
							</div>
								<div class="container" id="ContainerFormeJuridiqueFooter" style="border-top: 1px solid black; position: absolute; bottom: 0; left:0; right:0; height: 25%; width: 100%;">
									<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%;">
										<div class="row"><div class="col-sm-12"></div></div>
										<div class="col-sm-6"></div>
											<div class="col-sm-6" style="text-align: right;">
												<button onclick="document.getElementById('rechFOJTransparence').style.visibility='hidden'; document.getElementById('AddDelFormeJuridiqueCli').style.visibility='hidden'" style="width: 25%; height: 45px; float:right; " class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
												<button onclick="addEditCli()" style="padding: 1px 1px 2px 1px; width: 30%; height: 99%; float: right; margin-right: 2.5%;" class="btn btn-warning"  id="btnEnregistrement"><span class="glyphicon glyphicon-home"></span><br>Enregistrer</button>
											</div>
									</div>
								</div>
					</div>



				<div class="container" id="containerFormeJuridiqueHeader" style="height: 8%;"></div>
				<div class="container" id="containerFormeJuridiqueBody" style="height: 82%; overflow: auto; overflow-x: hidden; width: 100%;">
					<div class="row" style="margin-bottom: 1%; width: 100%">
						<div class="col-sm-2"></div>
						<div class="col-sm-10">
							<button class="btn btn-danger" title="Supprimez la forme juridique sélectionnée" id="btnDelFormeJuridique" style="height: 30px; padding: 0; width: 35px; float: right; margin-left: 5px; margin-right: 2%;"><span class="glyphicon glyphicon-minus"></span></button>
							<button onclick="document.getElementById('transparenceCli').style.visibility = 'visible';document.getElementById('rechFormeJuridiqueCli').style.visibility='visible'; document.getElementById('AddDelFormeJuridiqueCli').style.visibility = 'visible';" class="btn btn-success" style="height: 30px; padding: 0; width: 35px; float: right;"><span class="glyphicon glyphicon-plus" title="Ajoutez une catégorie" id="btnAddCategorie"></span></button>
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
							<button onclick="document.getElementById('rechFormeJuridiqueCli').style.visibility = 'hidden'; document.getElementById('transparenceCli').style.visibility= 'hidden';" style="margin-top: 5px; padding: 2px 1px 2px 1px; width: 70px; height: 45px; line-height: 16px; float: right;" class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
						</div>
					</div>
				</div>
			</div>
		<input type="number" id="SaveLigneCli" hidden value="0">





		<div class="container" id="infoClientHeader" style="width: 100%; margin: 0; height: 8%; border-bottom: 1px solid black;">
			<div class="row" style="width: 100%; height: 100%;">
				<div class="col-sm-12" style="width: 100%; text-align: center; padding: 2.3% 0 0 0;">
					<p style="width: 100%; height: inherit;">
						<span id="typeRechCliNom" style="padding-right: 0;">Test</span> 
					</p>
				</div>
			</div>
		</div>





		<div class="container" id="infoCliBody" style="height: 84%;">
			<input type="text" id="currentCli" hidden>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-12"><strong>Identification</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-5">
					<div class="col-sm-4">Type 	Client</div>
					<div class="col-sm-8">
						<select disabled style="height: 26px;" id="infoTypeCli">
							<option value="entite" id="infoEntCli" selected>Entité</option>
							<option value="personne" id="infoPersCli">Personne</option>
						</select>
					</div>
				</div>
				<div class="col-sm-7">
					<div class="col-sm-3">Catégorie client</div>
					<div class="col-sm-6" style="padding-left: 0.6%;">
						<select style="height: 26px; width: 71.7%;" id="infoCatCli">
							<option value="*" selected>Non renseignée</option>
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
						<button onclick="document.getElementById('transparenceCli').style.visibility = 'visible'; document.getElementById('rechCategorieCli').style.visibility = 'visible';document.getElementById('rechCategorieCli').style.display = 'block';" id="rechCatCli" style="width: 25px; height: 25px; padding: 0;"><span class="glyphicon glyphicon-search"></span></button>
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
					<div class="col-sm-8"><input type="text" id="infoRaiSocCli"></div>
				</div>
				<div class="col-sm-5">
					<div class="col-sm-4">Abréviation</div>
					<div class="col-sm-8"><input type="text" id="infoAbreviationCli"></div>
				</div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-5">
					<div class="col-sm-4">Forme juridique</div>					
					<div class="col-sm-8">
						<select style="height: 26px;" id="infoFjCli">
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
						<button onclick="document.getElementById('transparenceCli').style.visibility = 'visible'; document.getElementById('rechFormeJuridiqueCli').style.visibility = 'visible';document.getElementById('rechFormeJuridiqueCli').style.display = 'block';" id="rechFOJCli" style="width: 25px; height: 25px; padding: 0;"><span class="glyphicon glyphicon-search"></span></button>
					</div>					
				</div>
				<div class="col-sm-5">
					<div class="col-sm-4">SIRET / SIREN</div>
					<div class="col-sm-8"><input type="text" id="infoSiretCli"></div>
				</div>
				<div class="col-sm-2">
				</div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-5">
					<div class="col-sm-4">Code entité</div>
					<div class="col-sm-8"><input type="text" id="infoCodeEntiteCli"></div>
				</div>
				<div class="col-sm-5">
					<div class="col-sm-4">Contact</div>
					<div class="col-sm-8"><input type="text" id="infoContactCli"></div>
				</div>
				<div class="col-sm-2"></div>
			</div>
			<div class="row">
				<div class="col-sm-12"><strong>Commentaires</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-12">
					<textarea style="width: 67.5%; height: 50px;" id="infoCommentaireCli"></textarea>
				</div>
			</div>
			<div class="row" style="margin-top: 0.2%;">
				<div class="col-sm-12"><strong>Les coordonnées</strong></div>
			</div>
			<div class="row" style="margin-top: 0.4%;">
				<div class="col-sm-4" style="padding-right: 0">
					<div class="col-sm-4">Adresse</div>
					<div class="col-sm-8" style="padding-left: 2.5%">
						<select onchange="changeAdr()" style="width: 100%;  height: 26px;" id="infoTypeAdresseCli">
							<option value="Adressebureau" id="infoAdrCli">Adresse bureau</option>
							<option value="Adressecourrier" id="infoCourCli">Adresse courrier</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding-left: 0">
					<div class="col-sm-8" style="padding: 0;"><input type="checkbox" style="margin-left: 10px; width: 12px; height: 15px; float: left; margin-right: 5px;" id="infoAdresseDefautCli"><p style="float: left;">Coordonnée par défaut</p></div>
				</div>
			</div>
			<div class="row" style="margin-top: 0.4%; padding-left: 2%">
				<div class="col-sm-5">
					<div class="col-sm-3"></div>
					<div class="col-sm-9" style="padding-left: 0">
						<textarea style="width: 90%; height: 50px;" id="infoAdresseCli"></textarea>
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
						<select onchange="changeNum()" id="infoTelCli" style="width: 100%;  height: 26px;">
							<option id="tel" value="">Téléphone société</option>
							<option id="telecop" value="">Télécopie société</option>
							<option id="mob" value="">Mobile société</option>
							<option id="teldom" value="">Telephone domicile</option>
							<option id="telbur" value="">Téléphone bureau</option>
							<option id="telecdom" value="">Télécopie domicile</option>
							<option id="telecbur" value="">Télécopie bureau</option>
							<option id="mobperso" value="">Mobile personnel</option>
							<option id="mobpro" value="">Mobile professionnel</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding: 0;">
					<div class="col-sm-4" style="padding: 0;"><input type="tel" style="width: 100%;" id="infoTelephoneCli"></div>
					<div class="col-sm-8" style="padding: 2px;"><input type="checkbox" style="margin-left: 10px; width: 12px; height: 15px; float: left; margin-right: 5px;" id="infoTelephoneDefautCli"><p style="float: left;">Coordonnée par défaut</p></div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="col-sm-4">Courriel</div>
					<div class="col-sm-8">
						<select onchange="changeCour()" id="infoCourrielCli" style="width: 100%;  height: 26px;">
							<option id="infoCourPrincip" value="1" selected>Principale</option>
							<option id="infoCourSecond" value="2">Secondaire</option>
						</select>
					</div>
				</div>
				<div class="col-sm-5" style="padding: 0;">
					<div class="col-sm-12" style="padding: 0;"><input type="text" style="width: 80%;" id="infoEmailCli"></div>
				</div>
			</div>
			<div class="row" style="margin-top: 0.5%;">
				<div class="col-sm-8" style="padding-right: 0;">
					<div class="col-sm-2" style="padding-right: 0;">Site internet</div>
					<div class="col-sm-10" style="padding: 0 0 0 1%;"><input type="text" id="infoSiteCli" style="width: 100%;"></div>
				</div>
			</div>
		</div>


		<input type="text" hidden value="" id="newCli">
		<div class="container" id="infoCliFooter" style="position: absolute; bottom: 0; left:0; right:0; border-top: 1px solid black; height: 8%; width: 100%;">
			<div class="row" style="position: absolute; bottom: 5px; right: 5px; width: 100%;">
				<div class="row"><div class="col-sm-12"></div></div>
				<div class="col-sm-6"></div>
				<div class="col-sm-6" style="text-align: right;">
					<button onclick="infoCli('close')" style="width: 70px; height: 52px; float:right; " class="btn btn-warning"><span class="glyphicon glyphicon-home"></span><br>Fermer</button>
					<button onclick="addEditCli()" style="padding: 5px 1px 5px 1px; width: 80px; height: 100%; float: right; margin-right: 2.5%;" class="btn btn-warning"  id="btnEnregistrement"><span class="glyphicon glyphicon-home"></span><br>Enregistrer</button>
					<button onclick="pdf()" style="padding: 5px 1px 5px 1px; width: 80px; height: 52px; float: right; margin-right: 3%;" class="btn btn-success" id="btnPDF"><span class="glyphicon glyphicon-download-alt"></span><br>Edit</button>
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
