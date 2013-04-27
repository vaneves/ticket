<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para tipo de retorno inv�lido, utilizada no retorno da view, tratada pelo framework, que resulta num erro 500
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class InvalidReturnException extends TriladoException
{
	/**
	 * Contrutor da classe
	 * @param string $action	nome action
	 */
	public function __construct($action)
	{
		parent::__construct('A action '. $action .' deve retornar algo');
	}
	/**
	 * (non-PHPdoc)
	 * @see TriladoException::getDetails()
	 */
	public function getDetails()
	{
		return '&lt;?php'. br .'class '. controller .' extends Controller {'. 
		br . br . t() .'public function '. action .'() {'. br . t(2) . '<b>return $this->_view();</b>' . br . t() .'}' . br .'}';
	}
}