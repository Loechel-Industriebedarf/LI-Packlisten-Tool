<?php
	include 'header.php';
?>



<div class="row">
	<div class="col-10 offset-1">
	<?php	
		if(isset($_COOKIE["user"])){
			$user = $_COOKIE["user"];
		}
		else{
			setcookie("user", "VER");
			$user = "VER";
		}
		
		if(isset($_GET["user"])){
			setcookie("user", $_GET["user"]);
			$user = $_GET["user"];
			
			echo '<div class="alert alert-success" role="alert">Der Nutzer wurde erfolgreich auf ' . $_GET["user"] . ' abgeändert!</div><br>';
		}

	?>
	
	<div class="alert alert-info" role="alert">Du bist als <?php echo $user; ?> eingeloggt!</div><br>
	
		<form action="settings.php" method="GET">
		  <div class="mb-3">
			<label for="user" class="form-label">Username</label>
			<input type="text" class="form-control" name="user" aria-describedby="userName" value="<?php echo $user; ?>">
		  </div>
		  <button type="submit" class="btn btn-full-size btn-primary">Submit</button>
		</form>
	</div>
</div>