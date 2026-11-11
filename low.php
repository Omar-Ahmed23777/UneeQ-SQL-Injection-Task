<?php

if( isset( $_GET[ 'Submit' ] ) ) {
	// Get input
	$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	if($id===false || $id===null) {
			$html .= '<pre>ID is invalid.</pre>';
		} else {
			switch ($_DVWA['SQLI_DB']) {
				case MYSQL:
					// Check database
					$stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], "SELECT first_name, last_name FROM users WHERE user_id = ?");
					if($stmt === false)
						{
							error_log(mysqli_error($GLOBALS["___mysqli_ston"]));
							$html .= "<pre>Something went wrong.</pre>";
							break;
						}
						mysqli_stmt_bind_param($stmt, 'i', $id);
						mysqli_stmt_execute($stmt);
						mysqli_stmt_bind_result($stmt, $first, $last);

				// Get results
				while( mysqli_stmt_fetch($stmt) ) {
					// Get values
					$html .= "<pre>ID:" . (int)$id . "<br />First name: " . htmlspecialchars($first, ENT_QUOTES, 'UTF-8') . "<br />Surname: " . htmlspecialchars($last, ENT_QUOTES, 'UTF-8') . "</pre>";
				}
				mysqli_stmt_close($stmt);
				
					// Feedback for end user			

				mysqli_close($GLOBALS["___mysqli_ston"]);
				break;
			case SQLITE:
				global $sqlite_db_connection;

				#$sqlite_db_connection = new SQLite3($_DVWA['SQLITE_DB']);
				#$sqlite_db_connection->enableExceptions(true);

				#print $query;
				try {
					$stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id");
					$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
					$results = $stmt->execute();
					while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
						$html .= "<pre>ID: " . (int)$id . "<br />First name: " . htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8') . "<br />Surname: " . htmlspecialchars($row['last_name'], ENT_QUOTES, 'UTF-8') . "</pre>";
					}
				} catch (Exception $e) {
					error_log($e->getMessage());
					$html .= "<pre>Something went wrong.</pre>";
				}
				break;
		} 
	}
}

?>
