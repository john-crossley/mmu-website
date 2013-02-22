<?php
/**
 * The contact file, this sends all the data to the
 * html email template.
 */
if ( $_POST ) {

	// Check to see if we have all the required fields.
	if ( (isset($_POST['name']) && !empty($_POST['name'])) &&
				(isset($_POST['email']) && !empty($_POST['email'])) &&
				(isset($_POST['message']) && !empty($_POST['message'])) &&
				(isset($_POST['human']) && !empty($_POST['human'])) ) {

		// The only fields we need are name, email, message and human

		// Start to build the HTML email template.
		require_once 'lib/email.php';

		Email::to('john.crossley@stu.mmu.ac.uk', 'John Crossley');
		Email::from($_POST['email'], $_POST['name']);
		Email::subject('John Crossley | MMU Contact Form');
		Email::template('email_templates/email_to_me.html', array(
			'name' => $_POST['name'],
			'message' => $_POST['message'],
			'website' => (empty($_POST['website']) ? 'NOT SUPPLIED!' : $_POST['website']),
			'email' => $_POST['email'],
			'gravatar' => true
		));
		echo Email::view();

		// if ( Email::send() ) {
		// 	// Email has been sent.
		// 	echo "Email has been sent!";
		// } else {
		// 	// Email failed to send.
		// 	if ( Email::countErrors() > 0 ) {
		// 		var_dump( Email::getErrors() );
		// 	}
		// }


		// To send the email call:
		// Email::send(); // Disabled because it does not work on the server.

	} else {
		// Ok the user should not really see this because we have
		// JavaScript validating on the contact.html page. However,
		// the user could have it disabled so then we need this.
		header('Location: contact.html');
		exit;
	}

} else {

	// Uh-oh -- User did not submit any data.
	header('Location: contact.html');
	exit; // Redirect the user back to the contact field.

}