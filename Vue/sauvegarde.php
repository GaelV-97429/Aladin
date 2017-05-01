<!DOCTYPE html>
<html>
<head>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  	<script type="text/javascript">
  		function getQueryVariable(variable)
		{
	       var query = window.location.search.substring(1);
	       var vars = query.split("&");
	       for (var i=0;i<vars.length;i++) {
	               var pair = vars[i].split("=");
	               if(pair[0] == variable){return pair[1];}
	       }
	       return(false);
		}
  		function save()
  		{
  			if (liste != "undefined" && getQueryVariable("x") == "") 
  			{
	  			document.getElementById('liste').value = liste;
	  			document.getElementById('form').submit();
  			}
  			else
  			{
  				window.close();
  			}
  		}
  	</script>
</head>
<body onload="save()">
	<form method="POST" action="../index.php?action=SaveDataBase" id="form">
		<input type="text" id="liste" name="liste" hidden>
	</form>
</body>
</html>