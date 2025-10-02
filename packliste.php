<?php
	include 'header.php';
?>


<?php	
	if(isset($_GET["nr"])){
		$plnr = $_GET["nr"];
		
		//Write pl into history
		if(isset($_COOKIE["pl_history"])){
			$history = $_COOKIE["pl_history"];
		}
		else{
			$history = "";
		}
		
		$history = $plnr . "_" . $history;
		
		//Ca. 100 Packlisten in der Historie, doppelte Einträge sind erwünscht.
		if(strlen($history) >= 1000){
			$pos = strpos($history, '_');
			$history = substr($history, $pos + 1);
		}
			
		setcookie("pl_history", $history); //Unedliche Speicherzeit
		
		
		// SQL-Abfrage
		$sql = "SELECT TOP (1000) PACKLISTENNR, AUFTRAGSKOPF.BELEGNR, AUFTRAGSKOPF.BELEGART, dbo.AUFTRAGSPOS.LIEFERDATUM, dbo.AUFTRAGSKOPF.ERFASSUNGSDATUM, dbo.AUFTRAGSKOPF.AENDERUNGSDATUM, dbo.AUFTRAGSKOPF.[BESTELLUNG], dbo.AUFTRAGSPOS.ARTIKELNR, dbo.AUFTRAGSPOS.BEZEICHNUNG, dbo.AUFTRAGSPOS.MENGE_BESTELLT, dbo.AUFTRAGSPOS.EINHEITVK, dbo.ARTIKEL.EAN, dbo.ARTIKEL.P116LI_HoleSpacing, dbo.ARTIKEL.P116LI_Equipment, dbo.ARTIKEL.GTIN, dbo.ARTIKEL.PurchOrderNumber, dbo.ARTIKEL.CODE1, dbo.ARTIKEL.P116LI_TempMax, dbo.AUFTRAGSPOS.POSITIONSNR, CASE WHEN NetWeightPerSalesUnit is null THEN 3*MENGE_BESTELLT ELSE NetWeightPerSalesUnit*MENGE_BESTELLT END as WEIGHT, P116LI_Picturefile1, VATEXT, AUFTRAGSART, dbo.ARTIKELTEXT.TEXT as ARTIKELTEXT, dbo.AUFTRAGSKOPF.RFIRMA1, dbo.AUFTRAGSKOPF.LFIRMA1, dbo.AUFTRAGSKOPF.MEMO, dbo.AUFTRAGSKOPF.STATUS, dbo.AUFTRAGSKOPF.BO3_DELIVERYMEMO  
			FROM [LOE01].[dbo].[AUFTRAGSPOS] 
			LEFT JOIN dbo.AUFTRAGSKOPF ON dbo.AUFTRAGSKOPF.BELEGNR = dbo.AUFTRAGSPOS.BELEGNR
			LEFT JOIN dbo.ARTIKEL ON dbo.ARTIKEL.ARTIKELNR = dbo.AUFTRAGSPOS.ARTIKELNR
			LEFT JOIN dbo.VERSANDART ON dbo.VERSANDART.VANUMMER = dbo.AUFTRAGSKOPF.VANUMMER
			LEFT JOIN dbo.AUFTRAGSART ON dbo.AUFTRAGSART.NUMMER = dbo.AUFTRAGSKOPF.BELEGART
			LEFT JOIN dbo.ARTIKELTEXT ON dbo.ARTIKEL.ARTIKELNR = dbo.ARTIKELTEXT.ARTIKELNR
			WHERE dbo.ARTIKELTEXT.SPRACHE = 1 AND PACKLISTENNR = '" . $plnr . "' 
			ORDER BY POSITIONSNR";
		  
		//SQL Config auslesesen und Verbindung zum Server herstellen
		include 'sql.php';
		
		$showhead = true;
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			if($showhead){
				$showhead = false;
				
				?>
				 <main>
					 <section class="py-1 text-center container">
						<div class="row py-lg-1">
						  <div class="col-lg-8 col-md-10 mx-auto">
							<h1 class="fw-light"><?php echo $row['PACKLISTENNR']; ?></h1>
							<p class="lead text-body-secondary">
								<?php 
									echo $row['BELEGNR']; 
									echo " | ";
									echo $row['BESTELLUNG']; 
									echo "<br>";
									if(isset($row['LFIRMA1'])){ echo $row['LFIRMA1']; } else { echo $row['RFIRMA1']; }
								?>
							</p>
								<?php 
									if($row['STATUS'] > 1){
										echo '<div class="alert alert-warning" role="alert">Der Auftrag wurde bereits (teil)fakturiert! Status ' . $row['STATUS'] . '.</div>'; 
									}
									
									if(isset($row['BO3_DELIVERYMEMO'])){
										echo '<div class="alert alert-warning" role="alert">Dieser Auftrag wurde über das Tool aufgerufen!<br>' . $row['BO3_DELIVERYMEMO'] . '</div>'; 
										$writeMemo = false;
									}
									else{
										$writeMemo = true;
									}
									
									if(isset($row['MEMO'])){
										echo '<div class="alert alert-info" role="alert">' . $row['MEMO'] . '</div>'; 
									}
									
									$auftragsart = $row['AUFTRAGSART'];
									$vatext = $row['VATEXT'];
									$erfassungsdatum = $row['ERFASSUNGSDATUM'];
									$aenderungsdatum = $row['AENDERUNGSDATUM'];
									$belegnr = $row['BELEGNR'];
								?>
						  </div>
						</div>
					  </section>
				  
				  <div class="album py-3 bg-body-tertiary">
					<div class="container">
						<form class="form-inline" action="packliste.php" method="GET">
							<div class="input-group">
							  <input class="form-control mr-sm-2" type="search" placeholder="ToDo... Man soll EANs (o.ä.) scannen können, damit die jeweiligen Artikel grün markiert werden. Via JavaScript, nicht via GET." <?php if(isset($_GET["s"])){ echo 'value="'.$_GET["s"].'"'; } ?> "aria-label="Search" name="s">
							  <div class="input-group-append">
								<button type="submit" class="btn btn-outline-secondary">🔍</button>
							  </div>
							</div>
							
							<input type="hidden" name="nr" value="<?php echo $_GET["nr"]; ?>">
						 </form>
						<br>
						<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">
				<?php
			}
			
			?>
			<div class="col">
              <div class="card shadow-sm">
				<div class="card-header <?php if(str_contains(strtolower($row['BEZEICHNUNG']), "versand")){ echo "ignore"; } ?>" id="card-header<?php echo $row['POSITIONSNR']; ?>" onclick="changeBackgroundColor('card-header<?php echo $row['POSITIONSNR']; ?>')" data-bs-toggle="collapse" data-bs-target="#collapse-me<?php echo $row['POSITIONSNR']; ?>" style="cursor: pointer;">
					<?php 
						echo "<b>" . $row['MENGE_BESTELLT'] . "x</b> | ";
						echo $row['BEZEICHNUNG']; 
						echo "<br><b>Lagerort:</b> " . $row['P116LI_TempMax'];
					?>
				</div>  
				<div id="collapse-me<?php echo $row['POSITIONSNR']; ?>" class="collapse">
					<div class="card-body" id="card-body">
					  <div class="row">
						<div class="col-6">
							<p>EAN:</p>
						</div>
						<div class="col-6">
							<p><?php echo $row['EAN']; ?></p>
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
							<p>2. EAN:</p>
						</div>
						<div class="col-6">
							<p><?php echo $row['P116LI_HoleSpacing']; ?></p>
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
							<p>3. EAN:</p>
						</div>
						<div class="col-6">
							<p><?php echo $row['P116LI_Equipment']; ?></p>
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
							<p>GTIN:</p>
						</div>
						<div class="col-6">
							<p><?php echo $row['GTIN']; ?></p>
						</div>
					  </div>
					  <div class="row">
							<hr>
					  </div>
					  <div class="row">
						<div class="col-6">
							<p>Herstellernr:</p>
						</div>
						<div class="col-6">
							<p><?php echo $row['CODE1']; ?></p>
						</div>
					  </div>
					  <div class="row">
						<div class="col-6">
							<p>Bestellnr:</p>
						</div>
						<div class="col-6">
							<p><?php echo $row['PurchOrderNumber']; ?></p>
						</div>
					  </div>
					   <div class="row">
							<hr>
					  </div>
					  <div class="row">
						<div class="col-6">
							Artikelnr:
						</div>
						<div class="col-6">
							<p><?php echo $row['ARTIKELNR']; ?></p>
						</div>
					  </div>
					  <div
						class="d-flex justify-content-between align-items-center"
					  >
						<div class="btn-group">
						  <button
							type="button"
							class="btn btn-sm btn-outline-light"
						  >
							<?php echo number_format((float)$row['WEIGHT'], 3, ',', '.'); ?> kg
						  </button>
						</div>
							Position <?php echo $row['POSITIONSNR']; ?>
					  </div>
					</div>
					<div class="card-footer" id="card-footer">
						<div class="row">
							<div class="col-6">
								<button type="button" class="btn btn-full-size btn btn-outline-info" data-bs-toggle="collapse" data-bs-target="#description<?php echo $row['POSITIONSNR']; ?>" style="cursor: pointer;">Beschreibung<br>öffnen</button>
							</div>
							<div class="col-6">
								<?php
								if($row['P116LI_Picturefile1'] !== null){
									echo '<button type="button" class="btn btn-full-size btn btn-outline-info" data-bs-toggle="collapse" data-bs-target="#image' . $row['POSITIONSNR'] . '" style="cursor: pointer;">Bild<br>öffnen</button>';
								}
								?>
							</div>
						</div>
						<div class="row collapse" id="description<?php echo $row['POSITIONSNR']; ?>">
							&nbsp;<hr>
							<?php echo $row['ARTIKELTEXT']; ?>
						</div>
						<div class="row collapse" id="image<?php echo $row['POSITIONSNR']; ?>">
							&nbsp;<hr>
							<center><a href="https://www.tectree.de/bilder/<?php echo $row['P116LI_Picturefile1']; ?>" target="_blank"><img loading="lazy" src="https://www.tectree.de/bilder/<?php echo $row['P116LI_Picturefile1']; ?>" /></a></center>
						</div>
					</div>
				</div>		
              </div>
            </div>
			
			<?php
		}
		
		if($writeMemo){
			if(isset($_COOKIE["user"])){
				$user = $_COOKIE["user"];
			}
			else{
				$user = "VER";
			}
			
			date_default_timezone_set('Europe/Berlin');
			$insertValue = date('d.m.Y H:i:s') . " - " . $user;
			
			// UPDATE-Query
			$sql = " UPDATE [dbo].[AUFTRAGSKOPF] SET BO3_DELIVERYMEMO = '" . $insertValue . "', SACHBEARBEITERNR = '" . $user . "', AENDERUNGSDATUM = GETDATE() WHERE BELEGNR = '" . $belegnr . "'";
			
			$stmt = sqlsrv_query($conn, $sql);

			//Auftrag "sperren" = In ein Memo Feld das derzeitige Datum und den User importieren
			if ($stmt === false) {
				echo '<div class="alert alert-error" role="alert">Der Auftrag konnte nicht gesperrt werden!</div>';
				die(print_r(sqlsrv_errors(), true));
			}
		}			
		
		// Verbindung schließen
		sqlsrv_close($conn);
?>

          </div>
			<br>			
			<div class="row">
				<div class="col-sm-12 col-md-6">
					<a href="#" class="btn btn-full-size btn-secondary my-2 <?php if(str_contains(strtolower($vatext), "pri")){ echo "prime"; } ?> <?php if(!str_contains(strtolower($vatext), "dhl")){ echo "nondhl"; } ?>">
					  <?php 
						echo $auftragsart . "<br>" . $vatext; 							
					  ?>
					  </a>
				</div>
				<div class="col-sm-12 col-md-6">
					<a href="#" class="btn btn-full-size btn-secondary my-2">
					  <?php 
						echo "Erfassung: " . $erfassungsdatum->format('d.m.Y H:i:s');
						echo "<br>";
						echo "Änderung:&nbsp; " . $aenderungsdatum->format('d.m.Y H:i:s'); 							
					  ?></a>
				</div>
			</div>
			
        </div>
      </div>
    </main>

<?php		
		echo '<br><a href="finish.php?nr=' . $plnr . '"><button type="button" class="btn btn-lg btn-primary btn-full-size">Packliste abschließen</button></a><br>';
	}
	else{
		echo '<div class="alert alert-error" role="alert">Keine Packlistennummer angegeben!</div>';
	}
?>

