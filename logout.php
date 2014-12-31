<?php
session_start();
include('lib/admin/config.php');
if(!session_destroy()){
	session_unset();
	$_SESSION['status'] = 0;
}
header("Location: $url_protocollo");
exit();
?>