<?php
session_start();
if($_SESSION['status'] == 1){
	include('lib/admin/config.php');
	$con = mysqli_connect($url_db,$db_user,$db_pwd,$db_name);
	if (!$con){
		echo ("Errore di connessione al DB");
	}
	else{
		$NomeCognome = $_SESSION['Nome']." ".$_SESSION['Cognome'];
		$message = nl2br($_POST['message']);
		$values = "(NULL,'$_POST[from]', '$_POST[to]', '$_POST[cc]', '$_POST[ccn]', '$_POST[subject]', '$message', '','$NomeCognome')";
		$sql = "INSERT INTO $email_out_table $email_out_col VALUES $values";
		if (!mysqli_query($con,$sql))
			echo ("Errore nell'inserimento dei dati nel DB");
		else
			echo ("OK");

		if(count($_FILES["attachment"]["name"])) {
			$id_mail = mysqli_insert_id($con);
			$folder_upload = ".".$folder_url.$id_mail."/";
			if(!is_dir($folder_upload)){
				if(!mkdir($folder_upload, 0755))
					echo "Errore durante la creazione della cartella";
				else{
					for($i=0;$i<count($_FILES["attachment"]["name"]);$i++){
						if ($_FILES["attachment"]["error"][$i] > 0)
							echo "Return Code: " . $_FILES["attachment"]["error"][$i] . "<br />";
						else{
							if (!file_exists($folder_upload.$_FILES["attachment"]["name"][$i])){
								move_uploaded_file($_FILES["attachment"]["tmp_name"][$i],$folder_upload."/".$_FILES["attachment"]["name"][$i]);
								$attachments_url .="http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$id_mail."/".$_FILES["attachment"]["name"][$i].$scape_char;
							}
						}
					}
				}
			}
			else{
				for($i=0;$i<count($_FILES["attachment"]["name"]);$i++) {
					if ($_FILES["attachment"]["error"][$i] > 0)
						echo "Return Code: " . $_FILES["attachment"]["error"][$i] . "<br />";
					else{
						if (!file_exists($folder_upload.$_FILES["attachment"]["name"][$i])){
							move_uploaded_file($_FILES["attachment"]["tmp_name"][$i],$folder_upload."/".$_FILES["attachment"]["name"][$i]);
							$attachments_url .="http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$id_mail."/".$_FILES["attachment"]["name"][$i].$scape_char;
						}
					}
				}
			}
		$attachments_url = substr($attachments_url, 0,strlen($attachments_url)-strlen($scape_char));
		$update = "UPDATE $email_out_table SET Allegati='$attachments_url' WHERE ID_mail_out='$id_mail'";
		if (!mysqli_query($con,$update))
			echo ("Errore nell'inserimento dei dati nel DB");
		else
			echo ("OK");
		}
	}
	mysqli_close($con);
	header('Location: '.$url_protocollo.'nuova_mail.php');
	exit();
} else {
	session_destroy();
	header("Location: $url_protocollo");
	exit();
}?>