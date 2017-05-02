function formaTexte(texte)  //Formate un texte (Tout en minuscule, Sans espace, Sans apostrophe, Sans accent)
{
    var texteFormater = "";
    if (texte != "" && typeof(texte) !== "undefined") 
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





function capitalizeFirstLetter(texte) //Première lettre du texte en Majuscule
{
    return texte.charAt(0).toUpperCase() + texte.slice(1);
}





function rechercher()   //Lance un recherche en fonction des critères de recherches
{
    $('#MSG').html("Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Référence produit<br>-Famille produit<br>-Désignation produit<br>-Prix du produit<br>-Nom du fournisseur"); 
    var NbProd = 0;
    $('#TbF').val(0);
    reference = $('#Reference').val().toLowerCase();   //    
    famille = $('#Famille').val();                     //
    typeRechProd = $('#typeRechProd').val();           //            
    libelle = $('#Libelle').val();                     //Récupération des critères de recherches
    prix = $('#PrixProd').val();                       //
    nomRaison = $('#NomRaison').val();                 //     
    categorie = $('#Cat').val();                       // 
    if (reference != "" || famille != "*" || libelle != "" || prix != "*")  
    {
        effaceTableau();  //Vide le tableau Produit
        $('#AddProd').prop("disabled", false);  //Active le boutton Ajout Produit
        NbProd = tableProduit(reference, famille, typeRechProd, libelle, prix);   
    }
    else if (nomRaison != "")
    {
        effaceTableau();  //Vide le tableau Produit
        $('#AddProd').prop("disabled", false);  //Active le boutton Ajout Produit
        for (compteF = 0; compteF <= longueur(lstProduits)-1; compteF++)
        {
            if (lstProduits[compteF] != "¤")
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
            if (lstProduits[i] != "¤")
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
        effaceTableau();  //Vide le tableau Produit
        $('#transparenceMSG').css("visibility", "visible");     //Rend visible un fond noir translucide
        $('#containerMSG').css("visibility", "visible");        //Rend visible la fenêtre d'erreur
        $('#MSG').html("Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Référence produit<br>-Famille produit<br>-Désignation produit<br>-Prix du produit<br>-Nom du fournisseur");   //Initialise le message d'erreur
    }
    $('#NbProdMax').html(NbProd);       //Affiche le produit séléctionné
    $('#NumDuProd').html(NbProd);       //Affiche le nombre de produit
}






function tableProduit(ref,fam,typeRech, lib, prix)  //Filtre les produits en fonctions des critères de recherche
{
    lib = formaTexte(lib);
    var x = 0, x1 = 0, x2 = 0, ligneProd = 0;
    if (document.getElementById('LePrix'))         // 
    {                                              //
       x = $('#LePrix').val();                     //
    }                                              //
    if (document.getElementById('LePrix1'))        // 
    {                                              //
        x1 = $('#LePrix1').val();                  //=========> #Récupération du prix du produit
    }                                              //
    if (document.getElementById('LePrix2'))        // 
    {                                              //
        x2 = $('#LePrix2').val();                  //
    }                                              //
    if (ref != "") 
    {
        for (i = 0; i <= longueur(lstProduits)-1; i++)    //Recherche les produits qui ont la même référence que celle que l'utilisateur à entrée
        {
            if (lstProduits[i] != "¤")          //Vérifie si un produit existe à un index donné
            {
                var z = 0, verif = true;
                while (verif == true && ref.length != -1 && ref.length-1 >= z)  
                {
                    if(!(ref.charAt(z) == formaTexte(lstProduits[i]["referenceProd"]).charAt(z)))   //Vérifie l'existance d'un produit ayant la même référence que celle que l'utilisateur à entrée
                    {
                        verif = false;
                    }
                    z++;
                }
                if (verif)
                {
                    if (fam == "*" || lstProduits[i]["familleProd"] == fam)     //Vérifie l'existance d'un produit ayant la même famille de produit que celle que l'utilisateur à entrée
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
            if (lstProduits[parcourlib] != "¤")     //Vérifie si un produit existe à un index donné
            {
                if (fam == "*" || lstProduits[parcourlib]["familleProd"] == fam)    //Vérifie l'existance d'un produit ayant la même famille de produit que celle que l'utilisateur à entrée
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
            if (lstProduits[parcour] != "¤")        //Vérifie si un produit existe à un index donné
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
            if (lstProduits[parcourPrix] != "¤")        //Vérifie si un produit existe à un index donné
            {
                ligneProd = comparePrix(parcourPrix,ligneProd,prix,x,x1,x2);
            }   
        }
    }
    return ligneProd;
}





function triLibelleProd(typeRechProd,lib,i,ligneProd,prix,x,x1,x2) //Filtre les produits en fonction du type de recherche de libellé de produit
{
    var z = 0;
    var bool = true;
    if (typeRechProd == "*") 
    {
        while (bool == true && lib.length != -1 && lib.length-1 >= z)  //Vérifie la correspondance des caractères(du libellé entrée par l'utilisateur & le libellé de la liste produit) un à un
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
        if (lib == formaTexte(lstProduits[i]["rechLibelleProd"]) || lib == formaTexte(lstProduits[i]["libelleProd"])) //Vérifie si dans la liste produit, un produit ayant le même libellé que celui que l'utilisateur a entrée
        {
            ligneProd = comparePrix(i,ligneProd,prix,x,x1,x2);
        }
    }
    else if (typeRechProd == "Contient") 
    {
        if (formaTexte(lstProduits[i]["libelleProd"]).includes(lib)) // vérifie si un mot entrée par l'utilisateurs est contenu dans un libellé de la liste produit
        {
            ligneProd = comparePrix(i,ligneProd,prix,x,x1,x2);
        }
    }
    return ligneProd;
}





function comparePrix(i,ligneProd,prix,x,x1,x2)      //Filtre les produits en fonction des prix
{
    if (prix != "*") 
    {
        if (prix == "Egal") 
        {
            if (parseInt(x) == parseInt(lstProduits[i]["prixProd"]))      //Vérifie si l'un des prix des produits de la liste produit correspond à celui que l'utilisateur a entrée
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
            if (parseInt(lstProduits[i]["prixProd"]) >= parseInt(x))      //Vérifie si l'un des prix des produits de la liste produit est supérieur à celui que l'utilisateur à entrée
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
            if (parseInt(lstProduits[i]["prixProd"]) <= parseInt(x))      //Vérifie si l'un des prix des produits de la liste produit est inférieur à celui que l'utilisateur à entrée
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
            if (parseInt(x1) <= parseInt(lstProduits[i]["prixProd"]) && parseInt(x2) >= parseInt(lstProduits[i]["prixProd"]))         //Vérifie si l'un des prix des produits de la liste produit est entre les prix que l'utilisateur à entrée
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





function filtreFourn(lignePr, compte = 0)   //Filtres les fournisseurs en fonction du type de recherche de pour le nom ou la raison sociale d'un fournisseur
{
    var bool = false;
    switch ($('#typeRechFourn').val())
    {
        case "*":
            for (x = 0; x <= formaTexte(document.getElementById('NomRaison').value).length-1; x++)
            {
                if (formaTexte(lstProduits[lignePr]['fournisseurProd']['produitFournisseur']).charAt(x) == formaTexte($('#NomRaison').val()).charAt(x))     //Vérifie si le nom ou la raison sociale d'un fournisseur commence par le nom ou la raison sociale entrée par l'utilisateur
                {
                    bool = true
                }
            }
            break;
        case "Egal":
            if (formaTexte(lstProduits[lignePr]['fournisseurProd']['produitFournisseur']) == formaTexte($('#NomRaison').val()))         //Vérifie si l'un des nom ou raison sociale des fournisseur soit égal a celui que l'utilisateur a entrée
            {
                bool = true
            }
            break;
        case "Contient":
                if (formaTexte(lstProduits[lignePr]['fournisseurProd'][compte]['raisonSociale']).includes(formaTexte($('#NomRaison').val())))       //Vérifie si le nom ou la raison sociale contient le nom ou la raison sociale que l'utilisateur à entrée
                {
                    bool = true
                }
            break;
    }
    return bool;
}





function afficheProd(ligneP,ligneProd)  //Affiche le produit dans le tableau des produits
{
    if (tableFournisseur(ligneP)) 
    {
        $('#indexProduit'+ligneProd).val(ligneP);
        $("#ref"+ligneProd).html(lstProduits[ligneP]["referenceProd"]); 
        $("#lib"+ligneProd).html(lstProduits[ligneP]["libelleProd"]);
        $("#fam"+ligneProd).html(lstProduits[ligneP]["familleProd"]);
        $("#prix"+ligneProd).html(lstProduits[ligneP]["prixProd"]+"&euro;");
        return true;
    }
    return false;
}





function getFournisseur(produit = "*")         //Retourne soit la liste des fournisseurs d'un produit si un nom de produit est indiqué sinon retourne la liste de tout les fournisseurs
{
    var save = [];
    var liste = [];
    var info = ['raisonSociale', 'produitFournisseur', 'codeEntite', 'contact', 'commentaire', 'siret', 'abreviation', 'clefPhonetique', 'categorie', 
                'adresse', 'formeJuridique', 'email', 'rechCategorie', 'rechFormeJuridique', 'rechRaisonSociale', 'telephoneFixe','mobile','fax'];
    if (produit != "*")
    {
        for (a = 0; a <= longueur(lstProduits)-1; a++)
        {
            if (lstProduits[a] != "¤")      //Vérifie si un produit existe à l'index donné
            {
                if (lstProduits[a]['rechLibelleProd'].toUpperCase() == produit.toUpperCase())
                {
                    if (typeof(lstProduits[a]['fournisseurProd']) !== "undefined")      //Vérifie si un produit possède des fournisseurs
                    {
                        for (d = 0; d <= longueur(lstProduits[a]['fournisseurProd'])-1; d++)
                        {
                            if (lstProduits[a]['fournisseurProd'][d] != "¤") //Vérifie si un fournisseur existe à l'index donné
                            {
                                for (b = 0; b <= 16; b++)
                                {
                                    save[info[b]] = lstProduits[a]['fournisseurProd'][d][info[b]];
                                }
                                liste[d] = save;
                                save = [];
                            }
                            else
                            {
                                liste[d] = "¤";
                            }
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
            if (typeof(lstProduits[a]['fournisseurProd']) !== "¤")      //Vérifie si le fournisseur existe à l'index donné
            {
                if (typeof(lstProduits[a]['fournisseurProd']) !== "undefined")      //Vérifie si le produit possède des fournisseurs
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





function tableFournisseur(x)        //Filtre les fournisseurs en fonction des critères de recherches
{
    if ($('#NomRaison').val() != "") 
    {
        var bool = false;
        if ($('#Cat').val() == "*") 
        {
            if (lstProduits[x]['fournisseurProd'] != undefined)     //Vérifie si un produit à un fournisseur ou non
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





function afficheFour(y)     //Affiche le fournisseur dans le tableau des fournisseurs
{
    existe = false;
    q = 0;
    for (long = 0; long <= longueur(getFournisseur(lstProduits[y]['rechLibelleProd']))-1; long++)
    {
        if (lstProduits[y]['familleProd'] == $('#Famille').val() || $('#Famille').val() == "*") 
        {
            if (lstProduits[y]['fournisseurProd'][long] != "¤")     //Vérifie si le fournisseur existe à l'index donné
            {
                while ($('#raiSoc'+q).html() != "")
                {
                    if ($('#raiSoc'+q).html() == lstProduits[y]['fournisseurProd'][long]['raisonSociale']) 
                    {
                        existe = true;
                        break;
                    }
                    q++;
                }
                if (!existe) 
                {
                    var ligneFour = $('#TbF').val();
                    $("#forme"+ligneFour).html(lstProduits[y]['fournisseurProd'][long]["formeJuridique"]);
                    $("#raiSoc"+ligneFour).html(lstProduits[y]['fournisseurProd'][long]["raisonSociale"]);
                    $("#cat"+ligneFour).html(lstProduits[y]['fournisseurProd'][long]["categorie"]);
                    $('#TbF').val(parseInt(ligneFour)+1);
                    $('#indexFourn'+ligneFour).val(y);
                }
            }
        }
    }
}
 





function longueur(obj)  //Retourne la longueur d'une liste
{
    var size = 0, cle;
    for (cle in obj) //Parcour la liste
    {
        if (obj.hasOwnProperty(cle))        //Vérifie si chaque objet appartenant à la liste possède une clé
        { 
            size++
        }
    }
    return size;
}





function effacer()      //Remet à 0 les critères de recherches
{
    $('#Reference').val("");
    $('#Famille').val("*");
    $('#typeRechProd').val("*");
    $('#Libelle').val("");
    $('#PrixProd').val("*");
    $('#typeRechFourn').val("*");
    $('#NomRaison').val("");
    $('#Cat').val("*");
    $('#FormeJuridique').val("*");
    $('#NumDuProd').html("0");
    $('#NbProdMax').html("0");
    effaceTableau();
    ChangePrix();
}





function effaceTableau()        //Vide le tableau des produits et des fournisseurs
{
    $('#AddProd').prop("disabled", true);       //Désactive le bouton Ajout Produit
    $('#DelProd').prop("disabled", true);       //Désactive le bouton Supprimer Produit
    for (i = 0; i <= 300; i++)
    {
        $("#forme"+i).html("");             //
        $("#raiSoc"+i).html("");            //    
        $("#cat"+i).html("");               //
        $("#ref"+i).html("");               //Vide le tableau des produits et des fournisseurs
        $("#lib"+i).html("");               //
        $("#fam"+i).html("");               //
        $("#prix"+i).html("");              //
        $("#ref"+i).css("backgroundColor", "white");        //
        $("#lib"+i).css("backgroundColor", "white");        //
        $("#fam"+i).css("backgroundColor", "white");        //
        $("#prix"+i).css("backgroundColor", "white");       //Change la couleur du fond de toute les lignes du tableau des produits et des fournisseurs en blanc
        $("#forme"+i).css("backgroundColor", "white");      //
        $("#raiSoc"+i).css("backgroundColor", "white");     //
        $("#cat"+i).css("backgroundColor", "white");        //
    }
}





function ChangePrix()       //Change l'affichage des entrées des prix en fonction du type de recherche des prix
{
    x = $('#PrixProd').val();
    switch (x)
    {
        case "*":
            $('#InPrix').html("");
            break;
        case "Egal":
        case "Infer":
        case "Super":
            $('#InPrix').html('<input type="number" id="LePrix" placeholder="0.0" style="margin-top: 2%; text-align: center;">');
            break;
        case "Entre":
            $('#InPrix').html('<input type="number" id="LePrix1" placeholder="0.0" style="width: 38%; margin-right: 5px; margin-top: 2%; text-align: center;"> <input type="number" id="LePrix2" placeholder="0.0" style="width: 38%; margin-top: 2%; text-align: center;">');
            break;
    }
}





function selectProd(ligne)      //Permet la séléction d'un d'un produit
{
    if ($('#ref'+ligne).html() != "" || $('#lib'+ligne).html() != "" || $('#fam'+ligne).html() != "" || $('#prix'+ligne).html() != "") 
    {
        ancienneLigne = $('#SaveLigneProd').val();      //Récupération du précédent produit séléctionné

        $('#ref'+ancienneLigne).css("backgroundColor", "white");           //
        $('#lib'+ancienneLigne).css("backgroundColor", "white");           //Change la couleur de fond de l'ancien produit séléctionné en blanc
        $('#fam'+ancienneLigne).css("backgroundColor", "white");           //
        $('#prix'+ancienneLigne).css("backgroundColor", "white");          //

        $('#ref'+ligne).css("backgroundColor", "#BBBBBB");          //
        $('#lib'+ligne).css("backgroundColor", "#BBBBBB");          //Change la couleur du fond du produit sélectionné en gris
        $('#fam'+ligne).css("backgroundColor", "#BBBBBB");          //
        $('#prix'+ligne).css("backgroundColor", "#BBBBBB");         //

        $('#SaveLigneProd').val(ligne);         //Sauvegarde la position du produit séléctionné
        $('#NumDuProd').html(ligne+1);          //Affiche le numéro du prod séléctioné (NbProd/NbProdMax au dessus du tableau des produits)

        $('#DelProd').prop("disabled", false);      //Active le bouton Supprimer un produit
    }
}





function selectFourn(ligne)     //Permet la séléction d'un produit
{
    if ($('#forme'+ligne).html() != "" || $('#raiSoc'+ligne).html() != "" || $('#cat'+ligne).html() != "") 
    {

        ancienneLigne = $('#SaveLigneFourn').val();     //Récupération du précédent fournisseur séléctionné

        $('#forme'+ancienneLigne).css("backgroundColor", "white");      //
        $('#raiSoc'+ancienneLigne).css("backgroundColor", "white");     //Change la couleur du fond de l'ancien fournisseur séléctionné en blanc
        $('#cat'+ancienneLigne).css("backgroundColor", "white");        //

        $('#forme'+ligne).css("backgroundColor", "#BBBBBB");        //
        $('#raiSoc'+ligne).css("backgroundColor", "#BBBBBB");       //Change la couleur du fond du fournisseur sélectionné en gris
        $('#cat'+ligne).css("backgroundColor", "#BBBBBB");          //
        $('#SaveLigneFourn').val(ligne);
    }
}





function infoProd(fenetre,LigneProd = 0)        //Ouvre la fenêtre d'information d'un produit (événement: Clic droit) / Ferme la fenêtre d'information d'un produit (événement: Bouton fermer)
{
    $('#newProd').val("edit");      //Active le mode modification
    if (fenetre == "open")  //Le code suivant permet d'afficher toute les informations d'un produit
    {
        backupDel = [];
        backupAdd = [];
        if ($('#ref'+LigneProd).html() != "" || $('#lib'+LigneProd).html() != "" || $('#fam'+LigneProd).html() != "" || $('#prix'+LigneProd).html() != "") 
        {
            indexProduit = $('#indexProduit'+LigneProd).val();
            $('#transparence').css("visibility", "visible");
            $('#infoProd').css("visibility", "visible");
            $('#infoFamProd').val(lstProduits[indexProduit]['familleProd']);
            $('#infoRefProd').html(lstProduits[indexProduit]['referenceProd']);
            $('#infoLibProd').html(lstProduits[indexProduit]['libelleProd']);
            if (lstProduits[LigneProd]['actif'] == 1) 
            {
                $('#actif').prop("checked", true);
                lstSaveCurrentInfoProd["familleActif"] = 1;
            }
            $('#infoRefProdModif').val(lstProduits[indexProduit]['referenceProd']);
            $('#infoLibProdModif').val(lstProduits[indexProduit]['libelleProd']);
            $('#infoDescriptionProd').val(lstProduits[indexProduit]['descriptionProd'].replace("*", "\n"));
            $('#infoPrixProd').val(parseFloat(lstProduits[indexProduit]['prixProd']));
            $('#infoTvaPourcentage').val(lstProduits[indexProduit]['tvaProd']);
            for (j = 0; j <= 6; j++)
            {
                $('#infoFormeFournisseur'+j).html("");
                $('#infoRaisonSocialeFournisseur'+j).html("");
                $('#infoCategorieFournisseur'+j).html("");
            }  
            if (typeof(lstProduits[indexProduit]['fournisseurProd']) != "undefined") 
            {
                i = 0;
                var fournisseur = getFournisseur(lstProduits[indexProduit]['rechLibelleProd']);
                for (j = 0; j <= longueur(fournisseur)-1; j++)
                {
                    if(fournisseur[j] != "¤")
                    {
                        $('#infoFormeFournisseur'+i).html(fournisseur[j]['formeJuridique']);
                        $('#infoRaisonSocialeFournisseur'+i).html(fournisseur[j]['raisonSociale']);
                        $('#infoCategorieFournisseur'+i).html(fournisseur[j]['categorie']);
                        i++;
                    }
                }
            }
            calculTva();
            selectProd(LigneProd);
            InfoFournisseur(indexProduit);
            lstSaveCurrentInfoProd["famille"] = lstProduits[indexProduit]['familleProd'];           //
            lstSaveCurrentInfoProd["reference"] = lstProduits[indexProduit]['referenceProd'];       //
            lstSaveCurrentInfoProd["designation"] = lstProduits[indexProduit]['libelleProd'];       //
            lstSaveCurrentInfoProd["description"] = lstProduits[indexProduit]['descriptionProd'];   //Sauvegarde les information de base du produit
            lstSaveCurrentInfoProd["prixHT"] = $('#infoPrixProd').val();                            //                                    
            lstSaveCurrentInfoProd["TVA"] = $('#infoTvaPourcentage').val();                         //        
            lstSaveCurrentInfoProd["prixTTC"] = $('#infoTvaPrix').val();                            //        
            lstSaveCurrentInfoProd["note"] = $('#commentaire').html();                              //    
        }
    }
    else if (fenetre == "close")
    {
        if (longueur(backupDel) > 0)
        {
            for (x = 0; x <= longueur(backupDel)-1; x++)
            {
                lstProduits[backupDel[x].indexProd]['fournisseurProd'][backupDel[x].indexFourn] = backupDel[x].infoFourn;
            }
            backupDel = [];
        }
        $('#Save').css("display", "none");                           // 
        $('#Cancel').css("display", "none");                         //  
        $('#ok').css("display", "inline");                           // 
        $('#transparenceProd').css("visibility", "hidden");          //Ferme la fenêtre produit
        $('#containerMSG').css("visibility", "hidden");              //
        $('#transparenceMSG').css("visibility", "hidden");           //
        $('#transparence').css("visibility", "hidden");              //
        $('#infoProd').css("visibility", "hidden");                  //  
    }
}





function infoFourn(fenetre,LigneFourn = 0)  //Ouvre la fenêtre d'information d'un fournisseur (événement: Clic droit) / Ferme la fenêtre d'information d'un fournisseur (événement: Bouton fermer)
{
    if (fenetre == "open")  //LE code suivant permet d'afficher les informations d'un fournisseur
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





function changeNum()    //Permet d'afficher le numéro du fournisseur en function du téléphone choisit par l'utilisateur
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





function InfoFournisseur(NumProd)       //Affiche les fournisseurs d'un produit dans le tableau fournisseur se trouvant dans la fenêtre d'information d'un produit
{
    y = 0;
    for (i = 0; i <= longueur(lstProduits[NumProd]['fournisseurProd'])-1; i++)
    {
        if (lstProduits[NumProd]['rechLib'] == lstProduits[NumProd]['fournisseurProd'][i]['produitFournisseur'])
        {
            if (lstProduits[NumProd]['fournisseurProd'][i] != "¤")
            {
                document.getElementById('infoFormeFournisseur'+y).innerHTML = lstProduits[NumProd]['fournisseurProd'][i]['formeJuridique'];
                document.getElementById('infoRaisonSocialeFournisseur'+y).innerHTML = lstProduits[NumProd]['fournisseurProd'][i]['raisonSociale'];
                document.getElementById('infoCategorieFournisseur'+y).innerHTML = lstProduits[NumProd]['fournisseurProd'][i]['categorie'];
                if(document.getElementById('infoCategorieFournisseur'+y).innerHTML == "")
                {
                    document.getElementById('infoCategorieFournisseur'+y).innerHTML = lstProduits[NumProd]['fournisseurProd'][i]['codeEntite'];
                }
                document.getElementById('numFournisseur'+y).value = i;
                y++;
            }
        }
    }
}





function commentaireFournisseur(NumLigne)       //Affiche le commentaire du fournisseur en fonction du fournisseur séléctionné
{
    /*x = document.getElementById('numFournisseur'+NumLigne).value;
    selectFourn(NumLigne);
    if (listeFournisseur[x]['commentaire'] != "") 
    {
        document.getElementById('commentaire').innerHTML = listeFournisseur[x]['commentaire'].replace("*", "\n");
    }
    else
    {
        document.getElementById('commentaire').innerHTML = "Aucun commentaire"; 
    }*/
}





function calculTva()    //Permet de calculé et d'affiché la TVA
{
    x = document.getElementById('infoTvaPourcentage').value;
    if (x != "*") 
    {
        document.getElementById('infoTvaPrix').value = parseFloat(((document.getElementById('infoPrixProd').value) * ((parseFloat(document.getElementById('infoTvaPourcentage').value)/100)+1)).toFixed(3)).toFixed(2);
    }
    else
    {
        document.getElementById('infoTvaPrix').value = document.getElementById('infoPrixProd').value;  
    }
}





function pdf()
{
    window.open('index.php?action=pdf&prod='+apostrophe($('#infoLibProdModif').val()),'_blank');
}





function apostrophe(texte)  //Ajoute un apostrophe après un appostrophe (Contrainte PGsql)
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





function addEditProd(mode="")   //Permet d'enregistrer ou d'insérer (coté client) un nouveau produit
{
    if (document.getElementById('newProd').value == "new" && mode == "")        //Nouveau produit
    {
        if (document.getElementById('infoLibProdModif').value != "")  
        {   //Récupération des informations du produit entrée par l'utilisateur
            var lst = {referenceProd: document.getElementById('infoRefProdModif').value, 
                       libelleProd: document.getElementById('infoLibProdModif').value,
                       descriptionProd: document.getElementById('infoDescriptionProd').value ,
                       familleProd: "", 
                       prixProd: document.getElementById('infoPrixProd').value, 
                       tvaLibelleProd: "",
                       tvaProd: document.getElementById('infoTvaPourcentage').value, 
                       actifProd: 0, 
                       rechLibelleProd: formaTexte(document.getElementById('infoLibProdModif').value),
                       rechFamilleProd: "",
                       fournisseurProd: []
                      };
            if ($('#infoRaisonSocialeFournisseur0').html() != "")
            {
                for (h = 0; h <= 6; h++)
                {
                    if ($('#infoRaisonSocialeFournisseur'+h).html() != "")
                    {
                        if (backupAdd[h] != "") 
                        {   //Récupération des informations des fournisseur d'un produit entrée par l'utilisateur
                            lst.fournisseurProd[longueur(lst.fournisseurProd)] = {raisonSociale: backupAdd[h].infoFourn['typeRechCliNom'],
                                                                                  abreviation: backupAdd[h].infoFourn['abreviation'],
                                                                                  categorie: backupAdd[h].infoFourn['Categorie'],
                                                                                  codeEntite: backupAdd[h].infoFourn['code'],
                                                                                  contact: backupAdd[h].infoFourn['contact'],
                                                                                  adresse: backupAdd[h].infoFourn['adresseBureau'],
                                                                                  clefPhonetique: "",
                                                                                  formeJuridique: backupAdd[h].infoFourn['FOJ'],
                                                                                  siret: backupAdd[h].infoFourn['SIRET'],
                                                                                  commentaire: backupAdd[h].infoFourn['commentaires'],
                                                                                  produitFournisseur: backupAdd[h].produit,
                                                                                  rechCategorie: backupAdd[h].infoFourn['Categorie'].toUpperCase(),
                                                                                  rechFormeJuridique: backupAdd[h].infoFourn['FOJ'],
                                                                                  rechRaisonSociale: backupAdd[h].infoFourn['rechNom']+" "+backupAdd[h].infoFourn['rechPrenom'],
                                                                                  email: backupAdd[h].infoFourn['mail'],
                                                                                  fax: backupAdd[h].infoFourn['tel'].faxSociete,
                                                                                  mobile: backupAdd[h].infoFourn['tel'].mobileSociete,
                                                                                  telephoneFixe: backupAdd[h].infoFourn['tel'].telephoneSociete};
                        }
                    }
                    else
                    {
                        break;
                    }
                }
            }
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
            //Initialisation de la requête d'insertion du nouveau produit
            lstProduits[longueur(lstProduits)] = lst;       //Ajoute le nouveau produit à la liste produit
            lstChangements+= "INSERT INTO produit(pro_idproduit, pro_reference, pro_libelle, pro_description,pro_pxvente_ht, pro_iactif, pro_lib_rech, pro_idfamille, pro_idtva) VALUES((SELECT nextVal('\"SQ_ID\"')),'"+apostrophe(lst.referenceProd)+
                                                                                                                                                                            "', '"+apostrophe(lst.libelleProd)+
                                                                                                                                                                            "', '"+apostrophe(lst.descriptionProd)+
                                                                                                                                                                            "', "+lst.prixProd+
                                                                                                                                                                            ", "+lst.actifProd+
                                                                                                                                                                            ", '"+apostrophe(lst.libelleProd).toUpperCase()+
                                                                                                                                                                            "', (SELECT fam_idfamille FROM famille WHERE fam_libelle = '"+capitalizeFirstLetter(lst.rechFamilleProd)+
                                                                                                                                                                            "'), (SELECT tva_idtva FROM tva WHERE tva_libelle = '"+lst.tvaLibelleProd+
                                                                                                                                                                            "'));*";
            for (h = 0; h <= longueur(lst.fournisseurProd)-1; h++)
            {
                if (backupAdd[h] != "") 
                {
                    //Initialisation de la requête d'insertion des fournisseur du produit
                    lstChangements+="INSERT INTO prod_four VALUES((SELECT nextVal('\"SQ_ID\"')), (SELECT pro_idproduit FROM produit WHERE pro_libelle = '"+apostrophe(backupAdd[h].produit)+"'), (SELECT fou_idfournisseur FROM fournisseur WHERE fou_identite = (SELECT ent_identite FROM entite WHERE ent_raison_sociale = '"+apostrophe(backupAdd[h].infoFourn['typeRechCliNom'].replace("&amp;", "&"))+"')), null);*";
                }
            }
            $('#modif').css("display", "block");
            document.getElementById('infoProd').style.visibility = 'hidden'; 
            document.getElementById('transparence').style.visibility= 'hidden';
            exit("true");       //Active un événement lors du fermeture de la page (Des modificiations ont été apporté, êtes-vous sûr de vouloir quitté sans sauvegardé?)
            backupAdd = [];     //vide la variable global (Contient la liste des nouveaux fournisseurs ajouté aux produit)
            rechercher();
        }
        else
        {
            document.getElementById('transparenceMSG').style.visibility = "visible";                                    //
            document.getElementById('containerMSG').style.visibility = "visible";                                       //Affiche un message d'erreur
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins le libellé du produit<br>";        //
        }
    }
    else if (document.getElementById('newProd').value == "edit" && mode == "" || document.getElementById('newProd').value == "edit" && mode == "ok")        //Modification du produit
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
            //Récupération des informations non-modifié du produit
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
            for (j = 0; j <= 8; j++)        //Vérifie si un changement à été apporté ou pas
            {
                
                if (lstSaveCurrentInfoProd[liste[j]] != lstRecupInfoProd[liste[j]]) 
                {
                    //Initialisation de la requête de modification d'un produit
                    lstChangements+= "UPDATE produit SET pro_reference = '"+apostrophe(lstRecupInfoProd.reference)+
                                     "', pro_libelle= '"+apostrophe(lstRecupInfoProd.designation)+
                                     "', pro_description = '"+apostrophe(lstRecupInfoProd.description)+
                                     "', pro_pxvente_ht = '"+apostrophe(lstRecupInfoProd.prixHT)+
                                     "',pro_idfamille = (SELECT fam_idfamille FROM famille WHERE fam_libelle = '"+apostrophe(lstRecupInfoProd.famille)+
                                     "'), pro_idtva = (SELECT tva_idtva FROM tva WHERE tva_libelle = '"+apostrophe(lstRecupInfoProd.TVAlibelle)+
                                     "'), pro_lib_rech = '"+apostrophe(formaTexte(lstRecupInfoProd.designation).toUpperCase())+
                                     "' WHERE pro_libelle = '"+apostrophe(lstSaveCurrentInfoProd["designation"])+"';*";

                    $('#modif').css("display", "block");
                    exit("true");   //Active un événement lors du fermeture de la page (Des modificiations ont été apporté, êtes-vous sûr de vouloir quitté sans sauvegardé?)
                    break;
                }
            }
            for(j = 0; j <= longueur(lstProduits)-1; j++)
            {
                if (lstProduits[j] != "¤")      //Vérifie si un produit existe à l'index donné
                {
                    if (lstSaveCurrentInfoProd['designation'] == lstProduits[j]['libelleProd'])     //Vérifie s'il y a eu une modification et les mets à jour dans la liste des produits
                    {
                        lstProduits[j]["referenceProd"] = lstRecupInfoProd["reference"].toUpperCase();
                        lstProduits[j]["libelleProd"] = lstRecupInfoProd["designation"];
                        lstProduits[j]["descriptionProd"] = lstRecupInfoProd["description"];
                        lstProduits[j]["familleProd"] = lstRecupInfoProd["famille"];
                        lstProduits[j]["prixProd"] = lstRecupInfoProd["prixHT"];
                        lstProduits[j]["tvaProd"] = lstRecupInfoProd["TVA"];
                        break;
                    }
                }
            }
            if (longueur(backupDel) > 0)    //Vérifie si des fournisseurs on été supprimé
            {
                for (x = 0; x <= longueur(backupDel)-1; x++)
                {
                    //Initialisation de la requête de suppression du fournisseur 
                    lstChangements+="DELETE FROM prod_four WHERE pfo_idproduit = (SELECT pro_idproduit FROM produit WHERE pro_libelle = '"+apostrophe(lstProduits[backupDel[x].indexProd]['libelleProd'])+"') AND pfo_idfournisseur = (SELECT fou_idfournisseur FROM fournisseur WHERE fou_identite = (SELECT ent_identite FROM entite WHERE ent_raison_sociale = '"+apostrophe(backupDel[x].infoFourn['raisonSociale'])+"'));*"
                } 
                backupDel = [];
            }
            if (longueur(backupAdd) > 0)        //Vérifie si des fournisseurs on été ajouté
            {
                for (t = 0; t <= longueur(backupAdd)-1; t++)
                {
                    if (backupAdd[t] != "")         //Vérifie si un fournisseur ajouté à été supprimé
                    {
                        //Initialisation de la requête d'ajout fourniseur
                        lstChangements+="INSERT INTO prod_four VALUES((SELECT nextVal('\"SQ_ID\"')), (SELECT pro_idproduit FROM produit WHERE pro_libelle = '"+apostrophe(backupAdd[t].produit)+"'), (SELECT fou_idfournisseur FROM fournisseur WHERE fou_identite = (SELECT ent_identite FROM entite WHERE ent_raison_sociale = '"+backupAdd[t].infoFourn['typeRechCliNom']+"')), null);*";
                        for (m = 0; m <= longueur(lstProduits)-1; m++)
                        {
                            if (lstProduits[m]['libelleProd'] == backupAdd[t].produit) 
                            {
                                if (lstProduits[m]['fournisseurProd'] != undefined)
                                {
                                    //Ajout du nouveau fournisseur au produit dans liste produit
                                    lstProduits[m]['fournisseurProd'][longueur(lstProduits[m]['fournisseurProd'])] = {raisonSociale: backupAdd[t].infoFourn['typeRechCliNom'],
                                                                                                                      abreviation: backupAdd[t].infoFourn['abreviation'],
                                                                                                                      categorie: backupAdd[t].infoFourn['Categorie'],
                                                                                                                      codeEntite: backupAdd[t].infoFourn['code'],
                                                                                                                      contact: backupAdd[t].infoFourn['contact'],
                                                                                                                      adresse: backupAdd[t].infoFourn['adresseBureau'],
                                                                                                                      clefPhonetique: "",
                                                                                                                      formeJuridique: backupAdd[t].infoFourn['FOJ'],
                                                                                                                      siret: backupAdd[t].infoFourn['SIRET'],
                                                                                                                      commentaire: backupAdd[t].infoFourn['commentaires'],
                                                                                                                      produitFournisseur: backupAdd[t].produit,
                                                                                                                      rechCategorie: backupAdd[t].infoFourn['Categorie'].toUpperCase(),
                                                                                                                      rechFormeJuridique: backupAdd[t].infoFourn['FOJ'],
                                                                                                                      rechRaisonSociale: backupAdd[t].infoFourn['rechNom']+" "+backupAdd[t].infoFourn['rechPrenom'],
                                                                                                                      email: backupAdd[t].infoFourn['mail'],
                                                                                                                      fax: backupAdd[t].infoFourn['tel'].faxSociete,
                                                                                                                      mobile: backupAdd[t].infoFourn['tel'].mobileSociete,
                                                                                                                      telephoneFixe: backupAdd[t].infoFourn['tel'].telephoneSociete};
                                }
                                else
                                {
                                    //Si le produit n'a pas encore de fournisseur alors ajout de la sous-liste fournisseurProdpui ajout du nouveau fournisseur
                                    lstProduits[m]['fournisseurProd'] = {0:{raisonSociale: backupAdd[t].infoFourn['typeRechCliNom'],
                                                                            abreviation: backupAdd[t].infoFourn['abreviation'],
                                                                            categorie: backupAdd[t].infoFourn['Categorie'],
                                                                            codeEntite: backupAdd[t].infoFourn['code'],
                                                                            contact: backupAdd[t].infoFourn['contact'],
                                                                            adresse: backupAdd[t].infoFourn['adresseBureau'],
                                                                            clefPhonetique: "",
                                                                            formeJuridique: backupAdd[t].infoFourn['FOJ'],
                                                                            siret: backupAdd[t].infoFourn['siret'],
                                                                            commentaire: backupAdd[t].infoFourn['commentaires'],
                                                                            produitFournisseur: backupAdd[t].produit,
                                                                            rechCategorie: backupAdd[t].infoFourn['Categorie'].toUpperCase,
                                                                            rechFormeJuridique: backupAdd[t].infoFourn['FOJ'],
                                                                            rechRaisonSociale: backupAdd[t].infoFourn['rechNom']+" "+backupAdd[t].infoFourn['rechPrenom'],
                                                                            email: backupAdd[t].infoFourn['mail'],
                                                                            fax: backupAdd[t].infoFourn['tel'].faxSociete,
                                                                            mobile: backupAdd[t].infoFourn['tel'].mobileSociete,
                                                                            telephoneFixe: backupAdd[t].infoFourn['tel'].telephoneSociete}};
                                }
                            }
                        }
                    }
                } 
                backupAdd = [];     //Vide la variable
            }
            rechercher();
        }
        else
        {
            document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins le libellé du produit<br>";        //
            document.getElementById('transparenceMSG').style.visibility = "visible";                                    //Affiche un message d'erreur
            document.getElementById('containerMSG').style.visibility = "visible";                                       //
        }   //
    }
    else        //Sinon affiche ouvre la fenêtre information produit VIDE, pour la saisie d'un nouveau produit
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





function delProd()      //Suppression d'un produit
{
    c = -1;
    x = document.getElementById('SaveLigneProd').value;     //Récupération de la ligne du produit sélectionné
    for (j = 0; j <= longueur(lstProduits)-1; j++)
    {
        if (lstProduits[j] != "¤")  //Verifie si un produit existe à l'index donné
        {
            if (document.getElementById('lib'+x).innerHTML == lstProduits[j]["libelleProd"] && document.getElementById('ref'+x).innerHTML == lstProduits[j]["referenceProd"]) 
            {
                fourn = getFournisseur(formaTexte(lstProduits[j]['libelleProd']).toUpperCase());    //Récupération de la liste des fournisseur en fonction du nom du produit donné en paramétre
                if (longueur(fourn) > 0) 
                {
                    for (f = 0; f <= longueur(fourn)-1; f++)
                    {
                        //Initialisation de la requête permettant de supprimer tout les fournisseurs lié au produit
                        lstChangements+= "DELETE FROM prod_four WHERE pfo_idproduit = (SELECT pro_idproduit FROM produit WHERE pro_libelle = '"+apostrophe(lstProduits[j]['libelleProd'])+"') AND pfo_idfournisseur = (SELECT fou_idfournisseur FROM fournisseur WHERE fou_identite = (SELECT ent_identite FROM entite WHERE ent_raison_sociale = '"+apostrophe(fourn[f]['raisonSociale'].replace("&amp;", "&"))+"'));*";
                    }
                }
                //Initialisation de la requête du suppression du produit
                lstChangements+= "DELETE FROM produit WHERE pro_libelle = '"+apostrophe(lstProduits[j]['libelleProd'])+"';*";
                lstProduits[j] = "¤";       //Rend le produit inexistant à l'index donné
                $('#modif').css("display", "block");        //Affiche le message de Rappel de sauvegarde
                exit("true");       //Active un événement lors du fermeture de la page (Des modificiations ont été apporté, êtes-vous sûr de vouloir quitté sans sauvegardé?)
            }
        }
    }
    rechercher();
}





function enregistrer()         //Permet d'enregistrer dans la base de données toute modification apporté
{
    if (lstChangements != "") 
    {
        exit();     //Désactive l'événement au fermeture de la page
        var win = window.open("Vue/sauvegarde.php", "_blank", "width=50, height=50");     //Ouvre une nouvelle fenêtre
        win.liste = lstChangements;     //Envoie de la variable lstChangements vers la nouvelle fenêtre
        lstChangements = "";        //Vide la variable contenant les changements
        $('#modif').css("display", "none");     //Enlève le message de Rappel de sauvegarde
    }
    else
    {
        document.getElementById('transparenceMSG').style.visibility = "visible";        //
        document.getElementById('containerMSG').style.visibility = "visible";           //Affiche un message d'erreur
        document.getElementById('MSG').innerHTML = "Aucun changement n'a été apporté."; //
    }
    
}





function exit(save="false")     //Permet de lié ou de délié un événement à la fermeture de la page
{
    if (save == "true") 
    {
        $(window).bind('beforeunload', function()       //Lie l'événement
        {
            return 'Are you sure you want to leave?';
        });
    }
    else
    {
        $(window).unbind('beforeunload');       //Délie l'événement
    }
}





function initDetectRechCli()        //Permet de rendre la recherche des fournisseur dynamique (au moindre changement dans les entrées clavier, une recherche se lance)
{
    securisation();
    $('#rechCodeCli').bind("keydown", function(){
        //setTimeout permet de temporisé pendant quelque miliseconde, le temps que la lettre soit inscrise dans la balise input, puis la function démarre
        setTimeout(function()
                   { 
                        effaceCli();
                        if ($('#rechCodeCli').val() != "" || $('#rechNomCli').val() != "" || $('#rechSiretCli').val() != "") 
                        {
                            rechercheCli();
                        }
                    });
    });
    $('#typeRechNomCli').bind("change", function(){
        setTimeout(function()
                   { 
                        effaceCli();
                        if ($('#rechCodeCli').val() != "" || $('#rechNomCli').val() != "" || $('#rechSiretCli').val() != "") 
                        {
                            rechercheCli();
                        }
                    });
    });
    $('#rechNomCli').bind("keydown", function(){
        setTimeout(function()
                   { 
                        effaceCli();
                        if ($('#rechCodeCli').val() != "" || $('#rechNomCli').val() != "" || $('#rechSiretCli').val() != "") 
                        {
                            rechercheCli();
                        }
                    });
    });
    $('#rechCatCli').bind("change", function(){
        setTimeout(function()
                   { 
                        effaceCli();
                        if ($('#rechCodeCli').val() != "" || $('#rechNomCli').val() != "" || $('#rechSiretCli').val() != "") 
                        {
                            rechercheCli();
                        }
                    });
    });
    $('#rechSiretCli').bind("keydown", function(){
        setTimeout(function()
                   { 
                        effaceCli();
                        if ($('#rechCodeCli').val() != "" || $('#rechNomCli').val() != "" || $('#rechSiretCli').val() != "") 
                        {
                            rechercheCli();
                        }
                    });
    });
}


function rechercheCli()     //Permet de lancer une recherche de fournisseur en fonction des critère de recherche
{
    for (indexCli = 0; indexCli <= longueur(lstClients)-1; indexCli++)
    {
        if (lstClients[indexCli]['code'].charAt(0).toUpperCase() != 'P')        //Enlève de la recherche les clients de type personne
        {
            if ($('#rechCodeCli').val() != "")
            {
                for (j = 0; j <= $('#rechCodeCli').val().length-1; j++)
                {
                    var b = true;
                    if ($('#rechCodeCli').val().charAt(j).toUpperCase() != lstClients[indexCli]['code'].charAt(j).toUpperCase()) 
                    {
                        b = false;
                        break;
                    }
                }
                if (b)
                {
                    if ($('#rechNomCli').val() != "") 
                    {
                        if ($('#rechCatCli').val() == lstClients[indexCli]['Categorie'] || $('#rechCatCli').val() == "*") 
                        {
                            if (rechSiret(indexCli) || $('#rechSiretCli').val() == "") 
                            {
                                if (typeRechCli(indexCli))
                                {
                                    afficheClients(indexCli);
                                }
                            }
                        }                 
                    }
                    else if($('#rechCatCli').val() != "*")
                    {
                        if($('#rechCatCli').val() == lstClients[indexCli]['Categorie'])
                        {
                            if (rechSiret(indexCli) || $('#rechSiretCli').val() == "") 
                            {
                                afficheClients(indexCli);
                            }
                        }
                    }
                    else
                    {
                        afficheClients(indexCli);
                    }
                }
            }
            else if($('#rechNomCli').val() != "")
            {
                if ($('#rechCatCli').val() == lstClients[indexCli]['Categorie'] || $('#rechCatCli').val() == "*") 
                {
                    if (rechSiret(indexCli) || $('#rechSiretCli').val() == "") 
                    {
                        if (typeRechCli(indexCli))
                        {
                            afficheClients(indexCli);
                        }
                    }
                }
            }
            else if ($('#rechSiretCli').val() != "")
            {
                if (rechSiret(indexCli))
                {
                    afficheClients(indexCli);
                }
            }
            else
            {
                //Affiche un message d'erreur
                document.getElementById('transparenceMSG').style.visibility = "visible";
                document.getElementById('containerMSG').style.visibility = "visible";
                document.getElementById('MSG').innerHTML = "Vous devez indiquer au moins un critère significatif !<br>Liste des critères significatifs:<br>-Code du client<br>-Nom / raison sociale du client<br>-SIRET du client";
            }
        }
    }
}





function typeRechCli(indexCli)      //Filtre les fournisseurs en fonction du type de recherche du nom ou de la raison sociale du fournissseur
{
    var bool = true;
    var bool2 = true;
    switch ($('#typeRechNomCli').val())
    {
        case "*":
            for (i = 0; i <= formaTexte($('#rechNomCli').val()).length-1; i++)
            {
                if (formaTexte($('#rechNomCli').val()).charAt(i).toUpperCase() != formaTexte(lstClients[indexCli]['rechNom']+lstClients[indexCli]['rechPrenom']).charAt(i).toUpperCase()) 
                {
                    bool = false;
                }
                if (formaTexte($('#rechNomCli').val()).charAt(i).toUpperCase() != formaTexte(lstClients[indexCli]['rechPrenom']).charAt(i).toUpperCase()) 
                {
                    bool2 = false;
                }
            }
            break;
        case "Egal a":
            if (formaTexte($('#rechNomCli').val()) != formaTexte(lstClients[indexCli]['rechNom']+lstClients[indexCli]['rechPrenom'])) 
            {
                bool = false;
            }
            if (formaTexte($('#rechNomCli').val()).toUpperCase() != formaTexte(lstClients[indexCli]['rechPrenom']).toUpperCase()) 
            {
                bool2 = false;
            }
            break;
        case "Contient":
            if (!(formaTexte(lstClients[indexCli]['rechNom']+lstClients[indexCli]['rechPrenom']).includes(formaTexte($('#rechNomCli').val()))))
            {
                bool = false;
            }
            if (!(formaTexte(lstClients[indexCli]['rechPrenom']).includes(formaTexte($('#rechNomCli').val()))))
            {
                bool2 = false;
            }
            break;
    }
    if (bool || bool2) 
    {
        return true;
    }
    else
    {
        return false;
    }
}





function rechSiret(indexCli)    //Filtre les fournisseurs en fonction de leur SIRET
{
    var bool = true
    for(j = 0; j <= $('#rechSiretCli').val().length-1; j++)
    {
        if ($('#rechSiretCli').val().charAt(j) != lstClients[indexCli]['SIRET'].charAt(j)) 
        {
            bool = false;
            break;
        }
    }
    if (bool) 
    {
        return bool;
    }
    return bool;
}





function afficheClients(indexCli)       //Affiche les fournisseurs dans la fenêtre recherche fournisseur
{
    ligneCli = $('#ligneCli').val();
    $('#codeCli'+ligneCli).html(lstClients[indexCli]['code']);
    $('#formeCli'+ligneCli).html(lstClients[indexCli]['FOJ']);
    $('#identiteCli'+ligneCli).html(lstClients[indexCli]['typeRechCliNom']+" "+lstClients[indexCli]['prenom']);
    $('#categorieCli'+ligneCli).html(lstClients[indexCli]['Categorie']);
    $('#villeCli'+ligneCli).html(lstClients[indexCli]['ville']);
    $('#ligneCli').val(parseInt(ligneCli)+1);
}





function effaceCli()        //Vide le tableau des fournisseurs dans la fenêtre recherche fournisseur
{
    for (i = 0; i <= 300; i++)
    {
        $('#codeCli'+i).html(" ");
        $('#formeCli'+i).html(" ");
        $('#identiteCli'+i).html(" ");
        $('#categorieCli'+i).html(" ");
        $('#villeCli'+i).html(" ");
    }
    $('#ligneCli').val(0);
}





function effaceAllCli()     //Vide le tableau et vide les critère de recherche dans la fenêtre recherche fournisseur
{
    effaceCli();
    $('#rechCodeCli').val("");
    $('#rechNomCli').val("");
    $('#typeRechNomCli').val("*");
    $('#rechCatCli').val("*");
}





function selectCli(i)       //Quand un double clique est fait sur fournisseur, ajoute le fournisseur au tableau fournisseur de la fenêtre information produit
{    
    if ($('#codeCli'+i).html() != " ")
    {
        $('#transparenceProd').css('visibility', 'hidden');
        $('#rechCli').css('visibility', 'hidden');
        for (j = 0; j <= longueur(lstClients)-1; j++)   //Recherche du fournisseur selectionné dans la liste clients
        {
            if ($('#codeCli'+i).html() == lstClients[j]['code']) 
            {
                break;  //Mets fin à la boucle une fois que le fournisseur à été trouvé, l'index du fournisseur est alors stocké dans la variable j
            }
        }
        for (l = 0; l <= 6; l++)
        {
            if ($('#infoRaisonSocialeFournisseur'+l).html() == "")  //Recherche un emplacement vide dans le tableau fournisseur
            {
                break;      //une fois un emplacement vide trouvé, l'index de l'emplacement est alors stocké dans la variable l
            }
        }
        if (lstClients[j]['FOJ'] > 2)
        {
            $('#infoFormeFournisseur'+l).html(lstClients[j]['FOJ']);                                            //         ||
        }                                                                                                       //         ||
        else                                                                                                    //         ||
        {                                                                                                       //         ||
            if (lstClients[j]['FOJ'] == 1)                                                                      //         ||
            {                                                                                                   //         ||
                $('#infoFormeFournisseur'+l).html("M.");                                                        //         ||
            }                                                                                                   //         ||
            else                                                                                                //         ||
            {                                                                                                   //        \  /
                $('#infoFormeFournisseur'+l).html("Mme.");                                                      //         \/
            }
        }
        $('#infoRaisonSocialeFournisseur'+l).html(lstClients[j]['typeRechCliNom']+" "+lstClients[j]['prenom']); //Ajout du nouveau fournisseur dans le tableau fournisseur de la fenêtre information produit
        $('#infoCategorieFournisseur'+l).html(lstClients[j]['Categorie']);                                      //
        backupAdd[longueur(backupAdd)] = {produit: $('#infoLibProdModif').val(), infoFourn:lstClients[j]};  //Ajout du nouveau fournisseur dans la liste des fournisseurs ajoutés
    }
}





function selectFournProd(ligne)     //Selectionne un fournisseur dans la liste des fournisseurs de la fenêtre information produit
{
    if ($('#infoFormeFournisseur'+ligne).html() != "" || $('#infoRaisonSocialeFournisseur'+ligne).html() != "" || $('#infoCategorieFournisseur'+ligne).html() != "")
    {
        ancienneLigne = $('#saveLigneFournProd').val();     //Récupération du dernier fournisseur séléctionné

        $('#infoFormeFournisseur'+ancienneLigne).css("backgroundColor", "white");              //
        $('#infoRaisonSocialeFournisseur'+ancienneLigne).css("backgroundColor", "white");      //Change la couleur de fond du dernier fournisseur séléctionné en blanc
        $('#infoCategorieFournisseur'+ancienneLigne).css("backgroundColor", "white");          //

        $('#infoFormeFournisseur'+ligne).css("backgroundColor", "#CCCCCC");             //
        $('#infoRaisonSocialeFournisseur'+ligne).css("backgroundColor", "#CCCCCC");     //Change la couleur de fond du fournisseur séléctionné en gris
        $('#infoCategorieFournisseur'+ligne).css("backgroundColor", "#CCCCCC");         //

        $('#saveLigneFournProd').val(ligne);        //Enregistre la ligne du fournisseur séléctionné
        $('#delSelectFourn').prop("disabled",false);        //Active le bouton de suppression fournisseur
    }
}





function delSelectFournProd()       //Permet la suppression du fournisseur séléctionné
{
    ligne =  $('#saveLigneFournProd').val();        //Récupration du fournisseur séléctionné
    if (confirm("Êtes-vous sûr de vouloir retirer de la liste ce fournisseur ?"))       //Fait apparaite une fenêtre de validation de suppression (Met pause à la fonction tant que l'utilisateur n'a pas chosit)
    {
        raiSoc = $('#infoRaisonSocialeFournisseur'+ligne).html().replace("&amp;", "&"); //En html le & est remplacé par &amp; , Remplace le mot &amp; s'il existe, par &
        fourn = getFournisseur(formaTexte($('#infoLibProdModif').val()).toUpperCase());
        for (j = 0; j <= 6; j++)
        {
            document.getElementById('infoFormeFournisseur'+j).innerHTML = "";           //
            document.getElementById('infoRaisonSocialeFournisseur'+j).innerHTML = "";   //Vide le tableau fournisseur dans la fenêtre information produit
            document.getElementById('infoCategorieFournisseur'+j).innerHTML = "";       //
        }       
        k = 0;
        for (j = 0; j <= longueur(fourn)-1; j++)
        {
            if (fourn[j]['raisonSociale'] != raiSoc && fourn[j] != "¤")     //Vérifie si le fournisseur qui va être ajouté au tableau fournisseur sois différent de celui qui à était supprimé
            {
                $('#infoFormeFournisseur'+k).html(fourn[j]['rechFormeJuridique']);      
                $('#infoRaisonSocialeFournisseur'+k).html(fourn[j]['raisonSociale']);   
                $('#infoCategorieFournisseur'+k).html(fourn[j]['categorie']);           
                k++;
            }
            else
            {
                for (x = 0; x <= longueur(lstProduits)-1; x++)
                {
                    if (lstProduits[x] != "¤")      //Vérifie si un produit existe à un index donné
                    {
                        if (lstProduits[x]['fournisseurProd'] != "undefined")       //Vérifie si un fournisseur existe pour le produit
                        {
                            for (y = 0; y <= longueur(lstProduits[x]['fournisseurProd'])-1; y++)
                            {
                                if (lstProduits[x]['fournisseurProd'][y] != "¤")        //Véridie si un fournisseur existe à un index donné
                                {
                                    if (lstProduits[x]['fournisseurProd'][y]['raisonSociale'] == fourn[j]['raisonSociale'])
                                    {
                                        //Ajout de fournisseur supprimé à la variable stockant les fournisseurs supprimés
                                        backupDel[longueur(backupDel)] = {indexProd: x, indexFourn: y, infoFourn: lstProduits[x]['fournisseurProd'][y]};
                                        lstProduits[x]['fournisseurProd'][y] = "¤";     //Rend inexistant le fournisseur à l'index donné
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (longueur(backupAdd) > 0)        //Ajout des fournisseur précédemment ajouté au tableau fournisseur de la fenêtre information produit
        {
            if ($('#infoRaisonSocialeFournisseur'+k).html() != "") 
            {
                k++;
            }
            for (x = 0; x <= longueur(backupAdd)-1; x++)
            {
                if (backupAdd[x] != "")     //Vérifie si un fournisseur existe à l'index donné
                {
                    if (formaTexte(backupAdd[x].infoFourn['typeRechCliNom']) == formaTexte(raiSoc))     //Vérifie si le fournisseur supprimé précédement se trouve dans la variable des fournisseurs ajoutés
                    {
                        backupAdd[x] = "";      //Rend inexistant un fournisseur à un index donné
                    }
                    else if (backupAdd[x] != "")
                    {
                        $('#infoFormeFournisseur'+k).html(backupAdd[x].infoFourn['FOJ']);
                        $('#infoRaisonSocialeFournisseur'+k).html(backupAdd[x].infoFourn['typeRechCliNom']);
                        $('#infoCategorieFournisseur'+k).html(backupAdd[x].infoFourn['Categorie']);
                        k++;
                    }
                }
            }
        }
        rechercher();
        ancienneLigne = $('#saveLigneFournProd').val();     //Récupération de la ligne de l'ancien fournisseur séléctionné
        $('#infoFormeFournisseur'+ancienneLigne).css("backgroundColor", "white");           //
        $('#infoRaisonSocialeFournisseur'+ancienneLigne).css("backgroundColor", "white");   //Change la couleur de fond du fournisseur supprimé en blanc
        $('#infoCategorieFournisseur'+ancienneLigne).css("backgroundColor", "white");       //
    }
}





function securisation()     //Permet d'évité l'injection sql
{
    $('#infoLibProdModif').bind("keydown", function()
    {
        setTimeout(function()
        {
            if ($('#infoLibProdModif').val().length > 5)
            {
                //Vérifie si ce que l'utilisateur a entré comporte les mot suivants : CREATE, DELETE, UPDATE, INSERT INTO, ALTER
                if ("CREATE".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("CREATE") > -1 || "ALTER ".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("ALTER ") > -1 || "DELETE".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("DELETE") > -1 || "UPDATE".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("UPDATE") > -1 || "INSERT".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("INSERT") > -1)
                {
                    $('#infoLibProdModif').val("");
                }
            }
        });
    });
    $('#infoDescriptionProd').bind("keydown", function()
    {
        setTimeout(function()
        {
            if ($('#infoDescriptionProd').val().length > 5)
            {
                //Vérifie si ce que l'utilisateur a entré comporte les mot suivants : CREATE, DELETE, UPDATE, INSERT INTO, ALTER
                if ("CREATE".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("CREATE") > -1 || "ALTER ".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("ALTER ") > -1 || "DELETE".includes($('#infoDescriptionProd').val().toUpperCase()) || $('#infoDescriptionProd').val().toUpperCase().indexOf("DELETE") > -1 || "UPDATE".includes($('#infoDescriptionProd').val().toUpperCase()) || $('#infoDescriptionProd').val().toUpperCase().indexOf("UPDATE") > -1 || "INSERT".includes($('#infoDescriptionProd').val().toUpperCase()) || $('#infoDescriptionProd').val().toUpperCase().indexOf("INSERT") > -1)
                {
                    $('#infoDescriptionProd').val("");
                }
            }
        });
    });
    $('#infoRefProdModif').bind("keydown", function()
    {
        setTimeout(function()
        {
            if ($('#infoRefProdModif').val().length > 5)
            {
                //Vérifie si ce que l'utilisateur a entré comporte les mot suivants : CREATE, DELETE, UPDATE, INSERT INTO, ALTER
                if ("CREATE".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("CREATE") > -1 || "ALTER ".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("ALTER ") > -1 || "DELETE".includes($('#infoRefProdModif').val().toUpperCase()) || $('#infoRefProdModif').val().toUpperCase().indexOf("DELETE") > -1 || "UPDATE".includes($('#infoRefProdModif').val().toUpperCase()) || $('#infoRefProdModif').val().toUpperCase().indexOf("UPDATE") > -1 || "INSERT".includes($('#infoRefProdModif').val().toUpperCase()) || $('#infoRefProdModif').val().toUpperCase().indexOf("INSERT") > -1)
                {
                    $('#infoRefProdModif').val("");
                }
            }
        });
    });
    $('#commentaire').bind("keydown", function()
    {
        setTimeout(function()
        {
            if ($('#commentaire').val().length > 5)
            {
                //Vérifie si ce que l'utilisateur a entré comporte les mot suivants : CREATE, DELETE, UPDATE, INSERT INTO, ALTER
                if ("CREATE".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("CREATE") > -1 || "ALTER ".includes($('#infoLibProdModif').val().toUpperCase()) || $('#infoLibProdModif').val().toUpperCase().indexOf("ALTER ") > -1 || "DELETE".includes($('#commentaire').val().toUpperCase()) || $('#commentaire').val().toUpperCase().indexOf("DELETE") > -1 || "UPDATE".includes($('#commentaire').val().toUpperCase()) || $('#commentaire').val().toUpperCase().indexOf("UPDATE") > -1 || "INSERT".includes($('#commentaire').val().toUpperCase()) || $('#commentaire').val().toUpperCase().indexOf("INSERT") > -1)
                {
                    $('#commentaire').val("");
                }
            }
        });
    });
}




//Made by VITRY Gaël
