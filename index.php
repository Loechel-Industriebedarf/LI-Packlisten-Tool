<?php
	include 'header.php';
?>

 <main>
      <section class="py-2 text-center container">
        <div class="row py-lg-1">
          <div class="col-lg-6 col-md-8 mx-auto">
            <p class="lead text-body-secondary">
			  ToDo: Maybe direkte DHL Anbindung? + Lieferschein Job<br>
			  ToDo: Maybe presets bzw. Einstellungsmöglichkeiten<br>
			  ToDo: Möglichkeit EANs in der Packliste zu scannen<br>
			  ToDo: Nach Artikel/Lagerplatz kommissionieren?
            </p>
			  <form class="form-inline" action="index.php" method="GET">
				<div class="input-group">
				  <input class="form-control mr-sm-2" type="search" placeholder="Suche..." <?php if(isset($_GET["s"])){ echo 'value="'.$_GET["s"].'"'; } ?> "aria-label="Search" name="s">
				  <div class="input-group-append">
				    <button type="submit" class="btn btn-outline-secondary">🔍</button>
				  </div>
				</div>
			 </form>
          </div>
        </div>
      </section>
	  

	  
      <div class="album py-5 bg-body-tertiary">
<?php
	$searchString = "";
		if(isset($_GET["s"])){
			$s = $_GET["s"];
			echo '<div class="alert alert-info" role="alert">Suche nach "' . $s . '"...</div>';
			if(is_numeric($s)){
				$searchString = "WHERE " . "PACKLISTENNR = '" . $s . "' OR dbo.Auftragskopf.BELEGNR = '" . $s . "' OR BESTELLUNG LIKE '%". $s . "%'";
			}
			else{
				$searchString = "WHERE " . 
				"LFIRMA1 LIKE '%" . $s . "%' OR LFIRMA2 LIKE '%" . $s . "%' OR RFIRMA1 LIKE '%" . $s . "%' OR RFIRMA2 LIKE '%" . $s . "%' ";
			}
		}
		else{
			$searchString = "WHERE [PACKLISTENNR] is not null and dbo.auftragspos.STATUS = 2 and (BO3_DELIVERYMEMO is null or AENDERUNGSDATUM < DATEADD(hour, -1, GETDATE())) ";
		}
?>	  
        <div class="container-fluid">
          <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-2">
		  
<?php
		$count = 120;
	
		// SQL-Abfrage
		$sql = "SELECT TOP (" . $count . ") [PACKLISTENNR], COUNT([ARTIKELNR]) as POSITIONEN, min(dbo.auftragskopf.belegart) as BELEGART, min(dbo.auftragskopf.VANUMMER) as VERSANDNR, min(dbo.auftragskopf.BELEGNR) as AUFTRAGSNR, min(dbo.auftragskopf.ERFASSUNGSDATUM) as ERFDATE, min(dbo.auftragskopf.AENDERUNGSDATUM) as AENDATE, VATEXT, AUFTRAGSART, sum(CASE WHEN NetWeightPerSalesUnit is null THEN 3*MENGE_BESTELLT ELSE NetWeightPerSalesUnit*MENGE_BESTELLT END) as WEIGHT, COUNT(*) OVER() AS GESAMTANZAHL, CASE WHEN CHARINDEX('Pri', VATEXT) > 0 THEN 1 ELSE 0 END AS Prio, dbo.AUFTRAGSKOPF.RFIRMA1, dbo.AUFTRAGSKOPF.LFIRMA1, dbo.AUFTRAGSKOPF.BO3_DELIVERYMEMO   
		  FROM [LOE01].[dbo].[AUFTRAGSPOS] 
		  LEFT JOIN dbo.AUFTRAGSKOPF ON dbo.AUFTRAGSKOPF.BELEGNR = dbo.AUFTRAGSPOS.BELEGNR
		  LEFT JOIN dbo.VERSANDART ON dbo.VERSANDART.VANUMMER = dbo.AUFTRAGSKOPF.VANUMMER
		  LEFT JOIN dbo.AUFTRAGSART ON dbo.AUFTRAGSART.NUMMER = dbo.AUFTRAGSKOPF.BELEGART
		   "
		  . $searchString .
		  " GROUP BY [PACKLISTENNR], [VATEXT], [AUFTRAGSART], [RFIRMA1], [LFIRMA1], [BO3_DELIVERYMEMO]
		  ORDER BY [Prio] DESC, [AENDATE] DESC";
		
		// echo "<br>";
		// echo $sql;
		// echo "<br>";
		
		  
		//SQL Config auslesesen und Verbindung zum Server herstellen
		include 'sql.php';

		// Daten auslesen
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$totalNum = $row['GESAMTANZAHL'];
			?>
			<div class="col">
              <div class="card shadow-sm">
				<div class="card-header <?php if(str_contains(strtolower($row['VATEXT']), "pri")){ echo "prime"; } ?> ">
					<?php echo $row['PACKLISTENNR']; ?> | <?php echo $row['AUFTRAGSNR']; ?><br>
					<?php if(isset($row['LFIRMA1'])){ echo $row['LFIRMA1']; } else { echo $row['RFIRMA1']; } ?>
				</div>                
                <div class="card-body">
					<div class="row">
						<div class="col-6">
						  <p>Belegart:</p>
						  <p>Versand:</p>
						</div>
						<div class="col-6">
						  <p><?php echo $row['AUFTRAGSART']; ?></p>
						  <p><?php echo $row['VATEXT']; ?></p>
						</div>
					  </div>
                  <div
                    class="d-flex justify-content-between align-items-center"
                  >
                    <div class="btn-group">
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary"
                      >
                        <?php echo $row['POSITIONEN']; ?><br>
						Pos
                      </button>
					  <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary"
                      >
						<?php echo number_format((float)$row['WEIGHT'], 3, ',', '.'); ?><br>
						kg
                      </button>
                    </div>
                    <small class="text-body-secondary text-center"><?php echo $row['ERFDATE']->format('d.m.Y H:i:s') ?><br>
                    <?php echo $row['AENDATE']->format('d.m.Y H:i:s') ?></small>
                  </div>
				  
				   <a href="packliste.php?nr=<?php echo $row['PACKLISTENNR']; ?>" class="stretched-link"></a>
                </div>
              </div>
            </div>
			<?php
		}

		// Verbindung schließen
		sqlsrv_close($conn);
?>
          </div>
        </div>
      </div>
    </main>
	
<?php
	if(isset($_GET["s"])){
		echo '<br><center>Es wurden insgesamt ' . $totalNum . ' Ergebnisse gefunden!</center><br>';
	}
	else if(isset($totalNum)){
		echo '<br><center>Es sind insgesamt ' . $totalNum . ' Packlisten zu bearbeiten...</center><br>';
	}
?>