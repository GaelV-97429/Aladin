<?php // Module permettant de communiquer les différentes informations du site avec la base de données.
	function getDb()
	{
		$db = new PDO('pgsql:dbname=Aladin;host=192.168.10.23;port=5432;', 'postgres', 'asipdg97429');
		return $db;
	}
	function verification() //Compare les champs d'identification remplis par l'utilisateur avec la table utilisateur
	{
		$db = getDb();
		if (isset($_POST['login']) && $_POST['password']) 
		{
			$requete = $db->query("SELECT uti_login, uti_motdepasse, uti_administrateur, uti_actif, uti_identite FROM utilisateur;");
			while ($resultat = $requete->fetch(PDO::FETCH_ASSOC)) 
			{
				if ($resultat['uti_login'] == strtoupper($_POST['login']) && $resultat['uti_motdepasse'] == strtoupper($_POST['password']))
				{
					@session_start();
					$utilisateur = ["identite" => $resultat['uti_identite'], "administrateur" => $resultat['uti_administrateur'], "actif" => $resultat['uti_actif']];
					$_SESSION['infoUtilisateur'] = $utilisateur;
					return true;
				}
			}
		}
		return false;
	}
	function getProduits() //Renvoie l'ensemble des produits
	{
		$db = getDb();
		$requete = $db->query("SELECT DISTINCT p.pro_libelle, p.pro_lib_rech, fam_lib_rech, p.pro_description, p.pro_reference, 
						   					   fam_libelle, p.pro_pxvente_ht, p.pro_iactif, tva_libelle, tva_pourcentage 
							   FROM (produit p LEFT OUTER JOIN famille f ON p.pro_idfamille = f.fam_idfamille) LEFT OUTER JOIN tva 
							   ON tva.tva_idtva = p.pro_idtva 
							   ORDER BY fam_libelle, pro_libelle;");
		return $requete;
	}
	function getFamilles() //Renvoie la famille correspondant aux produits
	{
		$db = getDb();
		$requete = $db->query("SELECT fam_libelle, fam_iactif, fam_lib_rech FROM famille ORDER BY fam_libelle;");
		return $requete;
	}
	function getCategories() //Renvoie les différentes catégories du fournisseur.
	{
		$db = getDb();
		$requete = $db->query("SELECT cat_libelle, cat_iactif, cat_lib_rech FROM categorie WHERE cat_tpcategorie = 2 ORDER BY cat_libelle;");
		return $requete;
	}
	function getFormeJuridique() //Renvoie les formes juridiques
	{
		$db = getDb();
		$requete = $db->query("SELECT foj_libelle, foj_iactif, foj_lib_rech FROM forme_juridique ORDER BY foj_libelle;");
		return $requete;
	}
	function getFournisseurs() //Renvoie l'ensemble des entités fournisseur avec leurs infos
	{
		$db = getDb();
		$requete = $db->query("SELECT DISTINCT ent_raison_sociale, pro_lib_rech, co_tplien, ent_code_entite, ent_lib_contact, ent_raison_rech,
								      ent_siret, ent_abreviation, fou_commentaire, ent_clefphonetique, cat_libelle, tpi_libelle, tpi_indicateur, 
								      lsta_coord_defaut, adr_complete, lstt_numero, lstt_coord_defaut, lstm_mail, cat_lib_rech, 
								      foj_libelle, foj_lib_rech 
							   FROM ((((((((((fournisseur LEFT OUTER JOIN prod_four ON fou_idfournisseur = pfo_idfournisseur) 
							         LEFT OUTER JOIN produit ON pfo_idproduit = pro_idproduit)
							         LEFT OUTER JOIN categorie ON fou_idcategorie = cat_idcategorie)
							         LEFT OUTER JOIN entite ON fou_identite = ent_identite) 
							         LEFT OUTER JOIN forme_juridique ON ent_idforme = foj_idforme_juridique) 
								 	 LEFT OUTER JOIN co_coordonnee ON co_idlien = ent_identite)
								 	 LEFT OUTER JOIN co_lst_adresse ON lsta_idco_coordonnee = co_idco_coordonnee)
								 	 LEFT OUTER JOIN adresse ON adr_idadresse = lsta_idadresse)
								 	 LEFT OUTER JOIN co_lst_tel cotel ON co_idco_coordonnee = lstt_idco_coordonnee)
								 	 LEFT OUTER JOIN co_tp_indicateur ON lstt_idco_tp_indicateur = idco_tp_indicateur)
								 	 LEFT OUTER JOIN co_lst_mail comail ON lstm_idco_coordonnee = co_idco_coordonnee
							   ORDER BY ent_raison_sociale, foj_libelle;");
		return $requete;
	}
	function getClients($clients = '') //Renvoie l'ensemble des Clients (entités et personnes) avec leurs infos correspondantes.
	{
		$clients1 = "";
		if ($clients != '')
		{
			$clients1 = "AND per_nomfamille  = '".$clients."'";
			$clients = "WHERE ent_raison_sociale  = '".$clients."'";
		}
		$db = getDb();
		$requete = $db->query("SELECT PER_TITRE, PER_CODE_PERSONNE, PER_NOMMAJ AS RECH_NOM_MAJ, PER_PRENOMMAJ AS RECH_PRENOM_MAJ, PER_NOMFAMILLE, 
									  PER_DEUXIEME_NOM AS ENT_SIRET, PER_PRENOMS, CLI_IDCATEGORIE,  CAT_LIBELLE, CLI_IDCLIENT, PER_IDPERSONNE, 
									  CAT_IACTIF, NULL AS FOJ_LIBELLE, CAT_TPCATEGORIE,  NULL AS ENT_ABREVIATION, NULL AS ENT_LIB_CONTACT, 
									  CLI_COMMENTAIRES, 1 
							   FROM (PERSONNE LEFT OUTER JOIN CLIENT ON(PER_IDPERSONNE=CLI_IDLIEN AND CLI_TPLIEN=1) LEFT OUTER JOIN CATEGORIE ON(
							    	CLI_IDCATEGORIE=CAT_IDCATEGORIE)) 
							   WHERE per_titre IS NOT NULL ".$clients1."
							   UNION 
							   SELECT ENT_IDFORME, ENT_CODE_ENTITE, ENT_RAISON_RECH AS RECH_NOM_MAJ, NULL AS RECH_PRENOM_MAJ, ENT_RAISON_SOCIALE, ENT_SIRET, 
							          NULL, CLI_IDCATEGORIE, CAT_LIBELLE, CLI_IDCLIENT, ENT_IDENTITE, CAT_IACTIF, FOJ_LIBELLE, CAT_TPCATEGORIE, ENT_ABREVIATION, ENT_LIB_CONTACT, CLI_COMMENTAIRES, 2 
							   FROM (ENTITE LEFT OUTER JOIN CLIENT ON(ENT_IDENTITE=CLI_IDLIEN AND CLI_TPLIEN=2)) LEFT OUTER JOIN CATEGORIE ON
							   		(CLI_IDCATEGORIE=CAT_IDCATEGORIE) LEFT OUTER JOIN FORME_JURIDIQUE ON(ENT_IDFORME=FOJ_IDFORME_JURIDIQUE) ".$clients." 
							   ORDER BY 11");
		return $requete;
	}
	function getTelMail($TelMail = '') //Renvoie l'ensemble des numéros de téléphone et mails des clients(personnes etentités) et leurs infos correspondantes.
	{
		$TelMail1 = "";
		if ($TelMail != '')
		{
			$TelMail1 = "WHERE per_nomfamille = '".$TelMail."'";
			$TelMail = "WHERE ent_raison_sociale = '".$TelMail."'";
		}
		$db = getDb();
		$requete = $db->query("SELECT DISTINCT ent_code_entite, ent_raison_sociale, NULL AS per_prenoms, 
							   lstt_numero, lstt_idco_tp_indicateur, lstt_coord_defaut, lstm_mail, lstm_idco_tp_indicateur, 1 AS ID
							   FROM ((entite ent LEFT OUTER JOIN co_coordonnee coor ON ent.ent_identite = coor.co_idlien)LEFT OUTER JOIN co_lst_tel cotel ON coor.co_idco_coordonnee = cotel.lstt_idco_coordonnee) LEFT OUTER JOIN co_lst_mail mail
							   ON mail.lstm_idco_coordonnee = coor.co_idco_coordonnee ".$TelMail."
							   UNION
							   SELECT DISTINCT per_code_personne AS ent_code_entite, per_nomfamille, per_prenoms,  lstt_numero, lstt_idco_tp_indicateur, lstt_coord_defaut, lstm_mail, lstm_idco_tp_indicateur, 2 AS ID
							   FROM ((personne per LEFT OUTER JOIN co_coordonnee coor ON per.per_idpersonne = coor.co_idlien)LEFT OUTER JOIN co_lst_tel cotel ON coor.co_idco_coordonnee = cotel.lstt_idco_coordonnee) LEFT OUTER JOIN co_lst_mail mail
							   ON mail.lstm_idco_coordonnee = coor.co_idco_coordonnee ".$TelMail1."
							   ORDER BY 1,8;");
		return $requete;
	}
	function getCategories1() //Renvoie les différentes catégories de la personne(client et entités)
	{
		$db = getDb();
		$requete = $db->query("SELECT cat_libelle FROM categorie WHERE cat_tpcategorie = 1");
		return $requete;
	}

	function getVille($villes = '') //Renvoie la commune, site internet, adresse des personnes(clients et entités).
	{
		$villes1 = "";
		if ($villes != '')
		{
			$villes1 = "AND per_nomfamille = '".$villes."'";
			$villes = "AND ent_raison_sociale = '".$villes."'";
		}
		$db = getDb();
		$requete = $db->query("SELECT DISTINCT NULL AS per_nomfamille, ent_code_entite, lsta_idco_tp_indicateur, com_nom_min, adr_complete, coad.lsta_coord_defaut, 
							   co_adrinternet, 1 AS id
							   FROM (((entite ent LEFT OUTER JOIN  co_coordonnee coor
     						   ON ent.ent_identite = coor.co_idlien)LEFT OUTER JOIN co_lst_adresse coad
      						   ON coad.lsta_idco_coordonnee = coor.co_idco_coordonnee) LEFT OUTER JOIN adresse adr
						   	   ON coad.lsta_idadresse = adr.adr_idadresse)LEFT OUTER JOIN commune com ON com.com_idcommune = adr.adr_idcommune, 
						   	   co_tp_indicateur cotp
							   WHERE cotp.idco_tp_indicateur = coor.co_tplien ".$villes."
							   UNION 
							   SELECT DISTINCT per_nomfamille, per_code_personne AS ent_code_entite, lsta_idco_tp_indicateur, com_nom_min, adr_complete, coad.lsta_coord_defaut, co_adrinternet, 2 AS id
							   FROM (((personne per LEFT OUTER JOIN  co_coordonnee coor
     						   ON coor.co_idlien = per.per_idpersonne)LEFT OUTER JOIN co_lst_adresse coad
     						   ON coad.lsta_idco_coordonnee = coor.co_idco_coordonnee) LEFT OUTER JOIN adresse adr
     						   ON coad.lsta_idadresse = adr.adr_idadresse) LEFT OUTER JOIN commune com ON com.com_idcommune = adr.adr_idcommune, 
     						   co_tp_indicateur cotp
							   WHERE cotp.idco_tp_indicateur = coor.co_tplien ".$villes1."
							   ORDER BY 1,3;");		
		return $requete;
	}
	function getReglement() //Renvoie les dates de règlement des factures.
	{
		$db = getDb();
		$requete = $db->query("SELECT reg_dtreglement FROM reglement");
	}
	function getDevis() //Renvoie l'ensemble des devis.
	{
		$db = getDb();
		$requete = $db->query("SELECT pco_num_piece, ent_raison_sociale, pco_dtpiece, pco_dtecheance, pco_total_ht, pco_total_ttc FROM piece_comptable piecomp, entite e, client cli WHERE piecomp.pco_idclient = cli.cli_idclient AND cli.cli_idlien = e.ent_identite AND piecomp.pco_tppiece = 1;");
	}
	function getFacture() //Revnoie l'ensemble des factures.
	{
		$db = getDb();
		$requete = $db->query("SELECT pco_num_piece, ent_raison_sociale, pco_dtpiece, pco_dtecheance, pco_total_ht, pco_total_ttc FROM piece_comptable piecomp, entite e, client cli WHERE cli.cli_idclient = piecomp.pco_idclient AND piecomp.pco_tppiece = 2;");
	}
	function getFactureImp() //Renvoie l'ensemble des entités n'ayant toujours pas payé leurs factures.
	{
		$db = getDb();
		$requete = $db->query("SELECT ent_raison_sociale, pco_total_ttc FROM entite e, piece_comptable piecomp, client cli WHERE cli.cli_idclient = piecomp.pco_idclient AND piecomp.pco_tppiece = 2 AND piecomp.pco_etat_piece = 5;");
	}
	// fonctions pour la page paramètres
	function getParamAdrVille() 
	{
		$db = getDb();
		$requete = $db->query("");
	}
	function getParamAdrVilleEtr() //Renvoie la ville étrangère.
	{
		$db = getDb();
		$requete = $db->query("SELECT vet_nom_maj, pay_nom_maj FROM pays pay, ville_etrangere vet WHERE pay.pay_idpays = vet.vet_idpays;");
		return $requete;
	}
	function getParamAdrDepFrance() //Renvoie l'ensemble des départements avec leur code département correspondant.
	{
		$db = getDb();
		$requete = $db->query("SELECT dep_code_dept, dep_nom_min FROM departement");
	}
	function getParamAdrPaysEtr() //Renvoie l'ensemble des pays étrangers avec leur code INSEE correspondant.
	{
		$db = getDb();
		$requete = $db->query("SELECT pay_code_insee, pay_nom_maj, pay_membre_ce, pay_nationalite FROM pays ORDER BY pay_nom_maj ASC;");
		return $requete;
	}
	function getParamCliFourCat() //Renvoie les différentes catégories du client et fournisseur.
	{
		$db = getDb();
		$requete = $db->query("SELECT cat_libelle, cat_tpcategorie, cat_iactif FROM categorie ORDER BY cat_libelle ASC;"); 
		return $requete;
	}
	function getParamFOJ()  //Renvoie les formes juridiques
	{
		$db = getDb();
		$requete = $db->query("SELECT foj_libelle, foj_iactif FROM forme_juridique ORDER BY foj_libelle ASC;");
		return $requete;
	}
	function getParamProdFam() //Revnoie les familles de produits
	{
		$db = getDb();
		$requete = $db->query("SELECT fam_libelle, fam_iactif FROM famille ORDER BY fam_libelle ASC;");
		return $requete;
	}
	function getParamProdTVA() //Renvoie les différents taux de TVA avec leur libelle
	{
		$db = getDb();
		$requete = $db->query("SELECT tva_code, tva_libelle, tva_pourcentage, tva_np FROM tva;");
		return $requete;
	}
	function getParamProdTex() //Renvoie les départements ayant une exception du taux de TVA.
	{
		$db = getDb();
		$requete = $db->query("SELECT tva_libelle, dep_nom_maj, tex_taux FROM tva tva, departement dep, tva_exception tex WHERE tex.tex_idtva = tva.tva_idtva AND dep.dep_iddepartement = tex.tex_iddepartement;");
		return $requete;
	}
	function getParamDevFactMtr() //Renvoie le mode de transmission pour les pièces comptables.
	{
		$db = getDb();
		$requete = $db->query("SELECT mtr_libelle, mtr_actif FROM mode_transmission ORDER BY mtr_libelle ASC;");
		return $requete;
	}
	function getParamDevfactMdr() //Renvoie le mode de règlement d'une pièce comptable.
	{
		$db = getDb();
		$requete = $db->query("SELECT mdr_libelle, mdr_actif FROM mode_reglement ORDER BY mdr_libelle ASC;");
		return $requete;
	}
	function getParamDevFactMot1() //Renvoie les motifs d'annulatino d'une pièce comptable.
	{
		$db = getDb();
		$requete = $db->query("SELECT mot_libelle, mot_actif FROM motif WHERE mot_tpmotif = 1 ORDER BY mot_libelle ASC;");
		return $requete;
	}
	function getParamDevFactMot2() //Renvoie les motifs de refus d'une pièce comptable.
	{
		$db = getDb();
		$requete = $db->query("SELECT mot_libelle, mot_actif FROM motif WHERE mot_tpmotif = 2 ORDER BY mot_libelle ASC;");
		return $requete;
	}
	function getParamParNum()
	{
		$db = getDb();
		$requete = $db->query("SELECT ");
	}
	function getParamParMep() //Renvoie la mise en page d'une pièce comptable.
	{
		$db = getDb();
		$requete = $db->query("SELECT mep_libelle, mep_defaut, mep_actif FROM mise_en_page ORDER BY mep_libelle;");
		return $requete;
	}
	function execute($requete)
	{
		$db = getDb();
		var_dump($requete);
		var_dump($db->query(utf8_encode($requete)));
	}
	
?>