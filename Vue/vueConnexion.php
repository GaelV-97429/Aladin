<!DOCTYPE html>
<html>
<head>
	<title>AladIN</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="Autre/Style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script type="text/javascript" src="Autre/fonctionCli.js"></script>
</head>
<body style="background-color: #777777; opacity: 0.8; user-select: none">
<?php
	if ($msg != "")
	{
?>
	<div class="container" style="position: absolute; top: 40px; width: 50%; height: 40px; left: 25%; right: 25%; padding-top: 10px; text-align: center; background-color: #DD9900; opacity: 0.8; color: #FFFFFF; border-radius: 25px;" id="msgErreur"><strong style="letter-spacing: 1px">Erreur ! </strong><?= $msg ?></div>
<?php
	}
?>
	<div class="container" id="containerConnexion">
		<div class="container" id="containerGauche">
			<a target="_blank" href="Autre/logoASIaladin.png">
				<img src="Autre/logoASIaladin.png" alt="Aladin" style="position: absolute; bottom: 1px; left: 0; right: 0; width: 97%; border-bottom-left-radius: 25px; margin: 0 1.5% 0 1.5%"></a>
		</div>
		<div class="container" id="containerDroit">
			<div class="row">
				<div class="col-sm-12"><h2 style="color: #990099; font-weight: bold; font-size: 22px; text-shadow: 4px 4px #999999">Bienvenue dans Alad'IN</h2></div>
			</div>
			<div class="row">
				<div class="col-sm-12"><p style="text-indent: 2em; color: #990099; font-size: 16px;">Assistant Logiciel d'Aide à la Décision</p></div>
			</div>
			<div class="row">
				<div class="col-sm-12" style="border: 1px solid black; margin: 2%; width: 85%; height: 25px; text-align: center; font-weight: bold; color: #000000">Version: V21.160829a</div>
			</div>
			<div class="row">
				<div class="col-sm-12" id="date" style="border: 1px solid black; margin: 2%; width: 85%; height: 25px; text-align: center; font-weight: bold; color: #000000"></div>
			</div>
			<div class="row">
				<div class="col-sm-12" id="heure" style="border: 1px solid black; margin: 2%; width: 85%; height: 25px; text-align: center; font-weight: bold; color: #000000"></div>
			</div>
			<div class="row">
				<div class="col-sm-12" style="text-align: center; margin-bottom: 10px; color: #0055CC; font-weight: bold; letter-spacing: 2px; font-size: 20px; text-shadow: 5px 5px #999999;">Connexion au logiciel</div>
			</div>
			<form method="POST" action="index.php?action=VerificationConnexion" id="formConnexion">
				<div class="row" style="margin-bottom: 5px;">
					<div class="col-sm-3"><strong style="color: #000000">Utilisateur</strong></div>
					<div class="col-sm-9"><input type="text" style="text-transform: uppercase; border-radius: 5px; box-sizing: border-box; border-color: #AAAAAA; padding-left: 5px;" name="login" required autofocus></div>
				</div>
				<div class="row" style="margin-bottom: 10px;">
					<div class="col-sm-3"><strong style="color: #000000">Mot de passe</strong></div>
					<div class="col-sm-9"><input type="password" style="text-transform: uppercase; border-radius: 5px; box-sizing: border-box; border-color: #AAAAAA; padding-left: 5px; width: 71%;" name="password" id="pwd" required>&nbsp;<button onclick="alert("Utilisateur inconnu !") class="btn btn-warning" style="width: 8%; height: 23px; padding: 0; margin-bottom: 2px" title="Changer le mot de passe"><span class="glyphicon glyphicon-pencil"></span></button></div>
				</div>
			</form>
			<div class="row">
				<div class="col-sm-12"><p style="text-align: center; color: #000000"><strong>Suivi et rappel des actions commerciales.<br>Opérations de gestion commerciale.<br>Fonction de recherche multicritères.<br>Aide à la production de documents.<br>Indicateurs et statistiques.<br>Aide à la décision.</strong></p></div>
			</div>
			<div class="row" style="position: absolute; bottom: 10px; width: 100%;">
				<div class="col-sm-2"></div>
				<div class="col-sm-4" style="text-align: center;"><button onclick="document.getElementById('formConnexion').submit();" class="btn btn-success" style="color: black; font-weight: bold; border: 1px solid #666666;"><span class="glyphicon glyphicon-ok" style="color: #000000;"></span>&nbsp; Validation</button></div>
				<div class="col-sm-4" style="text-align: center;"><button class="btn btn-danger" style="font-weight: bold; color: black; border: 1px solid #666666;"><span class="glyphicon glyphicon-remove" style="color: #000000;" onclick="window.close();"></span>&nbsp; Abandon</button></div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
  		var currentdate = new Date(); 
  		date = new Date;
        annee = date.getFullYear();
        moi = date.getMonth();
        mois = new Array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
        j = date.getDate();
        jour = date.getDay();
        jours = new Array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
        date = jours[jour]+' '+j+' '+mois[moi]+' '+annee+'';
        var time = currentdate.getHours()+" h "+currentdate.getMinutes()+" min "+currentdate.getSeconds()+" sec";
		document.getElementById('date').innerHTML = date;
		document.getElementById('heure').innerHTML = time;
  		function ActualiseHeure()
  		{
            var time = currentdate.getHours()+" h "+currentdate.getMinutes()+" min "+currentdate.getSeconds()+" sec";
  			document.getElementById('heure').innerHTML = time;
  		}
  		setInterval(function(){ 
  					var currentdate = new Date(); 
  					document.getElementById('heure').innerHTML = (currentdate.getHours()+" h "+currentdate.getMinutes()+" min "+currentdate.getSeconds()+" sec"); }, 1000);
  		if (document.getElementById('msgErreur'))
  		{
  			setTimeout(function(){ document.getElementById('msgErreur').style.visibility = "hidden"; }, 3000);
  		}
  	</script>
</body>
</html>