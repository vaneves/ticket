<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Controller n�o encontrado, tratado pelo framework, que resulta numa p�gina n�o encontrada
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class ControllerNotFoundException extends PageNotFoundException
{
	/**
	 * Contrutor da classe
	 * @param string $controller	nome do controller
	 */
	public function __construct($controller)
	{
		parent::__construct('O controller '. $controller .' n�o foi encontrado');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PageNotFoundException::getDetails()
	 */
	public function getDetails()
	{
		return '&lt;?php'. br .'class <b>'. controller .'</b> extends Controller {'. br . br .'}';
	}
}