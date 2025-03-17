<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: logowanie.php');
		exit();
	}

	if (isset($_POST['email']))
	{
		$wszystko_OK=true;
			
		$imie = $_POST['imie'];
		if (strlen($imie)<1)
		{
			$wszystko_OK=false;
			$_SESSION['e_imie']="Wprowadź imię";
		}
		
		$nazwisko = $_POST['nazwisko'];
		if (strlen($nazwisko)<1)
		{
			$wszystko_OK=false;
			$_SESSION['e_nazwisko']="Wprowadź nazwisko";
		}
		
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail";
		}
		
		$telefon = $_POST['telefon'];
		if (strlen($telefon)<1)
		{
			$wszystko_OK=false;
			$_SESSION['e_telefon']="Podaj poprawny numer telefonu";
		}

	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
		
		try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$rezultat = $polaczenie->query("SELECT user_id FROM users WHERE email='$email'");
				
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0 && $_POST['email']!=$_SESSION['email'])
				{
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail";
				}		

				if ($wszystko_OK==true)
				{
					$new_imie = $_POST['imie'];
					$new_nazwisko = $_POST['nazwisko'];
					$new_email = $_POST['email'];
					$new_telefon = $_POST['telefon'];
					$id = $_SESSION['user_id'];
					
					if ($polaczenie->query("UPDATE users SET imie='$new_imie', nazwisko='$new_nazwisko', email='$new_email', telefon='$new_telefon' WHERE user_id='$id';" ))
					{
						$_SESSION['imie'] = $new_imie;
						$_SESSION['nazwisko'] = $new_nazwisko;
						$_SESSION['email'] = $new_email;
						$_SESSION['telefon'] = $new_telefon;
						header('Location: mojekonto.php');
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				}
				
				$polaczenie->close();
			}
			
		}
		catch(Exception $e)
		{
			echo '<br />Błąd: '.$e;
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
    <link rel="stylesheet" href="css/style_reg.css" type="text/css"/>
	<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Playpen+Sans:wght@700&family=Questrial&display=swap" rel="stylesheet">
	<title>DreamCars - Ustawienia</title>
</head>

<body>
<div id="container">
    <div class="start"> 
        <span class = "logo"><a href="index.php">DreamCars</a></span>
        <a href='logout.php'><div class="sign"> Wyloguj</div></a>
	</div>
    
    <a href="mojekonto.php"><div class="back"><i class="demo-icon icon-left-circled"></i> <span class="i-name"></span><span class="i-code"></span></div></a>

	<div id = "content">Zmiana danych
    <form method = "post">
	<div class="dane">
	Imię:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" value='<?php echo $_SESSION["imie"]?>' name="imie" /><br />
	<?php
		if (isset($_SESSION['e_imie']))
		{
			echo '<div class="error2">'.$_SESSION['e_imie'].'</div>';
			unset($_SESSION['e_imie']);
		}
	?>
	</div>

	<div class="dane">
	Nazwisko:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" value='<?php echo $_SESSION["nazwisko"]?>' name="nazwisko" /><br />
	<?php
		if (isset($_SESSION['e_nazwisko']))
		{
			echo '<div class="error2">'.$_SESSION['e_nazwisko'].'</div>';
			unset($_SESSION['e_nazwisko']);
		}
	?>
	</div>

	<div class="dane">
	E-mail:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" value='<?php echo $_SESSION["email"]?>' name="email" /><br />
	<?php
		if (isset($_SESSION['e_email']))
		{
			echo '<div class="error2">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
		}
	?>
	</div>

	<div class="dane">
	Numer telefonu:&nbsp&nbsp&nbsp<input type="tel" value='<?php echo $_SESSION["telefon"]?>' name="telefon" /><br />
	<?php
		if (isset($_SESSION['e_telefon']))
		{
			echo '<div class="error2">'.$_SESSION['e_telefon'].'</div>';
			unset($_SESSION['e_telefon']);
		}
	?>
	<div style="margin-bottom: 30px;"></div>
	</div>
	<a href="zmien_haslo.php"><div class="zmien_haslo">Zmień hasło</div></a>
    <input type="submit" value="Zapisz" />
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