<?php
include('lib/admin/config.php');
$recovery_fname = strtolower($_POST['rec_nome']);
$recovery_lname = strtolower($_POST['rec_cognome']);
$recovery_username = $_POST['rec_username'];
$user_found = 0;
$new_url = '';
$con = mysqli_connect($url_db,$db_user,$db_pwd,$db_name);
if (!$con){
	echo ("Errore di connessione al DB");
}
else{
	$result = mysqli_query($con,"SELECT * FROM $user_table");
	while($row = mysqli_fetch_array($result)){
		if($row['Nome']===$recovery_fname && $row['Cognome']===$recovery_lname && $row['Username']===$recovery_username){
			$new_pwd = "";
			$pwd_reset = 1;
			$user_found = 1;
		}
	}
	if($user_found){
		$sql = "UPDATE $user_table SET Password='',Username='', Reset='$pwd_reset' WHERE Nome='$recovery_fname' AND Cognome='$recovery_lname'";
		if (!mysqli_query($con,$sql))
			echo ("Errore nell'inserimento dei dati nel DB");
		else
			$new_url = "#register";
	}
	mysqli_close($con);
	header("Location: http://www.ciamcostruzioni.it/protocollo_email/$new_url");
	exit();
}
?>