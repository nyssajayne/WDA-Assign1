<html>

	<head>
		
		<title>Clear Wines</title>

	</head>

	<body>

		<?php

			session_start();

			session_unset();

			echo "<p>Wines Cleared.</p>";

			echo "<p><a href=\"search_screen.php\">Return to the Search Screen</a></p>";
		?>

	</body>

</html>
