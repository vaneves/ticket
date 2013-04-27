<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para m�todo n�o encontrado, tratada pelo framework, que resulta num erro 500
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class MethodNotFoundException extends TriladoException
{
	/**
	 * Construtor da classe
	 * @param string $method	nome do m�todo
	 */
	public function __construct($method)
	{
		parent::__construct('O m�todo '. $method .' n�o foi encontrado');
	}
}