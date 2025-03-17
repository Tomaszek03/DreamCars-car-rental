<?php

	session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: index.php');
		exit();
	}

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>DreamCars - logowanie</title>
	<link rel="stylesheet" href="css/style_main.css" type="text/css"/>
	<link rel="stylesheet" href="css/style_log.css" type="text/css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Playpen+Sans:wght@700&family=Questrial&display=swap" rel="stylesheet">
</head>

<body>
<div id="container">
	<div class="start"> 
        <span class = "logo"><a href="index.php">DreamCars</a></span>
    </div>
	<?php
		if(isset($_SESSION['udanarejestracja']))
		{
			echo '<div id="sukces">Pomyślnie zarejestrowano. Proszę się zalogować.<br/></div>';
			unset($_SESSION['udanarejestracja']);
		}
	?>
	<div id="content">
	<form action="zaloguj.php" method="post">
	
		Login: <br /> <input type="text" name="login" placeholder="adres e-mail" /> <br /><br/>
		Hasło: <br /> <input type="password" name="haslo" /> <br /><br />
		<input type="submit" value="Zaloguj się" />
	</form>
	</div>
	<div class="new">
		Nie posiadasz konta? <a href="rejestracja.php">Utwórz je</a>
	</div>
	<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
	?>
	<div style="margin-top: 150px"></div>
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