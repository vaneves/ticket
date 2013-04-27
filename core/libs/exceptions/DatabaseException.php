<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o quando ocorre algum erro no banco de dados (utilizando as classes Database e DatabaseQuery), se n�o tratada pelo usu�rio resulta num erro 500
 * 
 * @author	Valdirene da Cruz Neves J�nior
 * @version 1
 *
 */
class DatabaseException extends Exception
{
	/**
	 * Linha do erro
	 * @var int
	 */
	private $codeLine;
	
	/**
	 * Contrutor da classe
	 * @param string $message mensagem do erro
	 */
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
