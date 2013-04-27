<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o de valida��o de dados, deve ser tratada pelo programador para exibir a mensagem ao usu�rio
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class ValidationException extends TriladoException
{
	/**
	 * Construtor da classe
	 * @param string $message	mensagem do erro
	 * @param int $code			n�mero do erro
	 */
	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}
}