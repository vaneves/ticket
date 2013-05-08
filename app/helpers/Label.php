<?php
class Label
{
	public static function status($status, $detect = false)
	{
		$list = array('Aberto','Respondido','Fechado','Execução','Pausa');
		if($detect && IS_MOBILE)
			return $list[$status][0];
		return $list[$status];
	}
	
	public static function status_class($status)
	{
		$list = array('label-warning','label-success','','label-info','label-info');	
		return $list[$status];
	}
}