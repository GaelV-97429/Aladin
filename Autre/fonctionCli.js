function formaTexte(texte) // Permet de former tout texte (toute en minuscule, sans accent, sans espace, sans apostrophe)
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



//fonction permettant de remplacer la première lettre en Majuscule.


function capitalizeFirstLetter(string) 
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}


//fonction permettant d'effacer toutes les valeurs inscrites par le client dans la recherche.


function effacer()
{
    document.getElementById('Code').value = "";
    document.getElementById('TypeCli').value = "*";
    document.getElementById('Categorie').value = "*";
    document.getElementById('typeRechCli').value = "*";
    document.getElementById('Libelle').value = "";
    document.getElementById('typeRechCli').value = "*";
    document.getElementById('Prenom').value = "";
    document.getElementById('phonetique').checked = "true";
    document.getElementById('SIRET').value = "";
    document.getElementById('FormeJuridique').value = "*";
    document.getElementById('NumDuCli').innerHTML = "0";
    document.getElementById('NbCliMax').innerHTML = "0";
    effaceTableau();
}

//fonction permettant d'effacer les données affichées du tableau de la vueClients au moment de l'actualisation de la page ou au moment du clique sur le bouton effacer.


function effaceTableau()
{
    document.getElementById('AddCli').disabled = true; //Désactive le bouton Ajout Client
    document.getElementById('DelCli').disabled = true; //Désactive le bouton Supprimer Client
    for (i = 0; i <= 300; i++)
    {
        document.getElementById("num"+i).innerHTML = "";
        document.getElementById("le"+i).innerHTML = "";
        document.getElementById("echeance"+i).innerHTML = "";
        document.getElementById("bc"+i).innerHTML = "";
        document.getElementById("HT"+i).innerHTML = "";
        document.getElementById("TTC"+i).innerHTML = "";
        document.getElementById("T"+i).innerHTML = "";
        document.getElementById("code"+i).innerHTML = "";
        document.getElementById("entite"+i).innerHTML = "";
        document.getElementById("nom"+i).innerHTML = "";
        document.getElementById("dep"+i).innerHTML = "";
        document.getElementById("ville"+i).innerHTML = "";
        document.getElementById("T"+i).style.backgroundColor = "white";
        document.getElementById("code"+i).style.backgroundColor = "white";
        document.getElementById("entite"+i).style.backgroundColor = "white";
        document.getElementById("nom"+i).style.backgroundColor = "white";
        document.getElementById("dep"+i).style.backgroundColor = "white";
        document.getElementById("ville"+i).style.backgroundColor = "white";
        document.getElementById("num"+i).style.backgroundColor = "white";
        document.getElementById("le"+i).style.backgroundColor = "white";
        document.getElementById("echeance"+i).style.backgroundColor = "white";
        document.getElementById("bc"+i).style.backgroundColor = "white";
        document.getElementById("HT"+i).style.backgroundColor = "white";
        document.getElementById("TTC"+i).style.backgroundColor = "white";
        document.getElementById('SaveLigneCli').value = "0";
    }
}


// fonction permettant d'effectuer la recherche en fonction des critères de recherche.

function rechercher()
{
    document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Code Client<br>-Nom personne/entit&eacute;<br>-Pr&eacute;nom personne<br>-N&deg; SIRET<br>-Forme juridique<br>-Cat&eacute;gorie du client"; //Initialise le message d'erreur
    document.getElementById('Rech').innerHTML = "Recherche infructueuse ! <br>Voulez-vous créer un nouveau client ?<br>oui<hr>non"; // Initialise le message d'erreur
    var NbCli = 0;
    document.getElementById('TbF').value = 0;
	x = document.getElementById('Type').innerHTML;
	code = document.getElementById('Code').value;
    typeCli = document.getElementById('TypeCli').value;
	categorie = document.getElementById('Categorie').value;
	typeRechCli = document.getElementById('typeRechCli').value;
    typeRechCli1 = document.getElementById('typeRechCli1').value;
	libelle = document.getElementById('Libelle').value;
    prenom = document.getElementById('Prenom').value;
    siret = document.getElementById('SIRET').value;
    abreviation = document.getElementById('infoAbreviationCli').value;
    entite = document.getElementById('infoCodeEntiteCli').value;
    contact = document.getElementById('infoContactCli').value;
    website = document.getElementById('infoSiteCli').value;
    commentaires = document.getElementById('infoCommentaireCli').value;
    
	if (code != "" || typeCli !="*" || categorie != "*")  
	{
		effaceTableau(); //fait appel à la fonction permettant de vider le tableau Clients
        document.getElementById('AddCli').disabled = false; //Permet d'activer le bouton Ajout Client
		NbCli = tableClient(code, typeCli, categorie, NbCli);
	}
    else if (libelle != "")
    {
        effaceTableau(); // Vide le tableau Clients
        switch(typeRechCli)
        {
            case "*": 
                for (i=0; i<=longueur(lstClients)-1; i++)
                {
                    if (lstClients[i] != "¤")
                    {
                        var bool = true;
                        for (j=0; j<=libelle.length-1; j++)
                        {
                            if (libelle.charAt(j)!= lstClients[i]["typeRechCliNom"].charAt(j))
                            {
                                bool = false;
                            }
                        }
                        if (bool)
                        {
                            afficheCli(i);
                            NbCli = NbCli+1;
                        }                
                    }
                }
                break;
            case "Egal":
                for (i=0; i<=longueur(lstClients)-1; i++)
                {
                    if (lstClients[i] != "¤")
                    {
                        if (libelle == lstClients[i]["typeRechCliNom"])
                        {
                            afficheCli(i);
                            NbCli = NbCli+1;
                        }   
                    }
                }
                break; 
            case "Contient":
                for (i=0; i<=longueur(lstClients)-1; i++)
                {
                    if (lstClients[i] != "¤")
                    {
                        if (lstClients[i]["typeRechCliNom"].includes(libelle))
                        {
                            afficheCli(i);
                            NbCli = NbCli+1;
                        }
                    }
                }
                break;              
        }
    }
    else if (prenom != "")
    {
        effaceTableau(); // Vide le tableau Clients
        switch(typeRechCli1)
        {
            case "*": 
                for (i=0; i<=longueur(lstClients)-1; i++)
                {
                    if (lstClients[i] != "¤")
                    {
                        var bool = true;
                        for (j=0; j<=prenom.length-1; j++)
                        {
                            if (prenom.charAt(j)!= lstClients[i]["Prenom"].charAt(j))
                            {
                                bool = false;
                            }
                        }
                        if (bool)
                        {
                            afficheCli(i);
                            NbCli = NbCli+1;
                        }                
                    }
                }
                break;
            case "Egal":
                for (i=0; i<=longueur(lstClients)-1; i++)
                {
                    if (lstClients[i] != "¤")
                    {
                        if (prenom == lstClients[i]["Prenom"])
                        {
                            afficheCli(i);
                            NbCli = NbCli+1;
                        }   
                    }
                }
                break; 
            case "Contient":
                for (i=0; i<=longueur(lstClients)-1; i++)
                {
                    if (lstClients[i] != "¤")
                    {
                        if (lstClients[i]["Prenom"].includes(prenom))
                        {
                            afficheCli(i);
                            NbCli = NbCli+1;
                        }
                    }
                }
                break;              
        }
    }
	else
	{
		effaceTableau(); // Vide le tableau Clients
        document.getElementById('transparenceMSG').style.visibility = "visible"; //rend visible le message d'erreur
        document.getElementById('containerMSG').style.visibility = "visible"; //rend visible le message d'erreur
        document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Code Client<br>-Nom personne/entit&eacute;<br>-Pr&eacute;nom personne<br>-N&deg; SIRET<br>-Forme juridique<br>-Cat&eacute;gorie du client"; // Initialise le message d'erreur
        document.getElementById('transparenceRech').style.visibility = "visible";
	    document.getElementById('containerRech').style.visibility = "visible";       
        document.getElementById('Rech').innerHTML = "Recherche infructueuse ! <br>Voulez-vous créer un nouveau client ?<br>oui<hr>non"; // Initialise le message d'erreur
    }
    document.getElementById('NbCliMax').innerHTML = NbCli; // Affiche le client sélectionné
    document.getElementById('NumDuCli').innerHTML = 0; // Affiche le nombre de clients
}

