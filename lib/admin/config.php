<?php
$url_db = "";
$db_name = "";
$db_user = "";
$db_pwd = "";
$user_table = "users";
$email_out_table = "email_out";
/* $url_protocollo = "http://localhost/~guglio/protocollo_email/"; */
$url_protocollo = "";
$user_col = "(ID, Nome, Cognome, Username, Password, Email)";
$email_out_col = "(ID_mail_out,Da, A, CC, CCN, Oggetto, Messaggio, Allegati, NomeCognome)";
$key = "";
$nomi_utenti = array("name"=>"surname");
$email_da = array("name surname"=>"email@something.com","name surname "=>array("email_1@something.com", "email_1@something.com"));
$folder_url = "/uploaded_files/";
$attachments_url = "";
$scape_char = "@;@";
$email_out_pwd = array("email@something.com"=>"key","email_1@something.com"=>"key_1");
$contatti_aziende = array("Company name"=>array("Name"=>"...","Address"=>"...","Phone"=>"...","Web"=>"url"));
?>
