<?php

include('lib/admin/config.php');
$array_pwd_user = '(';
foreach($email_out_pwd as $account_email=>$account_password){
	echo rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($account_password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	echo "<br>";
	$secure_password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $account_password, MCRYPT_MODE_CBC, md5(md5($key))));
	$array_pwd_user .= '"'.$account_email.'"=>"'.$secure_password.'",';
	echo rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($secure_password), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	echo "<br>";
}
echo "<br>";
echo $array_pwd_user;
echo "<br>";
echo date('d/m/Y H:i:s');


?>