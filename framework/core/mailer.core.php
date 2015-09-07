<?php
/**
 * The Mailer class provides an OO interface to create MIME
 * enabled email messages. This class depends on PEAR Mail_mime.
 * NOTE: attachFile() method currently does not support for remote file attachments.
 * 
 * 
 * @category Mail
 * @package Mailer
 * @version 1beta
 * 
 *
 *
 */
class Mailer
{
	
	/**
	 * Delimiter to be used for string replacements
	 *
	 * @var string
	 */
	private $delimiter = "{key}";
	
	/**
	 * Receiver e-mail address
	 * 
	 * @var string
	 */
	private $to;
	
	/**
	 * Receiver e-mail addresses (BCC)
	 * 
	 * @var array
	 */
	private $bcc;
	
	/**
	 * Receiver e-mail addresses (CC)
	 * 
	 * @var array
	 */
	private $cc;
	
	/**
	 * E-mail subject
	 * 
	 * @var string
	 */
	private $subject;
	
	/**
	 * Mail headers
	 * 
	 * @var array
	 */
	private $headers;
	
	/**
	 * Message body
	 * 
	 * @var string
	 */
	private $msg;
	
	/**
	 * E-mail body
	 * 
	 * @var string
	 */
	private $email;
	
	/**
	 * Media attachments
	 * 
	 * @var array
	 */
	private $attachments;
	
	/**
	 * All invalid e-mails
	 * 
	 * @var array
	 */
	private $invalid_email;
	
	/**
	 * Web master's e-mail address
	 * 
	 * @var string
	 */
	private $webmaster_address = _RECIPIENT_CONTACT_;
	
	/**
	 * Server timezone (will be used on the header)
	 * 
	 * @var string
	 */
	private $tz = 'Asia/Manila';
	
	/**
	 * 
	 * 
	 * @var string
	 */
	private $line_break = "\r\n";
	
	/**
	 * E-mail header boundary
	 * 
	 * @var string
	 */
	private $boundary;
	
	/**
	 * Mail priority. 4 = low, 3 = normal, 2 = high
	 * 
	 * @var integer
	 */
	private $priotity = 3;
	
	/**
	 * Errors
	 * 
	 * @var array
	 */
	private $errors;
	
	/**
	 * PEAR Mail_mime instance
	 * 
	 * @var Mail_mime
	 */
	private $mime;
	
	public function __construct()
	{
		$this->boundary = md5(time());
	}
	
	/**
	 * 
	 * @param string $key
	 * @param string $filter
	 * @return mixed
	 */
	private function sanitize($key, $filter)
	{
		return filter_var($key, $filter);
	}
	
	private function sanitizeEmailAddr($key)
	{
		$email = $this->sanitize($key, FILTER_SANITIZE_EMAIL);
		
		if($this->sanitize($key, FILTER_VALIDATE_EMAIL) === false) {
			return null;
		} else {
			return $email;
		}
	}
	
	/**
	 * 
	 * 
	 * @param string $text
	 * @return string
	 */
	private function sanitizeText($text)
	{
		return $this->sanitize($text, FILTER_SANITIZE_STRING);
	}
	
	/**
	 * Sets e-mail headers.
	 * Reference: http://www.iana.org/assignments/message-headers/message-headers.xhtml
	 * Parameter should be passed as an associative array like ['To' => 'someuser@yahoo.com']
	 * 
	 * @param array $headers
	 * @return Mailer
	 */
	public function setHeaders(array $headers = [])
	{
		$this->headers = $headers;
		
		return $this;
	}
	
	/**
	 * Sets e-mail priority
	 * 4 = low, 3 = normal, 2 = high
	 * 
	 * @param int $priority
	 * @return Mailer
	 */
	public function setPriority($priority)
	{
		$this->priotity = $priority;
		
		return $this;
	}
	
	private function defaultHeaders()
	{
		$date = new DateTime();
		$date->setTimezone(new DateTimeZone($this->tz));
		
		$this->headers['Date'] = $date->format('D, j M o G:i:s O');
		$this->headers['From'] = _RECIPIENT_CONTACT_;
		$this->headers['Reply-To'] = _RECIPIENT_CONTACT_;
		$this->headers['Content-Type'] = "multipart/mixed; boundary=$this->boundary";
		$this->headers['Mime-Version'] = '1.0';
		$this->headers['X-Priority'] = $this->priotity;
	}
	
