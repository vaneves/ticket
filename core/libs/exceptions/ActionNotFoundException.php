<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para action n�o encontrada, � tratada pela framework, que resulta numa p�gina n�o encontrada
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class ActionNotFoundException extends PageNotFoundException
{
	/**
	 * Contrutor da classe
	 * @param string $action	nome da action
	 */
	public function __construct($action)
	{
		$this->file = str_replace('/', '\\', root .'app/controllers/'. controller .'.php');
		parent::__construct('A action '. $action .' n�o foi encontrada');
	}
	
	/**
	 * Se o debug estiver habilitado, informa ao usu�rio detalhes sobre a action
	 * @see PageNotFoundException::getDetails()
	 * @return string	retorna os detalhes da action
	 */
	public function getDetails()
	{
		return '&lt;?php'. br .'class '. controller .' extends Controller {'. 
		br . br . t() .'public function <b>'. action .'</b>() {'. br . t(2) . 
		'return $this->_view();' . br . t() .'}' . br .'}';
	}
}