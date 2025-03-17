<?php

	session_start();

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <title>DreamCars - wypożyczalnia luksusowych samochodów</title>
    <meta name="description" content="Wypożyczalnia luksusowych samochodów"/>
    <meta name="keywords" content="samochody, auta, cars, wypożyczalnia"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" href="css/style_main.css" type="text/css"/>
    <link rel="stylesheet" href="css/fontello.css" type="text/css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Playpen+Sans:wght@700&family=Questrial&display=swap" rel="stylesheet">
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
        <div id="content">
            <span class="header">Nasza flota</span>
            <div style = "clear:both"></div>
            <div class = "cars">
                <div class = "car">
                    <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="1"><div class="image"><input type="image" width="350" height="350" src="img/car1.png"></div></form>
                        <div style="clear:both"></div>
                    <div class="name">Dodge Challenger SRT Hellcat</div>
                </div>
                <div class = "car">
                    <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="2"><div class="image"><input type="image" width="350" height="350" src="img/car2.png"></div></form>
                        <div style="clear:both"></div>
                    <div class="name">Nissan GTR R35</div>
                </div>
                <div class = "car">
                <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="3"><div class="image"><input type="image" width="350" height="350" src="img/car3.png"></div></form>
                    <div style="clear:both"></div>
                    <div class="name">Lamborghini Huracan EVO</div>
                </div>
                <div style="clear:both"></div>
                <div class = "car">
                <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="4"><div class="image"><input type="image" width="350" height="350" src="img/car4.png"></div></form>
                    <div style="clear:both"></div>
                    <div class="name">Ferrari 812 GTS</div>
                </div>
                <div class = "car">
                <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="5"><div class="image"><input type="image" width="350" height="350" src="img/car5.jpg"></div></form>
                    <div style="clear:both"></div>
                    <div class="name">Audi R8</div>
                </div>
                <div class = "car">
                <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="6"><div class="image"><input type="image" width="350" height="350" src="img/car6.png"></div></form>
                    <div style="clear:both"></div>
                    <div class="name">Aston Martin DBS Superleggera</div>
                </div>
                <div class = "car">
                <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="7"><div class="image"><input type="image" width="350" height="350" src="img/car7.jpg"></div></form>
                    <div style="clear:both"></div>
                    <div class="name">Lamborghini Aventador SVJ</div>
                </div>
                <div class = "car">
                <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="8"><div class="image"><input type="image" width="350" height="350" src="img/car8.jpg"></div></form>
                    <div style="clear:both"></div>
                    <div class="name">McLaren 720S</div>
                </div>
                <div class = "car">
                <form action="car_bufor.php" method="post"><input type="hidden" name="numer" value="9"><div class="image"><input type="image" width="350" height="350" src="img/car9.jpg"></div></form>
                    <div style="clear:both"></div>
                    <div class="name">Porsche 911 GT3 RS</div>
                </div>
                <div style="clear:both"></div>
            </div>   
        </div>
        <div style="clear:both"></div>
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

