function formaTexte(texte)
{
    var texteFormater = "";
    if (texte != "" && typeof(texte) != "undefined") 
    {
        texte = texte.toLowerCase();
        for(a = 0; a <= (texte.length)-1; a++)
        {
            char = texte.charAt(a);
            switch (char)
            {
                case ' ':
                case "'":
                    break;
                case 'é':
                case 'è':
                    texteFormater+= 'e';
                    break;
                case 'à':
                    texteFormater+= 'a';
                    break;
                default:
                    texteFormater+= texte.charAt(a);
                    break;
            }
        }
    }
    return texteFormater;
}





function capitalizeFirstLetter(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}





function rechercher()
{
    document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Référence produit<br>-Famille produit<br>-Désignation produit<br>-Prix du produit<br>-Nom du fournisseur"; 
    var NbProd = 0;
    document.getElementById('TbF').value = 0;
	reference = document.getElementById('Reference').value;
	famille = document.getElementById('Famille').value;
	typeRechProd = document.getElementById('typeRechProd').value;
	libelle = document.getElementById('Libelle').value;
	prix = document.getElementById('PrixProd').value;
    nomRaison = document.getElementById('NomRaison').value;
    categorie = document.getElementById('Cat').value;
	if (reference != "" || famille != "*" || libelle != "" || prix != "*") 
	{
		effaceTableau();
		NbProd = tableProduit(reference, famille, typeRechProd, libelle, prix);
	}
    else if (nomRaison != "")
    {
        effaceTableau();
        for (compteF = 0; compteF <= longueur(lstProduits)-1; compteF++)
        {
            if (typeof(lstProduits[compteF]) != "¤")
            {
                if(afficheProd(compteF, NbProd))
                {
                    NbProd++;
                }
            }
        }
    }
    else if (categorie != "*")
    {
        for (i = 0; i <= longueur(lstProduits)-1; i++)
        {
            if (typeof(lstProduits[i]) != "¤")
            {
                if (lstProduits[i]['categorieProd'] == categorie) 
                {
                    afficheFour(i);
                }
            }
        }
    }
	else
	{
        effaceTableau();
        document.getElementById('transparenceMSG').style.visibility = "visible";
        document.getElementById('containerMSG').style.visibility = "visible";
		document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Référence produit<br>-Famille produit<br>-Désignation produit<br>-Prix du produit<br>-Nom du fournisseur";
	}
    document.getElementById('NbProdMax').innerHTML = NbProd;
    document.getElementById('NumDuProd').innerHTML = NbProd;
    document.getElementById('AddProd').disabled = false;
}






function tableProduit(ref,fam,typeRech, lib, prix)
{
	lib = formaTexte(lib);
	var x = 0, x1 = 0, x2 = 0, ligneProd = 0;
	if (document.getElementById('LePrix')) 
	{
	   x = document.getElementById('LePrix').value;
	}
	if (document.getElementById('LePrix1'))
	{
		x1 = document.getElementById('LePrix1').value;
	}
	if (document.getElementById('LePrix2'))
	{
		x2 = document.getElementById('LePrix2').value;
	}
	if (ref != "") 
	{
		for (i = 0; i <= longueur(lstProduits)-1; i++)
		{
            if (typeof(lstProduits[i]) != "¤")
            {
                var z = 0, verif = true;
                while (verif == true && ref.length != -1 && ref.length-1 >= z)
                {
                    if(!(ref.charAt(z) == formaTexte(lstProduits[i]["referenceProd"]).charAt(z)))
                    {
                        verif = false;
                    }
                    z++;
                }
                if (verif)
                {
                    if (fam == "*" || lstProduits[i]["familleProd"] == fam) 
                    {
                        if (lib == "") 
                        {
                            ligneProd = comparePrix(i,ligneProd,prix,x,x1,x2);
                        }
                        else
                        {
                           ligneProd = triLibelleProd(typeRech,lib,i,ligneProd,prix,x,x1,x2);
                        }               
                    }
                }
            }    
		}
	}
	else if (lib != "")
	{
		for (parcourlib = 0; parcourlib <= longueur(lstProduits)-1; parcourlib++)
		{
            if (typeof(lstProduits[parcourlib]) != "¤")
            {
                if (fam == "*" || lstProduits[parcourlib]["familleProd"] == fam) 
                {
                    ligneProd = triLibelleProd(typeRech,lib,parcourlib,ligneProd,prix,x,x1,x2);             
                }
            }	
		}
	}
	else if (fam != "*")
	{
		for (parcour = 0; parcour <= longueur(lstProduits)-1; parcour++)
		{
            if (typeof(lstProduits[parcour]) != "¤")
            {
    			if (lstProduits[parcour]["familleProd"] == fam) 
    			{
    				ligneProd = comparePrix(parcour,ligneProd,prix,x,x1,x2);
    			}	
            }
		}
	}
	else if (prix != "*") 
	{
		for (parcourPrix = 0; parcourPrix <= longueur(lstProduits)-1; parcourPrix++)
		{
            if (typeof(lstProduits[parcourPrix]) != "¤")
            {
			    ligneProd = comparePrix(parcourPrix,ligneProd,prix,x,x1,x2);
            }	
		}
	}
    return ligneProd;
}





