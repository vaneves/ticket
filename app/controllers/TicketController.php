<?php 
class TicketController extends Controller
{
	public function __construct() {}
	
	public function add()
	{
		$ticket = new Ticket();
		if(is_post)
		{
			try
			{
				$date = new DateTime('now');
				
				$ticket = $this->_data(new Ticket());
				$ticket->Date	= $date->format("Y-m-d H:i:s");
				$ticket->Status	= 0;
				$ticket->Attachment = Ticket::upload('File');
				
				if(Auth::is('client'))
				{
					$ticket->Name	= Session::get('user')->Name;
					$ticket->Email	= Session::get('user')->Email;
					$ticket->UserId	= Session::get('user')->Id;
				}
				
				$ticket->save();
				
				$this->_flash('alert', 'Ticket criado com sucesso');
				
				try
				{
					$mail = new Mail();
					$mail->setSubject('[Ticket ID: '. $ticket->Id .'] ' . html_entity_decode($ticket->Subject));
					$mail->setFrom($ticket->Email, html_entity_decode($ticket->Name));
					$mail->addTo('linkinsystem666@gmail.com', 'Van Neves');
					$mail->setTemplate('mail', 'new', array('name' => 'Van Neves', 'id' => $ticket->Id, 'code' => Security::encrypt($ticket->Id, Config::get('code_key'))));
					
					$response = $mail->send();
				}
				catch (Exception $e)
				{
					
				}
				$ticket = new Ticket();
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar enviar seu ticket');
			}
		}
		return $this->_view($ticket);
	}
	
	public function check()
	{
		$data = new Ticket();
		if(is_post)
		{
			$data = $this->_data();
			$ticket = Ticket::open($data->Email, str_replace('#', '', $data->Id));
			if($ticket)
			{
				Auth::set('ticket');
				Session::set('ticket', $ticket);
				$this->_redirect('~/ticket/view/'. $ticket->Id);
			}
			else
			{
				$this->_flash('alert alert-error', 'E-mail ou ID inválido');
			}
		}
		return $this->_view($data);
	}
	
	/**
	 * @Auth("client","ticket")
	 */
	public function view($id)
	{
		$tickets = Ticket::view($id);
		 if(count($tickets) && $tickets[0]->IdParent == null)
			 return $this->_view($tickets);
		 
		 return $this->_snippet('notfound');
	}
	
	public function view_free($id, $code)
	{
		$id = Security::decrypt($code, Config::get('code_key'));
		
		$tickets = Ticket::view($id);
		 if(count($tickets) && $tickets[0]->IdParent == null)
		 {
			 Auth::set('ticket');
			 Session::set('ticket', $tickets[0]);
				
			 return $this->_view('view', $tickets);
		 }
		 return $this->_snippet('notfound');
	}
	
	/**
	 * @Auth("client","ticket")
	 */
	public function reply($id)
	{
		if(is_post)
		{
			$parent = Ticket::get($id);
			if($parent && $parent->Status != 2)
			{
				try
				{
					$parent->Status		= 0;
					$parent->IdParent	= null;
					$parent->save();
					
					$date = new DateTime('now');
					
					$ticket = $this->_data(new Ticket());
					$ticket->Date		= $date->format("Y-m-d H:i:s");
					$ticket->Status		= 0;
					$ticket->IdParent	= (int)$id;
					$ticket->Subject	= $parent->Subject;
					$ticket->Priority	= $parent->Priority;
					
					$session = 'user';
					if(Auth::is('ticket'))
						$session = 'ticket';
					
					$ticket->Name		= Session::get($session)->Name;
					$ticket->Email		= Session::get($session)->Email;
					$ticket->Attachment = Ticket::upload('File');
					$ticket->Note		= '';
					
					$ticket->save();
					
					$this->_flash('alert', 'Ticket respondido com sucesso');
					
					try
					{
						$mail = new Mail();
						$mail->setSubject('[Ticket ID: '. $ticket->IdParent .'] ' . html_entity_decode($ticket->Subject));
						$mail->setFrom($ticket->Email, html_entity_decode($ticket->Name));
						$mail->addTo('linkinsystem666@gmail.com', 'Van Neves');
						$mail->setTemplate('mail', 'reply', array('name' => 'Van Neves', 'id' => $ticket->IdParent, 'code' => Security::encrypt($ticket->IdParent, Config::get('code_key'))));

						$response = $mail->send();
					}
					catch (Exception $e)
					{

					}
				}
				catch(ValidationException $e)
				{
					$this->_flash('alert alert-error', $e->getMessage());
				}
				catch(Exception $e)
				{
					$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar enviar sua resposta');
				}
				$this->_redirect('~/ticket/view/'. $id);
			}
			return $this->_snippet('notfound');
		}
		return $this->_snippet('badrequest');
	}
	
	/**
	 * @Auth("client")
	 */
	public function my($p = 1, $o = 'Id', $t = 'DESC')
	{
		$tickets = Ticket::client_all(Session::get('user')->Email, $p, 20, $o, $t);
		return $this->_view($tickets);
	}
	
	/**
	 * @Auth("client","ticket")
	 */
	public function close($id)
	{
		$ticket = Ticket::get($id);
		if($ticket && $ticket->Email == Session::get('user')->Email)
		{
			try
			{
				$ticket->Status		= 2;
				$ticket->IdParent	= null;
				$ticket->save();
				$this->_flash('alert', 'Ticket fechado com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar fechar o ticket');
			}
			$this->_redirect('~/ticket/view/'. $id);
		}
		return $this->_snippet('notfound');
	}
	
