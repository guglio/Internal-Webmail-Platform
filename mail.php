<?php
session_start();
require 'lib/php-mail/PHPMailerAutoload.php';
require 'lib/admin/config.php';
$flag_cancel = 0;

$identificativo_email = implode(",", $_POST['invia_selected']);
$autorizzata_da = ucfirst($_SESSION['Nome'])." ".ucfirst($_SESSION['Cognome']);
if(isset($_POST['cancel_selected'])){
	$email_da_cancellare = implode(",", $_POST['cancel_selected']);
	$flag_cancel = 1;
}

$con = mysqli_connect($url_db,$db_user,$db_pwd,$db_name);
if (!$con){
	echo ("Errore di connessione al DB");
}
else{
	$result = mysqli_query($con,"SELECT * FROM $email_out_table WHERE ID_mail_out IN ($identificativo_email)");
	while($row = mysqli_fetch_array($result)){
		$email_password = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($email_out_pwd[$row['Da']]), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
		switch($row['Da']){
			case "ciam.contabilita@gmail.com":
				$smtp_email = "smtp.gmail.com";
				$authentication_encryption = "ssl";
				$ditta = "CIAM";
				$port = 465;
				break;
			case "info@ciamcostruzioni.it":
				$smtp_email = "smtp.ciamcostruzioni.it";
				$ditta = "CIAM";
				$port = 25;
				break;
			case "info@officine-sicam.it":
				$smtp_email = "smtp.officine-sicam.it";
				$ditta = "SICAM";
				$port = 25;
				break;
			case "amministrazione.ciam@pec.it":
				$ditta = "CIAM";
				$authentication_encryption = "ssl";
				$smtp_email = "smtps.pec.aruba.it";
				$port = 465;
				break;
			case "amministrazione@pec.officine-sicam.it":
				$ditta = "SICAM";
				$authentication_encryption = "ssl";
				$smtp_email = "smtps.pec.aruba.it";
				$port = 465;
				break;
		}
		
		$mail = new PHPMailer();
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = $smtp_email;  // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = $row['Da'];                            // SMTP username
		$mail->Password = $email_password;                           // SMTP password
		$mail->SMTPSecure = $authentication_encryption;                            // Enable encryption, 'ssl' also accepted
		$mail->Port = $port;
		
		$mail->From = $row['Da'];
		$mail->FromName = ucfirst($row['NomeCognome']);
		$mail->addAddress($row['A']);               // Name is optional
		$mail->addReplyTo($row['Da'],ucfirst($row['NomeCognome']));
		$mail->addCC($row['CC']);
		$mail->addBCC($row['BCC']);
		
		//$mail->WordWrap = 50;                                // Set word wrap to 50 characters
		if(!empty($row['Allegati'])){
			$allegato = explode($scape_char, $row['Allegati']);
		  	for($i=0;$i<count($allegato);$i++){			  	
			  	$allegato_name = substr($allegato[$i],strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/"),strlen($allegato[$i])-strlen("http://www.ciamcostruzioni.it/protocollo_email".$folder_url.$row['ID_mail_out']."/"));
			  	$allegato_url = "uploaded_files/".$row['ID_mail_out']."/".$allegato_name;
			  	$mail->addAttachment($allegato_url,$allegato_name);
			  }
		}
		$messaggio_HTML = "<!DOCTYPE html>
<html lang=\"it\">
<head>
  <meta charset=\"utf-8\">
	<title>".$row['Oggetto']."</title>
	<style>
	footer p{
		line-height: 1.2em;
	}
	footer h4{
		margin-bottom: 0;
	}
	footer h4+p{
		margin-top: 0;
	}
	</style>
</head>
<body>
<div id=\"mail_body\">".$row['Messaggio']."</div>
<footer>
<p>--<br>
".ucfirst($row['NomeCognome'])."</p>
<p>".$contatti_aziende[$ditta]["Nome"]."</p>
<p>".$contatti_aziende[$ditta]["Indirizzo"]."</p>
<p>".$row['Da']."<br>
tel.  ".$contatti_aziende[$ditta]["Telefono"]."<br>
fax  ".$contatti_aziende[$ditta]["Fax"]."<br>
web: ".$contatti_aziende[$ditta]["Web"]."</p>
<h4>CONFIDENTIALITY</h4>
<p>This e-mail and any attachments are confidential and may also be privileged. If you are the intended recipient of this message you should not disclose or distribute this message to third parties without the consent of our company. If you are not the named recipient, please notify the sender immediately and do not disclose the contents to another person, use it for any purpose, or store or copy the information in any medium.</p>
<h4>PRIVACY</h4>
<p>Le informazioni contenute in questo messaggio di posta elettronica e/o nel/i file/s allegato/i, sono da considerarsi strettamente riservate. Il loro utilizzo &egrave; consentito esclusivamente al destinatario del messaggio, per le finalit&agrave; indicate nel messaggio stesso. Qualora riceveste questo messaggio senza esserne il destinatario, Vi preghiamo cortesemente di darcene notizia via e-mail e procedere alla distruzione del messaggio stesso, cancellandolo dal Vostro sistema; costituisce comportamento contrario ai principi dettati dal Decreto Lgs. 196/2003 il trattenere il messaggio stesso, divulgarlo anche in parte, distribuirlo ad altri soggetti, copiarlo, od utilizzarlo per finalit&agrave; diverse.</p>
</footer>
</body>
</html>";
		$messaggio_normal =  $row['Messaggio']."\n\n--\n".ucfirst($row['NomeCognome'])."\n".$contatti_aziende[$ditta]["Nome"]."\n".
		$contatti_aziende[$ditta]["Indirizzo"]."\n".$row['Da']."\n tel ".$contatti_aziende[$ditta]["Telefono"]."\n fax ".$contatti_aziende[$ditta]["Fax"]."web: ".$contatti_aziende[$ditta]["Web"]."\n CONFIDENTIALITY \n
		This e-mail and any attachments are confidential and may also be privileged. If you are the intended recipient of this message you should not disclose or distribute this message to third parties without the consent of our company. If you are not the named recipient, please notify the sender immediately and do not disclose the contents to another person, use it for any purpose, or store or copy the information in any medium.\n
		PRIVACY \n
		Le informazioni contenute in questo messaggio di posta elettronica e/o nel/i file/s allegato/i, sono da considerarsi strettamente riservate. Il loro utilizzo è consentito esclusivamente al destinatario del messaggio, per le finalità indicate nel messaggio stesso. Qualora riceveste questo messaggio senza esserne il destinatario, Vi preghiamo cortesemente di darcene notizia via e-mail e procedere alla distruzione del messaggio stesso, cancellandolo dal Vostro sistema; costituisce comportamento contrario ai principi dettati dal Decreto Lgs. 196/2003 il trattenere il messaggio stesso, divulgarlo anche in parte, distribuirlo ad altri soggetti, copiarlo, od utilizzarlo per finalità diverse.";
		// Optional name
		//$mail->isHTML(true);                                  // Set email format to HTML
		
		$mail->Subject = $row['Oggetto'];
		$mail->Body    = $messaggio_HTML;
		$mail->AltBody = $messaggio_normal;
		
		if(!$mail->send()) {
		   echo 'Message could not be sent.';
		   echo 'Mailer Error: ' . $mail->ErrorInfo;
		   exit;
		}
		else{
			mysqli_query($con,"UPDATE $email_out_table SET Status='1', Autorizzata_il=now(), Autorizzata_da='$autorizzata_da' WHERE ID_mail_out IN ($identificativo_email)");
		}
	}
	$update = "UPDATE $email_out_table SET Trash=1, Autorizzata_il=now(), Autorizzata_da='$autorizzata_da' WHERE ID_mail_out IN ($email_da_cancellare)";
	if (!mysqli_query($con,$update))
			echo ("Errore nell'inserimento dei dati nel DB");
		else
			echo ("OK");
		}
    mysqli_close($con);
	header('Location: '.$url_protocollo.'nuova_mail.php#email-out');
	exit();
?>