	/**
	 * Replaces/sets current headers. Use getHeaders() method to get all headers 
	 * 
	 * @param string $key
	 * @param string $value
	 * @return Mailer
	 */
	public function replaceHeader($key, $value)
	{
		$this->headers[$key] = $value;
		
		return $this;
	}
	
	/**
	 * Returns all set headers
	 * 
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
	
	private function replaceDelimiter($key)
	{
		return preg_replace('/(\bkey\b)(?=\})/', $key, $this->delimiter);
	}
	
	/**
	 * Replaces $key for $value
	 * Key in the HTML/TXT body should be enclosed with curly braces. (Example: {key})
	 * 
	 * @param string $key
	 * @param string $value
	 * @return Mailer
	 */
	public function replace($key, $value)
	{
		$value = $this->sanitizeText($value);
		
		$this->msg = str_ireplace($this->replaceDelimiter($key), $value, $this->msg);
		
		return $this;
	}
	
	private function fromRemoteLocation($filePath)
	{
		$loc = curl_init($filePath);
		
		curl_setopt($loc, CURLOPT_NOBODY, true);
		curl_exec($ch);
		
		$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	}
	
	private function fromLocalDrive($filePath)
	{
		if(file_exists($filePath)) {
			if($str = file_get_contents($filePath)) {
				return $str;
			} else {
				$this->errors[] = "Error while opening file " . basename($filePath) . ".";
		
				return false;
			}
		} else {
			$this->errors[] = "File: " . basename($filePath) . " does not exist.";
				
			return false;
		}
	}
	
	/**
	 * 
	 * @param string $filePath
	 * @return mixed: bool | string
	 */
	private function ifFileExists($filePath)
	{
		/**
		 * should check first if given file path is local or remote.
		 */
		return $this->fromLocalDrive($filePath);
	}
	
	/**
	 * Attaches file to e-mail
	 * 
	 * @param string $filePath currently only supports local storage.
	 * @param string $type
	 * @param string $disposition
	 * @return Mailer
	 */
	public function attachFile($filePath, $type = 'application/octet-stream', $disposition = 'attachment')
	{
		$file['data'] = $this->ifFileExists($filePath);
		
		if($file['data'] !== false) {
			$file['content_type'] = $type;
			$file['data'] = chunk_split(base64_encode($file['data']));
			$file['disposition'] = $disposition;
			$file['name'] = basename($filePath);
			$file['trans_enc'] = 'base64';
			
			$this->attachments[] = $file;
		}		
		
		return $this;
	}
	
	private function innerBoundary($key)
	{
		return "--$key";;
	}
	
	private function boundary($key)
	{
		return $this->innerBoundary($key) . '--';
	}
	
	private function createAttachments()
	{
		foreach($this->attachments as $attachment) {
			$boundary = md5($attachment['name'] . time());

			$att .= "Content-Type: " . $attachment['content_type'] . "; name=\"" . $attachment['name'] . "\"" . $this->line_break;
			$att .= "Content-Transfer-Encoding: " . $attachment['trans_enc'] . $this->line_break;
			$att .= "Content-Disposition: " . $attachment['disposition'] . "; filename=\"" . $attachment['name'] . "\"" . $this->line_break;
			$att .= $attachment['data'];
			$att .= $this->innerBoundary($this->boundary) . $this->line_break; 
		}
		
		return $att;
	}
	
	/**
	 * 
	 * @param array $emails
	 * @return array
	 */
	private function emailsIterator(array $emails)
	{
		foreach($emails as $single) {
			if($this->sanitizeEmailAddr($single) !== null) {
				if(is_null($collection)) {
					$collection = $single;
				} else {
					$collection .= ", $single";
				}
			} else {
				$this->invalid_email[] = $single;
				$this->errors[] = "E-mail address $single is invalid.";
			}
		};
		
		return $collection;
	}
	
	/**
	 * Sets timezone that will be used on the e-mail header
	 * Supported timezones: http://php.net/manual/en/timezones.php
	 * 
	 * @param string $tz
	 * @return Mailer
	 */
	public function setTimezone($tz)
	{
		$this->tz = $tz;
		
		return $this;
	}
	
	/**
	 * Sets recipient (Header: To)
	 * WARNING: using this method will override input from setBatchRecipient
	 * @param string $receiver
	 * @return Mailer
	 */
	public function setRecipient($receiver)
	{
		$this->to = $this->sanitizeEmailAddr($receiver);
		
		$this->headers['To'] = $this->to;
	
		return $this;
	}
	
