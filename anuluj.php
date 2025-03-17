<?php

session_start();
	
    if (!isset($_SESSION['zalogowany']))
    {
     header('Location: logowanie.php');
     exit();
    }
	require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    $anuluj = $_POST['do_usuniecia'];
    echo $anuluj;
    try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{	
                if ($polaczenie->query("DELETE FROM wypozyczenia WHERE id_wyp='$anuluj'"))
				{
					header('Location: mojekonto.php');
				}
				else
				{
				    throw new Exception($polaczenie->error);
				}
				$polaczenie->close();
			}
		}
		catch(Exception $e)
		{
			echo '<br />Error: '.$e;
		}
?>