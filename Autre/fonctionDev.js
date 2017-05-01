function formaTexte(texte)
{
    var texteFormater = "";
    if (texte != "") 
    {
        texte = texte.toLowerCase();
        for(a = 0; a <= (texte.length)-1; a++)
        {
            char = texte.charAt(a);
            switch (char)
            {
                case ' ':
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







function effacer()
{
    document.getElementById('Numero').value = "";
    document.getElementById('Dates').value = "*";
    document.getElementById('PrixDev').value = "*";
    document.getElementById('Boncommande').value = "";
    document.getElementById('Reference').value = "";
    document.getElementById('TypeCli').value = "*";
    document.getElementById('Categorie').value = "";
    document.getElementById('typeRechCli').value = "*";
    document.getElementById('Libelle').value = "";
    document.getElementById('typeRechCli1').value = "*";
    document.getElementById('Prenom').innerHTML = "0";
    document.getElementById('phonetique').checked = "true";
    document.getElementById('SIRET').value = "";
    effaceTableau();
    ChangePrix();
}





function effaceTableau()
{
    for (i = 0; i <= 300; i++)
    {
        document.getElementById("ref"+i).innerHTML = "";
        document.getElementById("design"+i).innerHTML = "";
        document.getElementById("qte"+i).innerHTML = "";
        document.getElementById("PUHT"+i).innerHTML = "";
        document.getElementById("TOTALTTC"+i).innerHTML = "";
        document.getElementById("num"+i).innerHTML = "";
        document.getElementById("cli"+i).innerHTML = "";
        document.getElementById("le"+i).innerHTML = "";
        document.getElementById("echeance"+i).innerHTML = "";
        document.getElementById("bc"+i).innerHTML = "";
        document.getElementById("HT"+i).innerHTML = "";
        document.getElementById("TTC"+i).innerHTML = "";
        document.getElementById("ref"+i).style.backgroundColor = "white";
        document.getElementById("design"+i).style.backgroundColor = "white";
        document.getElementById("qte"+i).style.backgroundColor = "white";
        document.getElementById("PUHT"+i).style.backgroundColor = "white";
        document.getElementById("TOTALTTC"+i).style.backgroundColor = "white";
        document.getElementById("num"+i).style.backgroundColor = "white";
        document.getElementById("cli"+i).style.backgroundColor = "white";
        document.getElementById("le"+i).style.backgroundColor = "white";
        document.getElementById("echeance"+i).style.backgroundColor = "white";
        document.getElementById("bc"+i).style.backgroundColor = "white";
        document.getElementById("HT"+i).style.backgroundColor = "white";
        document.getElementById("TTC"+i).style.backgroundColor = "white";
        document.getElementById('SaveLigneDevis').value = "0";
    }
}






function ChangePrix()
{
    x = document.getElementById('PrixDev').value;
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




function rechercher()
{
    document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-N&deg; Pi&egrave;ce<br>-Date de la pi&egrave;ce<br>-Montant de la pi&egrave;ce<br>-N&deg; du bon de commande<br>-Etat de la pi&egrave;ce (positionnement)<br>-Code client<br>-Nom personne/entit&eacute;<br>-Pr&eacute;nom personne<br>-N&deg; SIRET<br>-Forme juridique<br>-Cat&eacute;gorie du client";
    var NbDev = 0;
    document.getElementById('TbF').value = 0;
    x = document.getElementById('Type').innerHTML;
    numero = document.getElementById('Numero').value;
    dates = document.getElementById('Dates').value;
    prixdev = document.getElementById('PrixDev').value;
    boncommande = document.getElementById('Boncommande').value;
    reference = document.getElementById('Reference').value;
    typecli = document.getElementById('TypeCli').value;
    categorie = document.getElementById('Categorie').value;
    typerechcli = document.getElementById('typeRechCli').value;
    libelle = document.getElementById('Libelle').value;
    typerechcli1 = document.getElementById('typeRechCli1').value;
    prenom = document.getElementById('Prenom').value;
    phonetique = document.getElementById('phonetique').value;
    siret = document.getElementById('SIRET').value;
    
    if (numero != "" || dates !="*" || prixdev != "*" || boncommande != "")  
    {
        effaceTableau();
        NbDev = tableDevis(numero, dates, prixdev, NbDev);
    }
    else if (reference != "")
    {
        effaceTableau();
        switch(typerechcli)
        {
            case "*": 
                for (i=0; i<=longueur(lstDevis)-1; i++)
                {
                    if (typeof(lstDevis[i]) != "¤")
                    {
                        var bool = true;
                        for (j=0; j<=libelle.length-1; j++)
                        {
                            if (libelle.charAt(j)!= lstClients[i]["typeRechCli"].charAt(j))
                            {
                                bool = false;
                            }
                        }
                        if (bool)
                        {
                            afficheDev(i);
                            NbDev = NbDevNbDev+1;
                        }                
                    }
                }
                break;
            case "Egal":
                for (i=0; i<=longueur(lstDevis)-1; i++)
                {
                    if (typeof(lstDevis[i]) != "¤")
                    {
                        if (reference == lstDevis[i]["typeRechCli"])
                        {
                            afficheDev(i);
                            NbDevNbDev = NbDevNbDev+1;
                        }   
                    }
                }
                break; 
            case "Contient":
                for (i=0; i<=longueur(lstDevis)-1; i++)
                {
                    if (typeof(lstDevis[i]) != "¤")
                    {
                        if (lstDevis[i]["typeRechCliNom"].includes(reference))
                        {
                            afficheDev(i);
                            NbDevNbDev = NbDevNbDev+1;
                        }
                    }
                }
                break;              
        }
    }
    else if (libelle != "")
    {
        effaceTableau();
        switch(typerechcli)
        {
            case "*": 
                for (i=0; i<=longueur(lstDevis)-1; i++)
                {
                    if (typeof(lstDevis[i]) != "¤")
                    {
                        var bool = true;
                        for (j=0; j<=libelle.length-1; j++)
                        {
                            if (libelle.charAt(j)!= lstDevis[i]["Libelle"].charAt(j))
                            {
                                bool = false;
                            }
                        }
                        if (bool)
                        {
                            afficheDev(i);
                            NbDev = NbDev+1;
                        }                
                    }
                }
                break;
            case "Egal":
                for (i=0; i<=longueur(lstDevis)-1; i++)
                {
                    if (typeof(lstDevis[i]) != "¤")
                    {
                        if (libelle == lstDevis[i]["Libelle"])
                        {
                            afficheDev(i);
                            NbDev = NbDev+1;
                        }   
                    }
                }
                break; 
            case "Contient":
                for (i=0; i<=longueur(lstDevis)-1; i++)
                {
                    if (typeof(lstDevis[i]) != "¤")
                    {
                        if (lstDevis[i]["Libelle"].includes(libelle))
                        {
                            afficheDev(i);
                            NbDev = NbDev+1;
                        }
                    }
                }
                break;              
        }
    }
    else if (prenom != "" || phonetique != "" || siret != "")
    {
        effaceTableau();
    }
    else
    {
        effaceTableau();
        document.getElementById('transparenceMSG').style.visibility = "visible";
        document.getElementById('containerMSG').style.visibility = "visible";
        document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-N&deg; Pi&egrave;ce<br>-Date de la pi&egrave;ce<br>-Montant de la pi&egrave;ce<br>-N&deg; du bon de commande<br>-Etat de la pi&egrave;ce (positionnement)<br>-Code client<br>-Nom personne/entit&eacute;<br>-Pr&eacute;nom personne<br>-N&deg; SIRET<br>-Forme juridique<br>-Cat&eacute;gorie du client";
    }
    document.getElementById('NbDevMax').innerHTML = NbCli;
    document.getElementById('NumDuDev').innerHTML = 0;
}








function tableDevis(numero, dates, prixdev, NbDev)
{
    numero = formaTexte(numero);
    var ligneDev = 0;
    if (numero != "") 
    {
        for (i = 0; i <= longueur(lstDevis)-1; i++)
        {
            if (typeof(lstDevis[i]) != "¤")
            {
                var z = 0, verif = true;
                while (verif == true && code.length != -1 && code.length-1 >= z)
                {
                    if(!(code.charAt(z) == formaTexte(lstDevis[i]["numero"]).charAt(z)))
                    {
                        verif = false;
                    }
                    z++;
                }
                if (verif)
                {
                    if (categorie == "*"  && lstDevis[i]["typeCli"] == typeCli  || typeCli == "*" && categorie == "*") 
                    {
                           ligneDev = triLibelleCli(typeCli,code,i,ligneCli, categorie);
                           NbCli = NbCli+1;
                    }               
                }
            }
        }
    }
    return NbCli;
}










function selectDev(ligne)
{
    if (document.getElementById('num'+ligne).innerHTML != "" || document.getElementById('cli'+ligne).innerHTML != "" || document.getElementById('le'+ligne).innerHTML != "" || document.getElementById('echeance'+ligne).innerHTML != "" || document.getElementById("bc"+ligne).innerHTML != "" || document.getElementById('HT'+ligne).innerHTML != "" || document.getElementById('TTC'+ligne).innerHTML != "")
    {

        ancienneLigne = document.getElementById('SaveLigneDevis').value;

        document.getElementById('num'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('cli'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('le'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('echeance'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('bc'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('HT'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('TTC'+ancienneLigne).style.backgroundColor = "white";

        document.getElementById('num'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('cli'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('le'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('echeance'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('bc'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('HT'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('TTC'+ligne).style.backgroundColor = "#BBBBBB";

        document.getElementById('SaveLigneDevis').value = ligne;
    }
}