<?php
		
		$serverName = "server-03"; // oder IP-Adresse
		$connectionOptions = [
			"Database" => "uwu",
			"Uid" => "owo",
			"PWD" => "kek",
			"Encrypt" => true,
			"TrustServerCertificate" => true,
			"CharacterSet" => "UTF-8"
		];

		// Verbindung herstellen
		$conn = sqlsrv_connect($serverName, $connectionOptions);

		if ($conn === false) {
			die(print_r(sqlsrv_errors(), true));
		}

		
		$stmt = sqlsrv_query($conn, $sql);

		if ($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		}
		
?>