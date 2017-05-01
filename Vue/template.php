<?php
	@session_start();
	if (isset($_SESSION['infoUtilisateur']))
	{
?>
<!DOCTYPE html>    <!-- Correspond à la page d'accueil -->
<html>
<head>
	<title>AladIN</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Autre/Style.css"> <!-- fait appel à la page style.css pour la mise en page des différents éléments --> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script> 
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
	<script src="http://code.highcharts.com/modules/exporting.js"></script>
	<script src="http://code.highcharts.com/modules/offline-exporting.js"></script>
	<script src="http://thecodeplayer.com/uploads/js/prefixfree-1.0.7.js" type="text/javascript"></script>
	<script src="http://thecodeplayer.com/uploads/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		function aladin()
		{
			x = "<div style='text-align: center; width: 100%; margin: 1em 0 1em 0; background-color: #999999'><span class='glyphicon glyphicon-leaf'></span> Les Statistiques</div><div><button id='btnNavbar' onclick='OnDemand()'><span class='glyphicon glyphicon-stats'></span> &Agrave; la demande</button></div><div style='text-align: center; width: 100%; margin: 3em 0 1em 0; background-color: #999999'><span class='glyphicon glyphicon-tree-deciduous'></span> A propos</div><div><button id='btnNavbar' onclick='Aladin()'><span class='glyphicon glyphicon-info-sign'></span> A propos de Alad&#146;in</button></div><hr><div><button id='btnNavbar' onclick='Process()'><span class='glyphicon glyphicon-filter'></span> Proc&eacute;dures</button></div>";
			document.getElementById('contenuNavbar').innerHTML = x;
		}
		function OnDemand()
		{
			window.open('index.php?action=Statistiques','_blank');
		}
		function Aladin()
		{
			window.open('index.php?action=Apropos','_blank');
		}
		function Process()
		{
			alert("Le répertoire contenant les procédures n'a pas été paramétré !");
		}
		function cliProd()
		{
			x = "<div style='text-align: center; width: 100%; margin: 1em 0 1em 0; background-color: #999999'><span class='glyphicon glyphicon-th-list'></span> Les clients</div><div><button id='btnNavbar' onclick='Clients()'><span class='glyphicon glyphicon-user'></span> Les clients</button></div><div style='text-align: center; width: 100%; margin: 3em 0 1em 0; background-color: #999999'><span class='glyphicon glyphicon-list-alt'></span> Les produits</div><div><button id='btnNavbar' onclick='Produits()'><span class='glyphicon glyphicon-book'></span> Les produits</button></div>";
			document.getElementById('contenuNavbar').innerHTML = x;
		}
		function Produits()
		{
			window.open('index.php?action=Produits','_blank');
		}
		function Clients()
		{
			window.open('index.php?action=Clients','_blank');
		}
		function DevFact()
		{
			x = "<div style='text-align: center; width: 100%; margin: 1em 0 1em 0; background-color: #999999'><span class='glyphicon glyphicon-file'></span> Les devis / factures</div><div><button id='btnNavbar' onclick='Devis()'><span class='glyphicon glyphicon-grain'></span> Les devis</button></div><hr><button id='btnNavbar' onclick='Facture()'><span class='glyphicon glyphicon-euro'></span> Facture</button></div>";
			document.getElementById('contenuNavbar').innerHTML = x;
		}
		function Devis()
		{
			window.open('index.php?action=Devis','_blank');
		}
		function Facture()
		{
			window.open('index.php?action=Facture','_blank');
		}
		function Parametres()
		{
			x = "<div style='text-align: center; width: 100%; margin: 1em 0 1em 0; background-color: #999999 '><span class='glyphicon glyphicon-question-sign'></span> Les param&egrave;tres</div><div><button id='btnNavbar' onclick='Systeme()'><span class='glyphicon glyphicon-certificate'></span> Syst&egrave;me</button></div><hr><div><button id='btnNavbar' onclick='Specifiques()'><span class='glyphicon glyphicon-th-list'></span> Sp&eacute;cifiques</button></div><div style='text-align: center; width: 100%; margin: 1em 0 1em 0; background-color: #999999'>Les utilisateurs</div><div><button id='btnNavbar' onclick='Profil()'><span class='glyphicon glyphicon-check'></span> Profil</button></div><hr><div><button id='btnNavbar' onclick='ChangeUser()'><span class='glyphicon glyphicon-trash'></span> Changement d&#146;utilisateur</button></div><hr><div><button type='button' class='btn btn-primary disabled' title='Vous ne pouvez pas modifier votre profil !'><span class='glyphicon glyphicon-edit'></span> Modifier mon profil</button></div><div style='text-align: center; width: 100%; margin: 1em 0 1em 0; background-color: #999999'><span class='glyphicon glyphicon-eye-open'></span> Administration</div><div><button id='btnNavbar' onclick='SaveDataBase()'><span class='glyphicon glyphicon-cloud'></span> Sauvegarde de la base</button></div>";
			document.getElementById('contenuNavbar').innerHTML = x;
		}
		function Systeme()
		{
			window.open('index.php?action=Systeme','_blank');
		}
		function Specifiques()
		{
			x = "<div class='row' style='padding-left: 8%; height: 150px'><div class='col-sm-1' id='accordian'><ul><li><h3><span class='icon-dashboard'></span>Les param&egrave;tres sp&eacute;cifiques</h3><h3>Les adresses</h3><ul><li><a href='#Villes'>Villes</a></li><li><a href='DepFrance'>D&eacute;partements de France</a></li><li><a href='#'>Pays &eacute;trangers</a></li></ul></li><li class='active'><h3><span class='icon-tasks'></span>Client / Fournisseur</h3><ul><li><a href='#'>Les cat&eacute;gories</a></li><li><a href='#'>Les formes juridiques</a></li></ul></li><li><h3><span class='icon-calendar'></span>Le produit</h3><ul><li><a href='#'>Les familles</a></li><li><a href='#'>Les taux de TVA</a></li><li><a href='#'>Les exceptions de TVA</a></li></ul></li><li><h3><span class='icon-heart'></span>Devis / Facture</h3><ul><li><a href='#'>Les modes de transmission</a></li><li><a href='#'>Les modes de r&egrave;glement</a></li><li><a href='#'>Les motifs d&#146;annulation</a></li><li><a href='#'>Les motifs de refus</a></li></ul></li><li><h3>Le param&eacute;trage</h3><ul><li><a href='#'>Les num&eacute;ros</a></li><li><a href='#'>La mise en page</a></li></ul></li></ul></div>";
			document.getElementById('contenuNavbar').innerHTML = x; test();
		}
		function Profil()
		{
			window.open('index.php?action=Profil','_blank');
		}
		function ChangeUser()
		{
			window.open('index.php?action=ChangeUser','_blank');
		}
		function SaveDataBase()
		{
			window.open('index.php?action=SaveDataBase','_blank');
		}

		function test()
		{
			$(document).ready(function(){
					$("#accordian h3").click(function(){
					//slide up all the link lists
					$("#accordian ul ul").slideUp();
					//slide down the link list below the h3 clicked - only if its closed
					if(!$(this).next().is(":visible"))
					{
						$(this).next().slideDown();
					}
					})
				})
		

				var acc = document.getElementsByClassName("accordion");
				var i;

				for (i = 0; i < acc.length; i++) {
  					acc[i].onclick = function() {
    					this.classList.toggle("active");
    					var panel = this.nextElementSibling;
    					if (panel.style.maxHeight){
  	  						panel.style.maxHeight = null;
    					} else {
  	  						panel.style.maxHeight = panel.scrollHeight + 'px';
    					} 
  					}
				}
		}
				$(function () {
    Highcharts.chart('Graph', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Répartition devis / factures'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: false
      			},
      			showInLegend: true
      		}
      	},
        series: [{
            name: 'Pourcentage',
            colorByPoint: true,
            data: [{
                name: 'Devis',
                y: 50
            }, {
                name: 'Facture',
                y: 50,
                sliced: true,
                selected: true
            }]
            }]
    });
});
	</script>
	<style>
	/*custom font for text*/
