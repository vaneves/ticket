<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para controller que n�o herda da classe Controller, � trata pelo framework, que resulta no erro 500
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class ControllerInheritanceException extends TriladoException
{
	/**
	 * Controller da classe
	 * @param string $controller	nome do controller
	 */
	public function __construct($controller)
	{
		parent::__construct('A classe '. $controller .' n�o � subclasse de Controller');
	}
	/**
	 * (non-PHPdoc)
	 * @see TriladoException::getDetails()
	 */
	public function getDetails()
	{
		return '&lt;?php'. br .'class '. controller .' <b>extends Controller</b> {'. br .'}';
	}
}