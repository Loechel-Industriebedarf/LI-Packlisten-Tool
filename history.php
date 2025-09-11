<?php
	include 'header.php';
?>

<?php	
	if(isset($_COOKIE["pl_history"])){
		$lists = explode("_", $_COOKIE["pl_history"]);
	}
	
	$where = implode("' OR PACKLISTENNR = '", $lists);
	$sql = "SELECT RFIRMA1, LFIRMA1, PACKLISTENNR, dbo.auftragskopf.BELEGNR, BESTELLUNG from dbo.AUFTRAGSPOS " . 
	"LEFT JOIN dbo.AUFTRAGSKOPF on dbo.AUFTRAGSPOS.BELEGNR = dbo.AUFTRAGSKOPF.BELEGNR " .
	"WHERE PACKLISTENNR = '" . $where;
	$sql = substr($sql, 0, -20);	
	$sql = $sql . " GROUP BY RFIRMA1, LFIRMA1, PACKLISTENNR, dbo.auftragskopf.BELEGNR, BESTELLUNG";
	
	//SQL Config auslesesen und Verbindung zum Server herstellen
	include 'sql.php';
	
	
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		if(isset($row['LFIRMA1'])){ $firm = $row['LFIRMA1']; } else { $firm = $row['RFIRMA1']; }
		$plInfo = $firm . " | " . $row['BELEGNR'] . " | " . $row['BESTELLUNG'];
		$listInfo[$row['PACKLISTENNR']] = $plInfo;
	}
		
	// Verbindung schließen
	sqlsrv_close($conn);
	
	if(isset($lists)){
		foreach ($lists as $value) {
			if($value !== ""){
				echo '<div class="text-center">
					<a href="packliste.php?nr=' . $value . '"><h1>' . $listInfo[$value] . '<br>' . 
					' ( Packliste: ' . $value . ' ) ' .
				'</h1></a></div>';
				echo "<hr>";
			}
			
		}
		
	}
	else{
		echo "Es sind keine Packlisten vorhanden...<br>";
	}
?>