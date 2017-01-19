# Internal Webmail Platform

I create this platform to manage email linked to our Company domain and also other external domains. The need for this platform is because we need to hold outgoing emails, until they got the authorization to be sent.
I base the UI on jQuery mobile, to create a more mobile / desktop friendly environment.
This platform fetch from the remote server for new emails, saves them on MySQL database, and than the user (if he has the credentials) see the new email(s).
When a user send an email, before it is sent, the local admin has to authorize the email, until then, it's in waiting status.

## Getting started

### Local installation

1. install a local web-server
2. clone or download this project repo []()
3. launch the web-server
4. open browser and enter `http://localhost/`

### Configurations specs

The configuration file is located in *lib/admin/config.php*
The file structure is the following:
```php
$url_db = "";
$db_name = "";
$db_user = "";
$db_pwd = "";
$user_table = "users";
$email_out_table = "email_out";
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
```
`$url_db` -> url of the database

`$db_name` -> name of the database

`$db_user` -> user to log into the database

`$db_pwd` -> password to log in

`$user_table` -> table with the user's list ("users")

`$email_out_table` -> table with the emails sent ("email_out")

`$url_protocollo` -> absolute path of the platform

`$user_col` -> list of the `$user_table` column ("(ID, Nome, Cognome, Username, Password, Email)")

`$email_out_col` -> list of the `$email_out_table` column ("(ID_mail_out,Da, A, CC, CCN, Oggetto, Messaggio, Allegati, NomeCognome)")

`$key` -> master key to decode the password

`$nomi_utenti` -> array with the name and surnames of the users (array("name"=>"surname"))

`$email_da` -> array of the email(s) that users are authorize to use.
If the user has:
- single email: `"name surname"=>"email@something.com"`
- multiple emails: `"name surname "=>array("email_1@something.com", "email_1@something.com")`

`$folder_url` -> folder path for the attachments ("/uploaded_files/")

`$email_out_pwd` -> array with the emails and their corresponding password encoded ("email@something.com"=>"key")

`$contatti_aziende` -> array with the company contact informations  ("Company name"=>array("Name"=>"...","Address"=>"...","Phone"=>"...","Web"=>"url"))


## Built with

* PHP
* HTML
* CSS
* [jQuery](https://jquery.com/)
* [PHPMailer](http://phpmailer.github.io/PHPMailer/)

## Versioning

I use Git for versioning.

## Author

**Guglielmo Turco** - [guglio](https://github.com/guglio)
