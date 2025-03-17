<?php
    session_start();
	
    if (!isset($_SESSION['zalogowany']))
    {
      header('Location: logowanie.php');
      exit();
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
	
    $sukces = true;

	$osoba = $_SESSION['user_id'];
	$samochod = $_SESSION['auto'];

    $dni = $_POST['dni'];
	$data = $_POST['data'];

	$aktualna = new DateTime();

	try 
	{
		$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
		if ($polaczenie->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{	
			if($data<$aktualna->format('Y-m-d'))
			{
				$sukces = false;
				$_SESSION['e_data']="<div class='error4'>Podana data już minęła</div>";
				header('Location: car.php');
			}

			if($dni<=0)
			{
				$sukces = false;
				$_SESSION['e_dni']="Wprowadź poprawną liczbę dni";
				header('Location: car.php');
			}
			
			$sql = "SELECT * FROM `wypozyczenia` WHERE id_sam='$samochod' AND poczatek>='$data' && koniec <= '$data'+INTERVAL '$dni' DAY";
			if($rezultat = @$polaczenie->query($sql))
			{
				$ile=$rezultat->num_rows;
				if($ile>0)
				{
					$sukces = false;
					$_SESSION['e_data']="<div class='error5'>Istnieje już rezerwacja w tym terminie</div>";
					header('Location: car.php');
					$rezultat->free_result();
				}		
			}

			$sql2 = "SELECT * FROM `wypozyczenia` WHERE id_sam='$samochod' AND '$data'+INTERVAL '$dni' DAY > poczatek && '$data'+INTERVAL '$dni' DAY < koniec";
			if($rezultat2 = @$polaczenie->query($sql2))
			{
				$ile2=$rezultat2->num_rows;
				if($ile2>0)
				{
					$sukces = false;
					$_SESSION['e_data']="<div class='error5'>Istnieje już rezerwacja w tym terminie</div>";
					header('Location: car.php');
					$rezultat2->free_result();
				}		
			}

			$sql3 = "SELECT * FROM `wypozyczenia` WHERE id_sam='$samochod' AND '$data' > poczatek && '$data' < koniec";
			if($rezultat3 = @$polaczenie->query($sql3))
			{
				$ile3=$rezultat3->num_rows;
				if($ile3>0)
				{
					$sukces = false;
					$_SESSION['e_data']="<div class='error5'>Istnieje już rezerwacja w tym terminie</div>";
					header('Location: car.php');
					$rezultat3->free_result();
				}		
			}

			if($sukces)
			{
				if ($polaczenie->query("INSERT INTO wypozyczenia VALUES (NULL, '$osoba', '$samochod', now(), '$data', '$data' + INTERVAL '$dni' DAY)"))
				{
					$_SESSION['wypozyczono']=true;
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