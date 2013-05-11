<?php 
/**
 * @Entity("view_timer")
 */
class Timer extends Model
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
	}
}