function tableClient(code, typeCli, categorie, NbCli) //Filtre les clients en fonctions des critères de recherche
{
    code = formaTexte(code);
    var ligneCli = 0;
    if (code != "") 
    {
        for (i = 0; i <= longueur(lstClients)-1; i++) //Recherche les clients qui ont le même code que celui que l'utilisateur a entré.
        {
            if (lstClients[i] != "¤") //Vérifie si un client existe à un index donné
            {
                var z = 0, verif = true;
                while (verif == true && code.length != -1 && code.length-1 >= z)
                {
                    if(!(code.charAt(z) == formaTexte(lstClients[i]["code"]).charAt(z))) //Vérifie l'existance d'un client ayant le même code que celui que l'utilisateur a entré.
                    {
                        verif = false;
                    }
                    z++;
                }
                if (verif)
                {
                    if (categorie == "*"  && lstClients[i]["typeCli"] == typeCli  || typeCli == "*" && categorie == "*") 
                    {
                           ligneCli = triLibelleCli(typeCli,code,i,ligneCli, categorie);
                           NbCli = NbCli+1;
                    }               
                }
            }
        }
    }
    return NbCli;
}



function triLibelleCli(typeCli,code,i,ligneCli, categorie)  //Filtre les clients en fonction du type de recherche de code du client
{
    var z = 0;
    var bool = true;
    if (typeCli == "*") 
    {
        while (bool == true && code.length != -1 && code.length-1 >= z) //Vérifie la correspondance des caractères(du code entré par l'utilisateur et le code de la liste client) un à un
        {
            if(!(code.charAt(z) == lstClients[i]["code"].charAt(z).toLowerCase()))
            {
                bool = false;
            }
            z++;
        }
        if (bool)
        {
            afficheCli(i);
            return ligneCli;
        }
    }
    else if (typeCli == "Personne") // Vérifie si le client est une personne
    {
        for (k=0; k<=code.length-1; k++)
        {
            if (code.charAt(k) != formaTexte(lstClients[i]["code"]).charAt(k))
            {
                bool = false;
            }
            if (bool)
            {
                if (categorie == "*" || categorie == lstClients[i]["Categorie"])
                {
                    afficheCli(i);
                    return ligneCli; 
                }
                
            }
        }
    }
    else if (typeCli == "Entite") // Vérifie si le client est une entité
    {
        for (k=0; k<=code.length-1; k++)
        {
            if (code.charAt(k) != formaTexte(lstClients[i]["code"]).charAt(k))
            {
                bool = false;
            }
            if (bool)
            {
                if (categorie == "*" || categorie == lstClients[i]["Categorie"])
                {
                    afficheCli(i);
                    return ligneCli; 
                }
                
            }
        }
    }
}

//fonction permettant de séléectionner un client dans le tableau

