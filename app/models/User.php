<?php 
/**
 * @Entity("user")
 */
class User extends Model
{
	/**
	 * @AutoGenerated()
	 * @Column(Type="Int", Key="Primary")
	 */
	public $Id;
	
	/**
	 * @Required()
	 * @Label("Nome")
	 * @Column(Type="String")
	 */
	public $Name;
	
	/**
	 * @Required()
	 * @Label("Email")
	 * @Regex(Pattern="^([a-zA-Z0-9\.\-_]+)@([a-zA-Z0-9\.\-]+)\.([a-z]{2,})$",Message="Endereço de e-mail está inválido")
	 * @Column(Type="String")
	 */
	public $Email;
	
	/**
	 * @Column(Type="String")
	 * @Label("Senha")
	 */
	public $Password;
	
	/**
	 * @Required()
	 * @Column(Type="Int")
	 */
	public $Type;
	
	public $Tickers;
	
	public static function login($email, $password)
	{
		$db = Database::getInstance();
		return $db->User->single('Email = ? AND Password = ?', $email, md5($password));
	}
	
	public static function exists($email)
	{
		$db = Database::getInstance();
		return $db->User->single('Email = ?', $email);
	}
}