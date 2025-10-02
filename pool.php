<?php
	include 'header.php';
?>

<?php	
	if(isset($_GET["empty"])){
		// setcookie("pl_archive_backup", $_COOKIE["pl_archive"], time()+604800); //7 Tage Speicherzeit
		setcookie("pl_archive", "", time() - 3600);
		
		echo '<div class="alert alert-info" role="alert">Der Pool wurde geleert...</div><br>';
	}
	else{
		// if(isset($_GET["restore"])){
			// setcookie("pl_archive", $_COOKIE["pl_archive_backup"], time()+32400); //9h Speicherzeit
			// $lists = explode("_", $_COOKIE["pl_archive_backup"]);
		// }
		if(isset($_COOKIE["pl_archive"])){
			$lists = explode("_", $_COOKIE["pl_archive"]);
		}
		
		if(isset($lists)){
			$i = count($lists);
			
			foreach ($lists as $value) {
				echo '<div class="fullsize">
						<a href="packliste.php?nr=' . $value . '"><h5>*' . $value . '*</h5><br>
						' . $value . ' (' . $i . ')' . 
					'</a></div>';
				echo "<hr>";
				
				$i--;
			}
			
		}
		else{
			echo '<div class="alert alert-warning" role="alert">Es sind keine abgeschlossenen Packlisten vorhanden...</div>';
		}
	}
?>

<div class="row">
	<div class="col-10 offset-1">
		Es können maximal 100 Packlisten im Pool gespeichert werden, danach wird die Älteste entfernt. Nach Feierabend werden die Einträge automatisch geleert. Über die Historie werden immer die letzten 100 Listen angezeigt!<br><br>
	</div>
</div>

<a href="pool.php?empty=true"><button type="button" class="btn btn-lg btn-danger btn-full-size">Pool leeren</button></a><br>

<!--
<a href="list.php?restore=true"><button type="button" class="btn btn-lg btn-secondary btn-full-size">Letzte Leerung rückgängig machen</button></a><br>
-->