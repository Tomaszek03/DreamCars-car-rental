<?php

	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
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
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	
		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM users WHERE email='%s'",
		mysqli_real_escape_string($polaczenie,$login))))
		{
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{
				$wiersz = $rezultat->fetch_assoc();
				
				if (password_verify($haslo, $wiersz['haslo']))
				{
				$_SESSION['zalogowany'] = true;
				$_SESSION['user_id'] = $wiersz['user_id'];
				$_SESSION['imie'] = $wiersz['imie'];
				$_SESSION['nazwisko'] = $wiersz['nazwisko'];
				$_SESSION['email'] = $wiersz['email'];
				$_SESSION['telefon'] = $wiersz['telefon'];
				
				unset($_SESSION['blad']);
				$rezultat->free_result();
				header('Location: index.php');
				}
				else
				{
					$_SESSION['blad'] = '<div class="error">Nieprawidłowy login lub hasło</div>';
				header('Location: logowanie.php');
				}
				
			} else {
				
				$_SESSION['blad'] = '<div class="error">Nieprawidłowy login lub hasło</div>';
				header('Location: logowanie.php');
				
			}
			
		}
		
		$polaczenie->close();
	}
	
?>