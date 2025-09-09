<?php
	include 'header.php';
?>

<?php	
	if(isset($_GET["nr"])){
		$plnr = $_GET["nr"];
		
		echo '<div class="fullsize"><h5>*' . $_GET["nr"] . '*</h5><br>
		' . $_GET["nr"] . '</div>';
	}
	else{
		echo "Keine Packlistennummer angegeben!<br>";
	}
	
	echo '<br>';
	


?>