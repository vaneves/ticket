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
	public function view($id = 1)
	{
		$tickets = Ticket::view($id);
		 if(count($tickets) && $tickets[0]->IdParent == null)
			 return $this->_view($tickets);
		 
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
		return $this->_view($tickets);
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