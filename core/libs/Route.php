<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Classe de manipula��o das rotas (URL). Com ela � poss�vel alterar o endere�o da chamada controller ou actions
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class Route
{
	/**
	 * Guarda as rotas adicionadas pelo programador
	 * @var	array
	 */
	private static $routes = array();
	
	/**
	 * Guarda os prefixos adicionados pelo programador
	 * @var	array
	 */
	private static $prefix = array();
	
	/**
	 * Contrutor da classe, � privado porque n�o deve ser inst�nciada
	 */
	private function __construct() {}
	
	/**
	 * Adiciona um prefixo
	 * @param	string	$prefix		nome do prefixo
	 * @return	void
	 */
	public static function prefix($prefix)
	{
		self::$prefix[] = $prefix;
	}
	
	/**
	 * Adiciona uma rota
	 * @param	string	$route		endere�o da rota (ex.: '^([\d]+)/([a-z0-9\-]+)$')
	 * @param	string	$url		endere�o original (ex.: 'home/view/$1/$2')
	 * @return	void
	 */
	public static function add($route, $url)
	{
		self::$routes[] = array('route' => $route, 'url' => $url);
	}
	
	/**
	 * Verifica se a URL faz parte de alguma rota e pega os endere�o verdadeiro
	 * @param	string	$url	URL acessada pelo usu�rio
	 * @return	array			retorna o nome do prefixo, do controller, da action e com os par�metros baseado na rota
	 */
	public static function exec($url)
	{	
		if((auto_dotjson || auto_dotxml) && preg_match('@\.(json|xml)$@', $url, $matches))
		{
			$args['dot'] = $matches[1];
			$url = rtrim($url, $matches[0]);
			define('is_autodot', $matches[1]);
		}
		
		$url = trim(self::checkRoute($url), '/');
		$urls = explode('/', $url);
	
		if(self::isI18n($urls[0]))
			$args['lang'] = array_shift($urls);
		
		if(self::isPrefix($urls[0]))
			$args['prefix'] = array_shift($urls);
		
		$args['controller']	= array_shift($urls);
		$args['action']	= array_shift($urls);
		if($args['prefix'])
			$args['action'] = $args['prefix'] .'_'. ($args['action'] ? $args['action'] : default_action);
		$args['params']	= $urls;
		
		return $args;
	}
	
	/**
	 * Pega a URL verdadeira a partir de uma rota
	 * @param	string	$url	URL acessada pelo usu�rio
	 * @return	string			retorna a URL verdadeira ou o pr�prio par�metro caso n�o seja uma rota
	 */
	private static function checkRoute($url)
	{	
		$url = trim($url, '/');
		$urls = explode('/', $url);
		if(self::isI18n($urls[0]))
			$lang = array_shift($urls) .'/';
		$url = implode('/', $urls);
		
		foreach(self::$routes as $r)
		{
			$regex = '@'. $r['route'] .'@';
			if(preg_match($regex, $url, $matches))
				return $lang . preg_replace($regex, $r['url'], $url);
		}
		return $lang . $url;
	}
	
	/**
	 * Verifica se o come�o da URL � um prefixo
	 * @param	string	$first		primeira parte da URL
	 * @return	boolean				retorna true se for um prefixo, no contr�rio retorna false
	 */
	private static function isPrefix($first)
	{
		$args = array();
		foreach(self::$prefix as $p)
		{
			if($p == $first)
				return true;
		}
		return false;
	}
	
	/**
	 * Verifica se e o trecho da URL � internacionaliza��o
	 * @param	string			$first	primeiro trecho da URL
	 * @return	boolean			retorna true se for internacionaliza��o, no contr�rio retorna false
	 */
	private static function isI18n($first)
	{
		return $first == default_lang || (preg_match('/^([a-z]{2}|[a-z]{2}-[a-z]{2})$/',$first) && file_exists(root .'app/i18n/'. $first .'.lang'));
	}
}
