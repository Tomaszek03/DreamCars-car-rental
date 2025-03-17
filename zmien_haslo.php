<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: logowanie.php');
		exit();
	}
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" href="css/style_acc.css" type="text/css"/>
	<link rel="stylesheet" href="css/style_main.css" type="text/css"/>
    <link rel="stylesheet" href="css/style_reg.css" type="text/css"/>
	<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Playpen+Sans:wght@700&family=Questrial&display=swap" rel="stylesheet">
	<title>DreamCars - Zmiana hasła</title>
</head>

<body>
<div id="container">
    <div class="start"> 
        <span class = "logo"><a href="index.php">DreamCars</a></span>
        <a href='logout.php'><div class="sign"> Wyloguj</div></a>
	</div>
    
    <a href="ustawienia.php"><div class="back"><i class="demo-icon icon-left-circled"></i> <span class="i-name"></span><span class="i-code"></span></div></a>

	<div id = "content">Zmiana hasła
		<form method="post" action="zmien_haslo2.php">
			<div class="dane">
			Stare hasło:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="password" name="haslo_old" /><br />
			<?php
				if (isset($_SESSION['e_haslo_old']))
				{
					echo '<div class="error2">'.$_SESSION['e_haslo_old'].'</div>';
					unset($_SESSION['e_haslo_old']);
				}
			?>		
			</div>
			<div class="dane">
				Nowe hasło:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="password" name="haslo1" /><br />
				<?php
					if (isset($_SESSION['e_haslo']))
					{
						echo '<div class="error2">'.$_SESSION['e_haslo'].'</div>';
						unset($_SESSION['e_haslo']);
					}
				?>		
			</div>
			<div class="dane">
				Powtórz hasło:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="password" name="haslo2" /><br />
			</div>
			<input type="submit" value="Zmień hasło">
		</form>
		<div style="clear:both"></div>
	</div>
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