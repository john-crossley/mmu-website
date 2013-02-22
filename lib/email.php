<?php
/**
 * Email class
 * This email class simply sends mail. You have the option
 * to pass in an HTML email template and have PHP parse it.
 */
class Email {
	/**
	 * Who is this email from?
	 * @var string
	 */
	public static $from = '';
	/**
	 * Who is this email going to?
	 * @var string
	 */
	public static $to = '';
	/**
	 * Whats is the subject of this email?
	 * @var string
	 */
	public static $subject = '';
	/**
	 * Gets the users Gravatar if one exists.
	 * @var string
	 */
	public static $gravatar = '';
	/**
	 * What is the body of the email?
	 * @var string
	 */
	public static $message = '';
	/**
	 * A list of errors.
	 * @var array
	 */
	public static $error = array();
	/**
	 * Validates an email address using PHP's built in email
	 * validation function.
	 * @param  string $email 'john.crossley@stu.mmu.ac.uk'
	 * @return bool 					false=invalid; true=valid
	 */
	protected static function isValidEmail($email)
	{
		// PHPs built in email validation.
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false; // Uh-oh invalid email address
		}
		return true; // Valid email address.
	}
	/**
	 * Gets a list of errors, if any exist.
	 * @return mixed True on success; NULL on false.
	 */
	public static function getErrors()
	{
		// If errors return the static property
		if ( !empty(self::$error) )
			return self::$error;
	}
	/**
	 * Returns a count of the errors.
	 * @return int Error count.
	 */
	public static function countErrors()
	{
		// Return the error count.
		return count(self::$error);
	}
	/**
	 * The to function, allows the user to add
	 * a receivers email address.
	 * @param  string $to   Whos it going to?
	 * @param  string $name Your name 'John Crossley'
	 * @return NULL
	 */
	public static function to($to, $name = NULL)
	{
		$tmp = ''; // Get ready to build the email string.
		// Validate the email address
		if ( !static::isValidEmail($to) ) {
			self::$error[] = 'Invalid receiver email supplied: ' . $to;
			return false;
		}
		if ( !is_null($name) ) {
			$tmp = strip_tags($name); // Quick and dirty clean.
			$tmp = $tmp . ' <' . $to . '>';
		} else $tmp = $to;
		// Assign the $to to the class.
		self::$to = $tmp;
	}
	/**
	 * The from function, allows the user to add
	 * a senders email address.
	 * @param  string $from Whos this email from?
	 * @param  string $name Their name? 'John Doe'
	 * @return NULL
	 */
	public static function from($from, $name = NULL)
	{
		$tmp = ''; // Get ready to build the email string.
		// Validate the email address
		if ( !static::isValidEmail($from) ) {
			self::$error[] = 'Invalid sender email supplied: ' . $from;
			return false;
		}
		// MD4 the gravatar email because this is how they require it.
		self::$gravatar = md5($from);
		// Did the user have a name? If so format it correctly.
		if ( !is_null($name) ) {
			$tmp = strip_tags($name); // Quick and dirty clean.
			$tmp = $tmp . ' <' . $from . '>';
		} else $tmp = $from;
		// Assign the $from to the class.
		self::$from = $tmp;
	}
	/**
	 * The email subject.
	 * @param  string $subject Whats the subject of the email?
	 * @return NULL
	 */
	public static function subject($subject)
	{
		// Set the subject.
		self::$subject = strip_tags($subject);
	}
	/**
	 * The message.
	 * @param  string $message The message of the email.
	 */
	public static function message($message)
	{
		// Set the message.
		self::$message = strip_tags($message);
	}
	/**
	 * Clears all of the values from the object.
	 * @return NULL
	 */
	public static function clear()
	{
		// Clear the data should we wish to send multiple emails.
		self::$to = "";
		self::$from = "";
		self::$subject = "";
		self::$message = "";
	}
	/**
	 * The email templates you would like to use.
	 * @param  string $file The location of the file.
	 * @param  array $data The data for the template eg. ['username' => $username]
	 * @return NULL
	 */
	public static function template($file, $data)
	{
		// Assign some data.
		$data['subject'] = self::$subject;
		$data['email'] = self::$from;

		// Fetch the gravatar image if the user has one.
		if ( isset($data['gravatar']) && $data['gravatar'] === true ) {
			$data['gravatar'] = "http://www.gravatar.com/avatar/" . self::$gravatar;
		}

		// Check to see if the file exits, (user has supplied correct path)
		if ( file_exists($file) ) {
			// Get the file contents.
			$contents = file_get_contents($file);
			// Loop through and replace all of the data.
			foreach ($data as $key => $value) {
				// Use regex function and replace the keys we assigned in the array.
				$contents = preg_replace("/{{{$key}}}/", $value, $contents);
			}
			// Set the message with the new html contents.
			self::$message = $contents;
		}
	}
	/**
	 * Shows the generated content, useful for debugging.
	 * @return string Returns the compiled email.
	 */
	public static function view()
	{
		// We can't send emails on uni server to rather than sending
		// it to an email account just show the template that we built.
		$content = self::$message;
		if ( !empty($content) ) {
			return $content;
		}
	}

	/**
	 * Send the email.
	 * @return bool True on success, false on failure.
	 */
	public static function send()
	{
		// If we can send the email
		$headers = 'MIME-Version: 1.0' . "\r\n" .
				'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
				'From:' . self::$from . "\r\n" .
				'Reply-To:' . self::$from . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
		if ( mail( self::$to, self::$subject, self::$message, $headers ) ) return true;
	}

}