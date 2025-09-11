<?php
	header('Content-Type: text/html; charset=UTF-8');
?>

<!DOCTYPE html>
<html lang="de" data-bs-theme="dark">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta charset="UTF-8">

  <link rel="stylesheet" href="css/main.css">
  <link href="dist/css/bootstrap.min.css" rel="stylesheet" />
  <script
      src="dist/js/bootstrap.bundle.min.js"
      class="astro-vvvwv3sm"
    ></script>
	
	<script>
  function changeBackgroundColor(elementId) {
	  const targetDiv = document.getElementById(elementId);
	  console.log(targetDiv); 

		if (targetDiv) {
		  //targetDiv.style.backgroundColor =  targetDiv.style.backgroundColor === 'rgb(40, 167, 69)' ? 'var(--bs-card-cap-color)' : 'rgb(40, 167, 69)';
		  targetDiv.style.backgroundColor = 'rgb(40, 167, 69)'
		}
	}
</script>
</head>
<body>

    <header data-bs-theme="dark">
      <div class="collapse text-bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
			  <div class="col-sm-4 offset-md-1 py-4">
				  <h4>Menü</h4>
				  <a href="pool.php"><button type="button" class="btn btn-lg btn-primary btn-full-size">Packlisten-Pool</button></a><br>
				  <a href="history.php"><button type="button" class="btn btn-lg btn-primary btn-full-size">Historie (letzte 100)</button></a><br>
				  <a href="settings.php"><button type="button" class="btn btn-lg btn-primary btn-full-size">Einstellungen</button></a><br>
			</div>
            <div class="col-sm-8 col-md-7 py-4">
              <h4>Infos & News</h4>
              <p class="text-body-secondary">
					Wird ein Auftrag über das Tool geöffnet, wird er für eine Stunde lang nicht mehr auf der Startseite angezeigt.<br>
					Nutzt den Packlisten-Pool um mehrere Aufträge schnell hintereinander in eNVenta abzuschließen!
              </p>
            </div>
            
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
          <a href="index.php" class="navbar-brand d-flex align-items-center">
			<img src="img/logo.png" class="img-logo" />
            <strong>Packlisten Tool</strong>
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarHeader"
            aria-controls="navbarHeader"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>
	
<?php
	//Log every site visit
	//error_log("", 0);
?>