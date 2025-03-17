<?php
    session_start();

    function dane_auta($nr)
    {
        require_once "connect.php";
	    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
        
	    if ($polaczenie->connect_errno!=0)
	    {
		    echo "Error: ".$polaczenie->connect_errno;
	    }
	    else
        {
            $sql = "SELECT * FROM cars WHERE id_auta='$nr'";
            $sql2 = "SELECT * FROM wypozyczenia WHERE id_sam='$nr'AND koniec>CURDATE()";

            if($rezultat = @$polaczenie->query($sql))
            {
                $ile=$rezultat->num_rows;
                if($ile>0)
                {
                    $wiersz=$rezultat->fetch_assoc();
                    $marka=$wiersz['marka'];
                    $model=$wiersz['model'];
                    $kolor=$wiersz['kolor'];
                    $silnik=$wiersz['silnik'];
                    $moc=$wiersz['moc'];
                    $czas=$wiersz['czas'];
                    $rok=$wiersz['rok'];
                    $cena_dzien=$wiersz['cena_dzien'];

                    echo '<div class="auto">'.$marka." ".$model."</div><br/>";
                    echo "Kolor: ".$kolor."<br/>";
                    echo "Silnik: ".$silnik."<br/>";
                    echo "Moc: ".$moc." KM<br/>";
                    echo "Czas 0-100: ".$czas."s. <br/>";
                    echo "Rok produkcji: ".$rok."<br/><br/>";
                    echo "Cena wynajęcia:<br/>";
                    echo "1 dzień - ".$cena_dzien." PLN";
                    $rezultat->free_result();
                }
                else
                {
                    echo "<br>Error";
                }
            }
            else
            {
                echo "<br>Error";
            }
            if($rezultat2 = @$polaczenie->query($sql2))
            {
                $ile2=$rezultat2->num_rows;
                $current = new DateTime();

                echo '<div style="margin-top: 20px";></div>';
                echo '<br/>Aktualne rezerwacje';

                if($ile2>0)
                {
                    echo '<div style="clear: both";></div>';
                    echo '<div class="tabela"><div class="nagl">';
                    echo '<div class="komorka_data">Początek wypożyczenia</div><div class="komorka_data">Koniec wypożyczenia</div>';
                    echo '<div style="clear: both";></div>';
                    echo '</div></div>';
                    echo '<div style="clear: both";></div>';
                    while($wiersz=$rezultat2->fetch_assoc())
                    {
                        echo '<div class="komorka_data">'.$wiersz['poczatek'].'</div><div class="komorka_data">'.$wiersz['koniec'].'</div>';
                    }
                    echo '<div style="clear: both";></div>';
                    $rezultat2->free_result();
                }
                else
                {
                    echo '<br/><span style="font-size: 20px;">Brak aktualnych rezerwacji</span>';
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
	<link rel="stylesheet" href="css/style_main.css" type="text/css"/>
    <link rel="stylesheet" href="css/style_rez.css" type="text/css"/>
	<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Playpen+Sans:wght@700&family=Questrial&display=swap" rel="stylesheet">
	<title>
       <?php 
            if($_SESSION['auto']==1) echo 'DreamCars - Dodge Challenger SRT Hellcat';
            if($_SESSION['auto']==2) echo 'DreamCars - Nissan GTR R35';
            if($_SESSION['auto']==3) echo 'DreamCars - Lamborghini Huracan EVO';
            if($_SESSION['auto']==4) echo 'DreamCars - Ferrari 812 GTS';
            if($_SESSION['auto']==5) echo 'DreamCars - Audi R8';
            if($_SESSION['auto']==6) echo 'DreamCars - Aston Martin DBS Superleggera';
            if($_SESSION['auto']==7) echo 'DreamCars - Lamborghini Aventador SVJ';
            if($_SESSION['auto']==8) echo 'DreamCars - McLaren 720S';
            if($_SESSION['auto']==9) echo 'DreamCars - Porsche 911 GT3 RS';
        ?>
    </title>
</head>

<body>
<div id="container">
    <div class="start"> 
        <span class = "logo"><a href="index.php">DreamCars</a></span>
        <?php
        if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
        {
            echo '<a href="logout.php"><div class="sign"> Wyloguj</div></a>';
            echo '<a href="mojekonto.php"><div class="sign"> Moje konto </div></a>';
        }
        else
        {
            echo '<a href="rejestracja.php"><div class="sign"> Zarejestruj się</div></a>';
            echo '<a href="logowanie.php"><div class="sign"> Zaloguj </div></a>';
        }
        ?>
    </div>
	<div id = "content_rez">   
        <div class="left">
            <div class="dane_tech">	
                <?php
                    dane_auta($_SESSION['auto']);
                ?>
            </div>
            <form action="rezerwacja.php" method="post">
                    <div style="margin-top: 30px; margin-bottom: 10px;">
                        Data rozpoczęcia: <input type="date" name="data"><br/>
                        <div style="margin-top: 10px;"></div>
                        <?php
                            if (isset($_SESSION['e_data']))
                            {
                                echo $_SESSION['e_data'];
                                unset($_SESSION['e_data']);
                            }
                        ?>
                        <div style="clear:both"></div>
                        Liczba dni: <input type="number" name="dni">
                        <?php
                            if (isset($_SESSION['e_dni']))
                            {
                                echo '<div class="error3">'.$_SESSION['e_dni'].'</div>';
                                unset($_SESSION['e_dni']);
                            }
                        ?>
                        </div>
                        <input type="submit" value="Zarezerwuj" />
                    </div>
            </form>           
        <div class="left">
            <?php
                if($_SESSION['auto']==1) echo '<div class="image2"><img width="750" height="500" src="img/car1_2.png"/></div>';
                if($_SESSION['auto']==2) echo '<div class="image2"><img width="750" height="500" src="img/car2_2.jpg"/></div>';
                if($_SESSION['auto']==3) echo '<div class="image2"><img width="750" height="500" src="img/car3_2.jpg"/></div>';
                if($_SESSION['auto']==4) echo '<div class="image2"><img width="750" height="500" src="img/car4_2.jpg"/></div>';
                if($_SESSION['auto']==5) echo '<div class="image2"><img width="750" height="500" src="img/car5_2.jpg"/></div>';
                if($_SESSION['auto']==6) echo '<div class="image2"><img width="750" height="500" src="img/car6_2.jpg"/></div>';
                if($_SESSION['auto']==7) echo '<div class="image2"><img width="750" height="500" src="img/car7_2.jpg"/></div>';
                if($_SESSION['auto']==8) echo '<div class="image2"><img width="750" height="500" src="img/car8_2.jpg"/></div>';
                if($_SESSION['auto']==9) echo '<div class="image2"><img width="750" height="500" src="img/car9_2.jpg"/></div>';
            ?>
        </div>
	</div>
	<div style="clear:both"></div>
    <div style="margin-top: 100px;"></div>
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