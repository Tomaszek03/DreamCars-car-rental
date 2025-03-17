<?php

session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    $anuluj = $_POST['do_usuniecia'];
    
    try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{	
				if($sukces)
                {
                    if ($polaczenie->query("DELETE FROM wypozyczenia WHERE id_wyp='$anuluj'"))
				    {
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
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rezerwaację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
?>