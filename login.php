<?php
include('lib/admin/config.php');
/*
echo ini_get('display_errors');

if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}

echo ini_get('display_errors');
*/

$user = $_POST['username'];
$pwd = $_POST['password'];

$user_found = 0;
$new_url = "";
$con = mysqli_connect($url_db,$db_user,$db_pwd,$db_name);
if (!$con){
	echo ('Errore di connessione al DB. Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
}
else{
	$result = mysqli_query($con,"SELECT * FROM $user_table");
	
	while($row = mysqli_fetch_array($result)){
		if($row['Username']===$user && rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($row['Password']), MCRYPT_MODE_CBC, md5(md5($key))), "\0")===$pwd && $row['Reset']==0){
				$user_found = 1;
				$tmp_email = $row['Email'];
				$tmp_nome = $row['Nome'];
				$tmp_cognome = $row['Cognome'];
				$tmp_status = 1;
				$tmp_clearance = $row['Clearance'];
		}
	}
	if($user_found==1 && session_start()){
		$_SESSION['email'] = $tmp_email;
		$_SESSION['Nome'] = $tmp_nome;
		$_SESSION['Cognome'] = $tmp_cognome;
		$_SESSION['status'] = $tmp_status;
		$_SESSION['Clearance'] = $tmp_clearance;
		$new_url = "nuova_mail.php";
	}
	mysqli_close($con);
	header("Location: $url_protocollo$new_url");
	exit();
}
?>