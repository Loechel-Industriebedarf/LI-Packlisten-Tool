<?php
	include 'header.php';
?>

<?php	
	if(isset($_GET["nr"])){
		$plnr = $_GET["nr"];
		
		//Barcode generieren und Nummer in Folgezeile
		echo '<div class="fullsize"><h5>*' . $_GET["nr"] . '*</h5><br>
		' . $_GET["nr"] . '</div>';
		
		//Cookie auslesen und neue Packlistennummer anhängen
		if(isset($_COOKIE["pl_archive"])){
			$pl = $_COOKIE["pl_archive"];
			
			
			//Keine doppelten im Archiv
			if(!str_contains($pl, $plnr)){
				$pl = $plnr . "_" . $pl;
			}
			
			//Bei ca. 100 Packlisten (1000 Zeichen) Warnung anzeigen und älteste Liste aus dem String entfernen
			if(strlen($pl) >= 1000){
				echo '<div class="alert alert-warning" role="alert">Warnung! Es sind über 100 Packlisten im Pool. Die Älteste wird aus dem Speicher entfernt.</div>';
				$pos = strpos($pl, '_');
				$pl = substr($pl, $pos + 1);
			}
			
		}
		else{
			$pl = $plnr;
		}
		
		setcookie("pl_archive", $pl, time()+36000); //10h Speicherzeit
	}
	else{
		echo '<div class="alert alert-error" role="alert">Keine Packlistennummer angegeben!</div>';
	}
	
	echo '<br>';
?>