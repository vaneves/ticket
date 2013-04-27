<?php 
class UserController extends Controller
{
	public function __construct() {}
	
	public function register()
	{
		$db = Database::getInstance();
		$db->User->innerJoin('Ticket')->all();
		exit;
	
		if(is_post)
		{
			try
			{
				if($this->_data()->Password != $this->_data()->Confirm)
					throw new ValidationException('Os campos "Senha" e "Confirmar Senha" estão diferentes');
				if(User::exists($this->_data()->Email))
					throw new ValidationException('E-mail já está cadastrado');
				$user = $this->_data(new User());
				$user->Type = 2;
				$user->save();
				$this->_flash('success', 'Seu cadastro foi realizado com sucesso');
			}
			catch(ValidationException $e)
			{
				$this->_flash('error', $e->getMessage());
			}
			catch(Exception $e)
			{
				$this->_flash('error', 'Ocorreu um erro ao tentar salvar seus dados');
			}
		}
		return $this->_view($user);
	}
	
	public function login()
	{
		if(is_post)
		{
			$data = $this->_data();
			$user = User::login($data->Email, $data->Password);
			if($user)
			{
				$roles = array('admin', 'employee', 'client', 'ticket');
				Session::set('user', $user);
				Auth::set($roles[$user->Type]);
				$this->_redirect('~/welcome');
			}
			else
			{
				$this->_flash('error', 'E-mail ou senha incorreta!');
			}
		}
		return $this->_view();
	}
	
	public function logout()
	{
		Auth::clear();
		Session::clear();
		$this->_redirect('~/');
	}
	
	public function remember()
	{
		if(is_post)
		{
			$user  = User::exists($this->_data()->Email);
			if($user)
			{
				try
				{
					$passwd = new_passwd();
					$user->Password = md5($passwd);
					$user->save();
					
					$mail = new Mail();
					$mail->To		= $user->Email;
					$mail->Form		= 'Ticket Portal <nao-responda@ulbra-to.br>';
					$mail->Subject	= 'Recuperar Senha';
					$mail->Message	= 'Olá <b>'. $user->Name .'</b>, <br />';
					$mail->Message	.= 'sua nova senha é: '. $passwd;
					
					if($mail->Send())
						$this->_flash('success', 'Sua nova senha foi enviada por e-mail');
					else
						$this->_flash('error', 'Ocorreu um erro ao tentar enviar um e-mail com sua nova senha');
				}
				catch(Exception $e)
				{
					$this->_flash('error', 'Ocorreu um erro ao tentar recuperar sua senha');
				}
			}
			else
			{
				$this->_flash('error', 'E-mail não cadastrado');
			}
		}
		return $this->_view($this->_data());
	}
	
	public function admin_index()
	{
		$user = new User();
		return $this->_view($user->all());
	}
}