	/**
	 * Sets recipients (Header: To)
	 * WARNING: using this method will override input from setRecipient
	 * 
	 * @param array $receivers
	 * @return Mailer
	 */
	public function setBatchRecipient(array $receivers)
	{
		$this->to = $this->emailsIterator($receivers);
		
		return $this;
	}
	
	/**
	 * Sets carbon copy recipients (Header: Cc)
	 * 
	 * @param array $cc
	 * @return Mailer
	 */
	public function setCc(array $cc)
	{	
		if(!is_null($cc)) {	
			$this->cc = $this->emailsIterator($cc);
		}		
		
		return $this;
	}
	
	/**
	 * Returns all carbon copy recipients
	 * 
	 * @return array
	 */
	public function getCc()
	{
		if(is_string($this->cc)) {
			return explode(',', $this->cc);
		}
	}
	
	/**
	 * Sets blind carbon copy recipients (Header: Bcc)
	 * 
	 * @param array $bcc
	 * @return Mailer
	 */
	public function setBcc(array $bcc)
	{
		if(!is_null($bcc)) {
			$this->bcc = $this->emailsIterator($bcc);
		}
				
		return $this;
	}
	
	/**
	 * Returns all carbon copy recipients
	 *
	 * @return array
	 */
	public function getBcc()
	{
		if(is_string($this->bcc)) {
			return explode(',', $this->bcc);
		}
	}
	
	/**
	 * Sets e-mail subject (Header: Subject)
	 * 
	 * @param string $subject
	 * @return Mailer
	 */
	public function setSubject($subject)
	{
		$this->subject = $this->sanitizeText($subject);		
		
		return $this;
	}
	
	/**
	 * Sets HTML/TXT body
	 * 
	 * @var string/html
	 * @return Mailer
	 */
	public function setBody($html)
	{
		$this->msg = "Content-type:text/html; charset=utf-8 $this->line_break";
		$this->msg .= "Content-Transfer-Encoding: 8bit $this->line_break";
		$this->msg .= $html . $this->line_break;
		$this->msg .= $this->boundary($this->boundary);
		
		return $this;
	}
	
	/**
	 * Returns message body
	 * 
	 * @return string
	 */
	public function getBody()
	{
		return $this->msg;
	}
	
	/**
	 * Checks if Bcc and/or Cc are not null and add them to the header
	 * 
	 */
	private function carbonCopies()
	{
		if(!is_null($this->cc)) {
			$header = "Cc: $this->cc $this->line_break";
		}
		
		if(!is_null($this->bcc)) {
			$header .= "Bcc: $this->bcc $this->line_break";
		}
		
		return $header;
	}
	
	private function headersIterator()
	{	
		foreach($this->headers as $key => $value) {
			if($key == 'To') {
				$header .= $this->carbonCopies();
			} else {
				$header .= "$key: $value $this->line_break";
			}
		}
		
		$header .= $this->innerBoundary($this->boundary) . $this->line_break;
		return $header;
	}
	
	private function prepare()
	{
		if($this->to === null) {
			$this->errors[] = 'Receiver address should not be null.';
				
			return $this;
		}
		
		/**
		 * Sets default headers. Properties can be overwritten by using setHeaders() method
		 */
		$this->defaultHeaders();
		
		$this->headers = $this->headersIterator();	
		
		/**
		 * If attachments are present, iterates over $attachments property and build message body
		 */
		if($this->attachments !== null) {
			$attachments = $this->createAttachments();
		}
				
		/**
		 * Concatenates attachments to headers property
		 */
		$this->headers .=  $attachments . $this->msg;
		$this->msg = null;
		
		/**
		 * Nulls attachments to save memory
		 */
		$this->attachments = null;
	}
	
	private function sendTo($receiver)
	{
		/**
		 * NOTE: $msg property is NULL. All parameters are passed to $headers property.
		 * 
		 */
		mail($this->to,	$this->subject, $this->msg, $this->headers);
	}	
	
	/**
	 * Sends e-mail. This method automatically detects single or batch e-mail sending
	 * 
	 */
	public function send()
	{
		/**
		 * 
		 */
		$this->prepare();
		
		if($this->sanitizeEmailAddr($this->to)) {
			$this->sendTo($this->to);
		}
		
	}
	
	/**
	 * Returns all error from runtime
	 * 
	 * @return array
	 */
	public function errors()
	{
		return $this->errors;
	}
	
	/**
	 * Dumps all data
	 * 
	 * @return Mailer
	 */
	public function dumpAll()
	{
		echo "<pre>";
		print_r($this);
		echo "</pre>";
	}
}
?>