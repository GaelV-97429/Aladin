<?php // Module permettant de traiter les différentes actions de l'utilisateur
 	require './Modele/modele.php';
	function accueil()
	{
		require './vue/vueAccueil.php';
	}
	function ondemand()
	{
		require './vue/vueDemande.php';
	}
	function infos()
	{
		require './vue/vueInfos.php';
	}
	function procedures()
	{
		require './vue/vueProcedures.php';
	}
	function pdfCli()
	{
		$clients = getClients(str_replace("¤",'"',str_replace("*","&",$_GET['Cli'])));
		$categories = getCategories1();
		$formesJuridiques = getFormeJuridique();
		$telMail = getTelMail(str_replace("¤",'"',str_replace("*","&",$_GET['Cli'])));
		$villes = getVille(str_replace("¤",'',str_replace("*","&",$_GET['Cli'])));
		//var_dump((str_replace("¤","''",str_replace("*","&",$_GET['Cli']))));
		require './vue/vueCliPDF.php';
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
		require './vue/vueProduits.php';
	}
	function clients()
	{
		$clients = getClients();
		$categories = getCategories1();
		$formesJuridiques = getFormeJuridique();
		$telMail = getTelMail();
		$villes = getVille();
		require './vue/vueClients.php';
	}
	function inscription()
	{
		require './vue/vueInscription.php';
	}
	function devis()
	{
		$produits = getDevis();
		$clients = getClients();
		$categories = getCategories1();
		$formesJuridiques = getFormeJuridique();
		$telMail = getTelMail();
		require './vue/vueDevis.php';
	}
	function facture()
	{
		$produits = getProduits();
		$familles = getFamilles();
		$categories = getCategories();
		$formesJuridiques = getFormeJuridique();
		$fournisseurs = getFournisseurs();
		require './Vue/vueFacture.php';
	}
	function systeme()
	{
		require './vue/vueSysteme.php';
	}
	function profil()
	{
		require './vue/vueProfil.php';
	}
	function changeuser($msg = "")
	{
		require './vue/vueChangeUser.php';
	}
	function connexion($msg = "")
	{
		require './vue/vueConnexion.php';
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
		header("Location: vue/sauvegarde.php?x=test");
	}	 

	 
?>