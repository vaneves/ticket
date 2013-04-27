<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o padr�o do framework, � herda por v�rias outras classes de exce��o
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class TriladoException extends Exception
{
	/**
	 * Construtor da classe
	 * @param string $msg	mensagem do erro
	 * @param int $code		c�digo do erro
	 */
	public function __construct($msg, $code = 500)
	{
		parent::__construct($msg, $code);
	}
	
	/**
	 * Se o debug estiver habilitado, informa ao usu�rio detalhes sobre o erro
	 */
	public function getDetails()
	{
		return '';
	}
}