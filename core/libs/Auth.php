<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Classe para autentica��o do usu�rio
 * 
 * @author		Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version		2
 *
 */ 
class Auth 
{
	/**
	 * Construtor da classe, � privado porque a classe s� cont�m m�todo est�ticos e n�o pode inst�nciada
	 */
	private function __construct(){}
	
	/**
	 * Define um ou mais pap�is para o usu�rio na sess�o
	 * @param	string	$param1	nome do pap�l
	 * @param	string	$param2	nome do pap�l
	 * @param	string	$paramN	nome do pap�l
	 * @return	void
	 */
	public static function set()
	{
		Session::start();
		$roles = func_get_args();
		foreach($roles as $role)
			self::_set($role, $role);
	}
	
	/**
	 * Remove um mais pap�is do usu�rio na sess�o
	 * @param	string	$param1	nome do pap�l
	 * @param	string	$param2	nome do pap�l
	 * @param	string	$paramN	nome do pap�l
	 * @return	void
	 */
	public static function remove()
	{
		Session::start();
		$roles = func_get_args();
		foreach($roles as $role)
			self::_set($role, null);
	}
	
	/**
	 * Remove todos os pap�is do usu�rio na sess�o
	 * @return	void
	 */
	public static function clear()
	{
		Session::start();
		$_SESSION[self::key()] = null;
	}
	
	/**
	 * Verifica se o usu�rio possui, na sess�o, os pap�is informados no par�metro
	 * @param	string	$param1	nome do pap�l
	 * @param	string	$param2	nome do pap�l
	 * @param	string	$paramN	nome do pap�l
	 * @throws	AuthException	dispara se o usu�rio estiver algum pap�l na sess�o, por�m este n�o for informado do par�metro
	 * @return	void
	 */
	public static function allow()
	{
		Session::start();
		$roles = func_get_args();
		$is = call_user_func_array('Auth::is', $roles);
		if(!$is)
		{
			if(!self::isLogged())
			{
				$location = preg_match('@^~/@', default_login) ? root_virtual . trim(default_login, '~/') : default_login;
				header('Location: '. $location);
				exit;
			}
			throw new AuthException('Voc� n�o tem permiss�o para acessar esta p�gina', 403);
		}
	}
	
	/**
	 * Verifica se o usu�rio possui um ou mais pap�is informado como par�metro
	 * @param	string	$param1	nome do pap�l
	 * @param	string	$param2	nome do pap�l
	 * @param	string	$paramN	nome do pap�l
	 * @return	boolean			retorna true se tiver um dos pap�is, no contr�rio retorna false
	 */
	public static function is()
	{
		Session::start();
		$roles = func_get_args();
		foreach($roles as $role)
		{
			if(self::_get($role))
				return true;
		}
		return false;
	}
	
	/**
	 * Verifica se o usu�rio possuim um ou mais pap�is na sess�o
	 * @return	boolean		retorna true se o usu�rio possuir, caso contr�rio retorna false
	 */
	public static function isLogged()
	{
		Session::start();
		if(is_array($_SESSION[self::key()]))
		{
			foreach($_SESSION[self::key()] as $role)
			{
				if($role)
					return true;
			}
		}
		return false;
	}
	
	/**
	 * Pega um pap�l na sess�o
	 * @param	string	$key	nome do pap�l
	 * @return	string			retorna o pap�l
	 */
	private static function _get($key)
	{
		return $_SESSION[self::key()][$key];
	}
	
	/**
	 * Adiciona um pap�l na sess�o
	 * @param	string	$key	nome do pap�l
	 * @param	string	$value	valor
	 * @return	void
	 */
	private static function _set($key, $value)
	{
		$_SESSION[self::key()][$key] = $value;
	}
	
	/**
	 * Gera uma chave MD5 com base no navegador do usu�rio e no salt, definido na configura��o
	 * @return	string	retorn o MD5 gerado
	 */
	private static function key()
	{
		return 'Auth.'. md5($_SERVER['HTTP_USER_AGENT'] . salt);
	}
}