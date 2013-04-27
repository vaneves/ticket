<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Cont�m m�todo para facilitar a importa��o de arquivos, como controllers, models e helpers
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class Import
{
	/**
	 * Carrega um ou mais arquivos a partir de um diret�rio
	 * 
	 * @param	string	$folder				indica o diret�rio que ser�o carregados os arquivos, os valores poss�veis s�o 'core', 'exception', 'controller', 'model' e 'helper'
	 * @param	array	$class				um array com os nomes das classes
	 * @throws	DirectoryNotFoundException	disparada cara o diret�rio n�o conste na lista de diret�rios padr�o
	 * @throws	FileNotFoundException		disparada se o arquivo com o nome da classe n�o for encontrado
	 * @throws	ClassNotFoundException		disparada se dentro do arquivo n�o existir a classe
	 * @return	void
	 */
	public static function load($folder, $class = array())
	{
		$folders = array();
		$folders['core']		= 'core/libs/';
		$folders['exception']	= 'core/libs/exceptions/';
		$folders['controller']	= 'app/controllers/';
		$folders['model']		= 'app/models/';
		$folders['helper']		= 'app/helpers/';
		
		if(!array_key_exists($folder, $folders))
			throw new DirectoryNotFoundException($folder .'s');
		foreach($class as $c)
		{
			$file = root . $folders[$folder] . $c . '.php';
			if(!file_exists($file))
				throw new FileNotFoundException($folders[$folder] . $c .'.php');
			
			require_once $file;
			
			if(!class_exists($c))
				throw new ClassNotFoundException($c);
		}
	}
	
	/**
	 * Importa as classes espec�ficadas no par�metro no diret�rio do n�cleo do framework
	 * @param	string	$class1		nome da classe
	 * @param	string	$classN		nome da classe
	 * @return	void
	 */
	public static function core()
	{
		$args = func_get_args();
		self::load('core', $args);
	}
	
	/**
	 * Importa as classes espec�ficadas no par�metro no diret�rio dos controllers
	 * @param	string	$class1					nome da classe
	 * @param	string	$classN					nome da classe
	 * @throws	ControllerNotFoundException		disparado se o arquivo com o nome do controller n�o for encontrado
	 * @throws	ClassNotFoundException			disparado se dentro do arquivo n�o existir uma classe com o nome do controller
	 * @return	void
	 */
	public static function controller()
	{
		$args = func_get_args();
		foreach($args as $c)
		{
			$file = root . 'app/controllers/' . $c . '.php';
			if(!file_exists($file))
				throw new ControllerNotFoundException($c);
			
			require_once $file;
			
			if(!class_exists($c))
				throw new ClassNotFoundException($c);
		}
	}
	
	/**
	 * Importa as classes espec�ficadas no par�metro no diret�rio dos models
	 * @param	string	$class1		nome da classe
	 * @param	string	$classN		nome da classe
	 * @return	void
	 */
	public static function model()
	{
		$args = func_get_args();
		self::load('model', $args);
	}
	
	/**
	 * Importa as classes espec�ficadas no par�metro no diret�rio dos helpers
	 * @param	string	$class1		nome da classe
	 * @param	string	$classN		nome da classe
	 * @return	void
	 */
	public static function helper()
	{
		$args = func_get_args();
		self::load('helper', $args);
	}
	
	/**
	 * Importa uma view espec�ficada
	 * @param	array	$vars			vari�veis a serem utilizadas na view
	 * @param	string	$controller		nome do controller
	 * @param	string	$view			nome da view
	 * @throws	FileNotFoundException	disparado se o arquivo n�o for encontrado
	 * @return	string					retorna o conte�do da view
	 */
	public static function view($vars, $controller, $view)
	{
		$buffer = ob_get_clean();
		ob_start();
		extract($vars);
		$file = root . 'app/views/'. $controller .'/'. $view .'.php';
		if(!file_exists($file))
			throw new FileNotFoundException('views/'. $controller .'/'. $view .'.php');
		
		require_once $file;
		
		$content = ob_get_clean();
		echo $buffer;
		return $content;
	}
}