	/**
	 * @Auth("client","ticket")
	 */
	public function open($id)
	{
		$ticket = Ticket::get($id);
		if($ticket && $ticket->Email == Session::get('user')->Email)
		{
			try
			{
				$ticket->Status		= 0;
				$ticket->IdParent	= null;
				$ticket->save();
				$this->_flash('alert', 'Ticket aberto com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar abrir o ticket');
			}
			$this->_redirect('~/ticket/view/'. $id);
		}
		return $this->_snippet('notfound');
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_list($p = 1, $o = 'Id', $t = 'DESC')
	{
		$tickets = Ticket::admin_all($p, 20, $o, $t);
		return $this->_view($tickets);
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_list_status($s = 'open', $p = 1, $o = 'Id', $t = 'DESC')
	{
		$status = array('open' => 0, 'answered' => 1, 'closed' => 2);
		$tickets = Ticket::admin_all_status($status[$s], $p, 20, $o, $t);
		return $this->_view('admin_list', $tickets);
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_view($id)
	{
		$tickets = Ticket::view($id);
		return $this->_view($tickets);
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_reply($id)
	{
		if(is_post)
		{
			$parent = Ticket::get($id);
			if($parent)
			{
				try
				{
					$parent->Status		= 1;
					$parent->IdParent	= null;
					$parent->save();
					
					$date = new DateTime('now');
					
					$ticket = $this->_data(new Ticket());
					$ticket->Date		= $date->format("Y-m-d H:i:s");
					$ticket->Status		= 0;
					$ticket->IdParent	= (int)$id;
					$ticket->Subject	= $parent->Subject;
					$ticket->Priority	= $parent->Priority;
					$ticket->Name		= Session::get('user')->Name;
					$ticket->Email		= Session::get('user')->Email;
					$ticket->Attachment = Ticket::upload('File');
					
					$ticket->save();
					
					$this->_flash('alert', 'Ticket respondido com sucesso');
					
					try
					{
						$mail = new Mail();
						$mail->setSubject('[Ticket ID: '. $ticket->IdParent .'] ' . html_entity_decode($ticket->Subject));
						$mail->setTo($ticket->Email, html_entity_decode($ticket->Name));
						$mail->addFrom('vaneves@vaneves.com', 'Van Neves');
						$mail->setTemplate('mail', 'reply', array('name' => html_entity_decode($ticket->Name), 'id' => $ticket->IdParent, 'code' => Security::encrypt($ticket->IdParent, Config::get('code_key'))));

						$response = $mail->send();
					}
					catch (Exception $e)
					{

					}
				}
				catch(ValidationException $e)
				{
					$this->_flash('alert alert-error', $e->getMessage());
				}
				catch(Exception $e)
				{
					$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar enviar sua resposta');
				}
				$this->_redirect('~/admin/ticket/view/'. $id);
			}
			return $this->_snippet('notfound');
		}
		return $this->_snippet('badrequest');
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_close($id)
	{
		$ticket = Ticket::get($id);
		if($ticket)
		{
			try
			{
				$ticket->Status		= 2;
				$ticket->IdParent	= null;
				
				$ticket->save();
				
				$this->_flash('alert', 'Ticket fechado com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar fechar o ticket');
			}
			$this->_redirect('~/admin/ticket/view/'. $id);
		}
		return $this->_snippet('notfound');
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_open($id)
	{
		$ticket = Ticket::get($id);
		if($ticket)
		{
			try
			{
				$ticket->Status		= 0;
				$ticket->IdParent	= null;
				
				$ticket->save();
				
				$this->_flash('alert', 'Ticket aberto com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar abrir o ticket');
			}
			$this->_redirect('~/admin/ticket/view/'. $id);
		}
		return $this->_snippet('notfound');
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_start($id)
	{
		$ticket = Ticket::get($id);
		if($ticket)
		{
			try
			{
				$ticket->Status		= 3;
				$ticket->IdParent	= null;
				
				$ticket->save();
				
				Session::set('timer', time());
				
				$this->_flash('alert', 'A contagem de tempo foi iniciada');
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar iniciars a contagem de tempo');
			}
			$this->_redirect('~/admin/ticket/view/'. $id);
		}
		return $this->_snippet('notfound');
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_stop($id)
	{
		$ticket = Ticket::get($id);
		if($ticket)
		{
			try
			{
				$time_start = Session::get('timer');
				
				if($time_start)
				{
					$date = new DateTime(date('Y-m-d H:i:s', $time_start));
					$diff = $date->diff(new DateTime(date('Y-m-d H:i:s', time())));

					$date = new DateTime($ticket->Time);
					$date->add($diff);

					$ticket->Status		= 4;
					$ticket->IdParent	= null;
					$ticket->Time		= $date->format('H:i:s');

					$ticket->save();

					Session::del('timer');
					
					$this->_flash('alert', 'A contagem de tempo foi encerrada, totalizando em ' . $date->format('H:i:s'));
				}
				else
				{
					$this->_flash('alert alert-error', 'O tempo inicial não foi encontrado');
				}
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar encerrar a contagem de tempo');
			}
			$this->_redirect('~/admin/ticket/view/'. $id);
		}
		return $this->_snippet('notfound');
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_delete($id)
	{
		$ticket = Ticket::get($id);
		if($ticket)
		{
			try
			{
				$ticket->delete();
				$this->_flash('alert', 'Ticket deletado com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('alert alert-error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('alert alert-error', 'Ocorreu um erro ao tentar deletar o ticket');
			}
		}
		else
		{
			return $this->_snippet('notfound');
		}
		return $this->_redirect('~/admin/ticket/list');
	}
}