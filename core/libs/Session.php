<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com> 
 * All rights reserved.
 */


/**
 * Classe para manipula��o de Sess�es
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	2
 *
 */
class Session
{
	/**
	 * Contrutor da classe, � privado para n�o criar uma inst�ncia
	 */
	private function __construct()
	{
	}
	
	/**
	 * Inicia a sess�o
	 * @return	void
	 */
	public static function start()
	{
		if(defined('session_started'))
			return true;
		define('session_started', true);
		session_start();
		session_regenerate_id();
	}
	
	/**
	 * Cria uma chave MD5 com base no navegador do usu�rio e o salt, definido na configura��o
	 * @return	string		retorna uma string MD5 
	 */
	private static function key()
	{
		return 'Trilado.'. md5($_SERVER['HTTP_USER_AGENT'] . salt);
	}
	
	/**
	 * Cria uma sess�o criptograda para o usu�rio
	 * @param	string	$name		nome da sess�o
	 * @param	mixed	$value		valor da sess�o
	 * @throws	TriladoException	disparada caso o programador n�o defina a configura��o 'salt', ou o valor esteja vazio
	 * @return	void
	 */
	public static function set($name , $value)
	{
		if(!defined('salt') || salt == '')
			throw new TriladoException("A configura��o 'salt' n�o pode ter o valor nulo");
		self::start();
		$_SESSION[self::key()][$name] = Security::encrypt($value, salt);
	}
	
	/**
	 * Remove uma sess�o do usu�rio
	 * @param	string	$name		nome da sess�o a ser removida
	 * @return	void
	 */
	public static function del($name)
	{
		self::start();
		$_SESSION[self::key()][$name] = null;
	}
	
	/**
	 * Remove todas as sess�es do usu�rio
	 * @return	void
	 */
	public static function clear()
	{
		self::start();
		$_SESSION[self::key()] = null;
	}
	
	/**
	 * Descriptograda e retorna uma sess�o espec�fica do usu�rio
	 * @param	string	$name		nome da sess�o a ser retornada
	 * @throws	TriladoException	disparado se a configura��o 'salt' n�o for definida ou o valor for vazio
	 * @return	mixed				retorna o valor sess�o descriptografado
	 */
	public static function get($name)
	{
		if(!defined('salt') || salt == '')
			throw new TriladoException("A configura��o 'salt' n�o pode ter o valor nulo");
		self::start();
		return Security::decrypt($_SESSION[self::key()][$name], salt);
	}
}
