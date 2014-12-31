function validateForm() {
	if (document.new_email.pwd_reg.value == "" || document.registrazione.pwd_verify_reg.value == "" || document.registrazione.nome_reg.value == "" || document.registrazione.cognome_reg.value == "") {
		return false;
	}
	if (document.registrazione.pwd_reg.value != document.registrazione.pwd_verify_reg.value) {
		return false;
	}
return true;
};