function selectCli(ligne)
{
    if (document.getElementById('T'+ligne).innerHTML != "" || document.getElementById('code'+ligne).innerHTML != "" || document.getElementById('entite'+ligne).innerHTML != "" || document.getElementById('nom'+ligne).innerHTML != "" || document.getElementById('dep'+ligne).innerHTML != "" || document.getElementById('ville'+ligne).innerHTML != "")
    {
        ancienneLigne = document.getElementById('SaveLigneCli').value; //Récupération du précédent client séléctionné

        document.getElementById('T'+ancienneLigne).style.backgroundColor = "white"; //Change la couleur de fond de l'ancien client séléctionné en blanc
        document.getElementById('code'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('entite'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('nom'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('dep'+ancienneLigne).style.backgroundColor = "white";
        document.getElementById('ville'+ancienneLigne).style.backgroundColor = "white";


        document.getElementById('T'+ligne).style.backgroundColor = "#BBBBBB"; //Affiche un fon de couleur gris au moment de la sélection d'une client dans le tableau
        document.getElementById('code'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('entite'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('nom'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('dep'+ligne).style.backgroundColor = "#BBBBBB";
        document.getElementById('ville'+ligne).style.backgroundColor = "#BBBBBB";

        document.getElementById('DelCli').disabled = false; //Active le bouton Supprimer Client
        document.getElementById('SaveLigneCli').value = ligne; //Sauvegarde la position du client sélectionné.
        document.getElementById('NumDuCli').innerHTML = ligne+1; //Affiche le numéro du client séléctioné (NbCli/NbCliMax au dessus du tableau des clients)
    }
}




function selectDevis(ligne) //fonction permettant de sélectionner un devis dans le tableau
{
    if (document.getElementById('num'+ligne).innerHTML != "" || document.getElementById('le'+ligne).innerHTML != "" || document.getElementById('echeance'+ligne).innerHTML != "" || document.getElementById("bc"+ligne).innerHTML != "" || document.getElementById('HT'+ligne).innerHTML != "" || document.getElementById('TTC'+ligne).innerHTML != "")
    {

        ancienneLigne = document.getElementById('SaveLigneDevis').value; //Récupération du précédent devis séléctionné

        document.getElementById('num'+ancienneLigne).style.backgroundColor = "white"; //Change la couleur de fond de l'ancien devis séléctionné en blanc
        document.getElementById('le'+ancienneLigne).style.backgroundColor = "white"; //
        document.getElementById('echeance'+ancienneLigne).style.backgroundColor = "white"; //
        document.getElementById('bc'+ancienneLigne).style.backgroundColor = "white"; //
        document.getElementById('HT'+ancienneLigne).style.backgroundColor = "white"; //
        document.getElementById('TTC'+ancienneLigne).style.backgroundColor = "white"; //

        document.getElementById('num'+ligne).style.backgroundColor = "#BBBBBB"; //Affiche un fon de couleur gris au moment de la sélection d'une devis dans le tableau
        document.getElementById('le'+ligne).style.backgroundColor = "#BBBBBB"; //
        document.getElementById('echeance'+ligne).style.backgroundColor = "#BBBBBB"; //
        document.getElementById('bc'+ligne).style.backgroundColor = "#BBBBBB"; //
        document.getElementById('HT'+ligne).style.backgroundColor = "#BBBBBB"; //
        document.getElementById('TTC'+ligne).style.backgroundColor = "#BBBBBB"; //

        document.getElementById('SaveLigneDevis').value = ligne; //Sauvegarde la position du devis séléctionné
    }
}



function infoCli(fenetre,LigneCli = 0)
{
    if (fenetre == "open") 
    {
        if (document.getElementById('T'+LigneProd).innerHTML != "" || document.getElementById('code'+LigneProd).innerHTML != "" || document.getElementById('entite'+LigneProd).innerHTML != "" || document.getElementById('nom'+LigneProd).innerHTML != "" || document.getElementById('dep'+LigneProd).innerHTML != "" || document.getElementById('ville'+LigneProd).innerHTML != "")
        {
            indexClient = document.getElementById('indexClient'+LigneCli).value;
            document.getElementById('transparence').style.visibility = "visible";
            document.getElementById('infoCli').style.visibility = "visible";
            document.getElementById('infoRefCli').innerHTML = lstClients[indexClient]['code'];
            document.getElementById('infoLibCli').innerHTML = lstClients[indexClient]['TypeCli'];
            if (lstClients[LigneCli]['actif'] == 1) 
            {
                document.getElementById('actif').checked = true;
            }
            selectCli(LigneCli);
        }
    }
    else if (fenetre == "close")
    {
        document.getElementById('transparence').style.visibility = "hidden";
        document.getElementById('infoCli').style.visibility = "hidden";
    }
}

//fonction permettant d'ouvrir une fenêtre (évènement Clic droit) afin d'obtenir les informations supplémentaires sur un client / / Ferme la fenêtre d'information d'un client (événement: Bouton fermer)

function infoCli(fenetre, LigneCli = 0)
{
    clear();
    if (fenetre == "open") //Permet d'afficher toutes les informations d'un client
    {
        document.getElementById('newCli').value = "edit"; //Active le mode modification 
        if (document.getElementById('T'+LigneCli).innerHTML != "" || document.getElementById('code'+LigneCli).innerHTML != "" || document.getElementById('entite'+LigneCli).innerHTML != "" || document.getElementById('nom'+LigneCli).innerHTML != "" || document.getElementById('dep'+LigneCli).innerHTML != "" || document.getElementById('ville'+LigneCli).innerHTML != "")
        {
            for (size = 0; size <= longueur(lstClients)-1; size++)
            {
                if (lstClients[size] != "¤")
                {
                    if (document.getElementById('code'+LigneCli).innerHTML == lstClients[size]['code'].replace("&","&amp;"))
                    {
                        selectCli(LigneCli);
                        indexClient = document.getElementById('indexClient'+LigneCli).value;
                        document.getElementById('currentCli').value = lstClients[size]['code'];
                        document.getElementById('transparence').style.visibility = "visible";
                        document.getElementById('infoCli').style.visibility = "visible";
                        document.getElementById('infoRaiSocCli').value = lstClients[size]['typeRechCliNom'];
                        document.getElementById('typeRechCliNom').innerHTML = lstClients[size]['typeRechCliNom'];
                        document.getElementById('infoCatCli').value = lstClients[size]['Categorie'];
                        document.getElementById('infoAbreviationCli').value = lstClients[size]['abreviation'];
                        document.getElementById('infoFjCli').value = lstClients[size]['FOJ'];
                        document.getElementById('infoSiretCli').value = lstClients[size]['SIRET'];
                        document.getElementById('infoCodeEntiteCli').value = lstClients[size]['code'];
                        document.getElementById('infoContactCli').value = lstClients[size]['contact']; 
                        document.getElementById('infoSiteCli').value = lstClients[size]['website'];
                        document.getElementById('infoCommentaireCli').value = lstClients[size]['commentaires'].replace("*","\n");    
                        document.getElementById('infoCourPrincip').value = lstClients[size]['mail']['courrierPrincipal'];
                        document.getElementById('infoCourSecond').value = lstClients[size]['mail']['courrierSecondaire'];

                        lstSaveCurrentInfoCli["typeRechCliNom"] = lstClients[size]['typeRechCliNom'];
                        lstSaveCurrentInfoCli["SIRET"] = lstClients[size]['SIRET'];
                        lstSaveCurrentInfoCli["abreviation"] = lstClients[size]['abreviation'];
                        lstSaveCurrentInfoCli["code"] = lstClients[size]['code'];
                        lstSaveCurrentInfoCli["contact"] = lstClients[size]['contact'];
                        lstSaveCurrentInfoCli["website"] = lstClients[size]['website'];
                        lstSaveCurrentInfoCli["mail"] = "";
                        lstSaveCurrentInfoCli["tel"] = "" ;
                        lstSaveCurrentInfoCli["mail"]["courrierPrincipal"] = lstClients[size]['mail']['courrierPrincipal'];
                        lstSaveCurrentInfoCli["mail"]["courrierSecondaire"] = lstClients[size]['mail']['courrierSecondaire'];
                        lstSaveCurrentInfoCli["tel"]["telephoneDomicile"] = lstClients[size]['mail']['telephoneDomicile'];
                        lstSaveCurrentInfoCli["tel"]["telephoneBureau"] = lstClients[size]['mail']['telephoneBureau'];
                        lstSaveCurrentInfoCli["tel"]["faxDomicile"] = lstClients[size]['tel']['faxDomicile'];
                        lstSaveCurrentInfoCli["tel"]["faxBureau"] = lstClients[size]['tel']['faxBureau'];
                        lstSaveCurrentInfoCli["tel"]["mobilePersonnel"] = lstClients[size]['tel']['mobilePersonnel'];
                        lstSaveCurrentInfoCli["tel"]["mobileProfessionnel"] = lstClients[size]['tel']['mobileProfessionnel'];
                        lstSaveCurrentInfoCli["tel"]["faxSociete"] = lstClients[size]['tel']['faxSociete'];
                        lstSaveCurrentInfoCli["tel"]["telephoneSociete"] = lstClients[size]['tel']['telephoneSociete'];
                        lstSaveCurrentInfoCli["tel"]["mobileSociete"] = lstClients[size]['tel']['mobileSociete'];
                        lstSaveCurrentInfoCli["adresseBureau"] = lstClients[size]['adresseBureau'];
                        lstSaveCurrentInfoCli["adressePrincipale"] = lstClients[size]['adressePrincipale'];
                        lstSaveCurrentInfoCli["adresseSecondaire"] = lstClients[size]['adresseSecondaire'];
                        lstSaveCurrentInfoCli["adresseCourrier"] = lstClients[size]['adresseCourrier'];
                        lstSaveCurrentInfoCli["commentaires"] = lstClients[size]['commentaires'];
                        //document.getElementById('infoCommentaireCli').innerHTML = lstClients[size]['commentaire'].replace("*", "\n");
                        /*if (listeClient[indexCli]['adresseDefaut'] == 1) 
                        {
                            document.getElementById('infoAdresseDefautCli').checked = "true";
                        }*/

                        document.getElementById('infoAdresseCli').innerHTML = lstClients[size]['adresseBureau'].replace("*", "\n");
                        if (lstClients[size]['code'].charAt(0) == "P")
                        {
                            document.getElementById('teldom').style.display = "block";
                            document.getElementById('telbur').style.display = "block";
                            document.getElementById('telecdom').style.display = "block";
                            document.getElementById('telecbur').style.display = "block";
                            document.getElementById('mobperso').style.display = "block";
                            document.getElementById('mobpro').style.display = "block";
                            document.getElementById('telecop').style.display = "none";
                            document.getElementById('mob').style.display = "none";
                            document.getElementById('teldom').value = lstClients[size]['tel']['telephoneDomicile'];
                            document.getElementById('telbur').value = lstClients[size]['tel']['telephoneBureau'];
                            document.getElementById('telecdom').value = lstClients[size]['tel']['faxDomicile'];
                            document.getElementById('telecbur').value = lstClients[size]['tel']['faxBureau'];
                            document.getElementById('mobperso').value = lstClients[size]['tel']['mobilePersonnel'];
                            document.getElementById('mobpro').value = lstClients[size]['tel']['mobileProfessionnel'];


                            switch(h="")
                            {
                                case (lstClients[size]['telephoneDomicile'] != h):
                                    document.getElementById('infoTelCli').innerHTML = lstClients[size]['telephoneDomicile'];
                                    document.getElementById('infoTelCli').value = lstClients[size]['telephoneDomicile'];
                                case (lstClients[size]['telephoneBureau'] != h):
                                    document.getElementById('infoTelCli').innerHTML = lstClients[size]['telephoneBureau'];
                                    document.getElementById('infoTelCli').value = lstClients[size]['telephoneBureau'];
                                case (lstClients[size]['faxDomicile'] != h):
                                    document.getElementById('infoTelCli').innerHTML = lstClients[size]['faxDomicile'];
                                    document.getElementById('infoTelCli').value = lstClients[size]['faxDomicile'];
                                case (lstClients[size]['faxBureau'] != h):
                                    document.getElementById('infoTelCli').innerHTML = lstClients[size]['faxBureau'];
                                    document.getElementById('infoTelCli').value = lstClients[size]['faxBureau'];
                                case (lstClients[size]['mobilePersonnel'] != h):
                                    document.getElementById('infoTelCli').innerHTML = lstClients[size]['mobilePersonnel'];
                                    document.getElementById('infoTelCli').value = lstClients[size]['mobilePersonnel'];
                                case (lstClients[size]['mobileProfessionnel'] != h):
                                    document.getElementById('infoTelCli').innerHTML = lstClients[size]['mobileProfessionnel'];
                                    document.getElementById('infoTelCli').value = lstClients[size]['mobileProfessionnel'];
                                case (lstClients[size]['telephoneSociete'] != h):
                                    document.getElementById('infoTelCli').innerHTML = lstClients[size]['telephoneSociete'];
                                    document.getElementById('infoTelCli').value = lstClients[size]['telephoneSociete'];
                            }
                        }
                        else
                        {
                            document.getElementById('teldom').style.display = "none";
                            document.getElementById('telbur').style.display = "none";
                            document.getElementById('telecdom').style.display = "none";
                            document.getElementById('telecbur').style.display = "none";
                            document.getElementById('mobperso').style.display = "none";
                            document.getElementById('mobpro').style.display = "none";
                            document.getElementById('telecop').style.display = "block";
                            document.getElementById('mob').style.display = "block";
                            document.getElementById('telecop').value = lstClients[size]['tel']['faxSociete'];
                            document.getElementById('mob').value = lstClients[size]['tel']['mobileSociete'];
                            document.getElementById('tel').value = lstClients[size]['tel']['telephoneSociete'];
                        }
                        if (lstClients[size]['tel']['telephoneSociete'] != "")
                        {
                            document.getElementById('tel').innerHTML = "☑ Téléphone société";
                            if (document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('tel').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['telephoneSociete'];
                            }
                        }
                        else
                        {
                            document.getElementById('tel').innerHTML = "☐ Téléphone société";
                        }
                        if (lstClients[size]['tel']['mobileSociete'] != "")
                        {
                            document.getElementById('mob').innerHTML = "☑ Mobile société";
                            if (document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('mob').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['mobileSociete'];
                            }
                        }
                        else
                        {
                            document.getElementById('mob').innerHTML = "☐ Mobile société";
                        }
                        if (lstClients[size]['tel']['faxSociete'] != "")
                        {
                            document.getElementById('telecop').innerHTML = "☑ fax société";
                            if (document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('telecop').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['faxSociete'];
                            }
                        }
                        else
                        {
                            document.getElementById('telecop').innerHTML = "☐ fax société";
                        }
                        if (lstClients[size]['adresseBureau'] != "")
                        {
                            document.getElementById('infoAdrCli').innerHTML = "☑ Adresse bureau";
                            if (document.getElementById('infoAdresseCli').value == "")
                            {
                                document.getElementById('infoTypeAdresseCli').selected = true;
                                document.getElementById('infoAdresseCli').value = lstClients[size]['adresseBureau'];
                            }
                        }   

                        else
                        {
                            document.getElementById('infoAdrCli').innerHTML = "☐ Adresse bureau";
                        }
                        if (lstClients[size]['adresseCourrier'] != "")
                        {
                            document.getElementById('infoCourCli').innerHTML = "☑ Adresse courrier";
                            if ( document.getElementById('infoAdresseCli').value == "")
                            {
                                document.getElementById('infoTypeAdresseCli').selected = true;
                                document.getElementById('infoAdresseCli').value = lstClients[size]['adresseCourrier']; 
                            }
                        }
                        else
                        {
                            document.getElementById('infoCourCli').innerHTML = "☐ Adresse courrier";
                        }               
                        if (lstClients[size]['mail']['courrierPrincipal'] != "" )  
                        {
                            document.getElementById('infoCourPrincip').innerHTML = "☑ Principale";
                            if ( document.getElementById('infoEmailCli').value == "")
                            {
                                document.getElementById('infoCourPrincip').selected = true;
                                document.getElementById('infoEmailCli').value = lstClients[size]['mail']['courrierPrincipal']; 
                            }
                        }
                        else
                        {
                            document.getElementById('infoCourPrincip').innerHTML = "☐ Princiaple";
                        }      
                        if (lstClients[size]['mail']['courrierSecondaire'] != "")
                        {
                            document.getElementById('infoCourSecond').innerHTML = "☑ Secondaire";
                            if ( document.getElementById('infoEmailCli').value == "")
                            {
                                document.getElementById('infoCourSecond').selected = true;
                                document.getElementById('infoEmailCli').value = lstClients[size]['mail']['courrierSecondaire'];
                            } 
                        }
                        else
                        {
                            document.getElementById('infoCourSecond').innerHTML = "☐ Secondaire";
                        }
                        if (lstClients[size]['tel']['telephoneDomicile'] != "")
                        {
                            document.getElementById('teldom').innerHTML = "☑ Téléphone domicile";
                            if ( document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('teldom').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['telephoneDomicile'];
                            } 
                        }
                        else
                        {
                            document.getElementById('teldom').innerHTML = "☐ Téléphone domicile";
                        }
                        if (lstClients[size]['tel']['telephoneBureau'] != "")
                        {
                            document.getElementById('telbur').innerHTML = "☑ Téléphone bureau";
                            if ( document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('telbur').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['telephoneBureau'];
                            } 
                        }
                        else
                        {
                            document.getElementById('telbur').innerHTML = "☐ Téléphone bureau";
                        }
                        if (lstClients[size]['tel']['faxDomicile'] != "")
                        {
                            document.getElementById('telecdom').innerHTML = "☑ Télécopie domicile";
                            if ( document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('telecdom').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['faxDomicile'];
                            } 
                        }
                        else
                        {
                            document.getElementById('telecdom').innerHTML = "☐ Télécopie domicile";
                        }
                        if (lstClients[size]['tel']['faxBureau'] != "")
                        {
                            document.getElementById('telecbur').innerHTML = "☑ Télécopie bureau";
                            if ( document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('telecbur').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['faxBureau'];
                            } 
                        }
                        else
                        {
                            document.getElementById('telecbur').innerHTML = "☐ Télécopie bureau";
                        }
                        if (lstClients[size]['tel']['mobilePersonnel'] != "")
                        {
                            document.getElementById('mobperso').innerHTML = "☑ Mobile personnel";
                            if ( document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('mobperso').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['mobilePersonnel'];
                            } 
                        }
                        else
                        {
                            document.getElementById('mobperso').innerHTML = "☐ Mobile personnel";
                        }
                        if (lstClients[size]['tel']['mobileProfessionnel'] != "")
                        {
                            document.getElementById('mobpro').innerHTML = "☑ Mobile professionnel";
                            if ( document.getElementById('infoTelephoneCli').value == "")
                            {
                                document.getElementById('mobpro').selected = true;
                                document.getElementById('infoTelephoneCli').value = lstClients[size]['tel']['mobileProfessionnelephone'];
                            } 
                        }
                        else
                        {
                            document.getElementById('mobpro').innerHTML = "☐ Mobile professionnel";
                        }
                        document.getElementById('infoTelephoneDefautCli').checked = "true";
                    }
                }
            }
            
        }
    }
    else if (fenetre == "close") //Ferme la fenêtre client
    {   
        document.getElementById('transparence').style.visibility = "hidden"; //
        document.getElementById('infoCli').style.visibility = "hidden"; //
    }
}



//fonction permettant de remettre à 0 les champs.

function clear()
{
    document.getElementById('tel').value = "";
    document.getElementById('telecop').value = "";
    document.getElementById('mob').value = "";
    document.getElementById('teldom').value = "";
    document.getElementById('telbur').value = "";
    document.getElementById('telecdom').value = "";
    document.getElementById('telecbur').value = "";
    document.getElementById('mobperso').value = "";
    document.getElementById('mobpro').value = "";
    document.getElementById('tel').value = "";
    document.getElementById('telecop').selected = false;
    document.getElementById('mob').selected = false;
    document.getElementById('teldom').selected = false;
    document.getElementById('telbur').selected = false;
    document.getElementById('telecdom').selected = false;
    document.getElementById('telecbur').selected = false;
    document.getElementById('mobperso').selected = false;
    document.getElementById('mobpro').selected = false;
    document.getElementById('infoTelephoneCli').value = "";
    document.getElementById('infoRaiSocCli').value = "";
    document.getElementById('infoAbreviationCli').value = "";
    document.getElementById('infoSiretCli').value = "";
    document.getElementById('infoCodeEntiteCli').value = "";
    document.getElementById('infoContactCli').value = "";
    document.getElementById('infoLibCategorie').value = "";
    document.getElementById('infoCategorieCli').value = "";
    document.getElementById('infoEmailCli').value = "";
    document.getElementById('infoLibFormeJuridique').value = "";



}



function changeNum() //Permet d'afficher le numéro du client en function du téléphone choisie par l'utilisateur
{
    document.getElementById('infoTelephoneCli').value = document.getElementById('infoTelCli').value;
}

//Permet d'afficher l'adresse du client en function de l'adresse choisie par l'utilisateur
function changeAdr()
{
    var cli = getVille("*");
    for (i = 0; i <= longueur(cli)-1; i++)
    {
        if (document.getElementById('currentCli').value == cli[i]['Adresse'])
        {
            x = document.getElementById('infoTypeAdresseCli').value;
            switch(x)
            {
                case "Adressebureau":
                    document.getElementById('infoAdrCli').value = cli[i]['adresseBureau'];
                break;
                case "Adressecourrier":
                    document.getElementById('infoCourCli').value = cli[i]['adresseCourrier'];
            }
        }
    }
}

//Permet d'afficher le mail du client en function du mail choisi par l'utilisateur
function changeCour()
{
    var cli = getTelMail("*");
    for (i = 0; i <= longueur(cli)-1; i++)
    {
        if (document.getElementById('currentCli').value == cli[i]['mail'])
        {
            x = document.getElementById('infoCourrielCli').value;
            switch(x)
            {
                case "Adressebureau":
                    document.getElementById('infoCourPrincip').value = cli[i]["courrielPrincipal"];
                break;
                case "Adressecourrier": 
                    document.getElementById('infoCourSecond').value = cli[i]["courrielSecondaire"];
                break;
            }
        }
    }
}












function longueur(obj) //Retourne la longueur d'une liste
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




function afficheCli(indexClient) //Affiche le client dans le tableau des clients
{
    var x = parseInt(document.getElementById("SaveLigneCli").value);
    if (lstClients[indexClient]["code"].charAt(0) == "P")
    {
        document.getElementById('T'+x).innerHTML = "Personne";
    }
    else 
    {
        document.getElementById('T'+x).innerHTML = "Entite";
    }
    document.getElementById('code'+x).innerHTML = lstClients[indexClient]["code"];
    switch(lstClients[indexClient]["T"])
    {
        case "1":
            document.getElementById('entite'+x).innerHTML = "M.";
            break;
        case "2":
            document.getElementById('entite'+x).innerHTML = "Mme.";
            break;
        case "3": 
            document.getElementById('entite'+x).innerHTML = "Mlle.";
            break;
        default:
            document.getElementById("entite"+x).innerHTML = lstClients[indexClient]["FOJ"];
            break;
    }
    document.getElementById('nom'+x).innerHTML = lstClients[indexClient]["typeRechCliNom"];
    document.getElementById('dep'+x).innerHTML = lstClients[indexClient]["Categorie"];
    document.getElementById('ville'+x).innerHTML = lstClients[indexClient]["ville"];
    document.getElementById("SaveLigneCli").value = x+1;
}














function addEditCli(mode="") // Permet d'enregistrer, d'insérer, modifier un client(côté client)
{
    if (document.getElementById('newCli').value == "new" && mode == "")
    {
        document.getElementById('infoTypeCli').disabled = false;
        document.getElementById('rechCatCli').style.display = 'none';
        document.getElementById('rechFOJCli').style.display = 'none';
        if (document.getElementById('infoRaiSocCli').value != "" || document.getElementById('infoSiretCli').value != "") 
        { //Récupération des informations du client entrées par l'utilisateur
            var lst = {T: "",
                       Categorie: document.getElementById('infoCatCli').value,
                       typeRechCliNom: document.getElementById('infoRaiSocCli').value,
                       abreviation: document.getElementById('infoAbreviationCli').value,
                       FOJ: document.getElementById('infoFjCli').value,
                       SIRET: document.getElementById('infoSiretCli').value,
                       code: document.getElementById('infoCodeEntiteCli').value,
                       contact: document.getElementById('infoContactCli').value,
                       commentaires: document.getElementById('infoCommentaireCli').value,
                       adresseBureau: document.getElementById('infoAdrCli').value,
                       adresseCourrier: document.getElementById('infoCourCli').value,
                       ville: "",
                       actifPhonetique: "",
                       tel: {telephoneDomicile: "", telephoneBureau: "", faxDomicile: "", faxBureau: "", mobilePersonnel: "", mobileProfessionnel: "", faxSociete: "", telephoneSociete: "", mobileSociete: ""},
                       mail: {courrierPrincipal:"", courrierSecondaire: ""},
                       adressePrincipale: document.getElementById('infoCourPrincip').value,
                       adresseSecondaire: document.getElementById('infoCourSecond').value,
                       website: document.getElementById('infoSiteCli').value,
                       };
            if (document.getElementById('infoAdresseDefautCli').checked == true)
            {
                lst['actif'] = 1;
            }
            if (document.getElementById('infoCatCli').value != "*") 
            {
                lst['categorieCli'] = document.getElementById('infoCatCli').value;
            }
            if (document.getElementById('infoFjCli').value != "*")
            {
                lst['formejuridique'] = document.getElementById('infoFjCli').value;
            }
            if (document.getElementById('infoTypeAdresseCli').value != "Adresse bureau")
            {
                lst['adresse'] = document.getElementById('infoTypeAdresseCli').value;
            }
            if (document.getElementById('infoTelCli').value != "tel")
            {
                lst['telephone'] = document.getElementById('tel').value;
            }
            if (document.getElementById('infoTelephoneDefautCli').checked == true)
            {
                lst['defauttel'] = 1;
            }
            if (document.getElementById('infoCourrielCli').value != 1)
            {
                lst['courriel'] = document.getElementById('infoCourrielCli').value;
            }
            //Initialisation de la requête d'insertion du nouveau client
            lstClients[longueur(lstClients)] = lst; //Ajoute le nouveau client à la liste client
            //Initialisation de la requête d'insertion du client
            lstChangements+= "INSERT INTO entite(ent_identite, ent_raison_sociale, ent_abreviation, ent_siret, ent_code_entite, ent_lib_contact)VALUES((SELECT nextVal('\"SQ_ID\"')),'"+capitalizeFirstLetter(lst.typeRechCliNom)+"', '"+capitalizeFirstLetter(lst.abreviation)+"', '"+lst.SIRET+"', '"+capitalizeFirstLetter(lst.code)+"', '"+capitalizeFirstLetter(lst.contact)+"');*";
                             /*"INSERT INTO categorie(cat_libelle)VALUES('"+capitalizeFirstLetter(lst.Categorie)+"');*";
                             "INSERT INTO client(cli_commentaires)VALUES('"+capitalizeFirstLetter(lst.commentaires)+"');*";
                             "INSERT INTO forme_juridique(foj_libelle)VALUES('"+lst.SIRET+"');*";*/






            $('#modif').css("display", "block");
            document.getElementById('infoCli').style.visibility = 'hidden'; 
            document.getElementById('transparence').style.visibility= 'hidden';            
            document.getElementById('transparenceCli').style.visibility= 'hidden';
            document.getElementById('rechCategorieCli').style.visibility= 'hidden';
            document.getElementById('rechTransparence').style.visibility= 'hidden';
            rechercher();
        }
        else
        {
            document.getElementById('transparenceMSG').style.visibility = "visible"; //
            document.getElementById('containerMSG').style.visibility = "visible"; //
            document.getElementById('transparenceRech').style.visibility = "visible"; //
            document.getElementById('containerRech').style.visibility = "visible"; // 
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins la raison sociale du client<br>Vous devez saisir un numéro de SIRET(14 chiffres)<br>"; // Affiche un message d'erreur
        }
    }
    else if (document.getElementById('newCli').value == "edit" && mode == "" || document.getElementById('newCli').value == "edit" && mode == "ok") //Modification du client
    {
        if (document.getElementById('infoRaiSocCli').value != "") 
        {
            document.getElementById('TitreMSG').innerHTML = "Erreur:";
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Raison Sociale<br>-Abr&eacute;viation<br>-SIRET / SIREN<br>-Code entit&eacute<br>-Contact<br>-Commentaires<br>-Adresse<br>-T&eacute,l&eacute;phone<br>-Courriel;<br>-Site Internet";
            document.getElementById('Save').style.display = "none";
            document.getElementById('Cancel').style.display = "none";
            document.getElementById('ok').style.display = "inline";
            document.getElementById('infoCli').style.visibility = 'hidden'; 
            document.getElementById('transparence').style.visibility= 'hidden';
            document.getElementById('transparenceMSG').style.visibility = "hidden";
            document.getElementById('TitreRech').innerHTML = "Votre choix ?";
            document.getElementById('Rech').innerHTML = "Recherche infructueuse ! <br>Voulez-vous créer un nouveau client ?<br>oui<hr>non";
            document.getElementById('SaveR').style.display = "none";
            document.getElementById('CancelR').style.display = "none";
            document.getElementById('ok').style.display = "inline";
            document.getElementById('non').style.display = "inline";
            document.getElementById('containerMSG').style.visibility = "hidden";
            document.getElementById('transparenceRech').style.visibility = "hidden";
            document.getElementById('containerRech').style.visibility = "hidden";
            liste = ["Categorie", "typeRechCliNom", "abreviation", "FOJ", "SIRET", "code", "contact", "commentaires", "adresseBureau", "adresseCourrier", "adressedefault", "telephone", "telcli", "telactif", "courriel", "email", "siteinternet"];
            lstRecupInfoCli = {T: "",
                               Categorie: "",
                               typeRechCliNom: document.getElementById('infoRaiSocCli').value,
                               abreviation: document.getElementById('infoAbreviationCli').value,
                               FOJ: "",
                               SIRET: document.getElementById('infoSiretCli').value,
                               code: document.getElementById('infoCodeEntiteCli').value,
                               contact: document.getElementById('infoContactCli').value,
                               commentaires: document.getElementById('infoCommentaireCli').value,
                               adresseBureau: document.getElementById('infoAdrCli').value,
                               adresseCourrier: document.getElementById('infoCourCli').value,
                               ville: "",
                               actifPhonetique: "",
                               tel: {telephoneDomicile: "", telephoneBureau: "", faxDomicile: "", faxBureau: "", mobilePersonnel: "", mobileProfessionnel: "", faxSociete: "", telephoneSociete: "", mobileSociete: ""},
                               mail: {courrierPrincipal:"", courrierSecondaire: ""},
                               adressePrincipale: document.getElementById('infoCourPrincip').value,
                               adresseSecondaire: document.getElementById('infoCourSecond').value,
                               website: document.getElementById('infoSiteCli').value
                               };
            if (document.getElementById('infoAdresseDefautCli').checked == true)
            {
                lstRecupInfoCli.adresseBureau = 1;
            }
            else
            {
                lstRecupInfoCli.adresseBureau = 0;
            }

            if (document.getElementById('infoCatCli').value != "*")
            {
                lstRecupInfoCli.Categorie = document.getElementById('infoCatCli').value;
            }

            for (j = 0; j <= 16; j++) //Vérifie si un changement à été apporté ou pas
            {
                 if (lstSaveCurrentInfoCli[liste[j]] != lstRecupInfoCli[liste[j]]) 
                {   //Initialisation de la requête de modification d'un client
                    lstChangements+= "UPDATE entite SET ent_raison_sociale = '"+capitalizeFirstLetter(lstRecupInfoCli.typeRechCliNom)+
                                     "', ent_abreviation = '"+capitalizeFirstLetter(lstRecupInfoCli.abreviation)+
                                     "', ent_siret = '"+lstRecupInfoCli.SIRET+
                                     "', ent_code_entite = '"+capitalizeFirstLetter(lstRecupInfoCli.code)+
                                     "', ent_lib_contact = '"+capitalizeFirstLetter(lstRecupInfoCli.contact)+
                                     "' WHERE ent_raison_sociale = '"+capitalizeFirstLetter(lstRecupInfoCli["typeRechCliNom"])+"';*";
                                     /*"UPDATE categorie SET cat_libelle = '"+capitalizeFirstLetter(lstRecupInfoCli.Categorie)+"' WHERE cli_commentaires = '"+capitalizeFirstLetter(lstRecupInfoCli["Categorie"])+"';*";
                                     "UPDATE client SET cli_commentaires = '"+capitalizeFirstLetter(lstRecupInfoCli.commentaires)+"' WHERE cli_commentaires = '"+capitalizeFirstLetter(lstRecupInfoCli["commentaires"])+"';*";
                                     "UPDATE forme_juridique SET foj_libelle = '"+lstRecupInfoCli.FOJ+"' WHERE foj_libelle = '"+lstRecupInfoCli["FOJ"]+"';*";*/
                    exit("true"); //Active un événement lors du fermeture de la page (Des modificiations ont été apportées, êtes-vous sûr de vouloir quitter sans sauvegarder?)
                    break;
                }
            }
            for(j = 0; j <= longueur(lstClients)-1; j++)
            {
                if (lstClients[j] != "¤") //Vérifie si un client existe à l'index donné
                {
                    if (lstSaveCurrentInfoCli['typeRechCliNom'] == lstClients[j]['typeRechCliNom']) //Vérifie s'il y a eu une modification et les mets à jour dans la liste des clients
                    {
                        lstClients[j]["Abreviation"] = lstRecupInfoCli["abreviation"].toUpperCase();
                        lstClients[j]["typeRechCliNom"] = lstRecupInfoCli["typeRechCliNom"];
                        lstClients[j]["FOJ"] = lstRecupInfoCli["FOJ"];
                        lstClients[j]["SIRET"] = lstRecupInfoCli["SIRET"];
                        lstClients[j]["code"] = lstRecupInfoCli["code"];
                        lstClients[j]["Contact"] = lstRecupInfoCli["contact"];
                        lstClients[j]["Commentaires"] = lstRecupInfoCli["commentaires"];
                        lstClients[j]["adresseBureau"] = lstRecupInfoCli["adresseBureau"];
                        lstClients[j]["adresseCourrier"] = lstRecupInfoCli["adresseCourrier"];
                        lstClients[j]["adressePrincipale"] = lstRecupInfoCli["adressePrincipale"];
                        lstClients[j]["adresseSecondaire"] = lstRecupInfoCli["adresseSecondaire"];
                        lstClients[j]["tel"]["telephoneDomicile"] = lstRecupInfoCli["tel"]["telephoneDomicile"];
                        lstClients[j]["tel"]["telephoneBureau"] = lstRecupInfoCli["tel"]["telephoneBureau"];
                        lstClients[j]["tel"]["faxDomicile"] = lstRecupInfoCli["tel"]["faxDomicile"];
                        lstClients[j]["tel"]["faxBureau"] = lstRecupInfoCli["tel"]["faxBureau"];
                        lstClients[j]["tel"]["mobilePersonnel"] = lstRecupInfoCli["tel"]["mobilePersonnel"];
                        lstClients[j]["tel"]["mobileProfessionnel"] = lstRecupInfoCli["tel"]["mobileProfessionnel"];
                        lstClients[j]["tel"]["faxSociete"] = lstRecupInfoCli["tel"]["faxSociete"];
                        lstClients[j]["tel"]["telephoneSociete"] = lstRecupInfoCli["tel"]["telephoneSociete"];
                        lstClients[j]["tel"]["mobileSociete"] = lstRecupInfoCli["tel"]["mobileSociet"];
                        lstClients[j]["mail"]["courrierPrincipal"] = lstRecupInfoCli["courrielPrincipal"];
                        lstClients[j]["mail"]["courrierSecondaire"] = lstRecupInfoCli["courrielSecondaire"];
                        lstClients[j]["Site Internet"] = lstRecupInfoCli["website"];
                    }
                }
            }
            rechercher();
        }
        else
        {
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins la raison sociale du client<br>"; // Affiche un message d'erreur
            document.getElementById('transparenceMSG').style.visibility = "visible"; //
            document.getElementById('containerMSG').style.visibility = "visible"; //
            document.getElementById('Rech').innerHTML = "Recherche infructueuse ! <br>Voulez-vous créer un nouveau client ? <br>oui<hr>non"; //
            document.getElementById('transparenceRech').style.visibility = "visible"; //
            document.getElementById('containerRech').style.visibility = "visible"; //
        }
    }
    else    //Sinon affiche ouvre la fenêtre information client VIDE, pour la saisie d'un nouveau client
    {
        if (document.getElementById('infoCli').style.visibility != "visible") 
        {
            document.getElementById('infoCatCli').value = "*";
            document.getElementById('typeRechCliNom').innerHTML = "";
            document.getElementById('infoAdresseDefautCli').checked = false;
            document.getElementById('infoTelephoneDefautCli').checked = false;
            document.getElementById('infoRaiSocCli').value = "";
            document.getElementById('infoAbreviationCli').value = "";
            document.getElementById('infoFjCli').value = "*";
            document.getElementById('infoSiretCli').value = "";
            document.getElementById('infoCodeEntiteCli').value = "";
            document.getElementById('infoContactCli').value = "";
            document.getElementById('infoCommentaireCli').value = "";
            document.getElementById('infoTypeAdresseCli').value = "Adresse bureau";
            document.getElementById('infoAdresseCli').value = "";
            document.getElementById('infoTelCli').value = "tel";
            document.getElementById('infoTelephoneCli').value = "";
            document.getElementById('infoCourrielCli').value = 1;
            document.getElementById('infoEmailCli').value = "";
            document.getElementById('infoSiteCli').value = "";


            document.getElementById('transparence').style.visibility = "visible";
            document.getElementById('infoCli').style.visibility = "visible";
            document.getElementById('newCli').value = "new";
        }
        else
        {
            document.getElementById('infoCli').style.visibility = 'hidden'; 
            document.getElementById('transparence').style.visibility= 'hidden';
            document.getElementById('infoTypeCli').disabled = true;
        }
    }
    exit(true);
}




function delCli() //Permet de supprimer un client
{
    c = -1;
    x = document.getElementById('SaveLigneCli').value; //Récupère la position du client sélectionné.
    for (j = 0; j <= longueur(lstClients)-1; j++)
    {
        if (lstClients[j] != "¤") //Vérifie si un client existe à l'index donné
        {
            if (document.getElementById('nom'+x).innerHTML == lstClients[j]["typeRechCliNom"]) 
            {   //Initialisation de la requête de suppression du client
                lstChangements+= "DELETE FROM entite WHERE ent_raison_sociale = '"+capitalizeFirstLetter(lstClients[j]['typeRechCliNom'])+"';*";
                                 /*"DELETE FROM categorie WHERE cat_libelle = '"+capitalizeFirstLetter(lstClients[j]['Categorie'])+"';*";
                                 "DELETE FROM client WHERE cli_commentaires = '"+capitalizeFirstLetter(lstClients[j]['commentaires'])+"';*";
                                 "DELETE FROM forme_juridique WHERE foj_libelle = '"+lstClients[j]['FOJ']+"';*";*/
                lstClients[j] = "¤";
                exit("true"); //Active un événement lors du fermeture de la page (Des modificiations ont été apportées, êtes-vous sûr de vouloir quitter sans sauvegarder?)
            }
        }
    }
    rechercher();
}



function enregistrer() //Enregistre les changements effectués par l'utilisateur dans la base de données.
{
    if (lstChangements != "") 
    {
        exit();     //Désactive l'événement à la fermeture de la page
        var win = window.open("vue/sauvegarde.php", "_blank", "width=25, height=25");
        win.liste = lstChangements; //Envoi de la variable lstChangements vers la nouvelle fenêtre
        lstChangements = ""; //Vide la variable contenant les changements
        $('#modif').css("display", "none"); //Enlève le message de Rappel de sauvegarde
    }
    else
    {
        document.getElementById('transparenceMSG').style.visibility = "visible"; //
        document.getElementById('containerMSG').style.visibility = "visible"; // Affiche un message d'erreur
        document.getElementById('MSG').innerHTML = "Aucun changement n'a été apporté."; //
    }
}


function exit(save="false") //Permet de lier ou non un événement à la fermeture de la page 
{
    if (save == "true") 
    {
        $(window).bind('beforeunload', function() //Lie l'événement
        {
            return 'Are you sure you want to leave?';
        });
    }
    else
    {
        $(window).unbind('beforeunload'); // Ne lie pas l'évènement
    }
}





function pdf()
{
    window.open('index.php?action=pdfClients&Cli='+(($('#infoRaiSocCli').val().replace("&","*")).replace("'","¤")).replace("'","¤"),'_blank');
}