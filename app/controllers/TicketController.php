<?php 
class TicketController extends Controller
{
	public function __construct() {}
	
	public function add()
	{
		if(is_post)
		{
			try
			{
				$date = new DateTime('now');
				
				$ticket = $this->_data(new Ticket());
				$ticket->Date	= $date->format("Y-m-d H:i:s");
				$ticket->Status	= 0;
				$ticket->Attachment = Ticket::upload('File');
				$ticket->save();
				$this->_flash('success', 'Ticket criado com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('error', 'Ocorreu um erro ao tentar enviar seu ticket');
			}
		}
		return $this->_view($ticket);
	}
	public function open()
	{
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
				$this->_flash('error', 'E-mail ou ID inválido');
			}
		}
		return $this->_view($data);
	}
	
	/**
	 * @Auth("ticket")
	 */
	public function view($id = 1)
	{
		$tickets = Ticket::view($id);
		return $this->_view($tickets);
	}
	
	/**
	 * @Auth("ticket")
	 */
	public function reply($id)
	{
		if(is_post)
		{
			$parent = Ticket::get($id);
			if($parent)
			{
				try
				{
					$date = new DateTime('now');
					
					$ticket = $this->_data(new Ticket());
					$ticket->Date		= $date->format("Y-m-d H:i:s");
					$ticket->Status		= 0;
					$ticket->IdParent	= (int)$id;
					$ticket->Subject	= $parent->Subject;
					$ticket->Priority	= $parent->Priority;
					$ticket->Email		= Session::get('ticket')->Email;
					$ticket->Attachment = Ticket::upload('File');
					$ticket->save();
					$this->_flash('success', 'Ticket respondido com sucesso');
				}
				catch(ValidationException $e)
				{
					$this->_flash('error', $e->getMessage());
				}
				catch(Exception $e)
				{
					$this->_flash('error', 'Ocorreu um erro ao tentar enviar sua resposta');
				}
				$this->_redirect('~/ticket/view/'. $id);
			}
			else
			{
				return $this->_content('Ticket não encontrado');
			}
		}
	}
	
	/**
	 * @Auth("client")
	 */
	public function client_add()
	{
		if(is_post)
		{
			try
			{
				$date = new DateTime('now');
				
				$ticket = $this->_data(new Ticket());
				$ticket->Date	= $date->format("Y-m-d H:i:s");
				$ticket->Name	= Session::get('user')->Name;
				$ticket->Email	= Session::get('user')->Email;
				$ticket->Status	= 0;
				$ticket->Attachment = Ticket::upload('File');
				$ticket->save();
				$this->_flash('success', 'Ticket criado com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('error', 'Ocorreu um erro ao tentar enviar seu ticket');
			}
		}
		return $this->_view($ticket);
	}
	
	/**
	 * @Auth("client")
	 */
	public function client_list($p = 1, $o = 'Id', $t = 'DESC')
	{
		$tickets = Ticket::client_all(Session::get('user')->Email, $p, 20, $o, $t);
		return $this->_view($tickets);
	}
	
	/**
	 * @Auth("client")
	 */
	public function client_view($id)
	{
		$tickets = Ticket::view($id);
		return $this->_view('view', $tickets);
	}
	
	/**
	 * @Auth("client")
	 */
	public function client_reply($id)
	{
		if(is_post)
		{
			$parent = Ticket::get($id);
			if($parent)
			{
				try
				{
					$date = new DateTime('now');
					
					$ticket = $this->_data(new Ticket());
					$ticket->Date		= $date->format("Y-m-d H:i:s");
					$ticket->Status		= 0;
					$ticket->IdParent	= (int)$id;
					$ticket->Subject	= $parent->Subject;
					$ticket->Priority	= $parent->Priority;
					$ticket->Email		= Session::get('user')->Email;
					$ticket->Name		= Session::get('user')->Name;
					$ticket->Attachment = Ticket::upload('File');
					$ticket->save();
					$this->_flash('success', 'Ticket respondido com sucesso');
				}
				catch(ValidationException $e)
				{
					$this->_flash('error', $e->getMessage());
				}
				catch(Exception $e)
				{
					$this->_flash('error', 'Ocorreu um erro ao tentar enviar sua resposta');
				}
				$this->_redirect('~/ticket/view/'. $id);
			}
			else
			{
				return $this->_snippet('notfound','Ticket não encontrado');
			}
		}
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
		$status = array('open' => 0, 'closed' => 1, 'answered' => 2);
		$tickets = Ticket::admin_all_status($status[$s], $p, 20, $o, $t);
		return $this->_view($tickets);
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_add()
	{
		if(is_post)
		{
			try
			{
				$date = new DateTime('now');
				
				$ticket = $this->_data(new Ticket());
				$ticket->Date	= $date->format("Y-m-d H:i:s");
				$ticket->Status	= 0;
				$ticket->Attachment = Ticket::upload('File');
				$ticket->save();
				$this->_flash('success', 'Ticket criado com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('error', 'Ocorreu um erro ao tentar enviar seu ticket');
			}
		}
		return $this->_view($ticket);
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_edit($id)
	{
		$ticket = Ticket::get($id);
		if($ticket)
		{
			if($ticket->Email == Session::get('usuario')->Email)
			{
				if(is_post)
				{
					try
					{
						$date = new DateTime('now');
						
						$ticket = $this->_data($ticket);
						$ticket->Id	= $id;
						$ticket->Attachment = Ticket::upload('File');
						$ticket->save();
						$this->_flash('success', 'Ticket atualizado com sucesso');
					}
					catch(ValidatException $e)
					{
						$this->_flash('error', $e->getMessage());
					}
					catch(Exception $e)
					{
						$this->_flash('error', 'Ocorreu um erro ao tentar atualizar o ticket');
					}
				}
			}
			else
			{
				return $this->_snippet('notfound','Você não tem permissão para editar esse ticket');
			}
		}
		else
		{
			return $this->_snippet('notfound','Ticket não encontrado');
		}
		return $this->_view($ticket);
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
	 * @Auth("user")
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
					$date = new DateTime('now');
					
					$ticket = $this->_data(new Ticket());
					$ticket->Date		= $date->format("Y-m-d H:i:s");
					$ticket->Status		= 0;
					$ticket->IdParent	= (int)$id;
					$ticket->Subject	= $parent->Subject;
					$ticket->Priority	= $parent->Priority;
					$ticket->Email		= Session::get('user')->Email;
					$ticket->Name		= Session::get('user')->Name;
					$ticket->Attachment = Ticket::upload('File');
					$ticket->save();
					$this->_flash('success', 'Ticket respondido com sucesso');
				}
				catch(ValidationException $e)
				{
					$this->_flash('error', $e->getMessage());
				}
				catch(Exception $e)
				{
					$this->_flash('error', 'Ocorreu um erro ao tentar enviar sua resposta');
				}
				$this->_redirect('~/ticket/view/'. $id);
			}
			else
			{
				return $this->_content('Ticket não encontrado');
			}
		}
	}
	
	/**
	 * @Auth("admin","employee")
	 */
	public function admin_delete($id)
	{
		$ticket = Ticket::get($id);
		if($ticket)
		{
			if($ticket->Email == Session::get('user')->Email || Session::get('user')->Type === 0)
			{
				try
				{
					$ticket->delete();
					$this->_flash('success', 'Ticket deletado com sucesso');
				}
				catch(ValidationException $e)
				{
					$this->_flash('error', $e->getMessage());
				}
				catch(Exception $e)
				{
					$this->_flash('error', 'Ocorreu um erro ao tentar deletar o ticket');
				}
			}
			else
			{
				return $this->_snippet('notfound','Você não tem permissão para deletar esse ticket');
			}
		}
		else
		{
			return $this->_snippet('notfound','Ticket não encontrado');
		}
		return $this->_redirect('~/admin/ticket/list');
	}
}