/*CSS file for fontawesome - an iconfont we will be using. This CSS file imported contains the font-face declaration. More info: http://fortawesome.github.io/Font-Awesome/ */
@import url(http://thecodeplayer.com/uploads/fonts/fontawesome/css/font-awesome.min.css);

/*Basic reset*/
* {margin: 0; padding: 0;}

body {
	font-family: Nunito, arial, verdana;
}
#accordian {
	background: #004050;
	width: 190px;
	margin: 25px auto 0 auto;
	color: white;
	/*Some cool shadow and glow effect*/
	box-shadow: 
		0 5px 15px 1px rgba(0, 0, 0, 0.6), 
		0 0 200px 1px rgba(255, 255, 255, 0.5);
}
/*heading styles*/
#accordian h3 {
	font-size: 12px;
	line-height: 25px;
	padding: 0 10px;
	cursor: pointer;
	/*fallback for browsers not supporting gradients*/
	background: #003040; 
	background: linear-gradient(#003040, #002535);
}
/*heading hover effect*/
#accordian h3:hover {
	text-shadow: 0 0 1px rgba(255, 255, 255, 0.7);
}
/*iconfont styles*/
#accordian h3 span {
	font-size: 16px;
	margin-right: 10px;
}
/*list items*/
#accordian li {
	list-style-type: none;
}
/*links*/
#accordian ul ul li a {
	color: white;
	text-decoration: none;
	font-size: 11px;
	line-height: 27px;
	display: block;
	padding: 0 15px;
	/*transition for smooth hover animation*/
	transition: all 0.15s;
}
/*hover effect on links*/
#accordian ul ul li a:hover {
	background: #003545;
	border-left: 5px solid lightgreen;
}
/*Lets hide the non active LIs by default*/
#accordian ul ul {
	display: none;
}
#accordian li.active ul {
	display: block;
}
button.accordion 
{
    background-color: #eee;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

button.accordion.active, button.accordion:hover 
{
    background-color: #ddd;
}

button.accordion:after 
{
    content: '\002B';
    color: #777;
    font-weight: bold;
    float: right;
    margin-left: 5px;
}

button.accordion.active:after 
{
    content: "\2212";
}

div.panel 
{
    padding: 0 18px;
    background-color: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
}
	</style>
</head>
<body onload="test()">
	<div id="container" class="container">
		<div id="navbar" class="container-navbar"  style="background-color: #FFFAF0">
				<button id="btnNavbar" style="background-color: #FFFAF0" onclick="aladin()">ALAD'IN</button>
				<button id="btnNavbar" style="background-color: #FFFAF0" onclick="cliProd()"><span class="glyphicon glyphicon-user"></span> CLIENT / PRODUIT</button>
				<button id="btnNavbar" style="background-color: #FFFAF0" onclick="DevFact()"><span class="glyphicon glyphicon-list-alt"></span> DEVIS / FACTURE</button>
				<button id="btnNavbar" style="background-color: #FFFAF0" onclick=""><span class="glyphicon glyphicon-cog"></span> MODULES</button>
				<button id="btnNavbar" style="background-color: #FFFAF0" onclick="Parametres()"><span class="glyphicon glyphicon-wrench"></span> PARAMÈTRES</button>
			<div id="contenuNavbar"></div>
		</div>
		<div class="row" style="position: absolute; bottom: 0; right: 0; left: 0; margin-bottom: 2%">
			<div class="col-sm-12">
				<a target="_blank" href="AladIn.PNG">
					<img src="AladIn.PNG" style=" padding-left: 1%; width: 125px; height: 80px; border-radius: 20%"></a>					
			</div>
		</div>
		<div id="header" class="container-header" style="background-color: #FFFAF0">
			<div id="containerProd4">
			</div>

		<button class="accordion" style="width: 30%; float: left; background-color: #FFFAF0;">Devis</button>
				<div class="panel" style="position: absolute; top: 57px; left: 15%; width: 25%;">
  					<p>Attente de transmission</p>
  					<p>Attente r&eacute;ponse client</p>
  					<p>Attente facturation</p>
				</div>

		<button class="accordion" style="width: 30%; float: right; background-color: #FFFAF0">Facture</button>
				<div class="panel" style="position: absolute; top: 57px; left: 74%; width: 25%">
  					<p>Attente de transmission</p>
  					<p>Impay&eacute;e</p>
				</div>
		</div>

			<div id="contenu" style="background-color: #FFFAF0">	
				<div id="Graph" style="min-width: 100px; height: 100%; max-width: 600px; margin: 0 auto;"></div>
			</div>

	<div id="containerProd5" style="background-color: #FFFAF0">
		<div class="row">
			<div class="col-sm-10" style="color: #990099; font-size: 20px; text-shadow: 2px 5px #BBBBBB">
				<div class="col-sm-6" style="color: #990099; font-weight: bold; font-size: 20px; padding-left: 8%;"><strong>Les impay&eacute;s</strong></div>
				<div class="col-sm-4"><button onclick="impayes()" style="margin: 5px; padding: 0; width: 35px; margin-left: 5px;" class="btn btn-primary btn-xs" title='En savoir plus'><span class="glyphicon glyphicon-search"></span></button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-7" style="padding-left: 0%;">
							<div class="col-sm-5" style="height: 25px; border: 1px solid black; border-right: none; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Client</strong></div>
							<div class="col-sm-5" style="height: 25px; border: 1px solid black; background-color: #222222; opacity: 0.9; color: white; letter-spacing: 0.1em"><strong>Montant</strong></div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	for ($i = 0; $i <= 5; $i++) 
	{ 
?>
		<div class="row">
			<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-7" style="padding-left: 0%;">
							<input type="number" id="indexCli<?= $i ?>" value="0" hidden>
							<div class="col-sm-5" style="height: 25px; border: 1px solid black; border-right: none; border-top: none; border-left: double; border-bottom: double;"></div>
							<div class="col-sm-5" style="height: 25px; border: 1px solid black; border-right: double; border-top: none; border-left: double; border-bottom: double;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	}
?>
	</div>	
	<div style="position: absolute; top: 0; left: 0; bottom: 0; right: 0; width: 100%; height: 100%; background-color: #000000; opacity: 0.8; z-index: 1; visibility: hidden;"></div>

	<div class="container" style="position: absolute; top: 10%; left: 10%; width: 80%; height: 80%; border-radius: 25px; z-index: 2; visibility: hidden; padding: 0; background-color: white; ">
		<div class="row" style=" padding: 12px 0 0 0; margin: 0; width: 100%; height: 50px; text-align: center; font-weight: bold; border-bottom: 1px solid black">Répartition devis/facture</div>
		<div class="row">
			<div class=" col-sm-10">
				<h5 style="font-weight: bold; text-indent: 5px;">OPTIONS</h5>
			</div>
		</div>
		<div class="row" style="height: 25px;">
			<div class="col-sm-10" style="height: 25px;">
				<input type="checkbox" style="width: 15px; margin-left: 30px; float: left"> 
				<p style="height: 25px; width: 10%; margin: 0; padding: 0; float: left">&nbsp;Relief</p>
				<input type="number" style="width: 8%; float: left; text-align: center; margin-right: 40px;" placeholder="0">
				<p style="height: 25px; width: 10%; margin: 0; padding: 0; float: left">&nbsp;Opacité</p>
				<input type="range" style="width: 40%; float: left; margin-right: 50px;">
				<p style="height: 25px; width: 10%; margin: 0; padding: 0; float: left"><span id="opacitePourcent">0</span>%</p>
			</div>
		</div>
		<div class="row" style="margin-top: 1%;">
			<div class="col-sm-10">
				<h5 style="font-weight: bold; text-indent: 20px;">Légende</h5>
			</div>
		</div>
		<div class="row" style="margin-top: 1%;">
			<div class="col-sm-10">
				<input type="checkbox" style="width: 2%; margin-left: 3%; float: left"> 
				<p style="height: 25px; width: 18%; margin: 0; padding: 0; float: left">&nbsp;Affiche la légende</p>
				<select style="width: 10%; float: left">
					<option>À droite</option>
					<option>À gauche</option>
					<option>En bas</option>
					<option>En haut</option>
				</select>
				<input type="checkbox" style="width: 2%; margin-left: 5%; float: left"> 
				<p style="height: 25px; width: 18%; margin: 0; padding: 0; float: left">&nbsp;Affiche le titre</p>
				<select style="width: 10%; float: left">
					<option>À droite</option>
					<option>À gauche</option>
					<option>En bas</option>
					<option>En haut</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<div style="width: 33%; height: 80px; border: 1px solid black; margin-left: 2%; padding-top: 10px; float: left;">
					<input type="checkbox" style="width: 10%; margin-left: 1%; float: left"> 
					<p style="height: 25px; width: 80%; margin: 0; padding: 0; float: left">&nbsp;Affiche les valeurs</p>
					<input type="checkbox" style="width: 10%; margin-left: 9%; float: left"> 
					<p style="height: 25px; width: 60%; margin: 0; padding: 0; float: left">&nbsp;Affiche les valeurs nulles</p>
				</div>
				<input type="checkbox" style="width: 2%; margin-left: 3%; float: left"> 
				<p style="height: 25px; width: 20%; margin: 0; padding: 0; float: left">&nbsp;Affiche le pourcentage</p>
				<input type="checkbox" style="width: 2%; margin-left: 2%; float: left"> 
				<p style="height: 25px; width: 18%; margin: 0; padding: 0; float: left">&nbsp;Affiche les étiquettes</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<h5 style="font-weight: bold; text-indent: 20px;">Police</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<h5 style="font-weight: bold; text-indent: 50px;">Graphe</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<p style="height: 25px; width: 10%; margin: 0 0  0 3%; padding: 0; float: left">&nbsp;Police</p>
				<select style="width: 22%; float: left">
					<option></option>
					<option></option>
					<option></option>
					<option></option>
				</select>
				<p style="height: 25px; width: 8%; margin: 0 0 0 3%; padding: 0; float: left">Taille</p>
				<input type="number" style="width: 10%; float: left; margin-right: 6%; text-align: center;" placeholder="0">
				<input type="checkbox" style="width: 2%; float: left;">
				<p style="height: 25px; width: 5%; margin: 0; padding: 0; float: left">Gras</p>
				<input type="checkbox" style="width: 3%; float: left;">
				<p style="height: 25px; width: 8%; margin: 0 ; padding: 0; float: left">Italique</p>
				<p style="height: 25px; width: 8%; margin: 0 0.5% 0 0; padding: 0; float: left">Couleur</p>
				<span style="width: 25px; height: 25px; float: left; border: 1px solid black"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<h5 style="font-weight: bold; text-indent: 50px;">Légende</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<p style="height: 25px; width: 10%; margin: 0 0  0 3%; padding: 0; float: left">&nbsp;Police</p>
				<select style="width: 22%; float: left">
					<option></option>
					<option></option>
					<option></option>
					<option></option>
				</select>
				<p style="height: 25px; width: 8%; margin: 0 0 0 3%; padding: 0; float: left">Taille</p>
				<input type="number" style="width: 10%; float: left; margin-right: 6%; text-align: center;" placeholder="0">
				<input type="checkbox" style="width: 2%; float: left;">
				<p style="height: 25px; width: 5%; margin: 0; padding: 0; float: left">Gras</p>
				<input type="checkbox" style="width: 3%; float: left;">
				<p style="height: 25px; width: 8%; margin: 0 ; padding: 0; float: left">Italique</p>
				<p style="height: 25px; width: 8%; margin: 0 0.5% 0 0; padding: 0; float: left">Couleur</p>
				<span style="width: 25px; height: 25px; float: left; border: 1px solid black"></span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<h5 style="font-weight: bold; text-indent: 50px;">Titre</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10">
				<p style="height: 25px; width: 10%; margin: 0 0  0 3%; padding: 0; float: left">&nbsp;Police</p>
				<select style="width: 22%; float: left">
					<option></option>
					<option></option>
					<option></option>
					<option></option>
				</select>
				<p style="height: 25px; width: 8%; margin: 0 0 0 3%; padding: 0; float: left">Taille</p>
				<input type="number" style="width: 10%; float: left; margin-right: 6%; text-align: center;" placeholder="0">
				<input type="checkbox" style="width: 2%; float: left;">
				<p style="height: 25px; width: 5%; margin: 0; padding: 0; float: left">Gras</p>
				<input type="checkbox" style="width: 3%; float: left;">
				<p style="height: 25px; width: 8%; margin: 0 ; padding: 0; float: left">Italique</p>
				<p style="height: 25px; width: 8%; margin: 0 0.5% 0 0; padding: 0; float: left">Couleur</p>
				<span style="width: 25px; height: 25px; float: left; border: 1px solid black"></span>
			</div>
		</div>
	</div>
	</div>
</body>
</html>
<?php
	}
	else
	{
		header('Location: index.php?action=Connexion');
	}
?>