function triLibelleProd(typeRechProd,lib,i,ligneProd,prix,x,x1,x2)
{
	var z = 0;
	var bool = true;
	if (typeRechProd == "*") 
    {
    	while (bool == true && lib.length != -1 && lib.length-1 >= z)
    	{
    		if(!(lib.charAt(z) == lstProduits[i]["rechLibelleProd"].charAt(z).toLowerCase()))
    		{
    			bool = false;
    		}
    		z++;
    	}
    	if (bool)
    	{
    		ligneProd = comparePrix(i,ligneProd,prix,x,x1,x2);
    	}
    }
    else if (typeRechProd == "Egal")
    {
    	if (lib == formaTexte(lstProduits[i]["rechLibelleProd"]) || lib == formaTexte(lstProduits[i]["libelleProd"]))
    	{
    		ligneProd = comparePrix(i,ligneProd,prix,x,x1,x2);
    	}
    }
    else if (typeRechProd == "Contient") 
    {
    	if (formaTexte(lstProduits[i]["libelleProd"]).includes(lib))
    	{
    		ligneProd = comparePrix(i,ligneProd,prix,x,x1,x2);
    	}
    }
    return ligneProd;
}





function comparePrix(i,ligneProd,prix,x,x1,x2)
{
	if (prix != "*") 
    {
    	if (prix == "Egal") 
    	{
    		if (parseInt(x) == parseInt(lstProduits[i]["prixProd"]))
    		{
                if(afficheProd(i,ligneProd))
                {
                    return (ligneProd+1);
                }
                else
                {
                    return ligneProd;
                }
    		}
    		else
    		{
    			return ligneProd;
    		}
    	}
    	else if (prix == "Super") 
    	{
    		if (parseInt(lstProduits[i]["prixProd"]) >= parseInt(x)) 
    		{
                if(afficheProd(i,ligneProd))
                {
                    return (ligneProd+1);
                }
                else
                {
                    return ligneProd;
                }
    		}
    		else
    		{
    			return ligneProd;
    		}
    	}
    	else if (prix == "Infer")
    	{
    		if (parseInt(lstProduits[i]["prixProd"]) <= parseInt(x))
    		{
                if(afficheProd(i,ligneProd))
                {
                    return (ligneProd+1);
                }
                else
                {
                    return ligneProd;
                }
    		}
    		else
    		{
    			return ligneProd;
    		}
    	}
    	else if (prix  == "Entre")
    	{
    		if (parseInt(x1) <= parseInt(lstProduits[i]["prixProd"]) && parseInt(x2) >= parseInt(lstProduits[i]["prixProd"])) 
    		{
                if(afficheProd(i,ligneProd))
                {
                    return (ligneProd+1);
                }
                else
                {
                    return ligneProd;
                }
    		}
    		else
    		{
    			return ligneProd;
    		}
    	}
    }
    else
    {
        if(afficheProd(i,ligneProd))
        {
            return (ligneProd+1);
        }
        else
        {
            return ligneProd;
        }
    }					
}





function filtreFourn(lignePr, compte = 0)
{
    var bool = false;
    switch (document.getElementById('typeRechFourn').value)
    {
        case "*":
            for (x = 0; x <= formaTexte(document.getElementById('NomRaison').value).length-1; x++)
            {
                if (formaTexte(lstProduits[lignePr]['fournisseurProd']['produitFournisseur']).charAt(x) == formaTexte(document.getElementById('NomRaison').value).charAt(x)) 
                {
                    bool = true
                }
                else
                {
                    bool = false;
                    break;
                }
            }
            break;
        case "Egal":
            if (formaTexte(lstProduits[lignePr]['fournisseurProd']['produitFournisseur']) == formaTexte(document.getElementById('NomRaison').value)) 
            {
                bool = true
            }
            else
            {
                bool = false;
                break;
            }
            break;
        case "Contient":
                if (formaTexte(lstProduits[lignePr]['fournisseurProd'][compte]['raisonSociale']).includes(formaTexte(document.getElementById('NomRaison').value))) 
                {
                    bool = true
                }
                else
                {
                    bool = false;
                    break;
                }
            break;
    }
    return bool;
}





