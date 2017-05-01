<?php
	require('Autre/fpdf/fpdf.php');
	$produit = $produit->fetch(PDO::FETCH_ASSOC);
	$pdf = new FPDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Times','B',13);
	$pdf->cell(0,'',utf8_decode($produit['pro_reference']."  -  ".$produit['pro_libelle']),'',1,'C');
	$pdf->SetFont('Times','',13);
	$pdf->ln();
	$pdf->cell('',14,'');
	$pdf->ln();
	$pdf->cell(140,5,utf8_decode('Désignation :  '.$produit['pro_libelle']));
	$pdf->cell(10,5,utf8_decode('Référence :  '.$produit['pro_reference']),0,2);
	$pdf->ln();
	$pdf->ln();
	if ($produit['pro_iactif'] == 1) 
	{
		$actif = "Actif";
	}
	else
	{
		$actif = "Inactif";
	}
	$pdf->cell(140,5,utf8_decode('Famille :  '.$produit['fam_libelle']));
	$pdf->cell(10,5,utf8_decode('Produit :  '.$actif),0,2);
	$pdf->ln();
	$pdf->ln();
	for ($i = 0; $i <= substr_count($produit['pro_description'], "\n"); $i++)
	{
		$Description = explode("\n", $produit['pro_description'])[$i];
		if ($i == 0 && substr_count($produit['pro_description'], "\n") == 0) 
		{
			$pdf->cell(10,5,utf8_decode('Description :  '.$Description),0,2);
		}
		else if ($i == 0 && substr_count($produit['pro_description'], "\n") > 0)
		{
			$pdf->cell(10,5,utf8_decode('Description : - '.$Description),0,2);
		}
		else if ($i > 0 && substr_count($produit['pro_description'], "\n") > 0)
		{
			$pdf->cell(10,5,utf8_decode('                      - '.$Description),0,2);
		}
		else
		{
			$pdf->cell(10,5,utf8_decode('                       '.$Description),0,2);
		}
	}
	$pdf->ln();
	$pdf->ln();
	$pdf->cell(60,5,'Prix HT : '.$produit['pro_pxvente_ht']." ".CHR(128));
	$pdf->cell(80,5,utf8_decode('TVA : '.$produit['tva_libelle']."( ".$produit['tva_pourcentage']."% )"));
	$pdf->cell(10,5,'Prix TTC : '.round(floatval($produit['pro_pxvente_ht'])*((floatval($produit['tva_pourcentage'])/100)+1),2)." ".CHR(128),0,2);
	$pdf->ln();
	$pdf->ln();
	$pdf->cell(30,9,'Forme',1,0,'C');
	$pdf->cell(105,9,'Nom / Raison sociale',1,0,'C');
	$pdf->cell(55,9,utf8_decode("Catégorie"),1,0,'C');
	$save = array();
	$lstFourn = array();
	$NbLigne = 0;
	while ($fourn = $fournisseurs->fetch(PDO::FETCH_ASSOC))
	{
		if (!in_array($fourn['ent_raison_sociale'], $save)) 
		{
			$pdf->ln();
			$pdf->cell(30,13,utf8_decode($fourn['foj_libelle']),1,0,'C');
			$pdf->cell(105,13,utf8_decode($fourn['ent_raison_sociale']),1,0,'C');
			$pdf->cell(55,13,utf8_decode($fourn['cat_libelle']),1,0,'C');
			$save[] = $fourn['ent_raison_sociale'];
			$lstFourn[] = ["contact" => $fourn['ent_lib_contact'],"raisonSociale" => $fourn['ent_raison_sociale'], "codeEntite" => $fourn['ent_code_entite'], "adresse" => $fourn["adr_complete"]];
			$NbLigne++;
		}
	}
	if ($NbLigne < 6) 
	{
		for ($i = 0; $i <= 4; $i++) 
		{ 
			$pdf->ln();
			$pdf->cell(30,13,'',1,0,'C');
			$pdf->cell(105,13,'',1,0,'C');
			$pdf->cell(55,13,'',1,0,'C');
		}
	}
	else
	{
		for ($i = 0; $i <= 2; $i++) 
		{ 
			$pdf->ln();
			$pdf->cell(30,13,'',1,0,'C');
			$pdf->cell(105,13,'',1,0,'C');
			$pdf->cell(55,13,'',1,0,'C');
		}
	}
	if ($NbLigne > 0) 
	{
		while ($res = $telMail->fetch(PDO::FETCH_ASSOC))
		{
			for ($i = 0; $i <= sizeOf($lstFourn)-1; $i++)
			{
				if ($res['ent_raison_sociale'] == $lstFourn[$i]['raisonSociale']) 
				{
					if (!isset($lstFourn[$i]['telephoneSociete']))
					{
						$lstFourn[$i] = ["raisonSociale" => $lstFourn[$i]["raisonSociale"], "adresse" => $lstFourn[$i]['adresse'], "codeEntite" => $lstFourn[$i]['codeEntite'], "contact" => $lstFourn[$i]['contact'], "telephoneSociete" => "", "telephoneBureau" => "", "telephoneDomicile" => "", "faxSociete" => "", "faxBureau" => "", "faxDomicile" => "", "mobileSociete" => "", "mobilePersonnel" => "", "mobileProfessionnel" => "", "mailPrincipale" => "", "mailSecondaire" => ""];
					}
					switch ($res['lstt_idco_tp_indicateur']) 
					{
						case 1:
							$lstFourn[$i]['telephoneDomicile'] = $res['lstt_numero']; 
							break;
						case 2:
							$lstFourn[$i]['telephoneBureau'] = $res['lstt_numero']; 
							break;
						case 3:
							$lstFourn[$i]['faxDomicile'] = $res['lstt_numero']; 
							break;
						case 4:
							$lstFourn[$i]['faxBureau'] = $res['lstt_numero']; 
							break;
						case 5:
							$lstFourn[$i]['mobilePersonnel'] = $res['lstt_numero']; 
							break;
						case 6:
							$lstFourn[$i]['mobileProfessionnel'] = $res['lstt_numero']; 
							break;
						case 7:
							$lstFourn[$i]['telephoneSociete'] = $res['lstt_numero']; 
							break;
						case 8:
							$lstFourn[$i]['faxSociete'] = $res['lstt_numero']; 
							break;
						case 9:
							$lstFourn[$i]['mobileSociete'] = $res['lstt_numero']; 
							break;
					}
					if ($res['lstm_idco_tp_indicateur'] == 10)
					{
						$lstFourn[$i]['mailPrincipale'] = $res['lstm_mail']; 
					}
					else
					{
						$lstFourn[$i]['mailSecondaire'] = $res['lstm_mail']; 
					}
				}
			}	
			
		}
		for ($i =0; $i  <= sizeof($lstFourn)-1; $i++) 
		{ 
			$pdf->AddPage();
			$pdf->SetFont('Times','B',13);
			$pdf->cell(0,'',utf8_decode($lstFourn[$i]['raisonSociale']),'',1,'C');
			$pdf->SetFont('Times','',13);
			$pdf->ln();
			$pdf->cell(0,14,'');
			$pdf->ln();
			$pdf->cell(120,5,utf8_decode('Raison Sociale :  '.$lstFourn[$i]['raisonSociale']));
			$pdf->cell(10,5,utf8_decode('Code entité :  '.$lstFourn[$i]['codeEntite']),0,2);
			$pdf->ln();
			$pdf->cell(120,5,utf8_decode('Contact :  '.$lstFourn[$i]['contact']));
			for ($z = 0; $z <= substr_count($lstFourn[$i]['adresse'], "\n"); $z++)
			{
				$adresse = explode("\n", $lstFourn[$i]['adresse'])[$z];
				if ($z == 0) 
				{
					$pdf->cell(10,7,utf8_decode('Adresse :  '.$adresse),0,2);
				}
				else
				{
					$pdf->cell(10,7,utf8_decode('                 '.$adresse),0,2);
				}
			}
			$pdf->ln();
			$pdf->cell(30,5,utf8_decode('Téléphone :  '));
			for($z = 4; $z <= 12; $z++)
			{
				if ($lstFourn[$i]['telephoneSociete'] != "" && $z == 4)
				{
					$pdf->cell(30,5,utf8_decode('Téléphone société :  '.$lstFourn[$i]['telephoneSociete']),0,2);
				}
				else if ($lstFourn[$i]['faxSociete'] != "" && $z == 5)
				{
					$pdf->cell(30,5,utf8_decode('Télécopie société :  '.$lstFourn[$i]['faxSociete']),0,2);
				}
				else if ($lstFourn[$i]['mobileSociete'] != "" && $z == 6)
				{
					$pdf->cell(30,5,utf8_decode('Mobile société :  '.$lstFourn[$i]['mobileSociete']),0,2);
				}
				else if ($lstFourn[$i]['telephoneBureau'] != "" && $z == 7)
				{
					$pdf->cell(30,5,utf8_decode('Téléphone bureau :  '.$lstFourn[$i]['telephoneBureau']),0,2);
				}
				else if ($lstFourn[$i]['faxBureau'] != "" && $z == 8)
				{
					$pdf->cell(30,5,utf8_decode('Télécopie bureau :  '.$lstFourn[$i]['faxBureau']),0,2);
				}
				else if ($lstFourn[$i]['mobileProfessionnel'] != "" && $z == 9)
				{
					$pdf->cell(30,5,utf8_decode('Mobile professionnel :  '.$lstFourn[$i]['mobileProfessionnel']),0,2);
				}
				else if ($lstFourn[$i]['telephoneDomicile'] != "" && $z == 10)
				{
					$pdf->cell(30,5,utf8_decode('Téléphone domicile :  '.$lstFourn[$i]['telephoneDomicile']),0,2);
				}
				else if ($lstFourn[$i]['faxDomicile'] != "" && $z == 11)
				{
					$pdf->cell(30,5,utf8_decode('Télécopie domicile :  '.$lstFourn[$i]['faxDomicile']),0,2);
				}
				else if ($lstFourn[$i]['mobilePersonnel'] != "" && $z == 12)
				{
					$pdf->cell(30,5,utf8_decode('Mobile personnel :  '.$lstFourn[$i]['mobilePersonnel']),0,2);
				}
			}
		}
	}
	$pdf->output();
	//var_dump($i);
?>