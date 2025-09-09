<?php
	include 'header.php';
?>


<?php	
	if(isset($_GET["nr"])){
		$plnr = $_GET["nr"];
		
		// SQL-Abfrage
		$sql = "SELECT TOP (1000) PACKLISTENNR, AUFTRAGSKOPF.BELEGNR, AUFTRAGSKOPF.BELEGART, dbo.AUFTRAGSPOS.LIEFERDATUM, dbo.AUFTRAGSKOPF.ERFASSUNGSDATUM, dbo.AUFTRAGSKOPF.AENDERUNGSDATUM, dbo.AUFTRAGSKOPF.[BESTELLUNG], dbo.AUFTRAGSPOS.ARTIKELNR, dbo.AUFTRAGSPOS.BEZEICHNUNG, dbo.AUFTRAGSPOS.MENGE_BESTELLT, dbo.AUFTRAGSPOS.EINHEITVK, dbo.ARTIKEL.EAN, dbo.ARTIKEL.P116LI_HoleSpacing, dbo.ARTIKEL.P116LI_Equipment, dbo.ARTIKEL.GTIN, dbo.ARTIKEL.PurchOrderNumber, dbo.ARTIKEL.CODE1, dbo.ARTIKEL.P116LI_TempMax, dbo.AUFTRAGSPOS.POSITIONSNR, CASE WHEN NetWeightPerSalesUnit is null THEN 3*MENGE_BESTELLT ELSE NetWeightPerSalesUnit*MENGE_BESTELLT END as WEIGHT, P116LI_Picturefile1, VATEXT, AUFTRAGSART, dbo.ARTIKELTEXT.TEXT as ARTIKELTEXT  
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
					 <section class="py-5 text-center container">
						<div class="row py-lg-5">
						  <div class="col-lg-6 col-md-8 mx-auto">
							<h1 class="fw-light"><?php echo $row['PACKLISTENNR']; ?></h1>
							<h1 class="fw-light"><?php echo $row['BELEGNR']; ?></h1>
							<p class="lead text-body-secondary">
								<?php echo $row['BESTELLUNG']; ?>
							</p>
							<p>
							  <a href="#" class="btn btn-primary my-2">
							  <?php 
								echo $row['AUFTRAGSART'] . "<br>" . $row['VATEXT']; 							
							  ?>
							  </a>
							  <a href="#" class="btn btn-secondary my-2">
							  <?php 
								echo $row['ERFASSUNGSDATUM']->format('d.m.Y H:i:s');
								echo "<br>";
								echo $row['AENDERUNGSDATUM']->format('d.m.Y H:i:s'); 							
							  ?></a>
							</p>
						  </div>
						</div>
					  </section>
				  
				  <div class="album py-5 bg-body-tertiary">
					<div class="container">
					  <div class="row row-cols-1 row-cols-lg-3 g-3">
				<?php
			}
			
			?>
			<div class="col">
              <div class="card shadow-sm">
				<div class="card-header" id="card-header<?php echo $row['POSITIONSNR']; ?>" onclick="changeBackgroundColor('card-header<?php echo $row['POSITIONSNR']; ?>')" data-bs-toggle="collapse" data-bs-target="#collapse-me<?php echo $row['POSITIONSNR']; ?>" style="cursor: pointer;">
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
		
		// Verbindung schließen
		sqlsrv_close($conn);
?>

          </div>
        </div>
      </div>
    </main>

<?php		
		echo '<br><a href="finish.php?nr=' . $plnr . '"><button type="button" class="btn btn-lg btn-primary btn-full-size">Packliste abschließen</button></a><br>';
	}
	else{
		echo "Keine Packlistennummer angegeben!<br>";
	}
?>