function afficheProd(ligneP,ligneProd)
{
    if (tableFournisseur(ligneP)) 
    {
        var Ref = "ref"+ligneProd;
        var Lib = "lib"+ligneProd;
        var Fam = "fam"+ligneProd;
        var Prix = "prix"+ligneProd;
        document.getElementById('indexProduit'+ligneProd).value = ligneP;
        document.getElementById(Ref).innerHTML = lstProduits[ligneP]["referenceProd"];
        document.getElementById(Lib).innerHTML = lstProduits[ligneP]["libelleProd"];
        document.getElementById(Fam).innerHTML = lstProduits[ligneP]["familleProd"];
        document.getElementById(Prix).innerHTML = (lstProduits[ligneP]["prixProd"]+"&euro;");
        return true;
    }
    return false;
}





function getFournisseur(produit = "*")
{
    var save = [];
    var liste = [];
    var info = ['raisonSociale', 'produitFournisseur', 'codeEntite', 'contact', 'commentaire', 'siret', 'abreviation', 'clefPhonetique', 'categorie', 
                'adresse', 'formeJuridique', 'email', 'rechCategorie', 'rechFormeJuridique', 'rechRaisonSociale', 'telephoneFixe', 
                'telephoneFixeActif', 'mobile', 'mobileActif', 'fax', 'faxActif'];
    if (produit != "*")
    {
        for (a = 0; a <= longueur(lstProduits)-1; a++)
        {
            if (typeof(lstProduits[a]) != "¤") 
            {
                if (lstProduits[a]['rechLibelleProd'] == produit)
                {
                    if (typeof(lstProduits[a]['fournisseurProd']) !== "undefined")
                    {
                        for (d = 0; d <= longueur(lstProduits[a]['fournisseurProd'])-1; d++)
                        {
                            for (b = 0; b <= 19; b++)
                            {
                                save[info[b]] = lstProduits[a]['fournisseurProd'][d][info[b]];
                            }
                            liste[d] = save;
                            save = [];
                        }
                    }
                }
            }
        }
    }
    else
    {
        var listeF = [];
        for (a = 0; a <= longueur(lstProduits)-1; a++)
        {
            if (typeof(lstProduits[a]['fournisseurProd']) !== "¤")
            {
                if (typeof(lstProduits[a]['fournisseurProd']) !== "undefined")
                {
                    for (d = 0; d <= longueur(lstProduits[a]['fournisseurProd'])-1; d++)
                    {
                        if (listeF.indexOf(lstProduits[a]['fournisseurProd'][d]['raisonSociale']) == -1)
                        {
                            for (b = 0; b <= 19; b++)
                            {
                                save[info[b]] = lstProduits[a]['fournisseurProd'][d][info[b]];
                            }
                            liste.push(save);
                            save = [];
                            listeF.push(lstProduits[a]['fournisseurProd'][d]['raisonSociale']);
                        }
                    }
                }
            }
        }
    }
    return liste;
}





function tableFournisseur(x)
{
    if (document.getElementById('NomRaison').value != "") 
    {
        var bool = false;
        if (document.getElementById('Cat') == "*") 
        {
            if (lstProduits[x]['fournisseurProd'] != undefined)
            {
                for (parcourF = 0; parcourF <= longueur(lstProduits[x]['fournisseurProd'])-1; parcourF++)
                {
                    if (filtreFourn(x, parcourF)) 
                    {
                        afficheFour(x);
                        bool = true;
                    }
                }
            }
        }
        else
        {
            if (filtreFourn(x)) 
            {
                afficheFour(x);
                bool = true;
            }
        }
        return bool;
    }
    else
    {
        if (lstProduits[x]['fournisseurProd'] != undefined)
        {
            afficheFour(x);
        }
        return true;
    }
}





function afficheFour(y)
{
    for (long = 0; long <= longueur(getFournisseur(lstProduits[y]['rechLibelleProd']))-1; long++)
    {
        if (lstProduits[y]['familleProd'] == document.getElementById('Famille').value || document.getElementById('Famille').value == "*") 
        {
            var ligneFour = document.getElementById('TbF').value;
            document.getElementById("forme"+ligneFour).innerHTML = lstProduits[y]['fournisseurProd'][long]["formeJuridique"];
            document.getElementById("raiSoc"+ligneFour).innerHTML = lstProduits[y]['fournisseurProd'][long]["raisonSociale"];
            document.getElementById("cat"+ligneFour).innerHTML = lstProduits[y]['fournisseurProd'][long]["categorie"];
            document.getElementById('TbF').value = parseInt(ligneFour)+1;
            document.getElementById('indexFourn'+ligneFour).value = y;
        }
    }
}





function longueur(obj) 
{
    var size = 0, key;
    for (key in obj) 
    {
        if (obj.hasOwnProperty(key))
        { 
        	size++
        }
    }
    return size;
}





