<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para action privada ou protegida, � tratada pela framework, que resulta numa p�gina n�o encontrada
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class ActionVisibilityException extends PageNotFoundException
{
	/**
	 * Contrutor da classe
	 * @param string $action	nome da action
	 */
	public function __construct($action)
	{
		parent::__construct('A action '. $action .' n�o � p�blica');
	}
	
	/**
	 * Se o debug estiver habilitado, informa ao usu�rio detalhes sobre a action
	 * @see PageNotFoundException::getDetails()
	 * @return string	retorna os detalhes da action
	 */
	public function getDetails()
	{
		return '&lt;?php'. br .'class '. controller .' extends Controller {'. 
		br . br . t() .'<b>public</b> function '. action .'() {'. br . t(2) . 'return $this->_view();' . br . t() .'}' . br .'}';
	}
}