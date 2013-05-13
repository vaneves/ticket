<?php 
/**
 * @View("view_timer")
 */
class ViewTimer extends Model
{	
	/** @Column(Type="Int") */
	public $UserId;
	
	/** @Column(Type="Int") */
	public $TicketId;
	
	/** @Column(Type="String") */
	public $Time;
	
	public static function getByTicket($id)
	{
		$db = Database::factory();
		$timer = $db->ViewTimer->single('TicketId = ?', $id);
		return $timer;
	}
}