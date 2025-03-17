<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany'])||$_SESSION['email']!='tomekmand@gmail.com')
	{
		header('Location: logowanie.php');
		exit();
	}

    require_once "connect.php";
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($polaczenie->connect_errno!=0)
		{
			echo "Error: ".$polaczenie->connect_errno;
		}
		else
		{
            $users = "SELECT COUNT(users.user_id) FROM users";
            $ilosc_users = @$polaczenie->query($users);
            $rez1=$ilosc_users->fetch_assoc();
            
            $cars = "SELECT COUNT(cars.id_auta) FROM cars";
            $ilosc_cars = @$polaczenie->query($cars);
            $rez2=$ilosc_cars->fetch_assoc();

            $wypozyczenia = "SELECT COUNT(wypozyczenia.id_wyp) FROM wypozyczenia";
            $ilosc_wyp = @$polaczenie->query($wypozyczenia);
            $rez3=$ilosc_wyp->fetch_assoc();

            $wyp2 = "SELECT cars.marka, cars.model, COUNT(wypozyczenia.id_sam) AS ilosc, SUM(DATEDIFF(wypozyczenia.koniec, wypozyczenia.poczatek)) AS ilosc_dni, SUM(DATEDIFF(wypozyczenia.koniec, wypozyczenia.poczatek))*cena_dzien AS przychod FROM cars, wypozyczenia WHERE cars.id_auta=wypozyczenia.id_sam GROUP BY wypozyczenia.id_sam ORDER BY przychod DESC;";
            $wyp_tab = @$polaczenie->query($wyp2);
            $wyp_tab1 = @$polaczenie->query($wyp2);

            $rank = "SELECT users.imie, users.nazwisko, COUNT(wypozyczenia.id_sam) AS ilosc, SUM(DATEDIFF(wypozyczenia.koniec, wypozyczenia.poczatek)) AS ilosc_dni FROM users, wypozyczenia WHERE wypozyczenia.id_osoby=users.user_id GROUP BY users.imie ORDER BY ilosc_dni DESC;";
            $rank_tab = @$polaczenie->query($rank);

            function suma()
            {
                $przychod = 0;
                global $wyp_tab1;
                while($tabela_wyp1=$wyp_tab1->fetch_assoc())
				{
					$przychod=$przychod+$tabela_wyp1['przychod'];
				}
                echo "<div style='color: red; float: right;'>".$przychod."</div";
            }

            function tabela_wypozyczen()
            {
                echo '<div class="nagl2">';
				echo '<div class="komorka_name1">Marka</div><div class="komorka_name">Model</div><div class="komorka_name2">Ilość wypożyczeń</div><div class="komorka_name2">Łączna ilość dni</div><div class="komorka_name2">Przychód</div>';
				echo '<div style="clear: both";></div>';
				echo '</div>';

                global $wyp_tab;
                while($tabela_wyp=$wyp_tab->fetch_assoc())
				{
					echo '<div class="komorka_name1">'.$tabela_wyp['marka'].'</div><div class="komorka_name">'.$tabela_wyp['model'].'</div><div class="komorka_name2">'.$tabela_wyp['ilosc'].'</div><div class="komorka_name2">'.$tabela_wyp['ilosc_dni'].'</div><div class="komorka_name2">'.$tabela_wyp['przychod'].'&nbspPLN</div><div style="clear:both"></div>';
				}
            }

            function ranking()
            {
                echo '<div class="nagl3">';
				echo '<div class="komorka_name1">Imie</div><div class="komorka_name">Nazwisko</div><div class="komorka_name2">Ilość wypożyczeń</div><div class="komorka_name2">Łączna ilość dni</div>';
				echo '<div style="clear: both";></div>';
				echo '</div>';

                global $rank_tab;
                while($rank_wyp=$rank_tab->fetch_assoc())
				{
					echo '<div class="komorka_name1">'.$rank_wyp['imie'].'</div><div class="komorka_name">'.$rank_wyp['nazwisko'].'</div><div class="komorka_name2">'.$rank_wyp['ilosc'].'</div><div class="komorka_name2">'.$rank_wyp['ilosc_dni'].'</div><div style="clear:both"></div>';
				}
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
	<title>DreamCars - Panel administratora</title>
</head>

<body>
<div id="container">
    <div class="start"> 
        <span class = "logo"><a href="index.php">DreamCars</a></span>
        <a href='logout.php'><div class="sign"> Wyloguj</div></a>
	</div>
	<div id = "content">
		<p>Statystyki<p>
        <div id="statystyki">
            <div class="stats">Zarejestrowani użytkownicy:&nbsp<?php echo "<div style='color: red; float: right;'>".$rez1['COUNT(users.user_id)']."</div>";?></div>
            <div class="stats">Dostępne samochody :&nbsp<?php echo "<div style='color: red; float: right;'>".$rez2['COUNT(cars.id_auta)']."</div>";?></div>
            <div class="stats">Ilość wypożyczeń:&nbsp<?php echo "<div style='color: red; float: right;'>".$rez3['COUNT(wypozyczenia.id_wyp)']."</div>";?></div>
            <div class="stats">Łączny dochód:&nbsp<?php echo "<div style='color: red; float: right;'>".suma()."&nbspPLN</div>";?></div>
        </div>
	<div style="clear:both"></div>
    <div style="margin-top: 100px;"></div>
        <p>Szczegóły dotyczące wypożyczeń<p>
         <div class="tabela">   
            <div class="tab">
                <?php
                    tabela_wypozyczen();
                ?>
            </div>
        </div>
        <div style="clear:both"></div>
    <div style="margin-top: 100px;"></div>
        <p>Ranking wypożyczeń użytkowników<p>
            <div class="tabela">   
                <div class="tab2">
                    <?php
                        ranking();
                    ?>
                </div>
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