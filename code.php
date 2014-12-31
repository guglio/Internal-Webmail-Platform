<?php
$code = $_POST["code"];
$decode = $_POST["decode"];
include('lib/admin/config.php');
if(!empty($code)){
	$coded_word = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $code, MCRYPT_MODE_CBC, md5(md5($key))));
	echo "La codificata di ".$code." -> ".$coded_word."<br>";
}
if(!empty($decode)){
	$decoded_word = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($decode), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	echo "La decodifica di ".$decode." -> ".$decoded_word;
}
?>