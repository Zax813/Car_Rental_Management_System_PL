<head>
<meta charset="utf-8" />

<link rel="stylesheet" href="css/bootstrap.min.css">
<link  rel="stylesheet" href="css/bootstrap-icons.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/domyslny-styl.css">
<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

<script src="./js/default-functions.js"></script>
<script src="./js/jquery-3.6.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript" src="DataTables/datatables.min.js"></script>

</head>
<header>
	<div id="header">
		<?php
		if (isset($_SESSION['perm'])) {
			$name = $_SESSION['user'];
			if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") {
				
				//Linki panelu bocznego dla uprawnień admina i kierownika
				echo "<div id='mySidepanel' class='sidepanel'>";
					echo "<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>";
					echo "<a href='index.php?action=home' name='equipList'>Samochody</a>";
					echo "<a href='index.php?action=rentList' name='rentList'>Wypożyczenia</a>";
					echo "<a href='index.php?action=clientList' name='clientList'>Klienci</a>";
					echo "<a href='index.php?action=carInspectionList' name='carInspectionList'>Przeglądy</a>";
					echo "<a href='index.php?action=carServiceList' name='carServiceList'>Serwisy</a>";
					echo "<a href='index.php?action=userList' name='userList'>Pracownicy</a>";
				echo "</div>";

				//Guzik otwierania panelu bocznego
				echo "<button class='openbtn' onclick='openNav()'>&#9776</button>";
				echo "<a href='index.php?action=logout' class='btn btn-primary' style='float: right;' name='Wyloguj'>Wyloguj ($name)</a>";
			} else if ($_SESSION['perm'] == "sprzedawca" || $_SESSION['perm'] == "pracownik") {
				
				//Linki panelu bocznego dla uprawnień pracownika
				echo "<div id='mySidepanel' class='sidepanel'>";
					echo "<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>";
					echo "<a href='index.php?action=home' name='equipList'>Samochody</a>";
					echo "<a href='index.php?action=rentList' name='rentList'>Wypożyczenia</a>";
					echo "<a href='index.php?action=clientList' name='clientList'>Klienci</a>";
					echo "<a href='index.php?action=carInspectionList' name='carInspectionList'>Przeglądy</a>";
					echo "<a href='index.php?action=carServiceList' name='carServiceList'>Serwisy</a>";
					echo "<a href='index.php?action=userList' name='userList'>Pracownicy</a>";
				echo "</div>";

				//Guzik otwierania panelu bocznego
				echo "<button class='openbtn' onclick='openNav()'>&#9776</button>";
				echo "<a href='index.php?action=logout' class='btn btn-primary' style='float: right;' name='Wyloguj'>Wyloguj ($name)</a>";
			}
		} 
		else 
		{
		?>
		<form method="post" action="index.php?action=login">
			<?php
			//Guzik logowania gdy użytkownik nie jest zalogowany
			echo "<input type='submit' class='btn btn-primary' name='Zaloguj' value='Zaloguj'/>";
		}
		?>
	</div>

</header>
<section>