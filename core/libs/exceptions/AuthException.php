<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para usu�rio n�o autenticado, � tratada pelo framework, que resulta numa p�gina 403 ou redireciona para p�gina de login
 * dependendo da sess�o do usu�rio
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class AuthException extends TriladoException
{
	/**
	 * Construtor da classe
	 * @param string $message		mensagem a ser exibida ao usu�rio
	 * @param int $code				c�digo do erro
	 */
	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}
}