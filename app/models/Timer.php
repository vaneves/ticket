<?php 
/**
 * @Entity("timer")
 */
class Timer extends Model
{
	/**
	 * @AutoGenerated()
	 * @Column(Type="Int",Key="Primary")
	 */
	public $Id;
	
	/** @Column(Type="Int") */
	public $UserId;
	
	/** @Column(Type="Int") */
	public $TicketId;
	
	/**
	 * @Required()
	 * @Column(Type="String")
	 */
	public $StartDate;
	
	/**
	 * @Required()
	 * @Column(Type="String")
	 */
	public $EndDate;
	
	/**
	 * @Required()
	 * @Label("Descrição")
	 * @Column(Type="String")
	 */
	public $Description;
	
	public static function allByTicket($id)
	{
		$db = Database::getInstance();
		return $db->Timer->all('TicketId = ?', $id);
	}
	
	public static function calc($start, $end)
	{
		if($start)
		{
			$date = new DateTime(date('Y-m-d H:i:s', $start));
			$diff = $date->diff(new DateTime(date('Y-m-d H:i:s', $end)));
			return $diff->format('%H:%I:%S');
		}
		return '00:00:00';
	}
	public static function calcToString($start, $end)
	{
		if($start)
		{
			$date = new DateTime(date('Y-m-d H:i:s', $start));
			$diff = $date->diff(new DateTime(date('Y-m-d H:i:s', $end)));
			$r = '';
			$h = $diff->format('%h');
			$m = $diff->format('%i');
			if($h || $m)
			{
				if($h)
					$r .= $h . ' horas ';
				if($h && $m)
					$r .= 'e ';
				if($m)
					$r .= $m . ' minutos';
			}
			else
			{
				return '1 minuto';
			}
			return $r;
		}
		return '1 minuto';
	}
	
}