<?php
include('lib/admin/config.php');
$nome = strtolower($_POST['nome_reg']);
$cognome = strtolower($_POST['cognome_reg']);
$username = $_POST['username_reg'];
$password = $_POST['pwd_reg'];
$user_correct = 0;
$user_found = 0;
$secure_password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $password, MCRYPT_MODE_CBC, md5(md5($key))));
foreach($nomi_utenti as $fname=>$lname){
	if($fname===$nome && $lname===$cognome){
		$user_correct = 1;
		foreach($email_da as $utente_nome => $email){
			if($utente_nome === $nome." ".$cognome){
				if(is_array($email)){
					$mail_from = '';
					foreach($email as $i_email)
						$mail_from .= $i_email.",";
					$mail_from = rtrim($mail_from, ",");
				}
				else{
					$mail_from = $email;					
				}
			}
		}
	}
}

if($user_correct){		
	$con = mysqli_connect($url_db,$db_user,$db_pwd,$db_name);
	if (!$con){
		echo ("Errore di connessione al DB");
	}
	else{
		$result = mysqli_query($con,"SELECT Nome,Cognome,Reset FROM $user_table");
		while($row = mysqli_fetch_array($result)){
			if($row['Nome']===$nome && $row['Cognome']===$cognome){
				$user_found = 1;
				$pwd_change = $row['Reset'];
				
			}
		}
		if($user_found && $pwd_change){
			$sql = "UPDATE $user_table SET Username='$username', Password='$secure_password', Reset='0', Email='$mail_from' WHERE Nome='$nome' AND Cognome='$cognome'";	
		}
		if(!$user_found){
			$sql = "INSERT INTO $user_table $user_col VALUES ('NULL', '$nome', '$cognome', '$username', '$secure_password','$mail_from')";
		}
		if (!mysqli_query($con,$sql))
			echo ("Errore nell'inserimento dei dati nel DB");
		else
			$new_url = "";
	}
	mysqli_close($con);
	header("Location: $url_protocollo$new_url");
	exit();
}
?>