function effacer()
{
	document.getElementById('Reference').value = "";
	document.getElementById('Famille').value = "*";
	document.getElementById('typeRechProd').value = "*";
	document.getElementById('Libelle').value = "";
	document.getElementById('PrixProd').value = "*";
	document.getElementById('typeRechFourn').value = "*";
	document.getElementById('NomRaison').value = "";
	document.getElementById('Cat').value = "*";
	document.getElementById('FormeJuridique').value = "*";
    document.getElementById('NumDuProd').innerHTML = "0";
    document.getElementById('NbProdMax').innerHTML = "0";
    effaceTableau();
    ChangePrix();
}





function effaceTableau()
{
    document.getElementById('AddProd').disabled = true;
    document.getElementById('DelProd').disabled = true;
    for (i = 0; i <= 300; i++)
    {
        document.getElementById("forme"+i).innerHTML = "";
        document.getElementById("raiSoc"+i).innerHTML = "";
        document.getElementById("cat"+i).innerHTML = "";
        document.getElementById("ref"+i).innerHTML = "";
        document.getElementById("lib"+i).innerHTML = "";
        document.getElementById("fam"+i).innerHTML = "";
        document.getElementById("prix"+i).innerHTML = "";
        document.getElementById("ref"+i).style.backgroundColor = "white";
        document.getElementById("lib"+i).style.backgroundColor = "white";
        document.getElementById("fam"+i).style.backgroundColor = "white";
        document.getElementById("prix"+i).style.backgroundColor = "white";
        document.getElementById("forme"+i).style.backgroundColor = "white";
        document.getElementById("raiSoc"+i).style.backgroundColor = "white";
        document.getElementById("cat"+i).style.backgroundColor = "white";
    }
}





function ChangePrix()
{
	x = document.getElementById('PrixProd').value;
	switch (x)
	{
		case "*":
			document.getElementById('InPrix').innerHTML = "";
			break;
		case "Egal":
		case "Infer":
		case "Super":
			document.getElementById('InPrix').innerHTML = '<input type="number" id="LePrix" placeholder="0.0" style="margin-top: 2%; text-align: center;">';
			break;
		case "Entre":
			document.getElementById('InPrix').innerHTML = '<input type="number" id="LePrix1" placeholder="0.0" style="width: 38%; margin-right: 5px; margin-top: 2%; text-align: center;"> <input type="number" id="LePrix2" placeholder="0.0" style="width: 38%; margin-top: 2%; text-align: center;">';
			break;
	}
}





