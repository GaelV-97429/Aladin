<?php
require 'Controleur/controleur.php';
	if (isset($_GET['action'])) 
	{
		switch ($_GET['action']) 
		{
			case 'Accueil':
				accueil();
				break;
			case 'Statistiques':
				ondemand();
				break;
			case 'Connexion':
				connexion();
				break;
			case 'VerificationConnexion':
				verificationConnexion();
				break;
			case 'Apropos':
				infos();
				break;
			case 'Procedures':
				procedures();
				break;
			case 'pdf':
				pdf();
				break;
			case 'pdfClients':
				pdfCli();
				break;
			case 'Produits':
				produits();
				break;
			case 'Clients':
				clients();
				break;
			case 'Inscription':
				inscription();
				break;
			case 'Devis':
				devis();
				break;
			case 'Facture':
				facture();
				break;
			case 'Systeme':
				systeme();
				break;
			case 'Profil':
				profil();
				break;
			case 'ChangeUser':
				changeuser();
				break;
			case 'SaveDataBase':
				save();
				break;

			default:
				if (isset($_SESSION['infoUtilisateur']))
				{
					accueil();
				}
				else
				{
					connexion();
				}
				break;
		}
	}
	else
	{
		if (isset($_SESSION['infoUtilisateur']))
		{
			accueil();
		}
		else
		{
			connexion();
		}
	}
?>