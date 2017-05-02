<?php // Module permettant de traiter les différentes actions de l'utilisateur
 	require './Modele/modele.php';
	function accueil()
	{
		require 'Vue/vueAccueil.php';
	}
	function ondemand()
	{
		require 'Vue/vueDemande.php';
	}
	function infos()
	{
		require 'Vue/vueInfos.php';
	}
	function procedures()
	{
		require 'Vue/vueProcedures.php';
	}
	function pdfCli()
	{
		$clients = getClients(str_replace("¤",'"',str_replace("*","&",$_GET['Cli'])));
		$categories = getCategories1();
		$formesJuridiques = getFormeJuridique();
		$telMail = getTelMail(str_replace("¤",'"',str_replace("*","&",$_GET['Cli'])));
		$villes = getVille(str_replace("¤",'',str_replace("*","&",$_GET['Cli'])));
		//var_dump((str_replace("¤","''",str_replace("*","&",$_GET['Cli']))));
		require 'Vue/vueCliPDF.php';
	}
	function produits()
	{
		$produits = getProduits();
		$familles = getFamilles();
		$categories = getCategories();
		$formesJuridiques = getFormeJuridique();
		$fournisseurs = getFournisseurs();
		$telMails = getTelMail();
		$adresses = getVille();
		$clients = getClients();
		require 'Vue/vueProduits.php';
	}
	function clients()
	{
		$clients = getClients();
		$categories = getCategories1();
		$formesJuridiques = getFormeJuridique();
		$telMail = getTelMail();
		$villes = getVille();
		require 'Vue/vueClients.php';
	}
	function inscription()
	{
		require 'Vue/vueInscription.php';
	}
	function devis()
	{
		$produits = getDevis();
		$clients = getClients();
		$categories = getCategories1();
		$formesJuridiques = getFormeJuridique();
		$telMail = getTelMail();
		require 'Vue/vueDevis.php';
	}
	function facture()
	{
		$produits = getProduits();
		$familles = getFamilles();
		$categories = getCategories();
		$formesJuridiques = getFormeJuridique();
		$fournisseurs = getFournisseurs();
		require 'Vue/vueFacture.php';
	}
	function systeme()
	{
		require 'Vue/vueSysteme.php';
	}
	function profil()
	{
		require 'Vue/vueProfil.php';
	}
	function changeuser($msg = "")
	{
		require 'Vue/vueChangeUser.php';
	}
	function connexion($msg = "")
	{
		require 'Vue/vueConnexion.php';
	}
	function verificationConnexion()
	{
		if (verification()) 
		{
			accueil();
		}
		else
		{
			connexion("Identifiant ou mot de passe incorrect.");
		}
	}

	function save()
	{
		$array = $_POST['liste'];
		for ($i = 0; $i <= substr_count($array, "*")-1; $i++)
		{
			$req = str_replace(CHR(164), "\n", explode("*", $array)[$i]);
			execute($req);
		}
		header("Location: Vue/sauvegarde.php?x=test");
	}	
	function pdf()
	{
		$telMail = getTelMail();
		$adresses = getVille();
		$produit = getProduits($_GET['prod']);
		$fournisseurs = getFournisseurs($_GET['prod']);
		require 'Vue/vueProdPdf.php';
	} 

	 
?>
