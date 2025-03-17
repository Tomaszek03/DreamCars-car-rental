<?php

	session_start();
	
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

		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków";
		}
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne";
		}	

		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
		//echo $haslo_hash;
		
		if (!isset($_POST['regulamin']))
		{
			$wszystko_OK=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}				
		
		$_SESSION['fr_imie'] = $imie;
		$_SESSION['fr_nazwisko'] = $nazwisko;
		$_SESSION['fr_telefon'] = $telefon;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_haslo1'] = $haslo1;
		$_SESSION['fr_haslo2'] = $haslo2;
		if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;
		
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
				if($ile_takich_maili>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail";
				}		

				if ($wszystko_OK==true)
				{
					if ($polaczenie->query("INSERT INTO users VALUES (NULL, '$imie', '$nazwisko ', '$email', '$telefon', '$haslo_hash')"))
					{
						$_SESSION['udanarejestracja']=true;
						header('Location: logowanie.php');
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
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
	}
	
	
?>


<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>DreamCars - rejestracja</title>
	<link rel="stylesheet" href="css/style_main.css" type="text/css"/>
	<link rel="stylesheet" href="css/style_reg.css" type="text/css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Playpen+Sans:wght@700&family=Questrial&display=swap" rel="stylesheet">
</head>

<body>
<div id="container">
	<div class="start"> 
        <span class = "logo"><a href="index.php">DreamCars</a></span>
    </div>
	<div id="content">
		<span id = "rej">Rejestracja</span>
		<div style="margin-bottom: 30px"></div>
		<div class = "reg">
	
	
	<form method="post">
	
	<div class="dane">
	Imię:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" value="<?php
		if (isset($_SESSION['fr_imie']))
		{
			echo $_SESSION['fr_imie'];
			unset($_SESSION['fr_imie']);
		}
	?>" name="imie" /><br />
	<?php
		if (isset($_SESSION['e_imie']))
		{
			echo '<div class="error2">'.$_SESSION['e_imie'].'</div>';
			unset($_SESSION['e_imie']);
		}
	?>
	</div>

	<div class="dane">
	Nazwisko:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" value="<?php
		if (isset($_SESSION['fr_nazwisko']))
		{
			echo $_SESSION['fr_nazwisko'];
			unset($_SESSION['fr_nazwisko']);
		}
	?>" name="nazwisko" /><br />
	<?php
		if (isset($_SESSION['e_nazwisko']))
		{
			echo '<div class="error2">'.$_SESSION['e_nazwisko'].'</div>';
			unset($_SESSION['e_nazwisko']);
		}
	?>
	</div>

	<div class="dane">
	E-mail:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="text" value="<?php
		if (isset($_SESSION['fr_email']))
		{
			echo $_SESSION['fr_email'];
			unset($_SESSION['fr_email']);
		}
	?>" name="email" /><br />
	<?php
		if (isset($_SESSION['e_email']))
		{
			echo '<div class="error2">'.$_SESSION['e_email'].'</div>';
			unset($_SESSION['e_email']);
		}
	?>
	</div>

	<div class="dane">
	Numer telefonu:&nbsp&nbsp&nbsp<input type="tel" value="<?php
		if (isset($_SESSION['fr_telefon']))
		{
			echo $_SESSION['fr_telefon'];
			unset($_SESSION['fr_telefon']);
		}
	?>" name="telefon" /><br />
	<?php
		if (isset($_SESSION['e_telefon']))
		{
			echo '<div class="error2">'.$_SESSION['e_telefon'].'</div>';
			unset($_SESSION['e_telefon']);
		}
	?>
	</div>

	<div class="dane">
	Hasło:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="password"  value="<?php
		if (isset($_SESSION['fr_haslo1']))
		{
			echo $_SESSION['fr_haslo1'];
			unset($_SESSION['fr_haslo1']);
		}
	?>" name="haslo1" /><br />
	<?php
		if (isset($_SESSION['e_haslo']))
		{
			echo '<div class="error2">'.$_SESSION['e_haslo'].'</div>';
			unset($_SESSION['e_haslo']);
		}
	?>		
	</div>

	<div class="dane">
	Powtórz hasło:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="password" value="<?php
		if (isset($_SESSION['fr_haslo2']))
		{
			echo $_SESSION['fr_haslo2'];
			unset($_SESSION['fr_haslo2']);
		}
	?>" name="haslo2" /><br />
	</div>

	<div style="margin-top: 30px"></div>

	<div class="dane">
	<label class="label">
		<input type="checkbox" name="regulamin" <?php
		if (isset($_SESSION['fr_regulamin']))
		{
			echo "checked";
			unset($_SESSION['fr_regulamin']);
		}
			?>/> 
	Akceptuję regulamin
	</label>
	<div style="clear: both"></div>
	<?php
		if (isset($_SESSION['e_regulamin']))
		{
			echo '<div class="error2">'.$_SESSION['e_regulamin'].'</div>';
			unset($_SESSION['e_regulamin']);
		}
	?>	
	</div>
	<div style="clear: both"></div>
	</div>
	<br />
	
	<input type="submit" value="Zarejestruj się" />
	
</form>
	</div>
	</div>
	<div class="new">
		Masz już konto? <a href="logowanie.php">Zaloguj się</a>
	</div>
	<div style="margin-top: 200px"></div>
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