function selectProd(ligne)
{
    if (document.getElementById('ref'+ligne).innerHTML != "" || document.getElementById('lib'+ligne).innerHTML != "" || document.getElementById('fam'+ligne).innerHTML != "" || document.getElementById('prix'+ligne).innerHTML != "") 
    {
        ancienneLigne = document.getElementById('SaveLigneProd').value;

        document.getElementById('ref'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('lib'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('fam'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('prix'+ancienneLigne).style.backgroundColor = "white";

        document.getElementById('ref'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('lib'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('fam'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('prix'+ligne).style.backgroundColor = "#BBBBBB";

        document.getElementById('SaveLigneProd').value = ligne;
        document.getElementById('NumDuProd').innerHTML = ligne+1;

        document.getElementById('DelProd').disabled = false;
    }
}





function selectFourn(ligne)
{
    if (document.getElementById('forme'+ligne).innerHTML != "" || document.getElementById('raiSoc'+ligne).innerHTML != "" || document.getElementById('cat'+ligne).innerHTML != "") 
    {

        ancienneLigne = document.getElementById('SaveLigneFourn').value;

        document.getElementById('forme'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('raiSoc'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('cat'+ancienneLigne).style.backgroundColor = "white";

        document.getElementById('forme'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('raiSoc'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('cat'+ligne).style.backgroundColor = "#BBBBBB";

        document.getElementById('SaveLigneFourn').value = ligne;
    }
}





function infoProd(fenetre,LigneProd = 0)
{
    document.getElementById('newProd').value = "edit";
    if (fenetre == "open") 
    {
        if (document.getElementById('ref'+LigneProd).innerHTML != "" || document.getElementById('lib'+LigneProd).innerHTML != "" || document.getElementById('fam'+LigneProd).innerHTML != "" || document.getElementById('prix'+LigneProd).innerHTML != "") 
        {
            indexProduit = document.getElementById('indexProduit'+LigneProd).value;
            document.getElementById('transparence').style.visibility = "visible";
            document.getElementById('infoProd').style.visibility = "visible";
            document.getElementById('infoFamProd').value = lstProduits[indexProduit]['familleProd'];
            document.getElementById('infoRefProd').innerHTML = lstProduits[indexProduit]['referenceProd'];
            document.getElementById('infoLibProd').innerHTML = lstProduits[indexProduit]['libelleProd'];
            if (lstProduits[LigneProd]['actif'] == 1) 
            {
                document.getElementById('actif').checked = true;
                lstSaveCurrentInfoProd["familleActif"] = 1;
            }
            document.getElementById('infoRefProdModif').value = lstProduits[indexProduit]['referenceProd'];
            document.getElementById('infoLibProdModif').value = lstProduits[indexProduit]['libelleProd'];
            document.getElementById('infoDescriptionProd').value = lstProduits[indexProduit]['descriptionProd'].replace("*", "\n");
            document.getElementById('infoPrixProd').value = parseFloat(lstProduits[indexProduit]['prixProd']);
            document.getElementById('infoTvaPourcentage').value = lstProduits[indexProduit]['tvaProd'];
            for (j = 0; j <= 6; j++)
            {
                document.getElementById('infoFormeFournisseur'+j).innerHTML = "";
                document.getElementById('infoRaisonSocialeFournisseur'+j).innerHTML = "";
                document.getElementById('infoCategorieFournisseur'+j).innerHTML = "";
            }  
            if (typeof(lstProduits[indexProduit]['fournisseurProd']) != "undefined") 
            {
                              
                var fournisseur = getFournisseur(lstProduits[indexProduit]['rechLibelleProd']);
                for (j = 0; j <= longueur(fournisseur)-1; j++)
                {
                    document.getElementById('infoFormeFournisseur'+j).innerHTML = fournisseur[j]['formeJuridique'];
                    document.getElementById('infoRaisonSocialeFournisseur'+j).innerHTML = fournisseur[j]['raisonSociale'];
                    document.getElementById('infoCategorieFournisseur'+j).innerHTML = fournisseur[j]['categorie'];
                }
            }
            calculTva();
            selectProd(LigneProd);
            InfoFournisseur(indexProduit);
            lstSaveCurrentInfoProd["famille"] = lstProduits[indexProduit]['familleProd'];
            lstSaveCurrentInfoProd["reference"] = lstProduits[indexProduit]['referenceProd'];
            lstSaveCurrentInfoProd["designation"] = lstProduits[indexProduit]['libelleProd'];
            lstSaveCurrentInfoProd["description"] = lstProduits[indexProduit]['descriptionProd'];
            lstSaveCurrentInfoProd["prixHT"] = document.getElementById('infoPrixProd').value;
            lstSaveCurrentInfoProd["TVA"] = document.getElementById('infoTvaPourcentage').value;
            lstSaveCurrentInfoProd["prixTTC"] = document.getElementById('infoTvaPrix').value;
            lstSaveCurrentInfoProd["note"] = document.getElementById('commentaire').innerHTML;
        }
    }
    else if (fenetre == "close")
    {
        document.getElementById('Save').style.display = "none";
        document.getElementById('Cancel').style.display = "none";
        document.getElementById('ok').style.display = "inline";
        document.getElementById('transparenceProd').style.visibility = "hidden";
        document.getElementById('containerMSG').style.visibility = "hidden";
        document.getElementById('transparenceMSG').style.visibility = "hidden";
        document.getElementById('transparence').style.visibility = "hidden";
        document.getElementById('infoProd').style.visibility = "hidden";
    }
}





function infoFourn(fenetre,LigneFourn = 0)
{
    if (fenetre == "open") 
    {
        var fourn = getFournisseur("*");
        if (document.getElementById('forme'+LigneFourn).innerHTML != "" || document.getElementById('raiSoc'+LigneFourn).innerHTML != "" || document.getElementById('cat'+LigneFourn).innerHTML != "") 
        {
            for (size = 0; size <= longueur(fourn)-1; size++)
            {
                if (document.getElementById('raiSoc'+LigneFourn).innerHTML == fourn[size]['raisonSociale'].replace("&","&amp;"))
                {
                    selectFourn(LigneFourn);
                    document.getElementById('currentFourn').value = fourn[size]['raisonSociale'];
                    document.getElementById('transparence').style.visibility = "visible";
                    document.getElementById('infoFourn').style.visibility = "visible";
                    document.getElementById('infoRaiSocFourn').value = fourn[size]['raisonSociale'];
                    document.getElementById('infoNomFournisseur').innerHTML = fourn[size]['raisonSociale'];
                    document.getElementById('infoCatFournisseur').value = fourn[size]['categorie'];
                    document.getElementById('infoAbreviationFourn').value = fourn[size]['abreviation'];
                    document.getElementById('infoFjFourn').value = fourn[size]['formeJuridique'];
                    document.getElementById('infoSiretFourn').value = fourn[size]['siret'];
                    document.getElementById('infoCodeEntiteFourn').value = fourn[size]['codeEntite'];
                    document.getElementById('infoContactFourn').value = fourn[size]['contact'];
                    if (typeof(fourn[size]['commentaire']) != "undefined")
                    {
                        document.getElementById('infoCommentaireFourn').innerHTML = fourn[size]['commentaire'].replace("*", "\n");
                    }          
                    /*if (listeFournisseur[indexFourn]['adresseDefaut'] == 1) 
                    {
                        document.getElementById('infoAdresseDefautFourn').checked = "true";
                    }*/
                    document.getElementById('infoAdresseFourn').innerHTML = fourn[size]['adresse'].replace("*", "\n");
                    document.getElementById('infoTelephoneFourn').value = fourn[size]['telephoneFixe'];
                    if (fourn[size]['telephoneFixe'] != "")
                    {
                        document.getElementById('TPsoc').innerHTML = "☑ Téléphone société";
                    }
                    else
                    {
                        document.getElementById('TPsoc').innerHTML = "☐ Téléphone société";
                    }
                    if (fourn[size]['mobile'] != "")
                    {
                        document.getElementById('Msoc').innerHTML = "☑ Mobile société";
                    }
                    else
                    {
                        document.getElementById('Msoc').innerHTML = "☐ Mobile société";
                    }
                    if (fourn[size]['fax'] != "")
                    {
                        document.getElementById('TCsoc').innerHTML = "☑ fax société";
                    }
                    else
                    {
                        document.getElementById('TCsoc').innerHTML = "☐ fax société";
                    }
                    document.getElementById('infoEmailFournisseur').value = fourn[size]['email']; 
                    document.getElementById('infoTelephoneDefautFourn').checked = "true";
                }
            }
        }
    }
    else if (fenetre == "close")
    {
        document.getElementById('transparence').style.visibility = "hidden";
        document.getElementById('infoFourn').style.visibility = "hidden";
    }
}





function changeNum()
{
    var fourn = getFournisseur("*");
    for (i = 0; i <= longueur(fourn)-1; i++)
    {
        if (document.getElementById('currentFourn').value == fourn[i]['raisonSociale'])
        {
            x = document.getElementById('infoSelectNumFourn').value;
            switch (x)
            {
                case "Téléphone":
                    document.getElementById('infoTelephoneFourn').value = fourn[i]['telephoneFixe'];
                    break;
                case "Télécopie":
                    document.getElementById('infoTelephoneFourn').value = fourn[i]['fax'];
                    break;
                case "Mobile":
                    document.getElementById('infoTelephoneFourn').value = fourn[i]['mobile'];
                    break;
            }
        }
    }
}





function InfoFournisseur(NumProd)
{
    y = 0;
    for (i = 0; i <= longueur(lstProduits[NumProd]['fournisseurProd'])-1; i++)
    {
        if (lstProduits[NumProd]['rechLib'] == lstProduits[NumProd]['fournisseurProd'][i]['produitFournisseur'])
        {
            document.getElementById('infoFormeFournisseur'+y).innerHTML = lstProduits[NumProd]['fournisseurProd'][i]['formeJuridique'];
            document.getElementById('infoRaisonSocialeFournisseur'+y).innerHTML = lstProduits[NumProd]['fournisseurProd'][i]['raisonSociale'];
            document.getElementById('infoCategorieFournisseur'+y).innerHTML = lstProduits[NumProd]['fournisseurProd'][i]['categorie'];
            document.getElementById('numFournisseur'+y).value = i;
            y++;
        }
    }
}





function commentaireFournisseur(NumLigne)
{
    x = document.getElementById('numFournisseur'+NumLigne).value;
    selectFourn(NumLigne);
    if (listeFournisseur[x]['commentaire'] != "") 
    {
        document.getElementById('commentaire').innerHTML = listeFournisseur[x]['commentaire'].replace("*", "\n");
    }
    else
    {
        document.getElementById('commentaire').innerHTML = "Aucun commentaire"; 
    }
}





function calculTva()
{
    x = document.getElementById('infoTvaPourcentage').value;
    if (x != "*") 
    {
        document.getElementById('infoTvaPrix').value = (parseFloat(document.getElementById('infoPrixProd').value) * ((parseFloat(document.getElementById('infoTvaPourcentage').value)/100)+1)).toFixed(2);
    }
    else
    {
        document.getElementById('infoTvaPrix').value = document.getElementById('infoPrixProd').value;  
    }
}


function apostrophe(texte)
{
    texte2 = "";
    for (i = 0; i <= texte.length-1; i++)
    {
        if (texte.charAt(i) == "'")
        {
            texte2+= texte.charAt(i)+"'";
        }
        else
        {
            texte2+= texte.charAt(i);
        }
    }
    return texte2;
}

function addEditProd(mode="")
{
    if (document.getElementById('newProd').value == "new" && mode == "")
    {
        if (document.getElementById('infoLibProdModif').value != "") 
        {
            var lst = {referenceProd: document.getElementById('infoRefProdModif').value, 
                       libelleProd: document.getElementById('infoLibProdModif').value,
                       descriptionProd: document.getElementById('infoDescriptionProd').value ,
                       familleProd: "", 
                       prixProd: document.getElementById('infoPrixProd').value, 
                       tvaLibelleProd: "",
                       tvaProd: document.getElementById('infoTvaPourcentage').value, 
                       actifProd: 0, 
                       rechLibelleProd: formaTexte(document.getElementById('infoLibProdModif').value),
                       rechFamilleProd: ""
                      };
            if (document.getElementById('infoTvaPourcentage').value != "*")
            {
                if (document.getElementById('infoTvaPourcentage').value.toString() == "5.50") 
                {
                    lst['tvaLibelleProd'] = "Taux réduit";
                }
                if (document.getElementById('infoTvaPourcentage').value.toString() == "10.00") 
                {
                    lst['tvaLibelleProd'] = "Taux intermédiaire";
                }
                if (document.getElementById('infoTvaPourcentage').value.toString() == "19.00") 
                {
                    lst['tvaLibelleProd'] = "Taux normal";
                }
            }
            if (formaTexte(document.getElementById('infoFamProd').value) != "*")
            {
                lst.rechFamilleProd = formaTexte(document.getElementById('infoFamProd').value);        
            }
            if (document.getElementById('actif').checked == true)
            {
                lst['actifProd'] = 1;
            }
            if (document.getElementById('infoFamProd').value != "*") 
            {
                lst['familleProd'] = document.getElementById('infoFamProd').value;
            }
            lstProduits[longueur(lstProduits)] = lst;
            lstChangements+= "INSERT INTO produit(pro_idproduit, pro_reference, pro_libelle, pro_description,pro_pxvente_ht, pro_iactif, pro_lib_rech, pro_idfamille, pro_idtva) VALUES((SELECT nextVal('\"SQ_ID\"')),'"+apostrophe(lst.referenceProd)+
                                                                                                                                                                            "', '"+apostrophe(lst.libelleProd)+
                                                                                                                                                                            "', '"+apostrophe(lst.descriptionProd)+
                                                                                                                                                                            "', "+lst.prixProd+
                                                                                                                                                                            ", "+lst.actifProd+
                                                                                                                                                                            ", '"+apostrophe(lst.libelleProd).toUpperCase()+
                                                                                                                                                                            "', (SELECT fam_idfamille FROM famille WHERE fam_libelle = '"+capitalizeFirstLetter(lst.rechFamilleProd)+
                                                                                                                                                                            "'), (SELECT tva_idtva FROM tva WHERE tva_libelle = '"+lst.tvaLibelleProd+
                                                                                                                                                                            "'));*";
            document.getElementById('infoProd').style.visibility = 'hidden'; 
            document.getElementById('transparence').style.visibility= 'hidden';
            exit("true");
            rechercher();
        }
        else
        {
            document.getElementById('transparenceMSG').style.visibility = "visible";
            document.getElementById('containerMSG').style.visibility = "visible";
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins le libellé du produit<br>";
        }
    }
    else if (document.getElementById('newProd').value == "edit" && mode == "" || document.getElementById('newProd').value == "edit" && mode == "ok")
    {
        if (document.getElementById('infoLibProdModif').value != "") 
        {
            document.getElementById('TitreMSG').innerHTML = "Erreur:";
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Référence produit<br>-Famille produit<br>-Désignation produit<br>-Prix du produit<br>-Nom du fournisseur";
            document.getElementById('Save').style.display = "none";
            document.getElementById('Cancel').style.display = "none";
            document.getElementById('ok').style.display = "inline";
            document.getElementById('infoProd').style.visibility = 'hidden'; 
            document.getElementById('transparence').style.visibility= 'hidden';
            document.getElementById('transparenceMSG').style.visibility = "hidden";
            document.getElementById('containerMSG').style.visibility = "hidden";
            liste = ["famille", "familleActif", "reference", "designation", "description", "prixHT", "TVA", "prixTTC", "note"];
            lstRecupInfoProd = {famille: document.getElementById('infoFamProd').value,
                                familleActif: document.getElementById('actif').value,
                                reference: document.getElementById('infoRefProdModif').value,
                                designation: document.getElementById('infoLibProdModif').value,
                                description: document.getElementById('infoDescriptionProd').value.replace("\n", "¤"),
                                prixHT: document.getElementById('infoPrixProd').value,
                                TVA: document.getElementById('infoTvaPourcentage').value,
                                TVAlibelle: "",
                                prixTTC: document.getElementById('infoTvaPrix').value,
                                note: document.getElementById('commentaire').value
                               };
            if (document.getElementById('actif').checked == "true") 
            {
                lstRecupInfoProd.familleActif = 1;
            }
            else
            {
                lstRecupInfoProd.familleActif = 0;
            }
            switch (document.getElementById('infoTvaPourcentage').value)
            {
                case '5.50':
                    lstRecupInfoProd.TVAlibelle = "Taux réduit";
                    break;
                case '10.00':
                    lstRecupInfoProd.TVAlibelle = "Taux intermédiaire";
                    break;
                case '19.00':
                    lstRecupInfoProd.TVAlibelle = "Taux normal";
                    break;
            }
            for (j = 0; j <= 8; j++)
            {
                
                if (lstSaveCurrentInfoProd[liste[j]] != lstRecupInfoProd[liste[j]]) 
                {
                    lstChangements+= "UPDATE produit SET pro_reference = '"+apostrophe(lstRecupInfoProd.reference)+
                                     "', pro_libelle= '"+apostrophe(lstRecupInfoProd.designation)+
                                     "', pro_description = '"+apostrophe(lstRecupInfoProd.description)+
                                     "', pro_pxvente_ht = '"+apostrophe(lstRecupInfoProd.prixHT)+
                                     "',pro_idfamille = (SELECT fam_idfamille FROM famille WHERE fam_libelle = '"+apostrophe(lstRecupInfoProd.famille)+
                                     "'), pro_idtva = (SELECT tva_idtva FROM tva WHERE tva_libelle = '"+apostrophe(lstRecupInfoProd.TVAlibelle)+
                                     "'), pro_lib_rech = '"+apostrophe(formaTexte(lstRecupInfoProd.designation).toUpperCase())+
                                     "' WHERE pro_libelle = '"+apostrophe(lstSaveCurrentInfoProd["designation"])+"';*";
                    exit("true");
                    break;
                }
            }
            for(j = 0; j <= longueur(lstProduits)-1; j++)
            {
                if (typeof(lstProduits[j]) != "¤") 
                {
                    if (lstSaveCurrentInfoProd['designation'] == lstProduits[j]['libelleProd'])
                    {
                        lstProduits[j]["referenceProd"] = lstRecupInfoProd["reference"].toUpperCase();
                        lstProduits[j]["libelleProd"] = lstRecupInfoProd["designation"];
                        lstProduits[j]["descriptionProd"] = lstRecupInfoProd["description"];
                        lstProduits[j]["familleProd"] = lstRecupInfoProd["famille"];
                        lstProduits[j]["prixProd"] = lstRecupInfoProd["prixHT"];
                        lstProduits[j]["tvaProd"] = lstRecupInfoProd["TVA"];
                    }
                }
            }
            rechercher();
        }
        else
        {
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins le libellé du produit<br>";
            document.getElementById('transparenceMSG').style.visibility = "visible";
            document.getElementById('containerMSG').style.visibility = "visible";
        }
    }
    else
    {
        if (document.getElementById('infoProd').style.visibility != "visible") 
        {
            document.getElementById('infoFamProd').value = "*";
            document.getElementById('infoRefProd').innerHTML = "";
            document.getElementById('infoLibProd').innerHTML = "";
            document.getElementById('actif').checked = false;
            document.getElementById('infoRefProdModif').value = "";
            document.getElementById('infoLibProdModif').value = "";
            document.getElementById('infoDescriptionProd').value = "";
            document.getElementById('infoPrixProd').value = 0;
            document.getElementById('infoTvaPourcentage').value = "*";
            document.getElementById('infoTvaPrix').value = 0;
            for (j = 0; j <= 6; j++)
            {
                document.getElementById('infoFormeFournisseur'+j).innerHTML = "";
                document.getElementById('infoRaisonSocialeFournisseur'+j).innerHTML = "";
                document.getElementById('infoCategorieFournisseur'+j).innerHTML = "";
            }
            document.getElementById('transparence').style.visibility = "visible";
            document.getElementById('infoProd').style.visibility = "visible";
            document.getElementById('newProd').value = "new";
        }
        else
        {
            document.getElementById('infoProd').style.visibility = 'hidden'; 
            document.getElementById('transparence').style.visibility= 'hidden';
        }
    }
}





function delProd()
{
    c = -1;
    x = document.getElementById('SaveLigneProd').value;
    for (j = 0; j <= longueur(lstProduits)-1; j++)
    {
        if (typeof(lstProduits[j]) != "¤") 
        {
            if (document.getElementById('lib'+x).innerHTML == lstProduits[j]["libelleProd"] && document.getElementById('ref'+x).innerHTML == lstProduits[j]["referenceProd"]) 
            {
                lstChangements+= "DELETE FROM produit WHERE pro_libelle = '"+lstProduits[j]['libelleProd']+"';*";
                lstProduits[j] = "¤";
                exit("true");
            }
        }
    }
    rechercher();
}





function enregistrer()
{
    exit();
    var win = window.open("vue/sauvegarde.php", "_blank", "width=50, height=50");
    win.liste = lstChangements;
}





function exit(save="false")
{
    if (save == "true") 
    {
        $(window).bind('beforeunload', function()
        {
            return 'Are you sure you want to leave?';
        });
    }
    else
    {
        $(window).unbind('beforeunload');
    }
}