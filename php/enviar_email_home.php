<?php
/*
This first bit sets the email address that you want the form to be submitted to.
You will need to change this value to a valid email address that you can access.
*/
$to = "contato@camars.com.br";

/*
This bit sets the URLs of the supporting pages.
If you change the names of any of the pages, you will need to change the values here.
*/
$pg_contato = "/protected/index.html";

$error_page = "/protected/error_message.html";
$thankyou_page = "/protected/thank_you.html";

/*
This next bit loads the form field data into variables.
If you add a form field, you will need to add it here.
*/
$email = $_REQUEST['email'] ;
$mensagem = $_REQUEST['mensagem'] ;
$nome_completo = $_REQUEST['nome_completo'] ;

$corpo_email = 
"Nome Cliente: " . $nome_completo . "\r\n" . 
"Email Cliente: " . $email . "\r\n" . 
"Mensagem: " . $mensagem ;

/*
The following function checks for email injection.
Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
*/
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['email_address'])) {
header( "Location: $pg_contato" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($nome_completo) || empty($email)) {
header( "Location: $error_page" );
}

/* 
If email injection is detected, redirect to the error page.
If you add a form field, you should add it here.
*/
elseif ( isInjected($email) || isInjected($nome_completo)  || isInjected($mensagem) ) {
header( "Location: $error_page" );
}

// If we passed all previous tests, send the email then redirect to the thank you page.
else {

	mail( "$to", "Contato cliente", $corpo_email );

	header( "Location: $thankyou_page" );
}
?>