<?php
	require('Autre/fpdf/fpdf.php');
	$save = "";
	$save1 = "";
	$villes = $villes->fetch(PDO::FETCH_ASSOC);
	$clients = $clients->fetch(PDO::FETCH_ASSOC);
	$pdf = new FPDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','B',13);
	$pdf->cell('200','',utf8_decode($clients['ent_abreviation']."  -  ".$clients['per_nomfamille']),'',1,'C');
	$pdf->SetFont('Times','',13);
	$pdf->ln();
	$pdf->cell('',7,'');
	$pdf->cell('',7,'');
	$pdf->ln();
	$pdf->cell('',7,'');
	$pdf->cell('',7,'');
	$pdf->ln();
	if ($clients["per_code_personne"][0] == "P")
    {
		$pdf->cell(120,10,utf8_decode('Type Client :  Personne'));
    }
    else 
    {
		$pdf->cell(120,10,utf8_decode('Type client:  Entite'));
    } 
    if ($clients['cat_libelle']!= "")
    {
		$pdf->cell(10,5,utf8_decode('Categorie client:  '.$clients['cat_libelle']),0,2);
	}
	$pdf->ln();
	if ($clients['per_nomfamille'] != "")
	{
		$pdf->cell(120,5,utf8_decode('Raison Sociale:  '.$clients['per_nomfamille']));
	}
	if ($clients['ent_abreviation'] != "")
	{
		$pdf->cell(10,5,utf8_decode('Abreviation:  '.$clients['ent_abreviation']),0,2);
	}
	$pdf->ln();
	if ($clients['foj_libelle'] != "")
	{
		$pdf->cell(120,5,utf8_decode('Forme juridique:  '.$clients['foj_libelle']));
	}
	$pdf->ln();
	if ($clients['ent_siret']!= "")
	{
		$pdf->cell(10,5,utf8_decode('SIRET/SIREN: '.$clients['ent_siret']),0,2);
	}
	$pdf->ln();
	if ($clients['foj_libelle'] == "*")
	{
		$pdf->cell(10,5,utf8_decode('Forme juridique:  '.$clients['foj_libelle']));
	}
	$pdf->cell(120,5,utf8_decode('Code entite:  '.$clients['per_code_personne']));
	if ($clients['ent_lib_contact'] != "")
	{
		$pdf->cell(10,5,utf8_decode('Contact:  '.$clients['ent_lib_contact']),0,2);
	}
	$pdf->ln();
	if ($clients['cli_commentaires'] != "")
	{
		$pdf->MultiCell(120,5,utf8_decode('Commentaires:  '.$clients['cli_commentaires']),0,2);
	}
	$pdf->ln();
	if ($villes['adr_complete'] != "")
	{
		$pdf->cell(120,5,utf8_decode('Adresse:  '.$villes['adr_complete']));
	}
	$pdf->ln();
	$pdf->ln();
	if ($villes['adr_complete'] == "adressePrincipale")
	{
		$pdf->cell(120,5,utf8_decode('Adresse Principale:  '.$villes['adr_complete']));
	}
	if ($villes['adr_complete'] == "adresseSecondaire")
	{
		$pdf->cell(120,5,utf8_decode('Adresse Secondaire:  '.$villes['adr_complete']));
	}
	if ($villes['adr_complete'] == "adresseBureau")
	{
		$pdf->cell(120,5,utf8_decode('Adresse Bureau:  '.$villes['adr_complete']));
	}
	if ($villes['adr_complete'] == "adresseCourrier")
	{
		$pdf->cell(120,5,utf8_decode('Adresse Courrier:  '.$villes['adr_complete']));
	}
	while ($resultat = $telMail->fetch(PDO::FETCH_ASSOC))
	{
		if ($resultat['lstt_numero'] != "")
		{
			switch ($resultat['lstt_idco_tp_indicateur']) 
			{
				case 1:
					$pdf->cell(30,5,utf8_decode('Téléphone Domicile:  '.$resultat['lstt_numero']),0,2);
					break;
				case 2:
					$pdf->cell(30,5,utf8_decode('Téléphone Bureau:  '.$resultat['lstt_numero']),0,2);
					break;
				case 3:
					$pdf->cell(30,5,utf8_decode('Télécopie Domicile:  '.$resultat['lstt_numero']),0,2);
					break;
				case 4:
					$pdf->cell(30,5,utf8_decode('Télécopie Bureau:  '.$resultat['lstt_numero']),0,2); 
					break;
				case 5:
					$pdf->cell(30,5,utf8_decode('Mobile Personnel:  '.$resultat['lstt_numero']),0,2); 
					break;
				case 6:
					$pdf->cell(30,5,utf8_decode('Mobile Professionnel:  '.$resultat['lstt_numero']),0,2); 
					break;
				case 7:
					$pdf->cell(30,5,utf8_decode('Téléphone Société:  '.$resultat['lstt_numero']),0,2);
					break;
				case 8:
					$pdf->cell(30,5,utf8_decode('Télécopie Société:  '.$resultat['lstt_numero']),0,2);
					break;
				case 9:
					$pdf->cell(30,5,utf8_decode('Mobile Société:  '.$resultat['lstt_numero']),0,2);
					break;
			}
		}
		if ($resultat['lstm_idco_tp_indicateur'] == 10)
		{
			$save = $resultat['lstm_mail'];
		}
		else if ($resultat['lstm_idco_tp_indicateur'] == 11)
		{
			$save1 = $resultat['lstm_mail'];
		}	
	}
	if ($save != "")
	{
		$pdf->ln();
		$pdf->cell(120,5,utf8_decode('Mail Principal:  '.$save));
		if ($save1 != "")
		{
			$pdf->cell(120,5,utf8_decode('Mail Secondaire:  '.$save1));
		}
	}
	$pdf->ln();
	$pdf->ln();
	if ($villes['co_adrinternet'] != "")
	{
	$pdf->cell(120,5,utf8_decode('Site internet:  '.$villes['co_adrinternet']));
	}
	$pdf->ln();


	$pdf->output();
?>