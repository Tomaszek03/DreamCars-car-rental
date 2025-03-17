<?php
    session_start();
	
    $_SESSION['auto'] = $_POST['numer'];
    header('Location: car.php');
    exit();
?>