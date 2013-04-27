<?php
class mail
{
	public $To;
	public $From;
	public $Subject;
	public $Message;
	
	public function Send()
	{
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: '. $this->To ."\r\n";
		$headers .= 'From: '. $this->From ."\r\n";
		
		return mail($this->To, $this->Subject, $this->Message, $headers);
	}
}
