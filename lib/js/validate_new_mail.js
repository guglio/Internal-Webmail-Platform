function validate_new_mail() {	
	$('#popupErrorContent').empty();
	var error_msg = "";
	if(document.new_email.to.value==""){
		error_msg += "<p>Campo "+$('label[for="' + document.new_email.to.id + '"]').html()+" mancante</p>";
	}
	if(!validateEmail(document.new_email.to.value)){
		error_msg += "<p>Campo "+$('label[for="' + document.new_email.to.id + '"]').html()+" non valido</p>";
	}
	if(document.new_email.subject.value==""){
		error_msg += "<p>Campo "+$('label[for="' + document.new_email.subject.id + '"]').html()+" mancante</p>";
	}
	if(document.new_email.message.value==""){
		error_msg += "<p>Campo "+$('label[for="' + document.new_email.message.id + '"]').html()+" mancante</p>";
	}
	if(document.new_email.cc.value!="" && !validateEmail(document.new_email.cc.value)){
		error_msg += "<p>Campo "+$('label[for="' + document.new_email.cc.id + '"]').html()+" non valido</p>";
	}
	if(document.new_email.ccn.value!="" && !validateEmail(document.new_email.ccn.value)){
		error_msg += "<p>Campo "+$('label[for="' + document.new_email.ccn.id + '"]').html()+" non valido</p>";
	}
	if(error_msg){
		$('#popupErrorContent').append(error_msg);
		$('#error_popup').trigger('click');	
		console.log(error_msg);
		return false;
	}
	else{
		console.log(error_msg);
		return true;
	}
};
function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if( !emailReg.test( $email ))
    return false;
  else
    return true;
};