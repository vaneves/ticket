<?php
class Mail
{
	private $subject = '';
	private $to = array();
	private $from;
	private $message = '';
	private $html = '';
	
	public function __construct()
	{
		$this->from = new stdClass();
		$this->from->from_name = '';
		$this->from->from_email = '';
	}
	
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	public function addTo($email, $name = null)
	{
		$to = new stdClass();
		$to->name	= $name;
		$to->email	= $email;
		
		$this->to[] = $to;
	}
	
	public function setFrom($email, $name = null)
	{
		$this->from->from_name = $name;
		$this->from->from_email = $email;
	}
	
	public function setMessage($message)
	{
		$this->message = $message;
	}
	
	public function setTemplate($controller, $view, $vars = array())
	{
		$html = Import::view($vars, $controller, $view);
		$this->html = $html;
	}
	
	public function send()
	{
		$message = array();
		$message['type'] = 'messages';
		$message['call'] = 'send';
		$message['message']['subject']		= $this->subject;
		$message['message']['text']			= $this->message;
		$message['message']['html']			= $this->html;
		$message['message']['from_email']	= $this->from->from_email;
		$message['message']['from_name']	= $this->from->from_name;
		$message['message']['to']			= $this->to;

		$json = json_encode($message);
		
		$mandrill = new Mindrill(Config::get('mandrill_key'));
		return $mandrill->call('/messages/send.json', $message);
	}
}