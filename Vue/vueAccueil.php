<?php
	ob_start();
?>

<?php
	$contenu = ob_get_clean();
	require 'template.php';
?>