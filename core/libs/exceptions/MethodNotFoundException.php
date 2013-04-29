<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves Júnior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exceção para método não encontrado, tratada pelo framework, que resulta num erro 500
 * 
 * @author	Valdirene da Cruz Neves Júnior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class MethodNotFoundException extends TriladoException
{
	/**
	 * Construtor da classe
	 * @param	string	$method		nome do método
	 */
	public function __construct($method)
	{
		parent::__construct('O método '. $method .' não foi encontrado');
	}
}