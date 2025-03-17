<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: logowanie.php');
		exit();
	}

	function wypozyczone()
    {
        require_once "connect.php";
	    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        
		if ($polaczenie->connect_errno!=0)
		{
			echo "Error: ".$polaczenie->connect_errno;
		}
		else
		{
			$user =$_SESSION['user_id'];
			$current = new DateTime();
			$sql = "SELECT * FROM wypozyczenia,users,cars WHERE id_osoby='$user' AND wypozyczenia.id_osoby=users.user_id AND cars.id_auta=wypozyczenia.id_sam";
			if($rezultat = @$polaczenie->query($sql))
			{
				$ile=$rezultat->num_rows;
				if($ile>0)
				{
					echo '<div class="nagl">';
					echo '<div class="komorka1">ID</div><div class="komorka_name1">Marka</div><div class="komorka_name">Model</div><div class="komorka_data">Data rezerwacji</div><div class="komorka_data">Początek wypożyczenia</div><div class="komorka_data">Koniec wypożyczenia</div>';
					echo '<div style="clear: both";></div>';
					echo '</div>';

					while($wiersz=$rezultat->fetch_assoc())
					{
						$anulowany=$wiersz['id_wyp'];
						echo '<div class="komorka1">'.$wiersz['id_wyp'].'</div><div class="komorka_name1">'.$wiersz['marka'].'</div><div class="komorka_name">'.$wiersz['model'].'</div><div class="komorka_data">'.$wiersz['data_godzina'].'</div><div class="komorka_data">'.$wiersz['poczatek'].'</div><div class="komorka_data">'.$wiersz['koniec'].'</div>';
						if($wiersz['poczatek']>$current->format('Y-m-d')) echo '<div class="komorka"><form action="anuluj.php" method="post"><input type="hidden" name="do_usuniecia" value='.$anulowany.'><input type="submit" value="Anuluj rezerwację"/></form></div><br/><br/>';
						else
						{
							if($wiersz['koniec']>$current->format('Y-m-d')) echo '<div class="komorka">Trwa wypożyczenie</div><br/><br/>';
							else echo '<div class="komorka">Rezerwacja wygasła</div><br/><br/>';
						}
					}
					
					$rezultat->free_result();
				}
				else
				{
					echo "Brak rezerwacji";
				}
			}
			else
			{
				echo "<br>Error";
			}
			$polaczenie->close();
		}
    }   
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" href="css/style_acc.css" type="text/css"/>
	<link rel="stylesheet" href="css/style_main.css" type="text/css"/>
	<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Playpen+Sans:wght@700&family=Questrial&display=swap" rel="stylesheet">
	<title>DreamCars - Moje konto</title>
</head>

<body>
<div id="container">
    <div class="start"> 
        <span class = "logo"><a href="index.php">DreamCars</a></span>
        <a href='logout.php'><div class="sign"> Wyloguj</div></a>
	</div>
	<div id = "content">
		<div class="icon">
			<i class="demo-icon icon-user-1"></i> <span class="i-name"></span><span class="i-code"></span>
		</div>
		<div class = "im_naz">
			<?php
				if($_SESSION['email']=='[admin-email]')
				{
					echo '<span style="color: red";>[Administrator]&nbsp</span>';
				}
				else if(($_SESSION['email']=='[moderator-email]'))
				{
					echo '<span style="color: rgb(15, 200, 15)";>[Moderator]&nbsp</span>';
				}
					echo $_SESSION['imie']." ".$_SESSION['nazwisko'];
			?>
		</div>
		<a href='ustawienia.php'><div class = "settings" title="Zaktualizuj swoje dane">Zmień dane</div></a>
		
		<?php
			if($_SESSION['email']=='[admin-email]')
			{
				echo'<a href="admin.php"><div class = "settings" title="Panel administratora">Panel administratora</div></a>';
			}		

		?>
		<div style="clear:both"></div>
		<div class = "mail">
			<i class="demo-icon icon-mail"></i> <span class="i-name"></span><span class="i-code"></span>Adres e-mail:&nbsp
			<?php
				echo $_SESSION['email'];
			?>
		</div>
		<div class = "mail">
		<i class="demo-icon icon-phone"></i> <span class="i-name"></span><span class="i-code"></span>Numer telefonu:&nbsp
			<?php
				echo $_SESSION['telefon'];
			?>
		</div>
		<div style="clear:both; margin-top: 20px;"></div>
		<div class = "napis">Moje rezerwacje:</div>
		<div class="tabela">
			<?php
				wypozyczone();
			?>
		</div>
	</div>
	<div style="clear:both"></div>
	<div style="margin-top: 300px;"></div>
	<div id="footer">
            DreamCars Car Rental sp. z o.o<br/>
            ul. Stefanii i Stefana 7, 22-600 Papużkowo<br/>
            <i class="demo-icon icon-mail"></i> <span class="i-name"></span><span class="i-code"></span>dreamcars@gmail.com<br/>
            <i class="demo-icon icon-phone"></i> <span class="i-name"></span><span class="i-code"></span>888 777 333
    </div>
	<div class = "copy">
            Stronę wykonał Tomasz Mandat &copy
    </div>
</div>

<script src="jquery-1.11.3.min.js"></script>
	
	<script>

	$(document).ready(function() 
    {
	var NavY = $('.start').offset().top;
	 
	var stickyNav = function()
    {
	var ScrollY = $(window).scrollTop();
		  
	if (ScrollY > NavY) 
    { 
		$('.start').addClass('sticky');
	} 
    else 
    {
		$('.start').removeClass('sticky'); 
	}
	};
	 
	stickyNav();
	 
	$(window).scroll(function() 
    {
		stickyNav();
	});
	});
	
</script>

</body>
</html>