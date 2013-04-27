<?php 
/**
 * @Entity("ticket")
 */
class Ticket extends Model
{
	/**
	 * @AutoGenerated()
	 * @Column(Type="Int", Key="Primary")
	 */
	public $Id;
	
	/**
	 * @Required()
	 * @Column(Type="String")
	 */
	public $Date;
	
	/**
	 * @Required()
	 * @Label("Assunto")
	 * @Column(Type="String")
	 */
	public $Subject;
	
	/**
	 * @Required()
	 * @Label("E-mail")
	 * @Regex(Pattern="^([a-zA-Z0-9\.\-_]+)@([a-zA-Z0-9\.\-]+)\.([a-z]{2,})$",Message="Endere�o de e-mail est� inv�lido")
	 * @Column(Type="String")
	 */
	public $Email;
	
	/**
	 * @Required()
	 * @Label("Nome")
	 * @Column(Type="String")
	 */
	public $Name;
	
	/**
	 * @Required()
	 * @Label("Prioridade")
	 * @Column(Type="Int")
	 */
	public $Priority;
	
	/**
	 * @Required()
	 * @Label("Mensagem")
	 * @Column(Type="String")
	 */
	public $Message;
	
	/**
	 * @Column(Type="String")
	 */
	public $Note;
	
	/**
	 * @Required()
	 * @Column(Type="Int")
	 */
	public $Status;
	
	/**
	 * @Column(Type="Int")
	 */
	public $IdParent;
	
	/**
	 * @Column(Type="String")
	 */
	public $Attachment;
	
	/**
	 * @Column(Type="String")
	 */
	public $UserId;
	
	/** @HasOne(Model="User",Property="Id") */
	public $User;
	
	public static function upload($file)
	{
		if(Form::isFile($file))
		{
			$name = md5(uniqid('', true)) .'.jpg';
			$canvas = new canvas();
			$canvas->carrega(Form::file()->{$file}['tmp_name'])->grava(root . 'app/wwwroot/attachment/' . $name);
			return $name;
		}
	}
	
	public static function open($email, $id)
	{
		$db = Database::getInstance();
		return $db->Ticket->single('Email = ? AND Id = ? AND IdParent IS NULL', $email, $id);
	}
	public static function view($id)
	{
		$db = Database::getInstance();
		return $db->Ticket->where('Id = ? OR IdParent = ?', $id, $id)->all();
	}
	
	public static function client_all($email, $p = 1, $m = 20, $o = 'Id', $t = 'DESC')
	{
		$db = Database::getInstance();
		return $db->Ticket->where('Email = ? AND IdParent IS NULL', $email)->orderBy($o .' '. $t)->paginate($p-1, $m);
	}
	
	public static function admin_all($p = 1, $m = 20, $o = 'Id', $t = 'DESC')
	{
		$db = Database::getInstance();
		return $db->Ticket->whereSQL('IdParent IS NULL')->orderBy($o .' '. $t)->paginate($p-1, $m);
	}
	
	public static function admin_all_status($status, $p = 1, $m = 20, $o = 'Id', $t = 'DESC')
	{
		$db = Database::getInstance();
		return $db->Ticket->where('Status = ? AND IdParent IS NULL', $status)->orderBy($o .' '. $t)->paginate($p-1, $m);
	}
}