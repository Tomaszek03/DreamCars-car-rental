<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: logowanie.php');
		exit();
	}

	    $wszystko_OK = true;
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków";
            header('Location: zmien_haslo.php');
		}
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne";
            header('Location: zmien_haslo.php');
		}	

		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
		//echo $haslo_hash;

		try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$id = $_SESSION['user_id'];
				$sql="SELECT haslo FROM users WHERE user_id='$id'";
				if($rezultat = @$polaczenie->query($sql))
       			{
            		$ile=$rezultat->num_rows;
            		if($ile>0)
            		{
                		$wiersz=$rezultat->fetch_assoc();

                        if (password_verify($_POST['haslo1'], $wiersz['haslo']))
                        {
                            $wszystko_OK=false;
			                $_SESSION['e_haslo']="Nowe hasło jest takie samo jak poprzednie";
                            header('Location: zmien_haslo.php');
                        }
						if (!password_verify($_POST['haslo_old'], $wiersz['haslo']))
						{
							$wszystko_OK=false;
							$_SESSION['e_haslo_old']="Niepoprawne stare hasło";
                            header('Location: zmien_haslo.php');
						}
                		$rezultat->free_result();
            		}	
        		}
        
				if ($wszystko_OK==true)
				{
					$new_haslo = $_POST['haslo1'];
					
					if ($polaczenie->query("UPDATE users SET haslo='$haslo_hash' WHERE user_id='$id';" ))
					{
						header('Location: ustawienia.php');
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

?>